<?php

namespace App\Controllers\Internal\Services;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use \Firebase\JWT\JWT;


class Simbara_send_status extends ResourceController
{
	use ResponseTrait;

	protected $db;
	protected $config;
	protected $idlog;

	function __construct()
	{
		$this->config = new \stdClass();
		$this->db      = \Config\Database::connect();
        helper('api');
    }

	public function send($token="")
    {
    	if($token != 'cZCfbllSWrueEZvPS3aUkbX831GDhKDBKFDR79b9dc9wcYiSzBybZsCH96UrcXOh')
    	{
    		return redirect()->to(base_url(), 301);
    		exit;
    	}
    	echo 'Send Status Insw START @'.date('Y-m-d H:i:s').PHP_EOL;
    	$this->config->traffic = 'OUT';
    	$this->config->method = 'POST';
    	$this->config->endPoint = 'https://api.insw.go.id/api/integration/pabean/ekspor/send-response-surveyor';
    	$this->config->apiKeyName = 'Insw-key';
    	$this->config->apiKey = 'pZ66hobzPpXBn2bMHVPTz0wG1pxuWQdo';

    	try {

    		$dataCount = "NO DATA";
    		//$tableLog = $this->db->table('t_log_simbara')->where('kodeProses','010')->where('statusKirimNsw','READY')->get()->getRow();
    		$tableLog = $this->db->table('t_log_simbara')->where('statusKirimNsw','READY')->where('idPermohonan >=',33)->orderBy('id')->get()->getRow();
    		if($tableLog)
    		{
    			$dataCount = " 1 ";
    			$this->db->table('t_log_simbara')->set('statusKirimNSW','SENDING')->set('waktuKirimNSW',date('Y-m-d H:i:s'))->where('id',$tableLog->id)->update();

    			$permohonan = $this->db->table('tblPermohonan_pinsw')->where('id',$tableLog->idPermohonan)->get()->getRow();
	    		$data['noAju'] = $permohonan->nomorAju;
				$data['nomorPermohonan'] = $permohonan->nomorPermohonan;
				$data['kodeProses'] = $tableLog->kodeProses;
				$data['waktuProses'] = $tableLog->logTime;
				$data['keterangan'] = $tableLog->uraiProses;
				$data['urlLampiran'] = '';
				$data['payloadLs'] = null;
				$payload['data'] = [$data];
				$this->config->payload = json_encode($payload);
				$log = service_log($this->idlog,$this->config,NULL,$permohonan->nomorAju);
		    	$this->idlog = $log->id;
		    	// send act
		    	$sendTime = date('Y-m-d H:i:s');
		    	$response 		 = curlClientRaw($this->config);
				$deCodeResponse  = json_decode($response->getBody());
				if($deCodeResponse->status == '01' && isset($deCodeResponse->data))
				{
					if($deCodeResponse->data->kode == 'A01')
					{
						$this->db->table('tblPermohonan_pinsw')->set('statusInsw',$tableLog->kodeProses)->set('uraiStatusInsw',$tableLog->uraiProses)->set('timeStatusInsw',$sendTime)->where('id',$permohonan->id)->update();
						$this->db->table('t_log_simbara')->set('statusKirimNSW','SENT')->set('waktuKirimNSW',date('Y-m-d H:i:s'))->where('idPermohonan',$permohonan->id)->where('kodeProses',$tableLog->kodeProses)->update();
					}
					else{
						$this->db->table('t_log_simbara')->set('statusKirimNSW','FAILED')->set('waktuKirimNSW',date('Y-m-d H:i:s'))->where('idPermohonan',$permohonan->id)->where('kodeProses',$tableLog->kodeProses)->update();
					}
				}
				else{
					$this->db->table('t_log_simbara')->set('statusKirimNSW','FAILED')->set('waktuKirimNSW',date('Y-m-d H:i:s'))->where('idPermohonan',$permohonan->id)->where('kodeProses',$tableLog->kodeProses)->update();
				}

				$dataLS = $this->db->table('tx_lsehdr')->where('idPermohonanNSW',$permohonan->id)->get()->getRow();
				if(isset($dataLS->id) && !empty($dataLS->id))
				{
					$dataLog['idLS']        = $dataLS->id;
	                $dataLog['logAction']   = 'Kirim Insw Status = '.$tableLog->uraiProses;
	                $dataLog['note']        = $deCodeResponse->message;
	                save_log_process($dataLog,$dataLS);
	            }
				$resp = $this->set_response($deCodeResponse->status,$deCodeResponse->message,$deCodeResponse,$permohonan->nomorAju);
				return $this->response->setJSON($resp);
			}
			echo 'Send Status Insw data count = '.$dataCount.' STOP @'.date('Y-m-d H:i:s').PHP_EOL;
    	} catch (\Throwable $e) {
    		echo 'Send Status Insw data count = '.$dataCount.' ERROR @'.date('Y-m-d H:i:s').PHP_EOL;
    		//var_dump($e);
    		$this->db->table('t_log_simbara')->set('statusKirimNSW','FAILED')->set('waktuKirimNSW',date('Y-m-d H:i:s'))->where('idPermohonan',$permohonan->id)->where('kodeProses',$tableLog->kodeProses)->update();
    		$error = $e->getMessage().' Line '.$e->getLine().' File '.$e->getFile();
    		$resp = $this->set_response('','','',$permohonan->nomorAju,$error);
    		return $this->response->setJSON($resp);
    		exit;
    	}
    }


