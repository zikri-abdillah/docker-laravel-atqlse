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
class Batubara extends BaseController
{
    protected $logStart;
    protected $jenisLS;
    protected $idLS;
    protected $payload;
    //protected $xmlWriter;
    //protected $lse_document;
    protected $dataLS;
    protected $rootFileUrl;

    function __construct()
    {
        helper('api');
        $this->jenisLS = '01881'; // pastikan sudah sesuai
        $this->payload = new \stdClass();
        $this->rootFileUrl = 'https://appls.atq-lse.co.id';

        // $this->xmlWriter = new \DOMDocument();
        // $this->xmlWriter->encoding = 'utf-8';
        // $this->xmlWriter->xmlVersion = '1.0';
        // $this->xmlWriter->formatOutput = true;

        // $this->lse_document = $this->xmlWriter->createElement("LSE_Document");
        // $this->xmlWriter->appendChild($this->lse_document);
    }

    // Hati2 funsgi ini tetap hit ke endpoint yang ditentukan pada fungsi send act!!!!!
    // Testing menggunakan method get agar bisa langsung di hit dari browser url = services/xml/mineral/test_send_inatrade
    public function test_send_doc_simbara()
    {
        $postId = $this->request->getPost('idLs');
        if($postId)
        {
            $this->idLS = decrypt_id($postId);
            $this->dataLS = $this->db->table('tx_lsehdr')->where('id',$this->idLS)->get()->getRow();
            if(empty($this->dataLS))
            {
                $resp = resp_error('Aksi dibatalkan, status data tidak valid');
                qq($resp);
            }
            else
            {

                /* GENERATE XML HERE */
                $this->header();
                $this->barang();
                $this->referensi();
                $this->komoditas(true);
                $this->batubara();
                $this->package();
                $this->container();
                $this->payload->username = 'asiatrust';
                $this->payload = $this->filterArray($this->payload);

                $send = $this->send_act();
                return $this->response->setJSON($send);

                //echo json_encode($this->payload);
                //qq($this->payload);
                exit;
            }
        }
    }

