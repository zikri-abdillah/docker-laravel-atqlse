<?php

namespace App\Controllers\Internal\Services;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 *  $jenisLS = kode jenis ls pada inatrade pastikan sesuaikan dengan komoditi yang akan dikirim
 *
 *  perhatikan variable $config pada fungsi send_act
 *  pastikan setting berikut sesuai
 *  $config->endPoint
 *  $config->apiKeyName
 *  $config->apiKey
 *  $config->method
 */
class Mineral_xml extends BaseController
{
	protected $logStart;
	protected $jenisLS;
	protected $idLS;
	protected $xmlWriter;
	protected $lse_document;
	protected $dataLS;

	function __construct()
	{
        helper('api');
        $this->jenisLS = '01881'; // pastikan sudah sesuai

		$this->xmlWriter = new \DOMDocument();
		$this->xmlWriter->encoding = 'utf-8';
		$this->xmlWriter->xmlVersion = '1.0';
		$this->xmlWriter->formatOutput = true;

		$this->lse_document = $this->xmlWriter->createElement("LSE_Document");
		$this->xmlWriter->appendChild($this->lse_document);
    }

    // Hati2 funsgi ini tetap hit ke endpoint yang ditentukan pada fungsi send act!!!!!
    // Testing menggunakan method get agar bisa langsung di hit dari browser url = services/xml/mineral/test_send_inatrade
    public function test_send_inatrade()
    {
    	$postId = encrypt_id(53);
    	if($postId)
    	{
    		$this->idLS = decrypt_id($postId);
    		$this->dataLS = $this->db->table('tx_lsehdr')->where('id',$this->idLS)->where('statusKirim','READY')->get()->getRow();
    		if(empty($this->dataLS))
    		{
    			$resp = resp_error('Aksi dibatalkan, status data tidak valid');
    			qq($resp);
    		}
    		else
    		{

	    		/* GENERATE XML HERE */
	    		$this->xml_header();
	    		$this->xml_barang();

	    		//$this->xml_kalori(); // Mineral tidak mengirim kalori
	    		//$this->xml_asal_barang(); // Mineral tidak mengirim asal barang

	    		$this->send_act();
	    	}
    	}
    }

    public function send_inatrade()
    {
    	$postId = $this->request->getPost('idLs');
    	if($postId)
    	{
    		$this->idLS = decrypt_id($postId);
    		$this->dataLS = $this->db->table('tx_lsehdr')->where('id',$this->idLS)->where('statusKirim','READY')->get()->getRow();
    		if(empty($this->dataLS))
    		{
    			$resp = resp_error('Aksi dibatalkan, status data tidak valid');
    			return $this->response->setJSON($resp);
    		}
    		else
    		{
	    		/* GENERATE XML HERE */
	    		$this->xml_header();
	    		$this->xml_barang();

	    		//$this->xml_kalori(); // Mineral tidak mengirim kalori
	    		//$this->xml_asal_barang(); // Mineral tidak mengirim asal barang

	    		$send = $this->send_act();
	    		return $this->response->setJSON($send);
	    	}
    	}
    }

