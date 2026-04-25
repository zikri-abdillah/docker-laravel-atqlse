<?php

namespace App\Controllers\Resources\Simbara;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use \Firebase\JWT\JWT;


class Dokumen extends ResourceController
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

	public function doktambahan()
    {

    	$this->config->traffic = 'IN';
    	$this->config->method = 'POST';
    	$this->config->endPoint = site_url(uri_string());
    	$this->config->apiKeyName = 'ATQ-API-KEY';
		$this->config->apiKey = 'QamX~0YP96o0_jWwUamysNdznB2vZpD';
    	$noAju = NULL;

    	try {
    		$request = \Config\Services::request();
    		$this->config->payload = var_export($request->headers(),TRUE).var_export($request->getBody(),TRUE);
	    	$log = service_log($this->idlog,$this->config);
	    	$this->idlog = $log->id;

	    	$payload = $request->getBody();
	    	$errorJson = json_validate($payload);
	    	if($request->getHeaderLine($this->config->apiKeyName) != $this->config->apiKey)
	    	{
	    		$resp = $this->set_response('200','OK','A02','Invalid API KEY',$noAju);
	    	}
	    	else if(!empty($errorJson))
	    	{
	    		$resp = $this->set_response('200','OK','A02','Invalid Schema Validation : '.$errorJson,$noAju);
	    	}
	    	else{
	    		$data = $request->getJSON(true);

	    		if(!isset($data['header'])){
	    			$resp = $this->set_response('200','OK','A02','Tag Header tidak terbaca',$noAju);
	    		}
	    		else{
	    			if(isset($data['header']['nomorAju']))
	    				$noAju = $data['header']['nomorAju'];
	    			$permohonan = $this->db->table('tblPermohonan_pinsw')->where('nomorAju',$noAju)->whereIn('statusInsw',['010','020','030','050'])->get()->getRow();

	    			if(empty($permohonan->id)){
	    				$resp = $this->set_response('200','OK','A02','Nomor Aju tidak ditemukan / sudah dilakukan pengembalian / penolakan',$noAju);
	    			}
	    			else
	    			{
	    				$arrDokumen = $data['header']['dokumen'];
	    				try {
	    					$maxBatch = $this->db->table('tblPermohonanDok_pinsw')->selectMax('batch')->where('idPermohonan',$permohonan->id)->get()->getRow()->batch;
	    					if(empty($maxBatch))
	    						$maxBatch = 1;

	    					$batch = $maxBatch+1;
		    				$this->db->transException(true)->transStart();
							foreach ($arrDokumen as $key => $dokumen) {
								$dokumen['idPermohonan'] = $permohonan->id;
								$dokumen['batch'] = $batch;
								$dokumen['createdAt'] = date('Y-m-d H:i:s');
								$this->db->table('tblPermohonanDok_pinsw')->insert($dokumen);
							}
							$this->db->transComplete();

							if($this->db->transStatus() !== false){
								$resp = $this->set_response('200','OK','A01','Request Sukses',$noAju);
							}
							else{
								$resp = $this->set_response('200','OK','A02','Gagal menyimpan dokumen tambahan',$noAju);
							}
						} catch (DatabaseException $e) {
						    $resp = $this->set_response('200','OK','A02','Gagal menyimpan dokumen tambahan (exception)',$noAju);
						}
	    			}
	    		}
	    	}
	    	return $this->response->setJSON($resp);
	    	exit;
    	} catch (\Throwable $e) {
    		$resp = $this->set_response('200','OK','A02','Terdapat kesalahan ketika memproses request',$noAju);
    		return $this->response->setJSON($resp);
    		exit;
    	}
    }

    function set_response($resp_code,$resp_msg,$code,$msg,$noAju=NULL)
    {
    	$data = ['kode'=>$code,'keterangan'=>$msg];
    	$response = [
			'status' => $resp_code,
			'message' => $resp_msg,
			'data' => $data
		];

		$datalog['response'] = $this->response->setJSON($response);
		$datalog['responseCode'] = $code;
		$datalog['responseMsg'] = $msg;
		$log = service_log($this->idlog,$this->config,$datalog,$noAju);

		return $response;
    }
}