    private function send_act()
    {
        try {

            $config             = new \stdClass();
            $config->traffic    = 'OUT';
            $config->method     = 'POST';

            $config->endPoint   = 'https://ws.kemendag.go.id/surveyor/send_doc_simbara';
            // $config->endPoint   = 'https://services.kemendag.go.id/surveyor/1.0/send_doc_simbara';
            $config->apiKeyName = 'x-Gateway-APIKey';
            $config->apiKey     = '38ba63e6-a4df-4bec-b256-c6b80aa8dfbd';

            $config->payload = $this->payload;

            $idlog = NULL;
            $dataLog['idData']= $this->idLS;
            if(!empty($this->dataLS->ajuNSW))
                $aju = $this->dataLS->ajuNSW;
            else
                $aju = $this->dataLS->draftNo;

            $log = service_log($idlog,$config,$dataLog,$aju);
            $idlog = $log->id;

            $response         = curlClient($config,$config->payload);
            $deCodeResponse   = json_decode($response->getBody());
            //qq($deCodeResponse);

            $datalog['response']        = $response;
            $datalog['responseCode']    = $deCodeResponse->data->kode;
            $datalog['responseMsg']     = $deCodeResponse->data->keterangan;
            $log                        = service_log($idlog,$config,$datalog,$aju);

            //$log = service_log($idlog,$config,$datalog,$aju);
            $ret = json_decode( json_encode($datalog) , 1);
            if($deCodeResponse->data->kode == 'A01'){

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

                $resp = resp_success('Data berhasil terkirim.<br>Respon inatrade = '.$deCodeResponse->data->keterangan, NULL);
                return $resp;
            }
            else{

                $dataLS = $this->db->table('tx_lsehdr')->where('id',$this->idLS)->get()->getRow();
                $dataLogProses['idLS'] = $this->idLS;
                $dataLogProses['logAction'] = 'Gagal Kirim Inatrade';
                if(isset($datalog['responseMsg']))
                {
                    $respMsg = $datalog['responseMsg'];
                }
                $dataLogProses['note']      = $respMsg;

                save_log_process($dataLogProses,$dataLS);

                $resp = resp_error('Data gagal terkirim.<br>Respon inatrade = '.$deCodeResponse->data->keterangan, NULL);
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

    private function header()
    {
        //qq($this->dataLS);
        $jenisLS = $this->db->table('m_jenisls')->where('id',$this->dataLS->idJenisLS)->get()->getRow();
        $IUP = $this->db->table('m_jenis_iup')->where('id',$this->dataLS->idJnsIUP)->get()->getRow();
        if(empty($jenisLS->kodeinatrade))
            exit;

        if(!empty($this->dataLS->ajuNSW))
            $aju = $this->dataLS->ajuNSW;
        else
            $aju = $this->dataLS->noPveb;

        $this->payload->header = [
            "jns_penerbitan" => $this->dataLS->idJenisTerbit,
            "no_permohonan" => $aju,
            "kode_ls" => $jenisLS->kodeinatrade,
            "no_ls" => $this->dataLS->noLs,
            "tgl_ls" => $this->dataLS->tglLs,
            "tgl_expired" => $this->dataLS->tglAkhirLs,
            "keterangan" => "",
            "url_ls" => $this->rootFileUrl.'/view/file/'.$this->dataLS->fileUrl,
        ];
        $this->payload->header = array_filter($this->payload->header);

        $npwp = $this->dataLS->npwp;
        if(!empty($this->dataLS->npwp16))
            $npwp = $this->dataLS->npwp16;

        $this->payload->eksportir = [
            "npwp" => $npwp,
            "nitku" => $this->dataLS->nitku,
            "nib" => $this->dataLS->nib,
            "jns_iup" => $IUP->kdInatrade,
            "nama" => $this->dataLS->namaPersh,
            "alamat" => substr($this->dataLS->alamatPersh, 0,100),
            "kd_kota" => $this->dataLS->kdKotaInatrade,
            "kd_prov" => $this->dataLS->kdPropInatrade,
            "kodepos" => $this->dataLS->kodepos,
        ];
        $this->payload->eksportir = array_filter($this->payload->eksportir);

        $this->payload->importir = [
            "nama" => $this->dataLS->namaImportir,
            "alamat" => substr($this->dataLS->alamatImportir, 0, 100),
            "kd_kota" => $this->dataLS->kdKotaImportirInatrade,
            "kd_negara" => $this->dataLS->kdNegaraImportir,
        ];
        $this->payload->importir = array_filter($this->payload->importir);

        $this->payload->transportation = [
            "cara_angkut" => $this->dataLS->kodeModaTransport,
            "plb_muat" => $this->dataLS->kodePortMuat,
            "plb_transit" => $this->dataLS->kodePortTransit,
            "neg_transit" => $this->dataLS->kodeNegaraTransit,
            "plb_tujuan" => $this->dataLS->kodePortTujuan,
            "neg_tujuan" => substr($this->dataLS->kodePortTujuan, 0,2),
            "transport_name" => $this->dataLS->namaTransport,
            "transport_number" => $this->dataLS->voyage,
            "transport_country" => $this->dataLS->kodeBenderaKapal,
            "tgl_muat" => $this->dataLS->tglMuat,
            "tgl_berangkat" => $this->dataLS->tglBerangkat,
        ];
        $this->payload->transportation = array_filter($this->payload->transportation);
    }

    private function referensi()
    {
        $prefix = 'docref/';
        $pathUrl = 'https://appls.atq-lse.co.id/'.$prefix;

        $referensis = $this->db->table('tx_lse_referensi')
        ->join('m_jenis_dokumen', 'm_jenis_dokumen.id = tx_lse_referensi.idJenisDok')
        ->join('t_dokpersh', 't_dokpersh.id = tx_lse_referensi.idDokPersh')
        ->select('kodeInatrade,noDokumen,tglDokumen,tglAkhirDokumen,negaraPenerbit,CONCAT("'.$pathUrl.'", url) as url')->where('idLs',$this->idLS)->where('m_jenis_dokumen.kodeInatrade','101')->get()->getResult();

        $dataReferensi = [];
        foreach ($referensis as $key => $referensi) {
            $data = [
                "jns_dok" => $referensi->kodeInatrade,
                "no_dokumen" => $referensi->noDokumen,
                "tgl_dokumen" => $referensi->tglDokumen,
                "neg_penerbit" => $referensi->negaraPenerbit,
                "url_dok" => $referensi->url,
            ];
            $data = array_filter($data);
            $dataReferensi[] = $data;
        }
        $this->payload->referensi = $dataReferensi;
    }

    private function barang()
    {
        $this->payload->barang = [
            "jml_barang_netto" => $this->dataLS->qtyNetto,
            "jml_barang_bruto" => $this->dataLS->qtyBruto,
            "incoterm" => $this->dataLS->kodeIncoterm,
            "nilai_invoice" => $this->dataLS->nilaiInvoice,
            "currency" => $this->dataLS->currencyInvoice,
            "nilai_invoice_idr" => $this->dataLS->nilaiInvoiceIDR,
            "nilai_invoice_usd" => $this->dataLS->nilaiInvoiceUSD,
            "tgl_periksa" => $this->dataLS->tglPeriksa,
            "lokasi_periksa" => $this->dataLS->kodeLokasiPeriksa,
            "catatan_periksa" => $this->dataLS->catatanPeriksa,
        ];
        $this->payload->barang = array_filter($this->payload->barang);
    }

    private function komoditas($royalti=false)
    {
        $dataBarangs = $this->db->table('tx_lsedtlhs')->where('idLs',$this->idLS)->get()->getResult();
        $komoditas = [];
        if($royalti)
        {
            foreach ($dataBarangs as $key => $dataBarang) {
                $komoditas[] = [
                    "seri" => $dataBarang->seri,
                    "seri_izin" => $dataBarang->seriIzin,
                    "pos_tarif" => $dataBarang->postarif,
                    "ur_barang" => $dataBarang->uraianBarang,
                    "spesifikasi" => $dataBarang->sepesifikasi,
                    "neg_asal" => $dataBarang->kdNegaraAsal,
                    "jml_barang" => $dataBarang->jumlahBarang,
                    "satuan" => $dataBarang->kdSatuanBarang,
                    "berat_bersih" => $dataBarang->beratBersih,
                    "harga_barang" => $dataBarang->hargaBarang,
                    "currency" => $dataBarang->currencyHargaBarang,
                    "harga_barang_idr" => $dataBarang->hargaBarangIdr,
                    "harga_barang_usd" => $dataBarang->hargaBarangUsd,
                    "no_iup" => substr($dataBarang->noIup, 0,50),
                    "tgl_iup" => $dataBarang->tglIup,
                    "royalti" => $this->royalti($dataBarang->id)
                ];
            }
        }
        else{
            foreach ($dataBarangs as $key => $dataBarang) {
                $komoditas[] = [
                    "seri" => $dataBarang->seri,
                    "seri_izin" => $dataBarang->seriIzin,
                    "pos_tarif" => $dataBarang->postarif,
                    "ur_barang" => $dataBarang->uraianBarang,
                    "spesifikasi" => $dataBarang->sepesifikasi,
                    "neg_asal" => $dataBarang->kdNegaraAsal,
                    "jml_barang" => $dataBarang->jumlahBarang,
                    "satuan" => $dataBarang->kdSatuanBarang,
                    "berat_bersih" => $dataBarang->beratBersih,
                    "harga_barang" => $dataBarang->hargaBarang,
                    "currency" => $dataBarang->currencyHargaBarang,
                    "harga_barang_idr" => $dataBarang->hargaBarangIdr,
                    "harga_barang_usd" => $dataBarang->hargaBarangUsd,
                    //"no_iup" => $dataBarang->noIup,
                    //"tgl_iup" => $dataBarang->tglIup
                ];
            }
        }
        $this->payload->komoditas = $komoditas;
        //qq($this->payload->komoditas);

    }

    private function royalti($idPostarif)
    {
        $royaltis = $this->db->table('tx_lsehsntpn')
                        ->join('tx_lse_ntpn', 'tx_lse_ntpn.id = tx_lsehsntpn.idNtpn')
                        ->select('tx_lse_ntpn.*')
                        ->where('tx_lsehsntpn.idLs',$this->idLS)->where('tx_lsehsntpn.idPosTarif',$idPostarif)->get()->getResult();
        $dataRoyalti = [];
        foreach ($royaltis as $key => $ntpn) {
            if(isset($royalti))
                unset($royalti);
            $propinsi = $this->db->table('m_propinsi')->where('id',$ntpn->idProp)->get()->getRow();

            $npwp = $ntpn->npwp;
            if(strlen(trim($npwp)) < 16)
            {
                $pershNpwp16 = $this->db->table('temp_npwp')->where('npwp15',trim($npwp))->get()->getRow();
                if(!empty($pershNpwp16->npwp16))
                    $npwp = $pershNpwp16->npwp16;
            }

            $royalti = [
                        "no_ntpn" => $ntpn->noNtpn,
                        "tgl_ntpn" => $ntpn->tglNtpn,
                        "nib" => $ntpn->nib,
                        "npwp" => $npwp,
                        "nitku" => $ntpn->nitku,
                        "nama" => $ntpn->nama,
                        "jns_iup" => $ntpn->idJnsIUP,
                        "volume" => $ntpn->volume,
                        "satuan" => $ntpn->kdSatuan,
                        "kd_prop" => $propinsi->kodeInatrade,
                        "royalti" => $ntpn->royalti,
                        "currency" => $ntpn->currency,
                    ];
            $royalti = array_filter($royalti);
            $dataRoyalti[] = $royalti;
        }
        return $dataRoyalti;
    }

    private function batubara()
    {
        $batubaras = $this->db->table('tx_lse_kalori')->where('idLs',$this->idLS)->get()->getResult();
        $dataBatubara = [];
        foreach ($batubaras as $key => $batubara) {
            $dataBarang = $this->db->table('tx_lsedtlhs')->where('id',$batubara->idPosTarif)->get()->getRow();
            $data = [
                "seri" => $dataBarang->seri,
                "cal_arb" => $batubara->calArb,
                "cal_adb" => $batubara->calAdb,
                "tm_arb" => $batubara->tmArb,
                "tash_adb" => $batubara->tAsh,
                "tsulf_adb" => $batubara->tSulfur,
                "klasifikasi_bb" => $batubara->klasifikasiBatubara,
                "keterangan" => $batubara->keterangan
            ];
            $data = array_filter($data);
            $dataBatubara[] = $data;
        }
        $this->payload->batubara = $dataBatubara;
    }

    private function package()
    {
        $packages = $this->db->table('tx_lse_package')->where('idLs',$this->idLS)->get()->getResult();
        $dataPackage = [];
        foreach ($packages as $key => $package) {
            $data = [
                "seri" => $package->seri,
                "package_info" => $package->packageInfo,
                "jml_package" => $package->jml,
                "unit" => $package->unit,
            ];
            $data = array_filter($data);
            $dataPackage[] = $data;
        }
        $this->payload->package = $dataPackage;
    }

    private function container()
    {
        $packages = $this->db->table('tx_lse_container')->where('idLs',$this->idLS)->get()->getResult();
        $dataContainer = [];
        foreach ($packages as $key => $package) {
            $data = [
                "seri" => $package->seri,
                "jns_kontainer" => $package->kode,
                "no_kontainer" => $package->nomor
            ];
            $data = array_filter($data);
            $dataContainer[] = $data;
        }
        $this->payload->kontainer = $dataContainer;
    }

    private function filterArray($array) {
        $filteredArray = [];

        foreach ($array as $key => $value) {
            // Jika nilai adalah array, lakukan filter rekursif
            if (is_array($value)) {
                $value = $this->filterArray($value);
                // Tambahkan ke hasil jika tidak kosong
                if (!empty($value)) {
                    $filteredArray[$key] = $value;
                }
            } else {
                // Tambahkan ke hasil jika bukan null ,  bukan string kosong dan -
                if ($value !== null && $value !== '' && $value !== '-') {
                    $filteredArray[$key] = $value;
                }
            }
        }

        return $filteredArray;
    }
}

?>