    private function xml_header()
    {
    	$headerLS = $this->dataLS;

    	// from m_jenis_iup
    	$arrayIUPOP = [1,3,5,6,7,8];
    	$arrayIUPOPKPP = [4];
    	$arrayIUPOPOLAH = [2];

    	$noiupop = $tgliupop = $noiupopkpp = $tgliupopkpp = $noiupopkolah = $tgliupopkolah = '';
    	if(in_array($headerLS->idJnsIUP, $arrayIUPOP)){
    		$noiupop = $headerLS->noIUP;
    		$tgliupop = $headerLS->tglIUP;
    	}
    	else if(in_array($headerLS->idJnsIUP, $arrayIUPOPKPP)){
    		$noiupopkpp = $headerLS->noIUP;
    		$tgliupopkpp = $headerLS->tglIUP;
    	}
    	else if(in_array($headerLS->idJnsIUP, $arrayIUPOPOLAH)){
    		$noiupopkolah = $headerLS->noIUP;
    		$tgliupopkolah = $headerLS->tglIUP;
    	}


		$header = $this->lse_document->appendChild($this->xmlWriter->createElement('header'));

		$kdprop = $kdkab = '';
		$kdPropInatrade = $this->db->table('tbldmpropinsi')->where('kdTemp',$headerLS->kdPropInatrade)->get()->getRow();
		if(!empty($headerLS->kdPropInatrade))
			$kdprop = $kdPropInatrade->kdprop;

		$kdKabInatrade = $this->db->table('tbldmkabupaten')->where('KdTemp',$headerLS->kdKotaInatrade)->get()->getRow();
		if(!empty($headerLS->kdKotaInatrade))
			$kdkab = $kdKabInatrade->KdKab;

    	$headerElements = array(
	        'npwp' => $headerLS->npwp,
	        'refnumber' => $headerLS->ajuNSW,
	        'jenisLS' => $this->jenisLS,
	        'status' => '001',
	        'namaPerusahaan' => $headerLS->namaPersh,
	        'alamatPerusahaan' => $headerLS->alamatPersh,
	        'telepon' => $headerLS->telpPersh,
	        'fax' => $headerLS->faxPersh,
	        'emailPerusahaan' => $headerLS->emailPersh,
	        'idkab' => $kdkab,
	        'idprop' => $kdprop,
	        'no_ls' => $headerLS->noLs,
	        'tgl_ls' => $headerLS->tglLs,
	        'tgl_ls_expired' => $headerLS->tglAkhirLs,
	        'no_izin' => '',
	        'tgl_izin' => '',
	        'tgl_izin_expired' => '',
	        'noizinterdaftar' => $headerLS->noET,
	        'tglizinterdaftar' => $headerLS->tglET,
	        'tglizinterdaftar_expired' => $headerLS->tglAkhirET,
	        'no_lc' => $headerLS->noLc,
	        'tgl_lc' => $headerLS->tglLc,
	        'tgl_lc_expired' => '',
	        'modetransport' => $headerLS->modaTransport,
	        'cargotype' => $headerLS->cargoType,
	        'ionumber' => $headerLS->noPveb,
	        'bendera' => $headerLS->benderaKapal,
	        'jeniskapal' => $headerLS->tipeMuat,
	        'kapkapal' => $headerLS->kapasitasKapal,
	        'asuransi' => $headerLS->namaAsuransiKargo,
	        'catatanperiksa' => $headerLS->catatanPeriksa,
	        'tempatperiksa' => $headerLS->lokasiPeriksa,
	        'tglperiksa' => $headerLS->tglPeriksa,
	        'portperiksa' => $headerLS->kodeLokasiPeriksa,
	        'nopacklist' => $headerLS->noPackingList,
	        'tglpacklist' => $headerLS->tglPackingList,
	        'noinvoice' => $headerLS->noInvoice,
	        'tglinvoice' => $headerLS->tglInvoice,
	        'namaclient' => $headerLS->namaImportir,
	        'alamatclient' => $headerLS->alamatImportir,
	        'negaraclient' => $headerLS->kdNegaraImportir,
	        'tglmuat' => $headerLS->tglMuat,
	        'namavessel' =>$headerLS->namaTransport,
	        'tglmuatvessel' => $headerLS->tglMuat,

	        'noiupop' => $noiupop,
	        'tgliupop' => $tgliupop,
	        'noiupopkpp' => $noiupopkpp,
	        'tgliupopkpp' => $tgliupopkpp,
	        'noiupopkolah' => $noiupopkolah,
	        'tgliupopkolah' => $tgliupopkolah,

	        'nobayarroyalti' => '',
	        'tglbayarroyalti' => '',
	        'nopetikemas' => $headerLS->voyage,
	        'nosegel' => $headerLS->voyage,
	        'kesimpulan' => $headerLS->kesimpulanPeriksa,
	        'gross_weight' => $headerLS->qtyBruto,
	        'net_weight' => $headerLS->qtyNetto,
	        'unit' => $headerLS->satuanNetto
	    );

	    $arrayCDATA = ['alamatPerusahaan','alamatclient'];

	    foreach ($headerElements as $elementName => $value) {
	    	if(in_array($elementName, $arrayCDATA))
	        	$header->appendChild($this->xmlWriter->createElement($elementName))->appendChild($this->xmlWriter->createCDATASection($value));
	    	else
	        	$header->appendChild($this->xmlWriter->createElement($elementName,$value));
	    }

    }

