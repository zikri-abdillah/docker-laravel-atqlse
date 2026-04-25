<?php

namespace App\Controllers\Resources\Simbara;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use \Firebase\JWT\JWT;


class Permohonan extends ResourceController
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

	public function received()
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
	    	$resp = NULL;
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

	    			$cekAju = $this->db->table('tblPermohonan_pinsw')->where('nomorAju',$noAju)->whereIn('statusInsw',['010','020','030','050'])->get()->getRow();

	    			if(isset($cekAju->id))
	    			{
	    				$resp = $this->set_response('200','OK','A02','Nomor aju sudah ada dengan kode proses '.$cekAju->statusInsw,$noAju);
	    			}
	    			else
	    			{
	    				$pathLog = '';
		    			$insert = $this->insert_kirimPermohonanSurveyor($data,$pathLog);
		    			if($insert['status']){
		    				$resp = $this->set_response('200','OK','A01','Request Sukses',$noAju);
		    			}
		    			else{
		    				$resp = $this->set_response('200','OK','A02',$insert['error'],$noAju);
		    			}
	    			}
	    		}
	    	}
	    	return $this->response->setJSON($resp);
	    	exit;
    	} catch (\Throwable $e) {
    		var_dump($e->getTrace());
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

    function insert_kirimPermohonanSurveyor($data,$pathLog)
	{
		$noAju = NULL;
		if(!empty($data['header']['nomorAju']))
			$noAju = $data['header']['nomorAju'];
		$importir = $data['header']['importir'];
		$insert = $data['header'];
		$pengapalan = $data['header']['pengapalan'];
		$insert = array_merge($insert,$pengapalan);

		$insert['kodeLokasiMuatVessel'] = $pengapalan['lokasiMuatVessel'];
		$insert['lokasiMuatVessel'] = $this->db->table('tblLokasiMuatVessel_pinsw')->where('kode',$pengapalan['lokasiMuatVessel'])->get()->getRow()->uraian;
		unset($insert['eksportir']);
		unset($insert['importir']);
		unset($insert['dokumen']);
		unset($insert['pengapalan']);
		unset($insert['pelabuhan']);
		unset($insert['barang']);
		unset($insert['pelabuhan']);
		unset($insert['perusahaanAsuransiKapal']);
		unset($insert['perusahaanAsuransiCargo']);

		$insert = array_merge($insert,$data['header']['eksportir']);
		if(!isset($insert['npwp']) || empty($insert['npwp']))
			$insert['npwp'] = $data['header']['eksportir']['nomorIdentitas'];

		if(isset($importir['namaPerusahaan']))
			$insert['namaImportir'] = $importir['namaPerusahaan'];
		if(isset($importir['negaraPerusahaan']))
			$insert['negaraImportir'] = $importir['negaraPerusahaan'];
		if(!empty($insert['negaraImportir'])){
			$insert['uraiNegaraImportir'] = $this->db->table('m_negara')->where('kode',$insert['negaraImportir'])->get()->getRow()->nama;
		}
		if(isset($importir['alamatPerusahaan']))
			$insert['alamatImportir'] = $importir['alamatPerusahaan'];
		if(isset($importir['teleponPerusahaan']))
			$insert['teleponImportir'] = $importir['teleponPerusahaan'];
		if(isset($importir['faxPerusahaan']))
			$insert['faxImportir'] = $importir['faxPerusahaan'];
		if(isset($importir['emailPerusahaan']))
			$insert['emailImportir'] = $importir['emailPerusahaan'];


		if(isset($pengapalan['perusahaanAsuransiKapal']['nmPerusahaan']))
			$insert['nmPerusahaanAsuransiKapal'] = $pengapalan['perusahaanAsuransiKapal']['nmPerusahaan'];
		if(isset($pengapalan['perusahaanAsuransiKapal']['noPolisSertifikat']))
			$insert['noPolisSertifikatAsuransiKapal'] = $pengapalan['perusahaanAsuransiKapal']['noPolisSertifikat'];
		if(isset($pengapalan['perusahaanAsuransiKapal']['noSertifikat']))
			$insert['noSertifikatAsuransiKapal'] = $pengapalan['perusahaanAsuransiKapal']['noSertifikat'];
		if(isset($pengapalan['perusahaanAsuransiCargo']['nmPerusahaan']))
			$insert['nmPerusahaanAsuransiCargo'] = $pengapalan['perusahaanAsuransiCargo']['nmPerusahaan'];
		if(isset($pengapalan['perusahaanAsuransiCargo']['noPolisSertifikat']))
			$insert['noPolisSertifikatAsuransiCargo'] = $pengapalan['perusahaanAsuransiCargo']['noPolisSertifikat'];
		if(isset($pengapalan['perusahaanAsuransiCargo']['noSertifikat']))
			$insert['noSertifikatAsuransiCargo'] = $pengapalan['perusahaanAsuransiCargo']['noSertifikat'];

		$insert['pathJson'] = $pathLog;
		if(!empty($insert['jnsPengajuan'])){
			$insert['uraiJenisPengajuan'] = $this->db->table('tblJenisPengajuan_pinsw')->where('kode',$insert['jnsPengajuan'])->get()->getRow()->uraian;
		}

		if(!empty($insert['jenisIup'])){
			$insert['uraiJenisIup'] = $this->db->table('m_jenis_iup')->where('kdInsw',$insert['jenisIup'])->get()->getRow()->jenis;
		}

		if(!empty($insert['penjualan'])){
			$insert['uraiPenjualan'] = $this->db->table('tblPenjualan_pinsw')->where('kode',$insert['penjualan'])->get()->getRow()->uraian;
		}

		$created = date('Y-m-d H:i:s');
		$insert['created'] = $created;
		$insert['status'] = 'TERIMA';
		$insert['statusInsw'] = '010';
		$insert['uraiStatusInsw'] = 'TERKIRIM';
		$insert['timeStatusInsw'] = $created;

		$errMsg = '';
		try {
			//$this->db->transStart();
			$this->db->transException(true)->transStart();
			$cekAju = $this->db->table('tblPermohonan_pinsw')->where('nomorAju',$noAju)->whereIn('statusInsw',['010','020','030','050'])->get()->getRow();
			if(isset($cekAju->id))
			{
				$resp = $this->set_response('200','OK','A02','Nomor aju sudah ada dengan kode proses '.$cekAju->statusInsw,$noAju);
				return $this->response->setJSON($resp);
	    		exit;
			}
			$this->db->table('tblPermohonan_pinsw')->insert($insert);
			$idPermohonan = $this->db->insertID();

			$arrDokumen = $data['header']['dokumen'];
			foreach ($arrDokumen as $key => $dokumen) {
				$dokumen['idPermohonan'] = $idPermohonan;
				$dokumen['batch'] = 1;
				$dokumen['createdAt'] = date('Y-m-d H:i:s');
				$this->db->table('tblPermohonanDok_pinsw')->insert($dokumen);
			}

			$arrPelabuhan = $data['header']['pengapalan']['pelabuhan'];
			foreach ($arrPelabuhan as $key => $pelabuhan) {
				$pelabuhan['idPermohonan'] = $idPermohonan;
				$this->db->table('tblPermohonanPort_pinsw')->insert($pelabuhan);
			}

			$arrBarang = $data['header']['barang'];
			foreach ($arrBarang as $key => $barang) {
				if( strlen(trim($barang['kodeHs'])) == 8 ){

					$arrKerjasama = $barang['kerjasama'];
					unset($barang['kerjasama']);
					$barang['idPermohonan'] = $idPermohonan;

					$this->db->table('tblPermohonanBrg_pinsw')->insert($barang);
					$idBarang = $this->db->insertID();

					foreach ($arrKerjasama as $keys => $kerjasama) {
						$kerjasama['idPermohonan'] = $idPermohonan;
						$kerjasama['idBarang'] = $idBarang;
						$this->db->table('tblPermohonanBrgKerjasama_pinsw')->insert($kerjasama);
					}
				}
				else{
					$errMsg .= 'Seri barang '.$barang['seriBarang'].' kode hs tidak valid'.PHP_EOL;
				}
			}
			if($errMsg != ''){
				$this->db->transRollback();
				$resp['status'] = false;
				$resp['error'] = $errMsg;
				return $resp;
				exit;
			}

			// $log['idPermohonan'] = $idPermohonan;
			// $log['status'] = '010';
			// $log['markStatus'] = 'Terkirim';
			// $log['keterangan'] = NULL;
			// $log['userAct'] = 0;
			// $log['timeAct'] = $created;
			// $this->db->table('tblpermohonan_pinsw_log')->insert($log);
			save_log_simbara($idPermohonan,'010','Terkirim','Terima Pengajuan Simbara',NULL);

			$this->db->transComplete();
			if($this->db->transStatus() !== false){
				$resp['status'] = true;
				$resp['error'] = '';
			}
			else{
				$resp['status'] = false;
				$resp['error'] = 'Gagal menyimpan data';
			}
			return $resp;

		} catch (DatabaseException $e) {
		    $tagError = '';
		    if (str_contains($e->getMessage(), 'Unknown column')) {
			    $tagError = str_replace('Unknown column','', $e->getMessage());
			    $tagError = 'Cek tag json '.str_replace("in 'field list'","", $tagError);
			}
		    $resp['status'] = false;
			$resp['error'] = 'Gagal menyimpan data. '.$tagError;
		    return $resp;
		} catch (\Throwable $e) {
    		//var_dump($e);exit;
    		$resp = $this->set_response('200','OK','A02','Gagal memproses request',$noAju);
    		return $this->response->setJSON($e);
    	}
	}
}
