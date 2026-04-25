<?php

namespace App\Controllers\Internal\Services;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;


class Ntpn extends BaseController
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

	public function index()
    {
		$param['addJS'] = '<script src="' . base_url() . '/js/ws/ntpn.js?v='.date('YmdHis').'"></script>';
    	$param['content'] = $this->render('services.ntpn.index');

    	return $this->render('layout.template', $param);
    }

	public function act()
    {
    	$config 			= new \stdClass();
    	$config->traffic 	= 'OUT';
    	$config->method 	= 'POST';
    	$config->endPoint 	= 'https://ws.kemendag.go.id/insw/checkNTPNArr';//base_url().'api/check_izin';
    	// $config->endPoint 	= 'https://services.kemendag.go.id/surveyor/1.0/checkNTPNArr';//base_url().'api/check_izin';
    	$config->apiKeyName = 'x-Gateway-APIKey';
    	$config->apiKey 	= '38ba63e6-a4df-4bec-b256-c6b80aa8dfbd';
 
    	try { 
			$postNtpn 		= $this->request->getPost('ntpn');
 
			if(!empty(trim($postNtpn))){  
				$expPostNtpn	= explode("\r\n",$postNtpn);
				$arrNtpn		= array();

				foreach($expPostNtpn as $value) {
					array_push($arrNtpn,trim($value));
				}			
				
				//'ntpn'		  =>'E9CD40N9VRKSPSCA'
				$idlog 			  = NULL;
				$postData['ntpn'] = $arrNtpn; 
				$config->payload  = $postData; 
				$log 			  = service_log($idlog,$config);
				$idlog 			  = $log->id; 
				$response 		  = curlClient($config,$postData); 
				$deCodeResponse   = json_decode($response->getBody());
				
				$datalog['response'] 		= $response;
				$datalog['responseCode'] 	= $deCodeResponse->status;
				$log						= service_log($idlog,$config,$datalog); 
				$resp 						= resp_success('Success', $deCodeResponse);
				return $this->response->setJSON($resp);
			} else {
				$resp = resp_error('Silahkan input NTPN terlebih dahulu!');
				return $this->response->setJSON($resp);
			}
    	} catch (\Throwable $e) {
			//$datalog['error'] = $e;
			//log_error($idlog,$config,$datalog);
		   var_dump($e);
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