    private function xml_barang()
    {

    	$dataBarangs = $this->db->table('tx_lsedtlhs')->where('idLs',$this->idLS)->get()->getResult();

    	$detailBarang = $this->lse_document->appendChild($this->xmlWriter->createElement('detailBarang'));

    	foreach ($dataBarangs as $key => $dataBarang) {

    		$valCurrency = round($dataBarang->hargaBarangIdr / $dataBarang->hargaBarang,4);

    		$detailBarangElements = array(
		        'idposTarif'=>$dataBarang->seri, 'posTarif'=>$dataBarang->postarif, 'jmlVolume'=>$dataBarang->jumlahBarang, 'satuan'=>$dataBarang->kdSatuanBarang, 'uraian_barang'=>$dataBarang->uraianBarang,
		        'kdNegaraTujuan'=>$this->dataLS->kodeNegaraTujuan, 'kdNegaraAsal'=>$dataBarang->kdNegaraAsal, 'kdNegaraMuat'=>substr($this->dataLS->kodePortMuat, 0,2), 'kdPortAsal'=>$this->dataLS->kodePortMuat,
		        'kdPortBongkar'=>$this->dataLS->kodePortTujuan, 'kdPortMuat'=>$this->dataLS->kodePortMuat, 'value_currency'=>$valCurrency, 'currency'=>$dataBarang->currencyHargaBarang,
		        'value_fob'=>$dataBarang->hargaBarang, 'noiup'=>$dataBarang->noIup, 'tgliup'=>$dataBarang->tglIup, 'ska_type'=>'-'
		    );

	    	$loop = $detailBarang->appendChild($this->xmlWriter->createElement('loop'));

	    	foreach ($detailBarangElements as $elementName => $value) {
	    		$loop->appendChild($this->xmlWriter->createElement($elementName,$value));
	    	}
    	}
    }

    private function xml_kalori()
    {

    	$dataKaloris = $this->db->table('tx_lse_kalori')
    					->join('tx_lsedtlhs', 'tx_lsedtlhs.id = tx_lse_kalori.idPosTarif')
    					->select('tx_lse_kalori.*,tx_lsedtlhs.seri')
                        ->where('tx_lse_kalori.idLs',$this->idLS)->get()->getResult();

    	$detilBatubara = $this->lse_document->appendChild($this->xmlWriter->createElement('detilBatubara'));

    	foreach ($dataKaloris as $key => $dataKalori) {

    		$detilBatubaraElements = array(
		        'idposTarif'=>$dataKalori->seri, 'cal_arb'=>$dataKalori->calArb, 'cal_adb'=>$dataKalori->calAdb,
		        'tm_arb'=>$dataKalori->tmArb, 'tash_adb'=>$dataKalori->tAsh,'tsulf_adb'=>$dataKalori->tSulfur, 'klasifikasibb'=>$dataKalori->klasifikasiBatubara,
		        'keterangan'=>$dataKalori->keterangan
		    );

	    	$loop = $detilBatubara->appendChild($this->xmlWriter->createElement('loop'));

	    	$arrayCDATA = ['klasifikasibb'];
	    	foreach ($detilBatubaraElements as $elementName => $value) {
	    		if(in_array($elementName, $arrayCDATA))
		        	$loop->appendChild($this->xmlWriter->createElement($elementName))->appendChild($this->xmlWriter->createCDATASection($value));
		    	else
		        	$loop->appendChild($this->xmlWriter->createElement($elementName,$value));

	    	}
    	}
    }