    public function send_pengembalian()
    {
    	$this->config->traffic = 'OUT';
    	$this->config->method = 'POST';
    	$this->config->endPoint = 'https://api.insw.go.id/api/integration/pabean/ekspor/send-response-surveyor';
    	$this->config->apiKeyName = 'Insw-key';
    	$this->config->apiKey = 'pZ66hobzPpXBn2bMHVPTz0wG1pxuWQdo';

    	try {

    		if(!empty($this->request->getPost('idPermohonan'))){

    			$idPermohonan = decrypt_id($this->request->getPost('idPermohonan'));
    			$permohonan = $this->db->table('tblPermohonan_pinsw')->where('tblPermohonan_pinsw.id',$idPermohonan)->get()->getRow();
    			if(!empty($permohonan))
    			{
    				$cekLSTerbit = $this->db->table('tx_lsehdr')->where('idPermohonanNSW',$permohonan->id)->where('statusProses','ISSUED')->get()->getRow();
    				if(!empty($cekLSTerbit))
    				{
    					$resp = resp_error('Tidak dapat melakukan pengembalian. LS untuk pengajuan ini sudah terbit dengan nomor '.$cekLSTerbit->noLs);
						return $this->response->setJSON($resp);
						exit;
    				}
    				else
    				{
    					$this->db->transStart();

    					$kodeProses = '040';
    					$uraiProses = 'PENGEMBALIAN';
    					$keterangan = 'Pengembalian';
    					if(!empty($this->request->getPost('keterangan')))
    						$keterangan = $this->request->getPost('keterangan');

    					$kodeProses = '040';
    					$data['noAju'] = $permohonan->nomorAju;
						$data['nomorPermohonan'] = $permohonan->nomorPermohonan;
						$data['kodeProses'] = $kodeProses;
						$data['waktuProses'] = date('Y-m-d H:i:s');
						$data['keterangan'] = $keterangan;
						$data['urlLampiran'] = '';
						$data['payloadLs'] = null;
						$payload['data'] = [$data];
						$this->config->payload = json_encode($payload);
						$log = service_log($this->idlog,$this->config,NULL,$permohonan->nomorAju);
				    	$this->idlog = $log->id;


				    	$sendTime = date('Y-m-d H:i:s');
				    	$response 		 = curlClientRaw($this->config);
						$deCodeResponse  = json_decode($response->getBody());

						$statusKirimNsw = 'UNDEFINED';
						if($deCodeResponse->status == '01' && isset($deCodeResponse->data))
						{
							if($deCodeResponse->data->kode == 'A01')
							{
								$this->db->table('tblPermohonan_pinsw')->set('statusInsw',$kodeProses)->set('status',$uraiProses)->set('uraiStatusInsw',$uraiProses)->set('timeStatusInsw',$sendTime)->where('id',$permohonan->id)->update();

								$cekLSProses = $this->db->table('tx_lsehdr')->select('id')->where('idPermohonanNSW',$permohonan->id)->get()->getResult();
		    					if(isset($cekLSProses) && count($cekLSProses) > 0){
			    					$arrID = obj_flatten($cekLSProses,'id');
			    					foreach ($arrID as $key => $idLS) {

			    						$logBefore = $this->db->table('tx_lsehdr')->where('id',$idLS)->get()->getRow();
			    						$this->db->table('tx_lsehdr')->set(['statusProses'=>'DELETED','lastUser'=>decrypt_id(session()->get('sess_userid')),'lastUpdate'=>date('Y-m-d H:i:s')])->where('id',$idLS)->update();
			    						$logBefore = $this->db->table('tx_lsehdr')->where('id',$idLS)->get()->getRow();

			    						$dataLog['logAction']   = 'PENGEMBALIAN AJU INSW';
			    						$dataLog['idLS']     = $idLS;
		                    			$dataLog['note']     = 'Pengembalian ke insw aju '.$permohonan->nomorAju;
			    						save_log_process($dataLog,$logBefore);
			    					}
		    					}
		    					$statusKirimNsw = 'SENT';
		    					if(isset($arrID))
			    					$keterangan = 'ID LS dihapus = '.implode(", ", $arrID);
							}
							else{
								$statusKirimNsw = 'FAILED';
								$keterangan = json_encode($deCodeResponse);
							}
						}
						else{
							$statusKirimNsw = 'FAILED';
							$keterangan = json_encode($deCodeResponse);
						}

						save_log_simbara($idPermohonan,'040','Pengembalian','Pengembalian Aju ke SINSW',$keterangan,$statusKirimNsw);

    					$this->db->transComplete();

    					if ($this->db->transStatus() === false){
		                    $resp    = resp_error('Pengiriman pengembalian gagal (Transaction failed)');
    					}
		                else{
		                	if(isset($deCodeResponse->data) && $deCodeResponse->data->kode == 'A01')
			                    $resp = resp_success('Status pengembalian berhasil dikirim.');
			                else
			                	$resp    = resp_error('Pengiriman pengembalian gagal '.json_encode($deCodeResponse));
		                }
    				}
    			}
    		}
    		else
    		{
    			$resp    = resp_error('Invalid data. Silahkan muat ulang halaman ini dan coba kembali');
    		}
    		return $this->response->setJSON($resp);
    	} catch (\Throwable $e) {
    		var_dump($e);exit;
    		// $this->db->table('t_log_simbara')->set('statusKirimNSW','FAILED')->set('waktuKirimNSW',date('Y-m-d H:i:s'))->where('idPermohonan',$permohonan->id)->where('kodeProses',$tableLog->kodeProses)->update();
    		// $error = $e->getMessage().' Line '.$e->getLine().' File '.$e->getFile();
    		$resp = $this->set_response('','','',$permohonan->nomorAju,$error);
    		return $this->response->setJSON($resp);
    		exit;
    	}
    }

    function set_response($resp_code,$resp_msg,$data='',$noAju=NULL,$error=NULL)
    {
    	$response = [
			'status' => $resp_code,
			'message' => $resp_msg,
			'data' => $data
		];

		$datalog['response'] = $this->response->setJSON($response);
		$datalog['responseCode'] = $resp_code;
		$datalog['responseMsg'] = $resp_msg;
		$datalog['error'] = $error;
		$log = service_log($this->idlog,$this->config,$datalog,$noAju);

		return $response;
    }
}
