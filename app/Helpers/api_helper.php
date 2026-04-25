<?php

function curlClient($config)
{
	$client = \Config\Services::curlrequest();
	$response = $client->request($config->method, $config->endPoint, [
	    'headers' => [
	        'Content-Type'		=> 'application/json',
	        $config->apiKeyName	=> $config->apiKey,
	    ],
	    'http_errors' => false,
	    'json' => $config->payload,
	    'debug' => WRITEPATH.'service_log/curl_log.txt'
	]);
	return $response;
}

function curlClientRaw($config)
{
	$client = \Config\Services::curlrequest();
	$response = $client->setBody($config->payload)->request($config->method, $config->endPoint, [
	    'headers' => [
	        'Content-Type'		=> 'application/json',
	        $config->apiKeyName	=> $config->apiKey,
	    ],
	    'http_errors' => false,
	    'debug' => WRITEPATH.'service_log/curl_log.txt'
	]);
	return $response;
}

//function service_log($idlog=NULL,$config,$data=[])
function service_log($idlog, $config, $data=[],$noAju=NULL)
{
	helper('filesystem');
	$db      = \Config\Database::connect();
	$builder = $db->table('services_log');

	$dirLog = '/service_log/'.date('Ym').'/';
	if ( !is_dir( WRITEPATH.$dirLog ) ) {
	    mkdir( WRITEPATH.$dirLog );
	}

	if(!isset($data['error']))
		$data['error'] = NULL;


	if(!empty($idlog))
	{
		$response = $data['response'];

		if(!is_string($response))
		{
	    	$respString = 'code = '.$response->getStatusCode() . "\n";
	    	$respString .= 'reason = '.$response->getReason() . "\n";
	    	foreach ($response->headers() as $name => $value) {
	    		$respString .= $name . ': ' . $response->getHeaderLine($name) . "\n";
			}
			$respString .= 'body = '.$response->getBody() . "\n";
			$httpCode = $response->getStatusCode();
			$httpMsg = $response->getReason();
		}
		else
		{
			$httpCode = $httpMsg = '';
			$respString = $response;
		}

		$logName = 'response_'.uniqid().'.json';
		$pathLog = $dirLog.$logName;
		if (!write_file(WRITEPATH.$pathLog,$respString) )
		{
			$data['error'] .= 'Unable to write response file' . "\n";
			$data['responsePath'] = $respString;
		}
		else{
			$data['responsePath'] = $dirLog.$logName;
		}
		unset($data['response']);
		unset($data['payload']);

		$data['noAju'] = $noAju;
		$data['ipAddress'] = getUserIP();
		$data['httpCode'] = $httpCode;
		$data['httpMsg'] = $httpMsg;
		$data['endTime'] = date('Y-m-d H:i:s');
		$builder->where('id', $idlog);
		$builder->update($data);

	}
	else
	{
		$data['traffic'] = $config->traffic;
    	$data['method'] = $config->method;
    	$data['endPoint'] = $config->endPoint;
    	$data['apiKey'] = $config->apiKeyName.':'.$config->apiKey;
    	$data['noAju'] = $noAju;

		$logName = 'request_'.uniqid().'.json';
		$pathLog = $dirLog.$logName;

		if(is_string($config->payload))
			$payload = $config->payload;
		else
			$payload = json_encode($config->payload);

		if (!write_file(WRITEPATH.$pathLog,$payload) )
		{
			$data['error'] .= 'Unable to write request file' . "\n";
		}
		$data['payloadPath'] = $dirLog.$logName;
		unset($data['response']);
		unset($data['payload']);
		$data['startTime'] = date('Y-m-d H:i:s');
		$builder->insert($data);
		$idlog = $db->insertID();
	}
	$log = $builder->where('id',$idlog)->get()->getRow();
	return $log;
}

function log_error($idlog,$config,$datalog)
{
	$db      = \Config\Database::connect();
	$builder = $db->table('services_log');
	$dirLog = '/service_log/'.date('Ym').'/';
	if ( !is_dir( WRITEPATH.$dirLog ) ) {
	    mkdir( WRITEPATH.$dirLog );
	}

	$datalog['traffic'] = $config->traffic;
	$datalog['method'] = $config->method;
	$datalog['endPoint'] = $config->endPoint;
	$datalog['apiKey'] = $config->apiKeyName.':'.$config->apiKey;

	unset($datalog['payload']);

	if(!empty($idlog))
	{
		$response = $datalog['response'];
		if(!is_string($response))
		{
			$respString = 'code = '.$response->getStatusCode() . "\n";
			$respString .= 'reason = '.$response->getReason() . "\n";
			foreach ($response->headers() as $name => $value) {
				$respString .= $name . ': ' . $response->getHeaderLine($name) . "\n";
			}
			$respString .= 'body = '.$response->getBody() . "\n";
		}
		else{
			$respString = $response;
		}

		$logName = 'response_'.uniqid().'.json';
		$pathLog = $dirLog.$logName;
		if (!write_file(WRITEPATH.$pathLog,$respString) )
		{
			$datalog['error'] = 'Unable to write response file ('.$pathLog.') ' . "\n\n" .$datalog['error'];
			$datalog['responsePath'] = $respString;
		}
		else{
			$datalog['responsePath'] = $dirLog.$logName;
		}
		$datalog['startTime'] = date('Y-m-d H:i:s');
		unset($datalog['response']);

		$builder->where('id', $idlog);
		$builder->update($datalog);
	}
	else
	{
		$datalog['endTime'] = date('Y-m-d H:i:s');
		$builder->insert($datalog);
		$idlog = $db->insertID();
	}
}


/**
 * Validate and convert the json to array
 * @param string $string Json string to be validate
 * @return array|json Return array if success or json if failed
 */
function json_validate($string)
{
	$result = json_decode($string,true);
	$error = '';
	if(json_last_error() !== JSON_ERROR_NONE)
		$error = json_last_error_msg();
	return $error;
}

function getUserIP() {
    if( array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER) && !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ) {
        if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',')>0) {
            $addr = explode(",",$_SERVER['HTTP_X_FORWARDED_FOR']);
            return trim($addr[0]);
        } else {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
    }
    else {
        return $_SERVER['REMOTE_ADDR'];
    }
}


?>