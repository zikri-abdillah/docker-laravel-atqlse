<?php

namespace App\Controllers\Internal\Services;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;


class Laporan_bulanan extends BaseController
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

	public function send()
    {
    	$config 			= new \stdClass();
    	$config->traffic 	= 'OUT';
    	$config->method 	= 'POST';
    	$config->endPoint 	= 'https://ws.kemendag.go.id/surveyor/rekap_bulanan';
    	// $config->endPoint 	= 'https://services.kemendag.go.id/surveyor/1.0/rekap_bulanan';
    	// $config->endPoint 	= 'https://dummytest.id/surveyor/1.0/rekap_bulanan';
    	$config->apiKeyName = 'x-Gateway-APIKey';
    	$config->apiKey 	= '38ba63e6-a4df-4bec-b256-c6b80aa8dfbd';

    	try {
    		if(!empty($this->request->getPost('idLaporan')))
    			$idLaporan = decrypt_id($this->request->getPost('idLaporan'));

    		if(isset($idLaporan))
    		{
				$laporan = $this->db->table('t_laporan_bulanan')->where('id',$idLaporan)->get()->getRow();
				if( empty($laporan->noLaporan) || empty($laporan->tglLaporan) || empty($laporan->bulan) || empty($laporan->tglLaporan) ){
					$resp = resp_error('Nomor, tanggal, bulan , tahun harus di isi');
					return $this->response->setJSON($resp);
					exit;
				}
				else if(empty($laporan->urlFile) || !is_file(WRITEPATH.'uploads/'.$laporan->pathFile))
				{
					$resp = resp_error('File lampiran tidak terbaca');
					return $this->response->setJSON($resp);
					exit;
				}

				$arrJenisLS = $this->db->table('m_jenisls')->whereIn('kode',['LSEBB','PPHPP','LSI'])->where('isActive','Y')->get()->getResult();
				$arrDetail = [];
				foreach ($arrJenisLS as $key => $jenisLS) {
					$detailLaporan['jenis_ls'] = $jenisLS->kodeinatrade;
					$detailLaporan["total_terbit"] = $this->db->table('t_laporan_bulanandtl')->where('idLaporan',$idLaporan)->where('idJenisLS',$jenisLS->id)->whereNotIn('statusDok',['DIHAPUS'])->where('idJenisTerbit',1)->where('statusProses','ISSUED')->where('MONTH(tglLs)',$laporan->bulan)->where('YEAR(tglLs)',$laporan->tahun)->countAllResults();
        		    $detailLaporan["total_rubah"] = $this->db->table('t_laporan_bulanandtl')->where('idLaporan',$idLaporan)->where('idJenisLS',$jenisLS->id)->whereNotIn('statusDok',['DIHAPUS'])->where('idJenisTerbit',2)->where('statusProses','ISSUED')->where('MONTH(tglLs)',$laporan->bulan)->where('YEAR(tglLs)',$laporan->tahun)->countAllResults();
					$detailLaporan['total_beku'] = 0;
					$detailLaporan['total_bekuaktif'] = 0;
					$detailLaporan['total_panjang'] = 0;
					$detailLaporan['total_cabut'] = 0;
					$detailLaporan["total_batal"] = $this->db->table('t_laporan_bulanandtl')->where('idLaporan',$idLaporan)->where('idJenisLS',$jenisLS->id)->whereNotIn('statusDok',['DIHAPUS'])->where('statusDok','DIBATALKAN')->where('statusProses','ISSUED')->where('MONTH(tglLs)',$laporan->bulan)->where('YEAR(tglLs)',$laporan->tahun)->groupBy('noLs')->countAllResults();

					$arrDetail[] = $detailLaporan;
				}

				if($laporan->statusProses != 'KONSEP' || $laporan->statusKirim != 'READY')
				{
					$resp = resp_error('Status data tidak valid');
					return $this->response->setJSON($resp);
					exit;
				}

				$data['nib'] = '9120304103516';
				$data['npwp'] = '662816941411000';
				$data['nama_surveyor'] = 'PT ASIATRUST TECHNOVIMA QUALITI';
				$data['no_laporan'] = $laporan->noLaporan;
				$data['tgl_laporan'] = $laporan->tglLaporan;
				$data['periode'][] = [
					"bulan" => $laporan->bulan,
					"tahun" => $laporan->tahun,
					"detail_laporan" => $arrDetail,
					"fl_multi_bulan" => "N"
				];
				$data['referensi'][] = ["url_laporan"=> "https://appls.atq-lse.co.id/rekap-bulanan/".$laporan->urlFile];
				$data['username'] = 'asiatrust';
				// echo json_encode($data);exit;

				$idlog 			  = NULL;
				$config->payload  = $data;
				$log 			  = service_log($idlog,$config);
				$idlog 			  = $log->id;

				$response 		  = curlClient($config,$data);
				$deCodeResponse   = json_decode($response->getBody());

				// $response = '{"kode":200,"keterangan":"OK","data":[{"kode":"A01","keterangan":"Request Sukses"}]}';
				// $response = '{"kode":200,"keterangan":"OK","data":[{"kode":"A02","keterangan":"Invalid Schema Validation"}]}';
				// $deCodeResponse = json_decode($response);

				$datalog['response'] 		= $response;
				if(isset($deCodeResponse->data[0])){
					$datalog['responseCode'] 	= $deCodeResponse->data[0]->kode;
					$responseMsg 	= $deCodeResponse->data[0]->keterangan;
				}
				else if(isset($deCodeResponse->data)){
					$datalog['responseCode'] 	= $deCodeResponse->data->kode;
					$responseMsg 	= $deCodeResponse->data->keterangan;
				}
				$log = service_log($idlog,$config,$datalog);

				if($datalog['responseCode'] == 'A01')
				{
					$this->db->table('t_laporan_bulanan')->set('waktuKirim',date('Y-m-d H:i:s'))->set('statusProses', "TERKIRIM")->set('statusKirim', "SENT")->where('id',$idLaporan)->update();
					$resp = resp_success($responseMsg, $deCodeResponse);
				}
				else{
					$resp = resp_error($responseMsg, $deCodeResponse);
				}

				return $this->response->setJSON($resp);
			}

    	} catch (\Throwable $e) {
			//$datalog['error'] = $e;
			//log_error($idlog,$config,$datalog);
		   var_dump($e);
    	}
    }
}

?>