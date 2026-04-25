<?php

namespace App\Controllers\Internal\Services;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;


class Asuransi extends BaseController
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

	public function find()
    {
		$param['addJS'] = '<script src="' . base_url() . '/js/ws/asuransi.js?v='.date('YmdHis').'"></script>';
    	$param['content'] = $this->render('services.asuransi.find');

    	return $this->render('layout.template', $param);
    }

    public function survey()
    {
		$param['addJS'] = '<script src="' . base_url() . '/js/ws/asuransi.js?v='.date('YmdHis').'"></script>';
    	$param['content'] = $this->render('services.asuransi.survey');

    	return $this->render('layout.template', $param);
    }

    public function actfind()
    {
    	$config 			= new \stdClass();
    	$config->traffic 	= 'OUT';
    	$config->method 	= 'POST';
    	$config->endPoint 	= 'https://services.kemendag.go.id/surveyor/1.0/asuransifind';//base_url().'api/check_izin';
    	$config->apiKeyName = 'x-Gateway-APIKey';
    	$config->apiKey 	= '38ba63e6-a4df-4bec-b256-c6b80aa8dfbd';

    	try { 
			$nosertifikat 	= $this->request->getPost('nosertifikat');

			if(!empty(trim($nosertifikat))){
				$postData = [
					'no_sertifikat'	=> $nosertifikat,
				];

				//098.1010.101.2020.000183.00
				$config->payload = $postData;
	
				$idlog 			= NULL;
				$log 			= service_log($idlog,$config);
				$idlog 			= $log->id; 
				$response 		= curlClient($config,$postData); 
				$deCodeResponse	= json_decode($response->getBody());
				
				$datalog['response'] 		= $response;
				$datalog['responseCode'] 	= $deCodeResponse->kode;
				$datalog['responseMsg'] 	= $deCodeResponse->keterangan;
				$log 						= service_log($idlog,$config,$datalog);
	 
				$resp = resp_success($deCodeResponse->keterangan, $deCodeResponse);
				return $this->response->setJSON($resp);
			} else {
				$resp = resp_error('Silahkan input Nomor Asuransi terlebih dahulu!');
				return $this->response->setJSON($resp);
			} 
    	} catch (\Throwable $e) {
    		// $datalog['error'] = $e;
    		// log_error($idlog,$config,$datalog);
    		var_dump($e);
    	}
    }

	public function actsurvey()
    { 
    	$config 			= new \stdClass();
    	$config->traffic 	= 'OUT';
    	$config->method 	= 'POST';
    	$config->endPoint 	= 'https://services.kemendag.go.id/surveyor/v1.0.dev/asuransisurvey';//base_url().'api/';//1.0
    	$config->apiKeyName = 'x-Gateway-APIKey';
    	$config->apiKey 	= '38ba63e6-a4df-4bec-b256-c6b80aa8dfbd';

    	try {
	    	$postData = [
			    'no_sertifikat'	=>$this->request->getPost('nosertifikat'),
			    'tgl_survey'	=>reverseDate($this->request->getPost('tglsurvey')),
			    'hasil_survey'	=>$this->request->getPost('hasilsurvey'),
			    'keterangan'	=>$this->request->getPost('keterangan'),
			    'user_surveyed'	=>'asiatrust'
			];

			$config->payload = $postData; 
	    	$idlog 			 = NULL;
	    	$log 			 = service_log($idlog,$config);
	    	$idlog 			 = $log->id; 
	    	$response 		 = curlClient($config,$postData); 
			$deCodeResponse  = json_decode($response->getBody());
			
	    	$datalog['response'] 	 = $response;
	    	$datalog['responseCode'] = $deCodeResponse->data->kode;
			$datalog['responseMsg']  = $deCodeResponse->data->keterangan;
	    	$log 					 = service_log($idlog,$config,$datalog); 
			$resp   				 = resp_success($deCodeResponse->keterangan, $deCodeResponse);
			return $this->response->setJSON($resp);
    	} catch (\Throwable $e) {
    		// $datalog['error'] = $e;
    		// log_error($idlog,$config,$datalog);
    		var_dump($e);
    	}
    }

}

?>