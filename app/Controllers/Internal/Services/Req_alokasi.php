<?php

namespace App\Controllers\Internal\Services;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;


class Req_alokasi extends BaseController
{
	protected $logStart;
	protected $traffic;
	protected $method;
	protected $endPoint;
	protected $apiKey;

	function __construct()
	{
        helper('api');

        $this->traffic = 'traffic';
        $this->method = 'method';
        $this->endPoint = 'endPoint';
        $this->apiKey = 'apiKey';

        $this->logStart['traffic'] = $this->traffic;
    	$this->logStart['method'] = $this->method;
    	$this->logStart['endpoint'] = $this->endPoint;
    	$this->logStart['apiKey'] = $this->apiKey;
    }

	public function act()
    {
    	$config 			= new \stdClass();
    	$config->traffic 	= 'OUT';
    	$config->method 	= 'POST';

    	$config->username 	= 'test'; // username surveyor  JANGAN LUPA DIGANTI SESUAI CLIENT
    	//$config->endPoint = 'https://services.kemendag.go.id/surveyor/1.0/req_alokasi';

    	$config->endPoint 	= 'http://appls.pe.hu/dummy/inatrade/req_alokasi'; // dummy
    	$config->apiKeyName = 'x-Gateway-APIKey';
    	$config->apiKey 	= 'a56c8e09-1d1d-4543-9ad6-343b55e06ca6';

    	try {
			$idLS = decrypt_id($this->request->getPost('idData'));
			$act = $this->request->getPost('act');
			if(!in_array($act, ['request','revisi','batal']))
			{
				$resp = resp_error('Gagal request alokasi. Invalid Action');
				return $this->response->setJSON($resp);
				exit;
			}
			$dataLS = $this->db->table('tx_lsehdr')->where('id',$idLS)->get()->getRow();
			$arrKomoditas = $this->db->table('tx_lsedtlhs')->where('idLs',$idLS)->get()->getResult();

			$dokPE      = $this->db->table('tx_lse_referensi')
                            ->join('t_dokpersh', 'tx_lse_referensi.idDokPersh = t_dokpersh.id')
                            ->where('idLS',$idLS)->where('tx_lse_referensi.idJenisDok',4)
                            ->get()->getRow();
            if(!empty($dokPE->noDokumen))
            {
	            $reqAlokasiCount = $dataLS->reqAlokasiCount;
	            if($act == 'request'){
	            	$payload['header']['no_permohonan'] = $dataLS->noPveb.'.'.$reqAlokasiCount;
	            	$reqAlokasiCount = $dataLS->reqAlokasiCount+1;
	            }
	            else{
	            	$payload['header']['no_permohonan'] = $dataLS->reqAlokasiNumber;
	            	$reqAlokasiCount = $dataLS->reqAlokasiCount;
	            }

				$payload['header']['nib'] = $dataLS->nib;
				$payload['header']['npwp'] = $dataLS->npwp;
				$payload['header']['nitku'] = $dataLS->nitku;
				$payload['header']['no_izin'] = $dokPE->noDokumen;
				$payload['header']['tgl_izin'] = $dokPE->tglDokumen;
				if($act=='request')
					$payload['header']['jns_permintaan'] = 1;
				else if($act=='revisi')
					$payload['header']['jns_permintaan'] = 2;
				else if($act=='batal')
					$payload['header']['jns_permintaan'] = 3;

				foreach ($arrKomoditas as $key => $dataKomoditas) {
					$payload['komoditas'][$key]['seri_izin'] = $dataKomoditas->seriIzin;
					$payload['komoditas'][$key]['pos_tarif'] = $dataKomoditas->postarif;
					$payload['komoditas'][$key]['jml_volume'] = $dataKomoditas->jumlahBarang;
					$payload['komoditas'][$key]['jns_satuan'] = $dataKomoditas->kdSatuanBarang;

					$updateAfterRequestSukses[$key]['seri'] = $dataKomoditas->seri;
					$updateAfterRequestSukses[$key]['jumlahBarang'] = $dataKomoditas->jumlahBarang;
				}
				$payload['username'] = $config->username; // jangan lupa diganti sesuai client

				$idlog 			  = NULL;
				$datalog['idData']= $idLS;
				$config->payload  = json_encode($payload);

				$log 			  = service_log($idlog,$config,$datalog,$dataLS->draftNo);
				$idlog 			  = $log->id;
				$response 		  = curlClient($config,$config->payload);
				$deCodeResponse   = json_decode($response->getBody());
				//qq($deCodeResponse);

				$datalog['response'] 		= $response;
				$datalog['responseCode'] 	= $deCodeResponse->status;
				$datalog['responseMsg'] 	= $deCodeResponse->keterangan;
				$log						= service_log($idlog,$config,$datalog,$dataLS->draftNo);
				if($deCodeResponse->status == 'A01'){
					$dataLog['idLS']        = $dataLS->id;

					if($act == 'request')
		                $dataLog['logAction']   = 'Berhasil Request Alokasi';
		            else if($act == 'revisi')
		            	$dataLog['logAction']   = 'Revisi Request Alokasi Berhasil';
		            else if($act == 'batal')
		            	$dataLog['logAction']   = 'Pembatalan Request Alokasi Berhasil';

	                $dataLog['note']        = '';
	                save_log_process($dataLog,$dataLS);

	                foreach ($updateAfterRequestSukses as $key => $value) {
	                	$jmlBarang = $value['jumlahBarang'];
	                	$this->db->table('tx_lsedtlhs')->set('bookedAlokasi',$jmlBarang)->where('idLs', $dataLS->id)->where('seri', $value['seri'])->update();
	                }

					$resp = resp_success('Berhasil request alokasi. Response inatrade : '.$deCodeResponse->keterangan);

					if($act == 'batal'){
						$this->db->table('tx_lsehdr')->set(['idReqAlokasi'=>NULL,'reqAlokasiNumber'=>NULL,'reqAlokasiCount'=>$reqAlokasiCount])->where('id', $dataLS->id)->update();
						$this->db->table('tx_lsedtlhs')->set(['bookedAlokasi'=>NULL])->where('idLs', $dataLS->id)->update();
					}
					else
						$this->db->table('tx_lsehdr')->set(['idReqAlokasi'=>$idlog,'reqAlokasiNumber'=>$payload['header']['no_permohonan'],'reqAlokasiCount'=>$reqAlokasiCount])->where('id', $dataLS->id)->update();
				}
				else{
					$resp = resp_error('Gagal request alokasi . Response inatrade : '.$deCodeResponse->keterangan);
				}
			}
			else{
				$resp = resp_error('Silahkan upload dokumen SPE terlebih dahulu pada Tab Dokumen Referensi');
			}
			return $this->response->setJSON($resp);
    	} catch (\Throwable $e) {
		    $datalog['error'] = $e->getMessage().' Line '.$e->getLine().' File '.$e->getFile();
		    service_log($idlog,$config,$datalog);
		    if(isset($response))
			    $resp = resp_error('Gagal request alokasi . Exception : httpCode : '.$response->getStatusCode().' Message :'.$e->getMessage());
			else
				$resp = resp_error('Gagal request alokasi . Exception :'.$e->getMessage());
		    return $this->response->setJSON($resp);
    	}
    }

    public function actBackup()
    {
    	$idlog = NULL;
    	$this->logStart['payload'] = 'payload';
		$this->logStart['payloadPath'] = 'payloadPath';
		$this->logStart['startTime'] = date('Y-m-d H:i:s');
    	$log = service_log($idlog,$this->logStart);



    	$logEnd['response'] = 'response';
    	$logEnd['responsePath'] = 'responsePath';
    	$logEnd['endTime'] = date('Y-m-d H:i:s');
    	$logEnd['responseCode'] = 'responseCode';
		$logEnd['error'] = 'error';
    	$log = service_log($log->id,$logEnd);
    	echo 'as';
    }
}

?>