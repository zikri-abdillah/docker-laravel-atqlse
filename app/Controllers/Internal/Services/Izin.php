<?php

namespace App\Controllers\Internal\Services;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;


class Izin extends BaseController
{
	protected $logStart;

	function __construct()
	{
        helper('api');
    }

	public function index()
    {
    	$param['addJS'] = '<script src="' . base_url() . '/js/ws/izin.js?v='.date('YmdHis').'"></script>';
    	$param['content'] = $this->render('services.check_izin.index');

    	return $this->render('layout.template', $param);
    }

    public function act()
    {
    	$config = new \stdClass();
    	$config->traffic = 'OUT';
    	$config->method = 'POST';
    	$config->endPoint = 'https://ws.kemendag.go.id/surveyor/check_izin';//base_url().'api/check_izin';
    	// $config->endPoint = 'https://services.kemendag.go.id/surveyor/1.0/check_izin';//base_url().'api/check_izin';
    	$config->apiKeyName = 'x-Gateway-APIKey';
    	$config->apiKey = '38ba63e6-a4df-4bec-b256-c6b80aa8dfbd';

    	try {
			$nib 		= $this->request->getPost('nib');
			$npwp 		= clean_npwp($this->request->getPost('npwp'));
			$no_izin 	= $this->request->getPost('noIzin');
			$tgl_izin 	= reverseDate($this->request->getPost('tglIzin'));
			$flProbis 	= $this->request->getPost('probis');
			$errNote 	= "";

			if(empty(trim($nib))){ 
				$errNote .= "- NIB <br/>";
			} 
			
			if(empty(trim($npwp))){  
				$errNote .= "- NPWP <br/>"; 
			}
			
			if(empty(trim($flProbis))){  
				$errNote .= "- Jenis izin <br/>"; 
			}

			if(empty(trim($no_izin))){  
				$errNote .= "- Nomor Izin <br/>"; 
			}
			
			if(empty(trim($tgl_izin))){  
				$errNote .= "- Tanggal Izin <br/>"; 
			}

			if($errNote == ""){ 
				$idlog = NULL;
				$postData = [
					'nib'		=> $nib,
					'npwp'		=> $npwp,
					'no_izin'	=> $no_izin,
					'tgl_izin'	=> $tgl_izin,
					'flProbis'	=> $flProbis,
					'username'	=>'asiatrust',
				];
	
				if($this->request->getPost('idtku') != ''){
					$postData['idtku']=$this->request->getPost('idtku');
				}
	
				$config->payload = $postData; 
				$log 			 = service_log($idlog,$config);
				$idlog 			 = $log->id; 
				$response 		 = curlClient($config,$postData); 
				$deCodeResponse  = json_decode($response->getBody());

				if(isset($deCodeResponse->kepatuhan)){
					$datalog['response'] = $response;
					$datalog['responseCode'] = $deCodeResponse->kepatuhan->kode;
					$datalog['responseMsg'] = $deCodeResponse->kepatuhan->keterangan;
					$log = service_log($idlog,$config,$datalog);
				}
				else{
					$datalog['response'] = $response;
					$datalog['responseCode'] = $deCodeResponse->kode;
					$datalog['responseMsg'] = $deCodeResponse->keterangan;
					$log = service_log($idlog,$config,$datalog); 
				}

				if(isset($deCodeResponse->kepatuhan) && $deCodeResponse->kepatuhan->kode == 'A01'){
					$modelHeader 	= model('t_izin_inatradeHdr');  
					$modelKepatuhan = model('t_izin_inatradeKepatuhan');
					$modelKomoditas = model('t_izin_inatradeKomoditas'); 
					$modelPort 		= model('t_izin_inatradePort'); 
					$modelNegara 	= model('t_izin_inatradeNegara'); 

					$dataHeader  	= $modelHeader->where('npwp', $deCodeResponse->header->npwp);
					$dataHeader->where('npwp', $deCodeResponse->header->npwp);
					$dataHeader->where('no_izin', $deCodeResponse->header->no_izin);
					$dataHeader->where('tgl_awal', $deCodeResponse->header->tgl_awal);
					$dataHeader->where('tgl_akhir', $deCodeResponse->header->tgl_akhir);
	
					$totalData 	= $dataHeader->countAllResults(false);
					$dataHeader = $dataHeader->findAll();
					$respHapus = "";
					if($totalData > 0){
						$idHdrBase = ''; 
						foreach ($dataHeader as $key => $data) {
							$idHdrBase .= $data->id.",";
						} 
						$idHdrBase = rtrim($idHdrBase, ',');

						if ($modelHeader->delete($idHdrBase) !== false) {  
							if ($modelKepatuhan->where('idHdr', $idHdrBase)->delete() !== false) { 
								if ($modelKomoditas->where('idHdr', $idHdrBase)->delete() !== false) {  
									if ($modelPort->where('idHdr', $idHdrBase)->delete() !== false) {  
										if ($modelNegara->where('idHdr', $idHdrBase)->delete() !== false) {  
											$respHapus = ""; 
										} else {
											$arrError = $modelKomoditas->errors();
											
											foreach($arrError as $x => $val) { 
												$errorText .= "<br> - ".$val;
											} 
											$respHapus = resp_error('Perhatikan pesan berikut:'.$errorText);
										}  
									} else {
										$arrError = $modelKomoditas->errors();
										
										foreach($arrError as $x => $val) { 
											$errorText .= "<br> - ".$val;
										} 
										$respHapus = resp_error('Perhatikan pesan berikut:'.$errorText);
									}   
								} else {
									$arrError = $modelKomoditas->errors();
									
									foreach($arrError as $x => $val) { 
										$errorText .= "<br> - ".$val;
									} 
									$respHapus = resp_error('Perhatikan pesan berikut:'.$errorText);
								}  
							} else {
								$arrError = $modelKepatuhan->errors();
								
								foreach($arrError as $x => $val) { 
									$errorText .= "<br> - ".$val;
								} 
								$respHapus = resp_error('Perhatikan pesan berikut:'.$errorText);
							} 
						} else {
							$arrError = $modelHeader->errors();
							
							foreach($arrError as $x => $val) { 
								$errorText .= "<br> - ".$val;
							} 
							$respHapus = resp_error('Perhatikan pesan berikut:'.$errorText);
						}  
					} 
					
					$arrHeader = []; 
					$arrHeader['nib'] 				= $deCodeResponse->header->nib;
					$arrHeader['npwp'] 				= $deCodeResponse->header->npwp;
					$arrHeader['nitku'] 			= $deCodeResponse->header->nitku;
					$arrHeader['nama_perusahaan'] 	= $deCodeResponse->header->nama_perusahaan;
					$arrHeader['no_izin'] 			= $deCodeResponse->header->no_izin;
					$arrHeader['kd_izin'] 			= $deCodeResponse->header->kd_izin;
					$arrHeader['status_izin'] 		= $deCodeResponse->header->status_izin;
					$arrHeader['tgl_izin'] 			= $deCodeResponse->header->tgl_izin;
					$arrHeader['tgl_awal'] 			= $deCodeResponse->header->tgl_awal;
					$arrHeader['tgl_akhir'] 		= $deCodeResponse->header->tgl_akhir;
					$arrHeader['dateCreated'] 		= date('Y-m-d H:i:s');
					
					if($respHapus === ""){ 
						if ($modelHeader->insert($arrHeader) !== false) { 
							$idHdr 								= $modelHeader->insertID();
							$arrKepatuhan 						= [];  
							$arrKepatuhan['idHdr'] 				= $idHdr;
							$arrKepatuhan['kode'] 				= $deCodeResponse->kepatuhan->kode;
							$arrKepatuhan['keterangan'] 		= $deCodeResponse->kepatuhan->keterangan; 
							$arrKomoditas 						= [];  
							$arrNegara 							= []; 
							$arrPort 							= [];
							$jumlahKomoditas 					=  0;
							$jumlahBerhasil 					=  0;

							if ($modelKepatuhan->insert($arrKepatuhan) !== false) {  
								foreach($deCodeResponse->komoditas as $val) {   
									$arrKomoditas['idHdr'] 				= $idHdr;
									$arrKomoditas['seri'] 				= $val->seri;
									$arrKomoditas['pos_tarif'] 			= $val->pos_tarif;
									$arrKomoditas['ur_barang'] 			= $val->ur_barang;
									$arrKomoditas['spesifikasi'] 		= $val->spesifikasi;
									$arrKomoditas['jml_volume'] 		= $val->jml_volume;
									$arrKomoditas['terpakai_ls'] 		= $val->terpakai_ls;
									$arrKomoditas['terpakai_booking'] 	= $val->terpakai_booking;
									$arrKomoditas['sisa_volume'] 		= $val->sisa_volume;
									$arrKomoditas['avail_volume'] 		= $val->avail_volume;
									$arrKomoditas['jns_satuan'] 		= $val->jns_satuan;
									
									if ($modelKomoditas->insert($arrKomoditas) !== false) { 
										$idDtl 							= $modelKomoditas->insertID();
										 
										if(count($val->plb_asal) > 0){
											foreach($val->plb_asal as $item) {
												$port 						= model('port')->where('kode',$item)->first()->uraian;
												$arrPort['idHdr'] 			= $idHdr;
												$arrPort['idDtl']			= $idDtl;
												$arrPort['seriDtl']			= $val->seri;
												$arrPort['type'] 			= "ASAL";
												$arrPort['idPort'] 			= $item;
												$arrPort['uraiPort'] 		= $port;
												
												$modelPort->insert($arrPort);
											}
										}
										
										if(count($val->plb_muat) > 0){
											foreach($val->plb_muat as $item) {
												$port 						= model('port')->where('kode',$item)->first()->uraian;
												$arrPort['idHdr'] 			= $idHdr;
												$arrPort['idDtl'] 			= $idDtl;
												$arrPort['seriDtl']			= $val->seri;
												$arrPort['type'] 			= "MUAT";
												$arrPort['idPort'] 			= $item;
												$arrPort['uraiPort'] 		= $port;
												
												$modelPort->insert($arrPort);
											}
										}
										
										if(count($val->plb_tujuan) > 0){
											foreach($val->plb_tujuan as $item) { 
												$port 						= model('port')->where('kode',$item)->first()->uraian;
												$arrPort['idHdr'] 			= $idHdr;
												$arrPort['idDtl'] 			= $idDtl;
												$arrPort['seriDtl']			= $val->seri;
												$arrPort['type'] 			= "TUJUAN";
												$arrPort['idPort'] 			= $item;
												$arrPort['uraiPort'] 		= $port;
												
												$modelPort->insert($arrPort);
											}
										}

										if(count($val->plb_bongkar) > 0){
											foreach($val->plb_bongkar as $item) {
												$port 						= model('port')->where('kode',$item)->first()->uraian;
												$arrPort['idHdr'] 			= $idHdr;
												$arrPort['idDtl'] 			= $idDtl;
												$arrPort['seriDtl']			= $val->seri;
												$arrPort['type'] 			= "BONGKAR";
												$arrPort['idPort'] 			= $item;
												$arrPort['uraiPort'] 		= $port;
												
												$modelPort->insert($arrPort);
											}
										}
 
										if(count($val->neg_asal) > 0){
											foreach($val->neg_asal as $item) {
												$arrNegara['idHdr'] 		= $idHdr;
												$arrNegara['idDtl'] 		= $idDtl;
												$arrNegara['seriDtl']		= $val->seri;
												$arrNegara['type'] 			= "ASAL";
												$arrNegara['idNegara'] 		= $item;
												$arrNegara['uraiNegara'] 	= model('negara')->where('kode',$item)->first()->nama;
												
												$modelNegara->insert($arrNegara);
											}
										}
 
										if(count($val->neg_muat) > 0){
											foreach($val->neg_muat as $item) {
												$arrNegara['idHdr'] 		= $idHdr;
												$arrNegara['idDtl'] 		= $idDtl;
												$arrNegara['seriDtl']		= $val->seri;
												$arrNegara['type'] 			= "BONGKAR";
												$arrNegara['idNegara'] 		= $item;
												$arrNegara['uraiNegara'] 	= model('negara')->where('kode',$item)->first()->nama;
												
												$modelNegara->insert($arrNegara);
											}
										}

										if(count($val->neg_tujuan) > 0){
											foreach($val->neg_tujuan as $item) {
												$arrNegara['idHdr'] 		= $idHdr;
												$arrNegara['idDtl'] 		= $idDtl;
												$arrNegara['seriDtl']		= $val->seri;
												$arrNegara['type'] 			= "TUJUAN";
												$arrNegara['idNegara'] 		= $item;
												$arrNegara['uraiNegara'] 	= model('negara')->where('kode',$item)->first()->nama;
												
												$modelNegara->insert($arrNegara);
											}
										}

										$jumlahBerhasil++;
									} else {
										$arrError = $modelKomoditas->errors();
										
										foreach($arrError as $x => $val) { 
											$errorText .= "<br> - ".$val;
										} 
										$resp = resp_error('Perhatikan pesan berikut:'.$errorText);
										
										return $this->response->setJSON($resp);
									} 

									$jumlahKomoditas++;
								}
				
								if($jumlahKomoditas === $jumlahBerhasil){
									$resp = resp_success($deCodeResponse->kepatuhan->keterangan, $deCodeResponse);
								} else {
									$resp = resp_error('Data komoditas gagal disimpan');
								} 		
							} else {
								$arrError = $modelKepatuhan->errors();
								
								foreach($arrError as $x => $val) { 
									$errorText .= "<br> - ".$val;
								} 
								$resp = resp_error('Perhatikan pesan berikut:'.$errorText);
							}
						} else {  
							$arrError = $modelHeader->errors();
							
							foreach($arrError as $x => $val) { 
								$errorText .= "<br> - ".$val;
							} 
							$resp = resp_error('Perhatikan pesan berikut:'.$errorText);
						} 

						return $this->response->setJSON($resp); 
					} else { 
						$resp = resp_error($respHapus);
						return $this->response->setJSON($resp); 
					}
					
				}else if(isset($deCodeResponse->kode)){
					$resp = resp_error($deCodeResponse->keterangan.' ('.$deCodeResponse->error[0]->elemen.' - '.$deCodeResponse->error[0]->keterangan.')');
				} else { 
					$resp = resp_error($deCodeResponse->kepatuhan->keterangan);
				}
				return $this->response->setJSON($resp);
			}  else {
				$resp = resp_error("Silahkan lengkapi data berikut:<br/>".$errNote);
				return $this->response->setJSON($resp);
			}
    	} catch (\Throwable $e) {
    		// $datalog['error'] = $e;
    		// log_error($idlog,$config,$datalog);
    		var_dump($e);
    	}
    }
}

?>