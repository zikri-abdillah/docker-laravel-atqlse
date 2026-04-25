<?php

namespace App\Controllers\Internal\Services;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;


class Rekap extends BaseController
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
    	$param['content'] = $this->render('services.rekap.index');

    	return $this->render('layout.template', $param);
    }

    public function act()
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