    private function xml_asal_barang()
    {

    	$dataAsalBarangs = $this->db->table('tx_lsehsntpn')
    					->join('tx_lse_ntpn', 'tx_lse_ntpn.id = tx_lsehsntpn.idNtpn')
    					->join('tx_lsedtlhs', 'tx_lsedtlhs.id = tx_lsehsntpn.idPosTarif')
    					->select('tx_lse_ntpn.*,tx_lsedtlhs.seri as seriPostarif')
                        ->where('tx_lsehsntpn.idLs',$this->idLS)->get()->getResult();

        $detilBatubara = $this->lse_document->appendChild($this->xmlWriter->createElement('detilAsal'));

    	foreach ($dataAsalBarangs as $key => $asalBarang) {

    		$kdprop = '';
    		$kdPropInatrade = $this->db->table('tbldmpropinsi')->where('kdTemp',$asalBarang->kdPropInatrade)->get()->getRow();
    		if(!empty($asalBarang->kdPropInatrade))
    			$kdprop = $kdPropInatrade->kdprop;

    		$detilAsalElements = array(
		        'idseriAsal'=>$asalBarang->seri, 'idposTarif'=>$asalBarang->seriPostarif, 'nobayarroyalti'=>$asalBarang->noNtpn,
		        'tglbayarroyalti'=>$asalBarang->tglNtpn,'npwp'=>$asalBarang->npwp, 'nmpersh'=>$asalBarang->nama, 'idprop'=>$kdprop, 'nmprop'=>$asalBarang->namaProp,
		        'jmlVolume'=>$asalBarang->volume, 'satuan'=>$asalBarang->kdSatuan,'royalti'=>$asalBarang->royalti, 'kurs'=>$asalBarang->currency
		    );

    		$loop = $detilBatubara->appendChild($this->xmlWriter->createElement('loop'));

		    foreach ($detilAsalElements as $elementName => $value) {
		        $loop->appendChild($this->xmlWriter->createElement($elementName,$value));
		    }
    	}
    }

