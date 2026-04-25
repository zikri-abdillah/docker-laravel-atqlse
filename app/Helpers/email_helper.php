<?php

function pengajuan_online($data)
{   
	$email = \Config\Services::email();
	
	$teks_email             = "<p>
		&nbsp;&nbsp;&nbsp;&nbsp; Pengajuan LSE - Mineral 
		<table>
			<tr><td>NPWP Perusahaan</td><td>:</td><td>".formatNPWP($data->npwp)."</td></tr>
			<tr><td>Nama Perusahaan</td><td>:</td><td>".$data->bentukPersh." ".$data->namaPersh."</td></tr> 
			<tr><td>Nomor Pengajuan</td><td>:</td><td>".$data->draftNo."</td></tr>
		</table>
	</p>"; 
	$path_file = "http://localhost/ls/server/appls2023/public/assets/attachment/patrick.jpg";
 
	$email->setFrom("pkayangan911@gmail.com");
	$email->setTo("cee.pei14@gmail.com");
	$email->setCc($data->emailPersh);
	$email->setBcc("srezaari.edi@gmail.com");
	$email->setSubject("PT Asiatrust Technovima Qualiti - Pengajuan LSE Mineral");
	$email->setMessage($teks_email);
	$email->attach($path_file); 

	if($email->send()){
		return 1;
	} else { 
		return 0;
		// print_r($email->printDebugger('headers')); 
		// die();
	} 		
} 
