<?php

namespace App\Controllers\Resources\Simbara;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use \Firebase\JWT\JWT;


class Status extends ResourceController
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

	public function get()
    {
    	$this->config->traffic = 'IN';
    	$this->config->method = 'GET';
    	$this->config->endPoint = site_url(uri_string());
    	$this->config->apiKeyName = 'ATQ-API-KEY';
		$this->config->apiKey = 'QamX~0YP96o0_jWwUamysNdznB2vZpD';
    	$parameter = NULL;

    	try {
    		$request = \Config\Services::request();
    		$this->config->payload = var_export($request->headers(),TRUE).var_export($request->getBody(),TRUE);
	    	$log = service_log($this->idlog,$this->config);
	    	$this->idlog = $log->id;

	    	if($request->getHeaderLine($this->config->apiKeyName) != $this->config->apiKey)
	    	{
	    		$resp = $this->set_response('400','Invalid API KEY','',$parameter);
	    	}
	    	else{
	    		$parameter = $request->getHeaderLine('parameter');
	    		if(empty($parameter)){
	    			$resp = $this->set_response('400','Key Parameter tidak terbaca','',$parameter);
	    		}
	    		else{
	    			$permohonan = $this->db->table('tblPermohonan_pinsw')->where('nomorAju',$parameter)->get()->getRow();
	    			if(empty($permohonan->id)){
	    				$resp = $this->set_response('400','Nomor Aju tidak ditemukan','',$parameter);
	    			}
	    			else
	    			{
	    				// $statusResp = $this->db->table('t_log_simbara')->where('idPermohonan',$permohonan->id)->where('statusKirimNSW','READY')->orderBy('id','ASC')->limit(1)->get()->getRow();
	    				// if(empty($statusResp))
		    			// 	$statusResp = $this->db->table('t_log_simbara')->where('idPermohonan',$permohonan->id)->orderBy('id','DESC')->limit(1)->get()->getRow();

	    				$data['noAju'] = $permohonan->nomorAju;
						$data['nomorPermohonan'] = $permohonan->nomorPermohonan;
						$data['kodeProses'] = $permohonan->statusInsw;
						$data['waktuProses'] = $permohonan->timeStatusInsw;
						$data['keterangan'] = $permohonan->uraiStatusInsw;
						$data['urlLampiran'] = '';
						$data['payloadLs'] = '';
						$resp = $this->set_response('200','OK',[$data],$parameter);

						$this->db->table('t_log_simbara')->set('statusKirimNSW','REQUESTED')->set('waktuKirimNSW',date('Y-m-d H:i:s'))->where('idPermohonan',$permohonan->id)->where('kodeProses',$permohonan->statusInsw)->update();
	    			}
	    		}
	    	}
	    	return $this->response->setJSON($resp);
	    	exit;
    	} catch (\Throwable $e) {
    		var_dump($e);
    		$resp = $this->set_response('400','Terdapat kesalahan ketika memproses request','',$parameter);
    		return $this->response->setJSON($resp);
    		exit;
    	}
    }

    function set_response($resp_code,$resp_msg,$data='',$noAju=NULL)
    {
    	$response = [
			'status' => $resp_code,
			'message' => $resp_msg,
			'data' => $data
		];

		$datalog['response'] = $this->response->setJSON($response);
		$datalog['responseCode'] = $resp_code;
		$datalog['responseMsg'] = $resp_msg;
		$log = service_log($this->idlog,$this->config,$datalog,$noAju);

		return $response;
    }
}