    private function send_act()
    {
    	$xml = $this->xmlWriter->saveXML($this->lse_document);

    	try {

    		$config 			= new \stdClass();
	    	$config->traffic 	= 'OUT';
	    	$config->method 	= 'POST';
	    	// $config->endPoint 	= 'https://gwi.kemendag.go.id/ws/ls_exim?wsdl';
	    	// $config->apiKeyName = 'username'; // isi username milik client
	    	// $config->apiKey 	= 'password'; // isi password milik client

	    	$config->endPoint 	= 'http://appls.pe.hu/dummy/inatrade/ls_exim'; // dummy
	    	$config->apiKeyName = 'TEST'; // dummy
	    	$config->apiKey 	= 'TEST'; // dummy

	    	$config->payload = $xml;

	    	$idlog = NULL;
	    	$dataLog['idData']= $this->idLS;
	    	if(!empty($this->dataLS->ajuNSW))
		    	$aju = $this->dataLS->ajuNSW;
		    else
		    	$aju = $this->dataLS->draftNo;

			$log = service_log($idlog,$config,$dataLog,$aju);
			$idlog = $log->id;

	    	$curl = curl_init();
	        curl_setopt_array($curl, array(
	        CURLOPT_URL => $config->endPoint,
	        CURLOPT_RETURNTRANSFER => true,
	        CURLOPT_ENCODING => '',
	        CURLOPT_MAXREDIRS => 10,
	        CURLOPT_TIMEOUT => 0,
	        CURLOPT_FOLLOWLOCATION => true,
	        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	        CURLOPT_CUSTOMREQUEST => $config->method,
	        CURLOPT_POSTFIELDS =>'<?xml version="1.0" encoding="utf-8"?>
	        <soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
	        <soap:Body>
	          <SurveyorSendDocLs xmlns="http://tempuri.org/">
	          <string0>'.$config->apiKeyName.'</string0>
	          <string1>'.$config->apiKey.'</string1>
	          <string2>
	          '.$xml.'
	          </string2>
	          </SurveyorSendDocLs>
	        </soap:Body>
	        </soap:Envelope>
	        ',
	        CURLOPT_HTTPHEADER => array(
	          'SOAPAction: SurveyorSendDocLs',
	          'Content-Type: application/xml',
	          'Cookie: BIGipServer~k8s~Shared~ingress_inatrade_be_surveyor=2371770378.55676.0000'
	        ),
	        ));
	        $response = curl_exec($curl);
	        curl_close($curl);

	        $datalog['response'] = $response;

	        libxml_use_internal_errors(true);
            $checkxml = simplexml_load_string($response);
            $responsecheck = $checkxml->children('http://schemas.xmlsoap.org/soap/envelope/')->Body->children()->SurveyorSendDocLsResponse;

	        if (!$responsecheck)
	        {
				$datalog['responseCode'] = '';
				$datalog['responseMsg'] = 'Not valid XML Response';
	        }
	        else
	        {
				$datalog['responseCode'] = (string) $responsecheck->kode;
				$datalog['responseMsg'] = (string) $responsecheck->msg;
	        }

			$log = service_log($idlog,$config,$datalog,$aju);
	        $ret = json_decode( json_encode($datalog) , 1);

	        if(isset($ret['responseCode']) && $ret['responseCode'] == '200')
	        {

	        	$this->db->table('tx_lsehdr')->set(['statusKirim'=>'SENT','waktuKirim'=>date('Y-m-d H:i:s')])->where('id', $this->idLS)->update();

	        	$dataLS = $this->db->table('tx_lsehdr')->where('id',$this->idLS)->get()->getRow();
	        	$dataLogProses['idLS']      = $this->idLS;
                $dataLogProses['logAction'] = 'Terkirim Inatrade';

                $respMsg = '';
                if(isset($datalog['responseMsg']))
                {
                	$respMsg = $datalog['responseMsg'];
                }
                $dataLogProses['note']      = $respMsg;
                save_log_process($dataLogProses,$dataLS);

	        	$resp = resp_success('Data berhasil terkirim.<br>Respon inatrade = '.$ret['responseMsg'], NULL);
				return $resp;
	        }
	        else
	        {
	        	$dataLS = $this->db->table('tx_lsehdr')->where('id',$this->idLS)->get()->getRow();
	        	$dataLogProses['idLS'] = $this->idLS;
                $dataLogProses['logAction'] = 'Gagal Kirim Inatrade';
                if(isset($datalog['responseMsg']))
                {
                	$respMsg = $datalog['responseMsg'];
                }
                $dataLogProses['note']      = $respMsg;

                save_log_process($dataLogProses,$dataLS);

	        	$resp = resp_error('Data gagal terkirim. '.$respMsg);
	        	return $resp;
	        }

    	} catch (\Throwable $e) {
    		$datalog['error'] = $e;
			log_error($idlog,$config,$datalog);

			$dataLS = $this->db->table('tx_lsehdr')->where('id',$this->idLS)->get()->getRow();
        	$dataLogProses['idLS']      = $this->idLS;
            $dataLogProses['logAction'] = 'Gagal Kirim Inatrade';
            $dataLogProses['note']      = $e->getMessage();
            save_log_process($dataLogProses,$dataLS);

			$resp = resp_error('Data gagal terkirim<br>Exception :'.$e->getMessage().$e->getLine());
			return $resp;
    	}
    }
}

?>