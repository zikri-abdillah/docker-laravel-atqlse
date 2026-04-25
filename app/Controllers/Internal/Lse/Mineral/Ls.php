<?php

namespace App\Controllers\Internal\Lse\Mineral;

use App\Controllers\BaseController;
use CodeIgniter\Files\File;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
// use Psr\Log\LoggerInterface;


class Ls extends BaseController
{
    protected $jenisPenerbitanModel;
    protected $cabangModel;
    protected $penandatanganModel;
    protected $idJenisLS;
    protected $jenisLS;
    protected $uri;
    protected $validation;

    function __construct()
    {
        $this->idJenisLS = 1;
        $jenisLsModel = model('jenisLs');
        $this->jenisLS = $jenisLsModel->where('id', $this->idJenisLS)->first()->jenis;
        $this->jenisPenerbitanModel = model('jenisPenerbitan');
        $this->cabangModel = model('cabang');
        $this->penandatanganModel = model('penandatangan');
        $this->uri = service('uri');
        $this->validation = \Config\Services::validation();
        helper('filesystem');
    }

    public function index($dataFilter)
    {
        $req = $this->request->getGet();

        if ($dataFilter == 'konsep') {
            $table_title        = 'Data LSE Mineral - Konsep ';
            $breadcrumb_active  = 'Konsep';
        } else if ($dataFilter == 'proses') {
            $table_title        = 'Data LSE Mineral - Proses';
            $breadcrumb_active  = 'Proses';
        } else if ($dataFilter == 'terbit') {
            $table_title        = 'Data LSE Mineral - Terbit';
            $breadcrumb_active  = 'Terbit';
        }

        $page = [
            'table_title'       => $table_title,
            'breadcrumb_active' => $breadcrumb_active,
            'dataFilter'        => $dataFilter,
        ];
        $param['content'] = $this->render('ekspor.mineral.ls.index', $page);

        $param['addJS'] = '<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>';
        $param['addJS'] .= '<script src="https://cdn.datatables.net/2.0.8/js/dataTables.min.js"></script>';
        $param['addJS'] .= '<script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.min.js"></script>';
        $param['addJS'] .= '<script src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.min.js"></script>';
        $param['addJS'] .= '<script src="https://cdn.datatables.net/responsive/3.0.2/js/responsive.bootstrap5.min.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . '/js/ekspor/mineral/mineral.js?v=' . date('YmdHis') . '"></script>';

        return $this->render('layout.template', $param);
    }

    public function input()
    {
        $arrJenisPenerbitan = $this->jenisPenerbitanModel->where('isActive', 'Y')->findAll();
        $arrCabang          = $this->cabangModel->where('isActive', 'Y')->findAll();
        $arrPenandatangan   = $this->penandatanganModel->where('isActive', 'Y')->findAll();
        $fieldsMandatory    = [];
        $fields             = $this->db->table('t_mandatory')->whereIn('section', ['header', 'komoditas'])->where('idJenisLs', '1')->get()->getResult();

        foreach ($fields as $key => $value) {
            $fieldsMandatory[$value->section][] = $value->fieldName;
        }

        $req                = $this->request->getPost();
        $page               = [
            'page_title'            => 'Input LSE Mineral',
            'arrJenisPenerbitan'    => $arrJenisPenerbitan,
            'arrCabang'             => $arrCabang,
            'fieldsMandatory'       => $fieldsMandatory
        ];

        $param['addJS'] = '<script src="' . base_url() . 'assets/plugins/formwizard/jquery.smartWizard.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/formwizard/fromwizard.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/jquery-steps/jquery.steps.min.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/parsleyjs/parsley.min.js"></script>';
        $param['addJS'] .= '<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/datatable/js/dataTables.bootstrap5.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/datatable/dataTables.responsive.min.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/datatable/responsive.bootstrap5.min.js"></script>';

        $param['addJS'] .= '<script>var mandatoryFields = ' . json_encode($fieldsMandatory) . ' </script>';
        $param['addJS'] .= '<script src="' . base_url() . '/js/ekspor/mineral/input.js?v=' . date('YmdHis') . '"></script>';

        $param['content'] = $this->render('ekspor.mineral.ls.input', $page);
        return $this->render('layout.template', $param);
    }

    public function view()
    {
        $id             = decrypt_id($this->request->getPost('id'));
        $datals         = model('tx_lseHdr')->find($id);
        $packages       = model('tx_lsePackage')->where('idLS', $id)->findAll();
        $containers     = model('tx_lseContainer')->where('idLS', $id)->findAll();
        $refModel       = model('tx_lseReferensi');
        $refModel->select('tx_lse_referensi.id as idref,t_dokpersh.*,m_negara.nama as negara');
        $refModel->join('t_dokpersh', 't_dokpersh.id = tx_lse_referensi.idDokPersh');
        $refModel->join('m_negara', 'm_negara.kode = t_dokpersh.negaraPenerbit');
        $refModel->where('tx_lse_referensi.idLS', $id);
        $references     = $refModel->findAll();
        $royaltis       = model('tx_lseNtpn')->where('idLS', $id)->findAll();
        $komoditas      = model('tx_lseDtlHs')->where('idLS', $id)->findAll();
        $modelKal       = model('tx_lseKalori');
        $modelKal->select("tx_lse_kalori.*, tx_lsedtlhs.seri,  tx_lsedtlhs.postarif");
        $modelKal->join('tx_lsedtlhs', 'tx_lsedtlhs.id = tx_lse_kalori.idPosTarif');
        $kalori         =  $modelKal->where('tx_lse_kalori.idLS', $id)->findAll();
        $dataperubahan  = [];

        if ($datals->idJenisTerbit == '2') {
            $idPerubahan    = $datals->idPerubahan;
            $dataperubahan  = model('tx_lseHdr')->where('id', $idPerubahan)->first();
        }

        $page = [
            'page_title'    => 'Data LSE Mineral',
            'datals'        => $datals,
            'packages'      => $packages,
            'containers'    => $containers,
            'references'    => $references,
            'royaltis'      => $royaltis,
            'komoditas'     => $komoditas,
            'kalori'        => $kalori,
            'dataperubahan' => $dataperubahan
        ];

        $param['addJS'] = '<script src="' . base_url() . 'assets/plugins/formwizard/jquery.smartWizard.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/formwizard/fromwizard.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/jquery-steps/jquery.steps.min.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/parsleyjs/parsley.min.js"></script>';
        $param['addJS'] .= '<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/datatable/js/dataTables.bootstrap5.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/datatable/dataTables.responsive.min.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/datatable/responsive.bootstrap5.min.js"></script>';

        $param['addJS'] .= '<script src="' . base_url() . '/js/ekspor/mineral/input.js?v=' . date('YmdHis') . '"></script>';

        $param['content'] = $this->render('ekspor.mineral.ls.view', $page);
        return $this->render('layout.template', $param);
    }

    public function edit()
    {
        try {
            $id                 = decrypt_id($this->request->getPost('id'));
            $arrJenisPenerbitan = $this->jenisPenerbitanModel->where('isActive', 'Y')->findAll();
            $arrCabang          = $this->cabangModel->where('isActive', 'Y')->findAll();
            $arrPenandatangan   = $this->penandatanganModel->where('isActive', 'Y')->findAll();

            $datals     = model('tx_lseHdr')->where('id', $id)->first();
            $komoditas  = model('tx_lseDtlHs')->where('idLS', $id)->findAll();
            $references = model('tx_lseReferensi')->where('idLS', $id)->findAll();
            $royaltis   = model('tx_lseNtpn')->where('idLS', $id)->findAll();

            $fields = $this->db->table('t_mandatory')->whereIn('section', ['header', 'komoditas', 'ntpn'])->where('idJenisLs', '1')->get()->getResult();
            foreach ($fields as $key => $value) {
                $fieldsMandatory[$value->section][] = $value->fieldName;
            }

            $page = [
                'page_title'            => 'Edit LSE Mineral',
                'arrJenisPenerbitan'    => $arrJenisPenerbitan,
                'arrCabang'             => $arrCabang,
                'datals'                => $datals,
                'komoditas'             => $komoditas,
                'references'            => $references,
                'royaltis'              => $royaltis,
                'fieldsMandatory'       => $fieldsMandatory,
            ];

            if (!empty($datals->currencyInvoice))
                $page['currencyInvoice'] = model('currency')->where('kode', $datals->currencyInvoice)->first()->uraian;

            if (!empty($datals->satuanNetto))
                $page['uraiSatuanNetto'] = model('satuan')->where('kodeSatuan', $datals->satuanNetto)->first()->uraiSatuan;

            $param['addJS'] = '<script src="' . base_url() . 'assets/plugins/formwizard/jquery.smartWizard.js"></script>';
            $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/formwizard/fromwizard.js"></script>';
            $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/jquery-steps/jquery.steps.min.js"></script>';
            $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/parsleyjs/parsley.min.js"></script>';
            $param['addJS'] .= '<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>';
            $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/datatable/js/dataTables.bootstrap5.js"></script>';
            $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/datatable/dataTables.responsive.min.js"></script>';
            $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/datatable/responsive.bootstrap5.min.js"></script>';

            $param['addJS'] .= '<script>var mandatoryFields = ' . json_encode($fieldsMandatory) . ' </script>';
            $param['addJS'] .= '<script src="' . base_url() . '/js/ekspor/mineral/input.js?v=' . date('YmdHis') . '"></script>';
            $param['addJS'] .= '<script src="' . base_url() . '/js/ekspor/mineral/edit.js?v=' . date('YmdHis') . '"></script>';

            $param['content'] = $this->render('ekspor.mineral.ls.input', $page);
            return $this->render('layout.template', $param);
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!' . $e->getMessage());
            return $this->response->setJSON($resp);
        }
    }

    public function save()
    {
        try {

            $fileUpload = $this->request->getFile('fileLS');
            if ($fileUpload) {
                $validationRule = [
                    'fileLS' => [
                        'rules' => [
                            'uploaded[fileLS]',
                            'mime_in[fileLS,image/jpg,image/jpeg,image/png,image/webp,application/pdf]',
                            'max_size[fileLS,5120]',
                        ],
                        'errors' => [
                            'uploaded' => 'File belum di pilih',
                            'mime_in' => 'File yang diupload harus berupa image atau pdf dengan format jpg/png/pdf',
                            'max_size' => 'Ukuran file maksimal 5 MB'
                        ]
                    ],
                ];
                if (! $this->validate($validationRule)) {
                    $resp = resp_error($this->validator->getError(), '', 'Upload Gagal');
                    return $this->response->setJSON($resp);
                } else {

                    if (! $fileUpload->hasMoved()) {
                        $pathFile = $fileUpload->store('filels/' . date('Ym'));
                    } else {
                        throw new \Exception('Gagal upload.');
                    }
                }
            }

            $postdata   = $this->request->getPost('postdata');
            $data       = post_ajax_toarray($postdata);
            $act        = decrypt_id($this->request->getPost('act'));
            $idData     = $data['idData'] ? decrypt_id($data['idData']) : '';
            $idJenis    = $data['idJenisTerbit'];
            $errExpDate = "";

            if ($act == 'SEND')
                $data['statusProses']   = 'REVIEW';
            else if ($act == 'ISSUED') {
                $data['statusDok']      = 'TERBIT';
                $data['statusProses']   = 'ISSUED';
                $data['statusKirim']    = 'READY';
                $data['issuedDateTime'] = date('Y-m-d H:i:s');
                $tanggalSekarang         = date('Y-m-d');
                $tanggalAkhir            = reverseDateDB($data['tglAkhirLs']);

                if ((empty($data['noLs'])) && ($data['noLs'] == '')) {
                    $errExpDate         .= '- Nomor LS tidak boleh kosong.<br>';
                }

                if ((empty($data['noSi'])) && ($data['noSi'] == '')) {
                    $errExpDate         .= '- Nomor SI tidak boleh kosong.<br>';
                }

                if ((empty($data['noPveb'])) && ($data['noPveb'] == '')) {
                    $errExpDate         .= '- Nomor PVEB/WO tidak boleh kosong.<br>';
                }

                // pengecekan tanggal akhir pada penerbitan LS, apakah tanggal akhir sudah kadalursa atau belum
                if ($tanggalAkhir < $tanggalSekarang) {
                    $errExpDate         .= '- Tanggal akhir LS harus lebih dari tanggal sekarang.<br>';
                }

                $header         = model('tx_lseHdr')->find($idData);
                $nomorET        = $header->noET;
                $tanggalET      = $header->tglET;
                $tanggalETAkhir = $header->tglAkhirET;

                if ((empty($tanggalET)) || ($tanggalET == '')) {
                    $errExpDate         .= '- Tanggal awal ET tidak boleh kosong<br>';
                }

                if ((empty($tanggalETAkhir)) || ($tanggalETAkhir == '')) {
                    $errExpDate         .= '- Tanggal akhir ET tidak boleh kosong<br>';
                } else {
                    if ($tanggalETAkhir < $tanggalSekarang) {
                        $errExpDate         .= '- Tanggal akhir ET harus lebih dari tanggal sekarang<br>';
                    }

                    if ($tanggalETAkhir < $tanggalAkhir) {
                        $errExpDate         .= '- Tanggal akhir LS melebihi tanggal akhir ET<br>';
                    }
                }
            } else if ($act == 'REFUSE')
                $data['statusProses']   = 'REFUSED';
            else if ($act == 'REVOKE')
                $data['statusProses']   = 'REVOKE';

            if (!empty($data['noSi']) || $data['noLs'] != '') {
                $errExpDate .= cek_karakter("Nomor SI", $data['noSi']);
            }

            if (!empty($data['noPveb']) || $data['noPveb'] != '') {
                $errExpDate .= cek_karakter("Nomor PVE / WO", $data['noPveb']);
            }

            if (!empty($data['noLs']) || $data['noLs'] != '') {
                $errExpDate .= cek_karakter("Nomor LSE", $data['noLs']);
            }

            if ($errExpDate == '') {
                $logBefore                  = NULL;

                if (!empty($data['idData'])) {
                    $data['id']             = decrypt_id($data['idData']);
                    $logBefore              = model('tx_lseHdr')->find($data['id']);
                    $dataLog['logAction']   = $act;
                    $data['lastUser']       = decrypt_id(session()->get('sess_userid'));
                    $data['lastUpdate']     = date('Y-m-d H:i:s');

                    if (empty($data['statusProses']))
                        $data['statusProses'] = $logBefore->statusProses;
                } else {
                    $dataLog['logAction']   = 'CREATE';
                    $data['userCreate']     = decrypt_id(session()->get('sess_userid'));
                    $data['created']        = date('Y-m-d H:i:s');

                    if ($act != 'SEND') {
                        $data['statusProses']   = 'PROCESS';
                    }
                }

                $masterStatus = $this->db->table('m_status_proses')->where('status', $data['statusProses'])->get()->getRow();

                if (empty($act) || empty($masterStatus)) {
                    $resp = resp_error('Invalid Action');
                    return $this->response->setJSON($resp);
                    exit;
                }

                unset($data['idData']);

                if (!empty($data['id'])) {
                    $dokModel   = model('tx_lseReferensi');
                    $dokModel->select('t_dokpersh.*');
                    $dokModel->join('t_dokpersh', 't_dokpersh.id = tx_lse_referensi.idDokPersh');
                    $dokModel->where('tx_lse_referensi.idLS', $data['id'])->where('t_dokpersh.npwp <> ', clean_npwp($data['npwp']));
                    $arrData    = $dokModel->findAll();

                    if (count($arrData) > 0) {
                        $resp = resp_error('Tidak dapat menyimpan. Terdapat dokumen referensi yang berbeda dengan NPWP eksportir');
                        return $this->response->setJSON($resp);
                        exit;
                    }
                }

                $lsModel        = model('tx_lseHdr');
                if (!empty($data['idJenisLS'])) {
                    $jnsLSModel = model('jenisLs');
                    $jnsLS      = $jnsLSModel->find($data['idJenisLS']);

                    if (empty($data['id'])) {
                        $data['draftIncrement'] = $lsModel->select('IFNULL(MAX(draftIncrement),0)+1 as noDraft', false)->where('YEAR(created)', DATE('Y'))->get()->getRow()->noDraft;
                        $noDraft                = str_pad($data['draftIncrement'], 4, "0", STR_PAD_LEFT);
                        $data['draftNo']        = DATE('y') . '/' . DATE('m') . '/' . PREFIX_NUMBERING . '/' . $noDraft;
                    }
                    $data['jenisLS']            = $jnsLS->jenis;
                    $data['tglDraft']           = date('Y-m-d');
                }

                if (!empty($data['idPersh'])) {
                    $perusahaan             = model('perusahaan')->where('id', $data['idPersh'])->first();
                    $data['bentukPersh']    = $perusahaan->bentukPersh;
                    $data['namaPersh']      = $perusahaan->nama;
                }

                $data['namaCabang']     = $this->request->getPost('namaCabang');
                $data['namaTtd']        = $this->request->getPost('namaTtd');
                $data['tglSi']          = reverseDateDB($data['tglSi']);
                $data['tglPveb']        = reverseDateDB($data['tglPveb']);
                $data['tglLs']          = reverseDateDB($data['tglLs']);
                $data['tglAkhirLs']     = reverseDateDB($data['tglAkhirLs']);
                $data['jenisTerbit']    =  model('jenisPenerbitan')->find($data['idJenisTerbit'])->jenis;
                $data['npwp']           = clean_npwp($data['npwp']);
                $data['npwp16']         = clean_npwp($data['npwp16']);
                $data['jenisIUP']       = $this->request->getPost('jenisIUP');
                $data['tglIUP']         = reverseDateDB($data['tglIUP']);
                $data['tglPolis']       = reverseDateDB($data['tglPolis']);
                $data['tglAkhirPolis']  = reverseDateDB($data['tglAkhirPolis']);
                $data['nilaiPolisUSD']  = insertNumber($data['nilaiPolisUSD']);

                if (!empty($data['kdProp'])) {
                    $propEks                = model('propinsi')->where('id', $data['kdProp'])->first();
                    $data['kdPropInatrade'] = $propEks->kodeInatrade;
                    $data['namaProp']       = $propEks->namaPropinsi;
                }

                if (!empty($data['kdKota'])) {
                    $kotaEks                = model('kota')->where('id', $data['kdKota'])->first();
                    $data['kdKotaInatrade'] = $kotaEks->kodeInatrade;
                    $data['namaKota']       = $kotaEks->namaKota;
                }

                if (!empty($data['kdKotaImportir'])) {
                    $kotaImp                        = model('kota')->where('id', $data['kdKotaImportir'])->first();
                    $data['kdKotaImportirInatrade'] = $kotaImp->kodeUNLOCODE;
                    $data['kotaImportir']           = $kotaImp->namaKota;
                }

                if (!empty($data['kdNegaraImportir'])) {
                    $negaraImp              = model('negara')->where('kode', $data['kdNegaraImportir'])->first();
                    $data['negaraImportir'] = $negaraImp->nama;
                }

                $data['incoterm']           = $this->request->getPost('incoterm');
                $data['qtyNetto']           = insertNumber($data['qtyNetto']);
                $data['tglPackingList']     = reverseDateDB($data['tglPackingList']);
                $data['tglLc']              = reverseDateDB($data['tglLc']);
                $data['tglInvoice']         = reverseDateDB($data['tglInvoice']);
                if (isset($data['satuanNetto']))
                    $data['satuanNetto']        = $data['satuanNetto'];
                $data['qtyBruto']           = insertNumber($data['qtyBruto']);
                if (isset($data['satuanNetto']))
                    $data['satuanBruto']        = $data['satuanNetto'];
                $data['nilaiInvoice']       = insertNumber($data['nilaiInvoice']);
                $data['nilaiInvoiceIDR']    = insertNumber($data['nilaiInvoiceIDR']);
                $data['nilaiInvoiceUSD']    = insertNumber($data['nilaiInvoiceUSD']);
                $data['modaTransport']      = $this->request->getPost('modaTransport');
                $data['kapasitasKapal']     = insertNumber($data['kapasitasKapal']);

                if (!empty($data['kodeLokasiPeriksa'])) {
                    $port                   = model('port')->where('kode', $data['kodeLokasiPeriksa'])->first();
                    if (isset($port->uraian))
                        $data['lokasiPeriksa']  = $port->uraian;
                }

                if (!empty($data['kodePortMuat'])) {
                    $port                   = model('port')->where('kode', $data['kodePortMuat'])->first();
                    if (isset($port->uraian))
                        $data['portMuat']       = $port->uraian;
                }

                if (!empty($data['kodePortTransit'])) {
                    $port                   = model('port')->where('kode', $data['kodePortTransit'])->first();
                    $data['portTransit']    = $port->uraian;
                }

                if (!empty($data['kodePortTujuan'])) {
                    $port                   = model('port')->where('kode', $data['kodePortTujuan'])->first();
                    if (isset($port->uraian))
                        $data['portTujuan']     = $port->uraian;
                }

                if (!empty($data['kodeBenderaKapal'])) {
                    $negaraImp              = model('negara')->where('kode', $data['kodeBenderaKapal'])->first();
                    $data['benderaKapal']   = $negaraImp->nama;
                }

                $data['tglPeriksa']         = reverseDateDB($data['tglPeriksa']);
                $data['tglMuat']            = reverseDateDB($data['tglMuat']);
                $data['tglMuatAkhir']            = reverseDateDB($data['tglMuatAkhir']);
                $data['tglBerangkat']       = reverseDateDB($data['tglBerangkat']);

                if (!empty($data['kodeNegaraTransit'])) {
                    $negaraImp              = model('negara')->where('kode', $data['kodeNegaraTransit'])->first();
                    $data['negaraTransit']  = $negaraImp->nama;
                }

                if (!empty($data['kodeNegaraTujuan'])) {
                    $negaraImp              = model('negara')->where('kode', $data['kodeNegaraTujuan'])->first();
                    $data['negaraTujuan']   = $negaraImp->nama;
                }

                if (isset($pathFile)) {
                    $data['fileLS'] = $pathFile;
                    $data['fileUrl'] = md5($pathFile);
                } else {
                    $dataLS = $lsModel->find($idData);
                    if ($dataLS) {
                        $data['fileLS'] = $dataLS->fileLS;
                        $data['fileUrl'] = $dataLS->fileUrl;
                    }
                }

                $errMandatory = $this->cek_mandatory($data, 'HEADER', $act);

                if (count($errMandatory) == 0) {
                    if ($idJenis == 1) {

                        $dataDuplikasi = array(
                            array(
                                "fieldNo"    => "noSi",
                                "valNo"      => $data['noSi'],
                                "fieldNAwal" => "tglSi",
                                "valAwal"    => $data['tglSi'],
                                "fieldAkhir" => "",
                                "valAkhir"   => "",
                                // "note"       => "SI nomor #nomor# tanggal #awal# sudah digunakan pada LSE dengan nomor draft #draft#"),
                                "note"       => "SI nomor #nomor# sudah digunakan"
                            ),
                            array(
                                "fieldNo"    => "noPveb",
                                "valNo"      => $data['noPveb'],
                                "fieldNAwal" => "tglPveb",
                                "valAwal"    => $data['tglPveb'],
                                "fieldAkhir" => "",
                                "valAkhir"   => "",
                                // "note"       => "PVEB/WO nomor #nomor# tanggal #awal# sudah digunakan pada LSE dengan nomor draft #draft#"),
                                "note"       => "PVEB/WO nomor #nomor# sudah digunakan"
                            ),
                            array(
                                "fieldNo"    => "noLs",
                                "valNo"      => $data['noLs'] ? $data['noLs'] : '',
                                "fieldNAwal" => "tglLs",
                                "valAwal"    => $data['tglLs'],
                                "fieldAkhir" => "tglAkhirLs",
                                "valAkhir"   => $data['tglAkhirLs'],
                                // "note"       => "LS nomor #nomor# tanggal #awal# s.d #akhir# sudah digunakan pada LSE dengan nomor draft #draft#")
                                "note"       => "LS nomor #nomor# sudah digunakan"
                            )
                        );

                        $errDuplikasi  =  $this->cek_duplikasi($dataDuplikasi, 'tx_lseHdr', $idData);

                        if ($errDuplikasi != '') {
                            $resp    = resp_error('Perhatikan pesan berikut : <br>' . $errDuplikasi);
                            return $this->response->setJSON($resp);
                            exit;
                        }
                    }

                    $this->db->transStart();
                    $lsModel->upsert($data);
                    if (!empty($lsModel->insertID()))
                        $idLs = $lsModel->insertID();
                    else
                        $idLs = $data['id'];

                    $dataLS              = $lsModel->find($idLs);
                    $idJenisTerbit       = $dataLS->idJenisTerbit;
                    $statusProses        = $dataLS->statusProses;
                    $statusDok           = $dataLS->statusDok;
                    $idPerubahan         = $dataLS->idPerubahan;
                    $draftNo             = $dataLS->draftNo;
                    $respData['id']      = encrypt_id($idLs);
                    $respData['act']     = $act;
                    $respData['fileUrl'] = $dataLS->fileUrl;
                    $dataLog['idLS']     = $idLs;
                    $dataLog['note']     = '';

                    save_log_process($dataLog, $logBefore);

                    $this->create_qrtoken($idLs);

                    $this->db->transComplete();

                    if ($this->db->transStatus() === false) {
                        $resp    = resp_error('Data gagal disimpan (Transaction failed)');
                    } else {
                        if ($act == 'ISSUED') {
                            if ($idJenisTerbit = '2') {
                                if ($statusDok == 'TERBIT' && $statusProses == 'ISSUED' && session()->get('sess_role') == 7) {
                                    $this->update_old_lse($idPerubahan, 'ISSUED', 'Penerbitan LS Ekspor dengan nomor draft ' . $draftNo);
                                }
                            }

                            if (!empty($dataLS->idPermohonanNSW)) {
                                $this->db->table('tblPermohonan_pinsw')->set(['status' => 'TERBIT', 'statusInsw' => '050'])->where('id', $dataLS->idPermohonanNSW)->update();
                                save_log_simbara($dataLS->idPermohonanNSW, '050', 'Proses Penerbitan', $mark = '', $keterangan = 'Issued LS No ' . $dataLS->noLs);
                            }

                            $msg = 'LS berhasil diterbitkan. Anda akan segera dialihkan ke halaman LS Terbit, klik \'Ok\' untuk berpindah sekarang ';
                        } else if ($act == 'SEND')
                            $msg = 'Data berhasil disimpan dan dikirim ke supervisor';
                        else
                            $msg = 'Data berhasil disimpan';

                        $resp    = resp_success($msg, $respData);
                    }
                } else {
                    $textErr = implode('<br>', $errMandatory);
                    $resp    = resp_error('Perhatikan isian berikut <br>' . $textErr);
                }
                return $this->response->setJSON($resp);
            } else {
                $resp = resp_error($errExpDate);
                return $this->response->setJSON($resp);
            }
        } catch (\Throwable $e) {
            if (isset($pathFile))
                unlink(WRITEPATH . 'uploads/' . $pathFile);
            $resp = resp_error('An Exception has occured!' . $e->getMessage());
            return $this->response->setJSON($resp);
        }
    }

    function create_qrtoken($idls)
    {
        $token = $this->db->table('t_lse_qrcode')->where('idls', $idls)->get()->getRow();
        if (!$token) {
            $created = date('Y-m-d H:i:s');
            $token = hash('sha256', encrypt_id($idls . $created));
            $insert['idls'] = $idls;
            $insert['token'] = $token;
            $insert['url'] = QRCODE_URL . '/' . $token;
            $insert['ceatedAt'] = $created;
            $this->db->table('t_lse_qrcode')->insert($insert);
        }
    }

    public function view_file()
    {
        try {
            $idLS = decrypt_id($this->request->getPost('id'));
            $dataLS = model('tx_lseHdr')->find($idLS);
            $path = WRITEPATH . 'uploads/' . $dataLS->fileLS;
            if (file_exists($path))
                return $this->response->download($path, null, true)->inline();
            else
                return redirect()->to('filenotfound');
        } catch (\Throwable $e) {
            $resp = 'An Exception has occured at Resources view file';
            return $this->response->setJSON($resp);
        }
    }

    public function delete_file()
    {
        $idLS = decrypt_id($this->request->getPost('idLs'));
        $dataLS = model('tx_lseHdr')->find($idLS);
        if (file_exists(WRITEPATH . 'uploads/' . $dataLS->fileLS))
            unlink(WRITEPATH . 'uploads/' . $dataLS->fileLS);

        if (model('tx_lseHdr')->where('id', $idLS)->set(['fileLS' => NULL, 'fileUrl' => NULL, 'lastUpdate' => date('Y-m-d H:i:s')])->update())
            $resp = resp_success('File berhasil dihapus');
        else
            $resp = resp_error('File gagal dihapus');

        return $this->response->setJSON($resp);
    }

    public function add_package()
    {
        try {
            $data['idLs'] = decrypt_id($this->request->getPost('idLs'));
            $data['unit'] = $this->request->getPost('p_unit');
            $data['jml'] = insertNumber($this->request->getPost('p_jml'));
            $data['packageInfo'] = $this->request->getPost('p_packageInfo');

            if (!empty($this->request->getPost('idPackage')))
                $data['id'] = decrypt_id($this->request->getPost('idPackage'));

            if (!empty($data['unit'])) {
                $unit = model('package')->where('kode', $data['unit'])->first();
                $data['unit'] = $unit->kode;
                $data['uraiUnit'] = $unit->uraian;
            }

            $errMandatory = $this->cek_mandatory($data, 'PACKAGE');

            if (count($errMandatory) > 0) {
                $textErr = implode('<br>', $errMandatory);
                $resp = resp_error('Perhatikan isian berikut <br>' . $textErr);
            } else {
                $model = model('tx_lsePackage');
                $model->upsert($data);
                $resp = resp_success('Data berhasil disimpan');
            }

            $idData = $model->insertID();
            $this->update_seri_package($data['idLs']);

            return $this->response->setJSON($resp);
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!' . $e->getMessage());
            return $this->response->setJSON($resp);
        }
    }

    public function get_package()
    {
        try {
            $idLs = decrypt_id($this->request->getPost('idLs'));
            $dataLS = model('tx_lseHdr')->find($idLs);
            $packageModel = model('tx_lsePackage');
            $dataPackage = $packageModel->where('idLs', $idLs)->findAll();

            $html = '';
            foreach ($dataPackage as $key => $package) {

                $btnAct = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-danger me-2" onclick="del_package(\'' . encrypt_id($package->id) . '\')" title="Hapus"><i class="fa fa-trash"></i></button>
                                <button type="button" class="btn btn-sm btn-w-xs btn-icon btn-warning" onclick="edit_package(\'' . encrypt_id($package->id) . '\')" title="Edit"><i class="fa fa-edit"></i></button>';

                if (!in_array($dataLS->statusProses, ['PROCESS', 'REVIEW']))
                    $btnAct = '';
                else if (session()->get('sess_role') == 7 && !config('App')->spv_can_edit)
                    $btnAct = '';

                $html .= '<tr >
                            <td class="align-top text-nowrap text-center">' . ($key + 1) . '</td>
                            <td class="align-top">' . $package->packageInfo . '</td>
                            <td class="align-top text-nowrap">' . $package->jml . '</td>
                            <td class="align-top">' . $package->uraiUnit . ' - ' . $package->unit . '</td>
                            <td class="align-top text-nowrap text-center">
                                ' . $btnAct . '
                            </td>
                        </tr>';
            }
            $resp['content'] = $html;
            return $this->response->setJSON($resp);
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!' . $e->getMessage());
            return $this->response->setJSON($resp);
        }
    }

    public function del_package()
    {
        try {

            $id = decrypt_id($this->request->getPost('id'));
            $idLs = decrypt_id($this->request->getPost('idLs'));

            $delete = model('tx_lsePackage')->delete($id);
            if ($delete) {
                $resp = resp_success('Package berhasil dihapus');
            } else {
                $resp = resp_error('Package gagal dihapus');
            }
            return $this->response->setJSON($resp);
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!' . $e->getMessage());
            return $this->response->setJSON($resp);
        }
    }

    public function edit_package()
    {
        try {
            $id = decrypt_id($this->request->getPost('id'));
            $data = model('tx_lsePackage')->find($id);
            $data->id = encrypt_id($data->id);

            return $this->response->setJSON($data);
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!' . $e->getMessage());
            return $this->response->setJSON($resp);
        }
    }

    public function add_container()
    {
        try {
            $data['idLs'] = decrypt_id($this->request->getPost('idLs'));
            $data['nomor'] = $this->request->getPost('cnt_nomor');
            $data['idJenis'] = $this->request->getPost('cnt_jenis');
            $data['noSegel'] = $this->request->getPost('cnt_noSegel');

            if (!empty($this->request->getPost('idContainer')))
                $data['id'] = decrypt_id($this->request->getPost('idContainer'));

            if (!empty($data['idJenis'])) {
                $container = model('jenisContainer')->where('id', $data['idJenis'])->first();
                $data['kode'] = $container->kode;
                $data['keterangan'] = $container->keterangan;
                $data['panjang'] = $container->panjang_Ft;
                $data['tinggi'] = $container->tinggi_Ft;
            }

            $errMandatory = $this->cek_mandatory($data, 'CONTAINER');

            if (count($errMandatory) > 0) {
                $textErr = implode('<br>', $errMandatory);
                $resp = resp_error('Perhatikan isian berikut <br>' . $textErr);
            } else {
                $model = model('tx_lseContainer');
                $model->upsert($data);
                $resp = resp_success('Data berhasil disimpan');
            }

            $idData = $model->insertID();
            $this->update_seri_container($data['idLs']);

            return $this->response->setJSON($resp);
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!' . $e->getMessage());
            return $this->response->setJSON($resp);
        }
    }

    public function get_container()
    {
        try {
            $idLs = decrypt_id($this->request->getPost('idLs'));
            $dataLS = model('tx_lseHdr')->find($idLs);
            $containerModel = model('tx_lseContainer');
            $dataContainer = $containerModel->where('idLs', $idLs)->findAll();

            $html = '';
            foreach ($dataContainer as $key => $container) {

                $btnAct = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-danger me-2" onclick="del_container(\'' . encrypt_id($container->id) . '\')" title="Hapus"><i class="fa fa-trash"></i></button>
                            <button type="button" class="btn btn-sm btn-w-xs btn-icon btn-warning" onclick="edit_container(\'' . encrypt_id($container->id) . '\')" title="Edit"><i class="fa fa-edit"></i></button>';

                if (!in_array($dataLS->statusProses, ['PROCESS', 'REVIEW']))
                    $btnAct = '';
                else if (session()->get('sess_role') == 7 && !config('App')->spv_can_edit)
                    $btnAct = '';

                $html .= '<tr >
                            <td class="align-top text-nowrap text-center">' . ($key + 1) . '</td>
                            <td class="align-top">' . $container->kode . ' - ' . $container->keterangan . '<br>' . $container->panjang . ' X ' . $container->tinggi . ' Ft</td>
                            <td class="align-top text-nowrap">' . $container->nomor . '</td>
                            <td class="align-top text-nowrap">' . $container->noSegel . '</td>
                            <td class="align-top text-nowrap text-center">
                                ' . $btnAct . '
                            </td>
                        </tr>';
            }
            $resp['content'] = $html;
            return $this->response->setJSON($resp);
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!' . $e->getMessage());
            return $this->response->setJSON($resp);
        }
    }

    public function del_container()
    {
        try {

            $id = decrypt_id($this->request->getPost('id'));
            $idLs = decrypt_id($this->request->getPost('idLs'));

            $delete = model('tx_lseContainer')->delete($id);
            if ($delete) {
                $resp = resp_success('Container berhasil dihapus');
            } else {
                $resp = resp_error('Container gagal dihapus');
            }
            return $this->response->setJSON($resp);
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!' . $e->getMessage());
            return $this->response->setJSON($resp);
        }
    }

    public function edit_container()
    {
        try {
            $id = decrypt_id($this->request->getPost('id'));
            $data = model('tx_lseContainer')->find($id);
            $data->id = encrypt_id($data->id);

            return $this->response->setJSON($data);
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!' . $e->getMessage());
            return $this->response->setJSON($resp);
        }
    }

    public function list_dok()
    {
        $npwp       = clean_npwp($this->request->getPost('npwp'));
        $dokModel   = model('t_dokpersh');
        $arrData    = $dokModel->where('npwp', $npwp)->where('tglAkhirDokumen >=', date('Y-m-d'))->findAll();
        $html       = '';
        $btnEdit    = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-warning me-1" onclick="edit_dok_persh(\'' . encrypt_id($data->id) . '\')" title="Edit"><i class="fa fa-edit"></i></button>';
        $btnDelete  = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-danger" onclick="del_dok_persh(\'' . encrypt_id($data->id) . '\')" title="Hapus"><i class="fa fa-trash"></i></button>';
        $btnView    = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-info" onclick="view_dok_persh(\'' . encrypt_id($data->id) . '\')" title="Lihat File"><i class="fa fa-eye"></i></button> ';

        foreach ($arrData as $key => $data) {
            $html .= '<tr >
                        <td class="align-top text-nowrap text-center"><label class="ckbox" for="ckbox-dok_' . $key . '"><input type="checkbox" class="ckbox-dok" data-iddata="' . encrypt_id($data->id) . '" id="ckbox-dok_' . $key . '"><span>' . ($key + 1) . '</span></label></td>
                        <td class="align-top">' . $data->jenisDok . '</td>
                        <td class="align-top">' . $data->negaraPenerbit . '</td>
                        <td class="align-top text-nowrap">' . $data->noDokumen . '</td>
                        <td class="align-top text-nowrap">' . reverseDate($data->tglDokumen) . '</td>
                        <td class="align-top text-nowrap">' . reverseDate($data->tglAkhirDokumen) . '</td>
                        <td class="align-top text-nowrap text-center">' . $btnEdit . $btnDelete . $btnView . '</td>
                    </tr>';
        }
        $resp['html'] = $html;
        return $this->response->setJSON($resp);
    }

    public function save_dok_pilih()
    {
        if (!empty($this->request->getPost('dokpilih'))) {
            try {
                $reffModel  = model('tx_lseReferensi');
                $idData     = decrypt_id($this->request->getPost('idData'));
                // $postdata   = json_decode($this->request->getPost('dokpilih'));
                $dokpilih   = $this->request->getPost('dokpilih');
                $postdata   = explode(",",  $dokpilih);
                $postdata   = array_map('decrypt_id', $postdata);
                $seleted    = $reffModel->select('idDokPersh')->where('idLS', $idData)->find();
                $seleted    = obj_flatten($seleted, 'idDokPersh');
                $unselect   = array_diff($seleted, $postdata);

                if (!empty($unselect)) {
                    $reffModel->whereIn('idDokPersh', $unselect)->delete();
                }

                foreach ($postdata as $key => $idDok) {
                    $dokPilih                   = model('t_dokpersh')->find($idDok);
                    if (!in_array($idDok, $seleted)) {
                        $rowDok['idLS']         =  $idData;
                        $rowDok['idJenisDok']   =  $dokPilih->idJenisDok;
                        $rowDok['idDokPersh']   =  $idDok;
                        $rowDok['created']      =  date('Y-m-d H:i:s');
                        $reffModel->insert($rowDok);
                    }
                }
                $noET       = $tglET = $tglAkhirET = '';
                if ($dokPilih->idJenisDok == 1) {

                    $perizinan = model('tx_lseReferensi');
                    $perizinan->select('tx_lse_referensi.id as idref,t_dokpersh.*');
                    $perizinan->join('t_dokpersh', 't_dokpersh.id = tx_lse_referensi.idDokPersh');
                    $perizinan->where('tx_lse_referensi.idJenisDok', 1)->where('tx_lse_referensi.idLS', $idData);
                    $arrET      = $perizinan->first();
                    if (isset($arrET->noDokumen)) {
                        $noET       = $arrET->noDokumen;
                        $tglET      = $arrET->tglDokumen;
                        $tglAkhirET = $arrET->tglAkhirDokumen;
                    }

                    $setET = ['noET' => $noET, 'tglET' => $tglET, 'tglAkhirET' => $tglAkhirET];
                    model('tx_lseHdr')->where('id', $idData)->set($setET)->update();
                }

                $resp = resp_success('Data berhasil disimpan', ['iddata' => encrypt_id($idData), 'noET' => $noET, 'tglET' => reverseDate($tglET), 'tglAkhirET' => reverseDate($tglAkhirET)]);
                return $this->response->setJSON($resp);
            } catch (\Throwable $e) {
                $resp = resp_error('An Exception has occured!' . $e->getMessage());
                return $this->response->setJSON($resp);
            }
        }
    }

    public function delete()
    {
        try {
            $idLs           = decrypt_id($this->request->getPost('idLs'));
            $dataLS         = model('tx_lseHdr')->find($idLs);
            $statusProses   = $dataLS->statusProses;
            $idJenisTerbit  = $dataLS->idJenisTerbit;
            $idPerubahan    = $dataLS->idPerubahan;
            $draftNo        = $dataLS->draftNo;


            if ($statusProses == 'PROCESS' && session()->get('sess_role') == 6) {
                $this->db->transStart();
                $setStatus              = ['statusDok' => 'DIHAPUS', 'statusProses' => 'DELETED'];
                model('tx_lseHdr')->where('id', $idLs)->set($setStatus)->update();

                $dataLog['idLS']        = $idLs;
                $dataLog['logAction']   = 'Hapus LS';
                $dataLog['note']        = '';
                save_log_process($dataLog, $dataLS);

                $this->db->transComplete();

                $this->update_old_lse($idPerubahan, 'DELETED', 'Hapus draft LS Ekspor nomor ' . $draftNo);

                if ($this->db->transStatus() === false)
                    throw new \CodeIgniter\Database\Exceptions\DatabaseException('Data gagal dihapus. Transaction exception occured');
                else {
                    $resp = resp_success('Data berhasil dihapus', ['iddata' => encrypt_id($idLs)]);
                }
            } else {
                $resp = resp_error('Data gagal dihapus. Status / role tidak valid.', ['iddata' => encrypt_id($idLs)]);
            }
            return $this->response->setJSON($resp);
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!' . $e->getMessage());
            return $this->response->setJSON($resp);
        }
    }

    public function list($dataFilter)
    {
        $searchParam    = $this->request->getPost('searchParam');
        $arrParam       = post_ajax_toarray($searchParam);
        $castDataFilter = ['konsep' => ['PROCESS', 'REFUSED'], 'proses' => ['REVIEW'], 'terbit' => ['ISSUED']];
        $lsModel        = model('tx_lseHdr');
        $arrData        = $lsModel->whereIn('idJenisLS', [2, 3, 4, 7, 8, 9, 10, 11])->where('statusProses <>', 'DELETED')->whereIn('statusProses', $castDataFilter[$dataFilter]);

        if (config('App')->operator_branch) {
            $arrData    = $lsModel->groupStart()
                ->where('idCabang', session()->get('sess_branch'))
                ->orWhere('idCabang IS NULL')
                ->groupEnd();
        }

        if (!empty($arrParam['idJenisTerbit'])) {
            $arrData->where('idJenisTerbit', $arrParam['idJenisTerbit']);
        }

        if (!empty($arrParam['draftNo'])) {
            $arrData->like('draftNo', $arrParam['draftNo']);
        }

        if (!empty($arrParam['namaPersh'])) {
            $arrData->like('namaPersh', $arrParam['namaPersh']);
        }

        if (!empty($arrParam['idCabang'])) {
            $arrData->where('idCabang', $arrParam['idCabang']);
        }

        if (!empty($arrParam['noLs'])) {
            $arrData->like('noLs', $arrParam['noLs']);
        }

        if (!empty(trim($arrParam['tglLs'])) && !empty(trim($arrParam['tglAkhirLs']))) {
            $arrData    = $lsModel->groupStart()
                ->where("tglLs BETWEEN '" . reverseDateDB($arrParam["tglLs"]) . "' AND '" . reverseDateDB($arrParam["tglAkhirLs"]) . "'")
                ->groupEnd();
        }

        if (!empty(trim($arrParam['tglLs'])) && empty(trim($arrParam['tglAkhirLs']))) {
            $arrData->where('tglLs >=', reverseDateDB($arrParam['tglLs']));
        }

        if (empty(trim($arrParam['tglLs'])) && !empty(trim($arrParam['tglAkhirLs']))) {
            $arrData->where('tglLs <=', reverseDateDB($arrParam['tglAkhirLs']));
        }

        $recordsTotal   = $arrData->countAllResults(false);
        $arrData        = $arrData->orderBy('created', 'desc')->findAll($this->request->getPost('length'), $this->request->getPost('start'));
        $row            = [];

        $no             = $this->request->getPost('start') + 1;
        $masterStatus   = $this->db->table('m_status_proses')->get()->getResult();

        foreach ($arrData as $key => $data) {
            $arrStatus  = array_column($masterStatus, 'html', 'status');
            $btnEdit    = '';
            $btnCabut   = '';
            $btnView    = '';
            $btnCetak   = '';
            $btnDelete  = '';
            $btnLog     = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-success" onclick="view_log(\'' . encrypt_id($data->id) . '\')" title="Log"><i class="fa fa-history" aria-hidden="true"></i></button> ';

            if (in_array(session()->get('sess_role'), [6, 7])) {
                $btnView    = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-info" onclick="view(\'' . encrypt_id($data->id) . '\')" title="Lihat"><i class="fa fa-eye"></i></button> ';
                $btnCetak   = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-green" onclick="print(\'' . encrypt_id($data->id) . '\')" title="Cetak"><i class="fa fa-print"></i></button> ';
                $btnDelete  = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-danger" onclick="del(\'' . encrypt_id($data->id) . '\')" title="Hapus"><i class="fa fa-trash"></i></button> ';
                $btnEdit    = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-warning" onclick="edit(\'' . encrypt_id($data->id) . '\')" title="Edit"><i class="fa fa-edit"></i></button> ';
            }

            if (session()->get('sess_role') == 6) // operator
            {
                if ($data->statusProses == 'PROCESS')
                    $btnView = '';
                else if ($data->statusProses == 'REVIEW' || $data->statusProses == 'ISSUED')
                    $btnDelete = $btnEdit = '';

                if ($data->statusDok == 'TERBIT' && $data->statusProses == 'ISSUED' && $data->statusKirim == 'SENT') {
                    $btnCabut = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-danger" onclick="cabut(\'' . encrypt_id($data->id) . '\')" title="Pembatalan"><i class="fa fa-ban"></i></button> ';
                    $btnDelete  = $btnEdit = '';
                }
            } else if (session()->get('sess_role') == 7) // supervisor
            {
                if (!config('App')->spv_can_edit) {
                    $btnDelete  = $btnEdit = '';
                } else {
                    $btnDelete      =  '';
                    if ($data->statusProses == 'PROCESS' || $data->statusProses == 'REFUSED')
                        $btnEdit    = '';
                    else if ($data->statusProses == 'ISSUED')
                        $btnDelete  = $btnEdit = '';
                }
            }

            $badgeStatusDok         = '';
            if ($data->statusProses  == 'ISSUED') {
                if ($data->statusDok == 'DIBATALKAN' || $data->statusDok == 'DIUBAH')
                    $badgeStatusDok = '<br><span class="badge rounded-pill bg-danger my-1 fs-2">' . $data->statusDok . '</span>';
                else if ($data->statusDok == 'PERUBAHAN')
                    $badgeStatusDok = '<br><span class="badge rounded-pill bg-warning my-1 fs-2">PROSES PERUBAHAN</span>';
            }

            $badgeStatusKirim       = '';
            if ($data->statusProses  == 'ISSUED') {

                if ($data->statusKirim == 'SENT')
                    $bgKirim = 'bg-success';
                else if ($data->statusKirim == 'FAILED')
                    $bgKirim = 'bg-danger';
                else
                    $bgKirim = 'bg-dark';

                $badgeStatusKirim = '<hr><span class="text-warning">Pengiriman Inatrade</span>';
                $badgeStatusKirim .= '<br><span class="badge rounded-pill ' . $bgKirim . ' my-1">' . status_kirim($data->statusKirim) . '</span>';
            }

            $badgeJenisTerbit       = '';
            if ($data->idJenisTerbit  == 1)
                $badgeJenisTerbit = '<span class="badge bg-info">Jenis Penerbitan : ' . $data->jenisTerbit . '</span>';
            else
                $badgeJenisTerbit = '<span class="badge bg-danger">Jenis Penerbitan : ' . $data->jenisTerbit . '</span>';

            $perizinan = model('tx_lseReferensi');
            $perizinan->select('tx_lse_referensi.id as idref,t_dokpersh.*');
            $perizinan->join('t_dokpersh', 't_dokpersh.id = tx_lse_referensi.idDokPersh');
            $perizinan->where('tx_lse_referensi.idJenisDok', 1)->where('tx_lse_referensi.idLS', $data->id);
            $arrET      = $perizinan->first();
            $noET       = $tglET = $tglAkhirET = '';

            if (isset($arrET->noDokumen)) {
                $noET       = $arrET->noDokumen;
                $tglET      = reverseDate($arrET->tglDokumen);
                $tglAkhirET = reverseDate($arrET->tglAkhirDokumen);
            }

            $btnKirimInatradeXML        = $btnKirimInatradeJSON = $btnRollback = '';

            if ($data->statusDok == 'TERBIT' && $data->statusProses == 'ISSUED' && $data->statusKirim == 'READY') {
                //$btnKirimInatradeXML    = '<div class="mt-4"><button type="button" class="btn btn-sm btn-danger" data-iddata="'.encrypt_id($data->id).'" id="btn-xml-inatrade" ><i class="fa fa-cloud me-2" aria-hidden="true"></i>KIRIM INATRADE</button></div>';
                $btnKirimInatradeJSON   = '<div class="mt-4"><button type="button" class="btn btn-sm btn-danger" data-iddata="' . encrypt_id($data->id) . '" id="btn-json-inatrade" ><i class="fa fa-cloud me-2" aria-hidden="true"></i>KIRIM INATRADE</button></div>';
                $btnRollback            = ' <button type="button" class="btn btn-sm btn-w-xs btn-icon btn-warning" onclick="rolback(\'' . encrypt_id($data->id) . '\')" title="Rollback"><i class="fa fa-ban"></i></button>';
            }

            $btnKirimInatradeXML        = '';

            $jenisKomoditi = $this->db->table('m_jenisls')->where('id', $data->idJenisLS)->get()->getRow()->markJenis;

            $columns = [];
            $columns[] = $no++;
            $columns[] = '<span>' . $badgeJenisTerbit . '<br><span style="color:#1fa617;">' . $data->draftNo . '</span></span><br>Tanggal : ' . reverseDate($data->tglDraft) . '<br><span style="color:#ff9324;">' . $jenisKomoditi . '</span><hr><span style="color:#00bcd4;">SI No: ' . $data->noSi . '</span><br><span>Cabang : ' . $data->namaCabang . '</span><br><span>Created : ' . reverseDateTime($data->created) . '</span>';
            $columns[] = '<span id="no-ls">No. LS: <span class="fw-semibold" style="color:#0000ff;">' . $data->noLs . '</span></span><br><span>Tgl LS: <span style="color:#0000ff;">' . reverseDate($data->tglLs) . '</span></span><br><span>Tgl Akhir LS: <span style="color:#0000ff;">' . reverseDate($data->tglAkhirLs) . '</span></span>';
            $columns[] = '<span style="color:blue;">' . $data->namaPersh . '</span><br><span>' . formatNPWP($data->npwp) . '</span><br><span>' . $data->namaKota . '</span><hr><span>No ET: ' . $noET . '</span><br><span>Tgl Awal: ' . $tglET . '</span><br><span>Tgl Akhir: ' . $tglAkhirET . '</span>';
            $columns[] = '<span><i>Pel Muat:</i><br><span style="color:#f44336;">' . $data->kodePortMuat . ' - ' . $data->portMuat . '</span></span><br><span><i>Pel Tujuan:</i><br><span style="color:#2196f3;">' . $data->kodePortTujuan . ' - ' . $data->portTujuan . '</span></span><br><span><i>Transport:</i><br>' . $data->namaTransport . '</span>';
            $columns[] = $arrStatus[$data->statusProses] . $badgeStatusDok . $badgeStatusKirim;
            $columns[] = '<div class="btn-list text-nowrap">' . $btnLog . $btnCabut . $btnDelete . $btnEdit . $btnView . $btnCetak . $btnRollback . '</div>' . $btnKirimInatradeXML . $btnKirimInatradeJSON;
            $row[] = $columns;
        }

        $table['draw']              = $this->request->getPost('draw');
        $table['recordsTotal']      = $recordsTotal;
        $table['recordsFiltered']   = $recordsTotal;
        $table['data']              = $row;

        echo json_encode($table);
    }

    public function add_ntpn()
    {
        try {
            $postdata = $this->request->getPost('postdata');
            $data = post_ajax_toarray($postdata);
            if ($data['idNtpn'])
                $data['id'] = decrypt_id($data['idNtpn']);
            unset($data['idNtpn']);

            $errMandatory = $this->cek_mandatory($data, 'NTPN');

            if (count($errMandatory) > 0) {
                $textErr = implode('<br>', $errMandatory);
                $resp = resp_error('Perhatikan isian berikut <br>' . $textErr);
            } else {

                if ((strtoupper($data['noNtpn']) === 'SELISIHLEBIHMUAT') && ($data['volume'] < 0)) {
                    $resp = resp_error('Volume NTPN untuk SELISIHLEBIHMUAT harus kurang dari 0');
                } else {
                    $data['idLs'] = decrypt_id($this->request->getPost('idLs'));
                    $data['tglNtpn'] = reverseDateDB($data['tglNtpn']);
                    $data['npwp'] = clean_npwp($data['npwp']);
                    $data['volume'] = insertNumber($data['volume']);
                    $data['royalti'] = insertNumber($data['royalti']);
                    $data['kdPropInatrade'] = $this->request->getPost('kdPropInatrade');
                    $data['namaProp'] = $this->request->getPost('namaProp');
                    $data['jenisIUP'] = $this->request->getPost('jenisIUP');

                    if (!empty($data['kdSatuan']))
                        $data['uraiSatuan'] = model('satuan')->where('kodeSatuan', $data['kdSatuan'])->first()->uraiSatuan;
                    $data['created'] = date('Y-m-d H:i:s');

                    $ntpnModel = model('tx_lseNtpn');
                    $ntpnModel->upsert($data);
                    $this->update_seri_ntpn($data['idLs']);
                    $resp = resp_success('Data berhasil disimpan');
                }
            }
            return $this->response->setJSON($resp);
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!' . $e->getMessage());
            return $this->response->setJSON($resp);
        }
    }

    public function add_komoditas()
    {
        try {
            $idLs       = decrypt_id($this->request->getPost('idLs'));
            $postdata   = $this->request->getPost('postdata');
            $data       = post_ajax_toarray($postdata, true, ['k_ntpn']);

            if ($data['idPosTarif'])
                $data['id'] = decrypt_id($data['idPosTarif']);
            unset($data['idPosTarif']);

            if (isset($data['ntpn'])) {
                $ntpns = $data['ntpn'];
                unset($data['ntpn']);
            }

            $textErr = '';
            if (!isset($ntpns)) {
                $textErr = 'NTPN tidak boleh kosong<br>';
            }

            $data['idLs']           = $idLs;
            $data['postarif']       = numeric_only($data['postarif']);
            $data['jumlahBarang']   = insertNumber($data['jumlahBarang']);
            $data['beratBersih']    = insertNumber($data['beratBersih']);
            $data['hargaBarang']    = insertNumber($data['hargaBarang']);
            $data['hargaBarangIdr'] = insertNumber($data['hargaBarangIdr']);
            $data['hargaBarangUsd'] = insertNumber($data['hargaBarangUsd']);
            $data['tglIup']         = reverseDateDB($data['tglIup']);

            if (!empty($data['kdSatuanBarang']))
                $data['uraiSatuanBarang']   = model('satuan')->where('kodeSatuan', $data['kdSatuanBarang'])->first()->uraiSatuan;
            if (!empty($data['kdNegaraAsal']))
                $data['negaraAsal']         = model('negara')->where('kode', $data['kdNegaraAsal'])->first()->nama;

            $errMandatory = $this->cek_mandatory($data, 'KOMODITAS');
            if (count($errMandatory) > 0) {
                $textErr .= implode('<br>', $errMandatory);
            }

            if ($textErr != '') {
                $resp    = resp_error('Perhatikan isian berikut <br>' . $textErr);
                return $this->response->setJSON($resp);
                exit;
                exit;
            }

            $this->db->transStart();
            $dtlModel = model('tx_lseDtlHs');
            $dtlModel->upsert($data);

            $idHs = $dtlModel->insertID();

            if (isset($data['id'])) {
                $idHs = $data['id'];
            } else {
                $idHs = $dtlModel->insertID();
            }

            $this->update_seri_hs($idLs);

            if (isset($idHs)) {
                if (isset($ntpns)) {
                    // $idHs       = $data['id'];
                    $ntpnModel  = model('tx_hsNtpn');
                    $ntpnModel->where('idPosTarif', $idHs)->delete();
                    $seleted    = $ntpnModel->select('idNtpn')->where('idPosTarif', $idHs)->find();
                    $seleted    = obj_flatten($seleted, 'idNtpn');
                    $unselect   = array_diff($seleted, $ntpns);

                    if (!empty($unselect)) {
                        $ntpnModel->whereIn('idDokPersh', $unselect)->delete();
                    }

                    foreach ($ntpns as $key => $ntpn) {
                        if (!empty($ntpn) && ($ntpn !== NULL) && ($ntpn !== '')) {
                            $hsntpn['idLs']         = $idLs;
                            $hsntpn['idPosTarif']   = $idHs;
                            $hsntpn['idNtpn']       = $ntpn;
                            $ntpnModel->upsert($hsntpn);
                        }
                    }
                }
            }

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new \CodeIgniter\Database\Exceptions\DatabaseException('Gagal menambahkan / update komoditas. Transaction exception occured');
            } else {
                $resp = resp_success('Data berhasil disimpan');
                return $this->response->setJSON($resp);
            }
        } catch (\CodeIgniter\Database\Exceptions\DatabaseException $e) {
            $resp = resp_error($e->getMessage());
            return $this->response->setJSON($resp);
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!' . $e->getMessage());
            return $this->response->setJSON($resp);
        }
    }

    protected function update_seri_hs($idLs)
    {
        $dtlModel   = model('tx_lseDtlHs');
        $dataHS     = $dtlModel->where('idLs', $idLs)->findAll();
        foreach ($dataHS as $key => $hs) {
            $dtlModel->update($hs->id, ['seri' => ($key + 1)]);
        }
    }

    protected function update_seri_package($idLs)
    {
        $model = model('tx_lsePackage');
        $data = $model->where('idLs', $idLs)->findAll();
        foreach ($data as $key => $item) {
            $model->update($item->id, ['seri' => ($key + 1)]);
        }
    }

    protected function update_seri_container($idLs)
    {
        $model = model('tx_lseContainer');
        $data = $model->where('idLs', $idLs)->findAll();
        foreach ($data as $key => $item) {
            $model->update($item->id, ['seri' => ($key + 1)]);
        }
    }

    protected function update_seri_ntpn($idLs)
    {
        $dtlModel   = model('tx_lseNtpn');
        $data     = $dtlModel->where('idLs', $idLs)->findAll();
        foreach ($data as $key => $ntpn) {
            $dtlModel->update($ntpn->id, ['seri' => ($key + 1)]);
        }
    }

    public function get_ntpn()
    {
        try {
            $idLs = decrypt_id($this->request->getPost('idLs'));
            $dataLS = model('tx_lseHdr')->find($idLs);

            $ntpnModel = model('tx_lseNtpn');
            $dataNtpn = $ntpnModel->where('idLs', $idLs)->findAll();

            $html = '';
            foreach ($dataNtpn as $key => $ntpn) {
                $btnAct = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-danger me-2" onclick="del_ntpn(\'' . encrypt_id($ntpn->id) . '\',\'' . $ntpn->idNtpnNsw . '\')" title="Hapus"><i class="fa fa-trash"></i></button>
                        <button type="button" class="btn btn-sm btn-w-xs btn-icon btn-warning" onclick="edit_ntpn(\'' . encrypt_id($ntpn->id) . '\',\'' . $ntpn->idNtpnNsw . '\')" title="Edit"><i class="fa fa-edit"></i></button>';

                if (!in_array($dataLS->statusProses, ['PROCESS', 'REVIEW']))
                    $btnAct = '';
                else if (session()->get('sess_role') == 7 && !config('App')->spv_can_edit)
                    $btnAct = '';

                $notifNpwp = '';
                if (strlen($ntpn->npwp) != 16) {
                    $cekNpwp16 = $this->db->table('temp_npwp')->where('npwp15', $ntpn->npwp)->get()->getRow();
                    if (isset($cekNpwp16) && !empty($cekNpwp16->npwp16)) {
                        $notifNpwp = '<small style="color:blue">NPWP 16 DIGIT = ' . formatNPWP($cekNpwp16->npwp16) . ' (Bisa di update pada menu Master Data NPWP). <a href="' . base_url() . '/management/npwp" target="_blank">Klik untuk akses menu</a></small>';
                    } else {
                        $notifNpwp = '<small style="color:red">NPWP 16 Digit tidak ditemukan. Silahkan input / update menu Master Data NPWP. <a href="' . base_url() . '/management/npwp" target="_blank">Klik untuk akses menu</a></small>';
                    }
                }

                $html .= '<tr >
                            <td class="align-top text-nowrap text-center">' . ($key + 1) . '</td>
                            <td class="align-top text-nowrap"><span style="color:#2160f3;">' . $ntpn->noNtpn . '</span><br>' . reverseDate($ntpn->tglNtpn) . '<br>' . formatAngka($ntpn->royalti) . ' (' . $ntpn->currency . ')</td>
                            <td class="align-top text-nowrap">Nama: ' . $ntpn->nama . '<br>NPWP: <span style="color:#2160f3;">' . formatNPWP($ntpn->npwp) . '</span><br>NIB: ' . $ntpn->nib . '<br>NITKU: ' . $ntpn->nitku . '<br>' . $notifNpwp . '</td>
                            <td class="align-top text-nowrap">' . $ntpn->namaProp . '<br><span style="color:#ff5722;">' . formatAngka($ntpn->volume) . ' (' . $ntpn->kdSatuan . ')</span></td>
                            <td class="align-top text-nowrap text-center">
                                ' . $btnAct . '
                            </td>
                        </tr>';
            }
            $resp['content'] = $html;
            return $this->response->setJSON($resp);
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!' . $e->getMessage());
            return $this->response->setJSON($resp);
        }
    }

    public function del_ntpn()
    {
        try {

            $idNtpn = decrypt_id($this->request->getPost('id'));
            $idLs = decrypt_id($this->request->getPost('idLs'));
            $cek = model('tx_hsNtpn')->where('idNtpn', $idNtpn)->where('idLs', $idLs)->findAll();
            if (count($cek) == 0) {
                $delete = model('tx_lseNtpn')->delete($idNtpn);
                if ($delete) {
                    $resp = resp_success('NTPN berhasil dihapus');
                } else {
                    $resp = resp_error('NTPN gagal dihapus');
                }
            } else
                $resp = resp_error('Tidak dapat menghapus. NTPN ini digunakan pada data komoditas, silahkan edit komoditas terlebih dahulu.');
            return $this->response->setJSON($resp);
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!' . $e->getMessage());
            return $this->response->setJSON($resp);
        }
    }

    public function edit_ntpn()
    {
        try {
            $idNtpn = decrypt_id($this->request->getPost('id'));
            $data = model('tx_lseNtpn')->find($idNtpn);
            $dataLS = model('tx_lseHdr')->find($data->idLs);
            $htmlSELECT = '';
            if (!empty($dataLS->idPermohonanNSW)) {
                $ntpnNSW = model('t_inswPermohonanBrgKerjasama')->find($data->idNtpnNsw);
                if (!empty($ntpnNSW->npwpPenjual))
                    $htmlSELECT .= '<option value="' . $ntpnNSW->npwpPenjual . '">PENJUAL - ' . formatNPWP($ntpnNSW->npwpPenjual) . '</option>';
                if (!empty($ntpnNSW->npwpPenambang))
                    $htmlSELECT .= '<option value="' . $ntpnNSW->npwpPenambang . '">PENAMBANG - ' . formatNPWP($ntpnNSW->npwpPenambang) . '</option>';
                if (!empty($ntpnNSW->npwpTrader))
                    $htmlSELECT .= '<option value="' . $ntpnNSW->npwpTrader . '">TRADER - ' . formatNPWP($ntpnNSW->npwpTrader) . '</option>';
            }
            $data->id = encrypt_id($data->id);
            $data->tglNtpn = reverseDate($data->tglNtpn);
            $currency = model('currency')->where('kode', $data->currency)->first();
            if ($currency)
                $data->uraiCurrency = $currency->uraian;
            $data->selectNPWP = $htmlSELECT;
            return $this->response->setJSON($data);
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!' . $e->getMessage());
            return $this->response->setJSON($resp);
        }
    }

    public function get_komoditas()
    {
        try {
            $idLs = decrypt_id($this->request->getPost('idLs'));
            $dataLS = model('tx_lseHdr')->find($idLs);
            $dtlModel = model('tx_lseDtlHs');
            $dataHS = $dtlModel->where('idLs', $idLs)->findAll();

            $html = '';
            foreach ($dataHS as $key => $hs) {

                $ntpnModel = model('tx_hsNtpn');
                $ntpnModel->select('id, CONCAT(noNtpn, " | ", FORMAT(volume, 0), " - ",IFNULL(nama,"{NAMA PERUSAHAAN}")) as noNtpn');
                $ntpnModel->join('tx_lse_ntpn', 'tx_lse_ntpn.id = tx_lsehsntpn.idNtpn');
                $ntpnModel->where('tx_lsehsntpn.idPosTarif', $hs->id);
                $ntpns = $ntpnModel->findAll();
                $nptn = array_column($ntpns, 'noNtpn');
                $ntpnHS = implode('<br>', $nptn);

                $btnAct = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-danger me-2" onclick="del_hs(\'' . encrypt_id($hs->id) . '\')" title="Hapus"><i class="fa fa-trash"></i></button>
                                <button type="button" class="btn btn-sm btn-w-xs btn-icon btn-warning" onclick="edit_hs(\'' . encrypt_id($hs->id) . '\')" title="Edit"><i class="fa fa-edit"></i></button>';

                if (!in_array($dataLS->statusProses, ['PROCESS', 'REVIEW']))
                    $btnAct = '';
                else if (session()->get('sess_role') == 7 && !config('App')->spv_can_edit)
                    $btnAct = '';

                $html .= '<tr >
                            <td class="align-top text-nowrap text-center">' . $hs->seri . '</td>
                            <td class="align-top text-nowrap"><span style="color:#03a9f4;">' . formatHS($hs->postarif) . '</span><br><span style="color:#f44336;">' . formatAngka($hs->jumlahBarang) . ' (' . $hs->kdSatuanBarang . ') ' . '</span></td>
                            <td class="align-top">Uraian:<br>' . $hs->uraianBarang . '<br>Spesifikasi:<br>' . $hs->sepesifikasi . '</td>
                            <td class="align-top">' . $ntpnHS . '<hr>' . $hs->noIup . '<br>Tgl IUP: ' . $hs->tglIup . '</td>
                            <td class="align-top text-nowrap">
                                <span>Berat Bersih: ' . formatAngka($hs->beratBersih) . '</span><br>
                                <span>Negara Asal: ' . $hs->negaraAsal . '</span><br>
                                <span>Harga Barang: ' . formatAngka($hs->hargaBarang) . ' ' . $hs->currencyHargaBarang . '</span><br>
                                <span>Harga IDR: ' . formatAngka($hs->hargaBarangIdr) . '</span><br>
                                <span>Harga USD: ' . formatAngka($hs->hargaBarangUsd) . '</span><br>
                            </td>
                            <td class="align-top text-nowrap text-center">
                                ' . $btnAct . '
                            </td>
                        </tr>';
            }
            $resp['content'] = $html;
            return $this->response->setJSON($resp);
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!' . $e->getMessage());
            return $this->response->setJSON($resp);
        }
    }

    public function edit_hs()
    {
        try {
            $idHs = decrypt_id($this->request->getPost('id'));
            $data = model('tx_lseDtlHs')->find($idHs);
            $data->id = encrypt_id($data->id);
            $data->tglIup = reverseDate($data->tglIup);
            $data->hscode = formatHS($data->postarif);
            $currency = model('currency')->where('kode', $data->currencyHargaBarang)->first();
            if ($currency)
                $data->uraiCurrency = $currency->uraian;

            $ntpnModel = model('tx_hsNtpn');
            $ntpnModel->select('id, CONCAT(noNtpn, " | ", FORMAT(volume, 0), " - ",IFNULL(nama,"{NAMA PERUSAHAAN}")) as noNtpn');
            $ntpnModel->join('tx_lse_ntpn', 'tx_lse_ntpn.id = tx_lsehsntpn.idNtpn');
            $ntpnModel->where('tx_lsehsntpn.idPosTarif', $idHs);
            $data->ntpns = $ntpnModel->findAll();

            return $this->response->setJSON($data);
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!' . $e->getMessage());
            return $this->response->setJSON($resp);
        }
    }

    public function delete_hs()
    {
        try {

            $id = decrypt_id($this->request->getPost('id'));
            $idLs = decrypt_id($this->request->getPost('idLs'));

            $this->db->transStart();
            $delete = model('tx_lseDtlHs')->delete($id);
            if ($delete) {
                $resp = resp_success('komoditas berhasil dihapus');
            } else {
                $resp = resp_error('komoditas gagal dihapus');
            }
            $this->update_seri_hs($idLs);

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new \CodeIgniter\Database\Exceptions\DatabaseException('Gagal menghapus komoditas. Transaction exception occured');
            } else {
                return $this->response->setJSON($resp);
            }
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!' . $e->getMessage());
            return $this->response->setJSON($resp);
        }
    }

    public function get_list_dok()
    {
        $idLS = decrypt_id($this->request->getPost('idLs'));
        $dataLS = model('tx_lseHdr')->find($idLS);

        $dataModel = model('tx_lseReferensi');
        $dataModel->select('tx_lse_referensi.id as idref,t_dokpersh.*');
        $dataModel->join('t_dokpersh', 't_dokpersh.id = tx_lse_referensi.idDokPersh');
        $dataModel->where('tx_lse_referensi.idLS', $idLS);
        $arrData = $dataModel->findAll();

        $html = '';
        $no = 1;
        if (!empty($dataLS->idPermohonanNSW)) {
            $dokReffINSW = model('t_inswPermohonanDok')->where('idPermohonan', $dataLS->idPermohonanNSW)->findAll();
            foreach ($dokReffINSW as $key => $data) {

                $btnEdit = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-warning me-2" onclick="showUploadDokNsw(\'' . encrypt_id($data->id) . '\')" title="Edit"><i class="fa fa-pencil"></i></button>';

                if (!in_array($dataLS->statusProses, ['PROCESS', 'REVIEW']))
                    $btnEdit = '';
                else if (session()->get('sess_role') == 7 && !config('App')->spv_can_edit) {
                    $btnEdit = '';
                }

                $html .= '<tr >
                        <td class="align-top text-nowrap text-center">' . ($no++) . '</td>
                        <td class="align-top">' . $data->uraiNegaraPenerbit . '</td>
                        <td class="align-top">' . $data->namaDokumen . '</td>
                        <td class="align-top">' . $data->nomorDokumen . '</td>
                        <td class="align-top">' . reverseDate($data->tanggalDokumen) . '</td>
                        <td class="align-top">' . reverseDate($data->tglAkhirDokumen) . '</td>
                        <td class="align-top text-nowrap text-center">
                        ' . $btnEdit . '
                        <a class="btn btn-sm btn-w-xs btn-icon btn-info" href="' . $data->urlDokumen . '" role="button" target="_blank" title="Lihat File"><i class="fa fa-eye"></i></a>
                        </td>
                    </tr>';
            }
        }

        foreach ($arrData as $key => $data) {
            $btnDelete      = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-danger me-2" onclick="del_dok_ref(\'' . encrypt_id($data->idref) . '\')" title="Hapus"><i class="fa fa-trash"></i></button>';

            if (session()->get('sess_role') == 7 && !config('App')->spv_can_edit)
                $btnDelete  = '';

            if ($dataLS->statusProses != 'PROCESS')
                $btnDelete  = '';

            $inputET        = '';
            if ($data->idJenisDok == '1') {
                $inputET = '<input type="hidden" id="noET" value="' . $data->noDokumen . '"></input>
                            <input type="hidden" id="tglET" value="' . reverseDate($data->tglDokumen) . '"></input>
                            <input type="hidden" id="tglAkhirET" value="' . reverseDate($data->tglAkhirDokumen) . '"></input>
                ';
            }
            $html .= '<tr >
                        <td class="align-top text-nowrap text-center">' . ($no++) . '</td>
                        <td class="align-top">' . model("negara")->where("kode", $data->negaraPenerbit)->first()->nama . '</td>
                        <td class="align-top">' . $data->jenisDok . '</td>
                        <td class="align-top">' . $data->noDokumen . '</td>
                        <td class="align-top">' . reverseDate($data->tglDokumen) . '</td>
                        <td class="align-top">' . reverseDate($data->tglAkhirDokumen) . '</td>
                        <td class="align-top text-nowrap text-center">
                        ' . $btnDelete . '
                        <button type="button" class="btn btn-sm btn-w-xs btn-icon btn-info" onclick="view_dok_persh(\'' . encrypt_id($data->id) . '\')" title="Lihat File"><i class="fa fa-eye"></i></button>
                        </td>
                    </tr>
                    ' . $inputET . '
                    ';
        }

        $resp['content'] = $html;
        return $this->response->setJSON($resp);
    }

    public function delete_dok_ref()
    {
        try {

            $idRef = decrypt_id($this->request->getPost('idRef'));
            $dokRef = model('tx_lseReferensi')->find($idRef);
            $delete = model('tx_lseReferensi')->delete($idRef);
            if ($delete) {
                $noET       = $tglET = $tglAkhirET = '';
                $perizinan = model('tx_lseReferensi');
                $perizinan->select('tx_lse_referensi.id as idref,t_dokpersh.*');
                $perizinan->join('t_dokpersh', 't_dokpersh.id = tx_lse_referensi.idDokPersh');
                $perizinan->where('tx_lse_referensi.idJenisDok', 1)->where('tx_lse_referensi.idLS', $dokRef->idLS);
                $arrET      = $perizinan->first();
                if (isset($arrET->noDokumen)) {
                    $noET       = $arrET->noDokumen;
                    $tglET      = $arrET->tglDokumen;
                    $tglAkhirET = $arrET->tglAkhirDokumen;
                }

                $setET = ['noET' => $noET, 'tglET' => $tglET, 'tglAkhirET' => $tglAkhirET];
                model('tx_lseHdr')->where('id', $dokRef->idLS)->set($setET)->update();

                $resp = resp_success('Referensi berhasil dihapus', ['noET' => $noET, 'tglET' => reverseDate($tglET), 'tglAkhirET' => reverseDate($tglAkhirET)]);
            } else {
                $resp = resp_error('Referensi gagal dihapus');
            }
            return $this->response->setJSON($resp);
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!' . $e->getMessage());
            return $this->response->setJSON($resp);
        }
    }

    private function cek_mandatory($data, $section)
    {
        $errMandatory = [];
        if (empty($data['idJenisLS']))
            $errMandatory[] = 'Jenis LS tidak boleh kosong';
        else {
            $mandatories = model('mandatory')->where('idJenisLS', $data['idJenisLS'])->where('section', $section)->where('checkDraft', 'Y')->findAll();
            foreach ($mandatories as $key => $mandatory) {
                $mandatory = (object) $mandatory;
                if (empty($data[$mandatory->fieldName])) {
                    $errMandatory[] = $mandatory->fieldLabel . ' tidak boleh kosong ';
                }
                if (!empty($mandatory->maxLength)) {
                    if (strlen($data[$mandatory->fieldName]) > $mandatory->maxLength) {
                        $errMandatory[] = $mandatory->fieldLabel . ' maksimal ' . $mandatory->maxLength . ' karakter';
                    }
                }
            }
        }
        return $errMandatory;
    }

    public function batal()
    {
        try {
            $idLs   = decrypt_id($this->request->getPost('idLs'));
            $note   = $this->request->getPost('note');
            $dataLS = model('tx_lseHdr')->find($idLs);
            $statusProses   = $dataLS->statusProses;
            $statusDok      = $dataLS->statusDok;
            $idJenisTerbit  = $dataLS->idJenisTerbit;
            $idPerubahan    = $dataLS->idPerubahan;
            $draftNo        = $dataLS->draftNo;

            if ($statusDok == 'TERBIT' && $statusProses == 'ISSUED' && session()->get('sess_role') == 6) {

                $this->db->transStart();

                // CALL SERVICES PEMBATALAN

                $setStatus = ['statusDok' => 'DIBATALKAN', 'statusKirim' => 'READY'];
                model('tx_lseHdr')->where('id', $idLs)->set($setStatus)->update();

                $dataLog['idLS']      = $idLs;
                $dataLog['logAction'] = 'Pembatalan LS';
                $dataLog['note']      = $note;
                save_log_process($dataLog, $dataLS);

                $this->db->transComplete();

                $this->update_old_lse($idPerubahan, 'CANCELED', 'Pencabutan LS Ekspor dengan nomor draft ' . $draftNo);

                if ($this->db->transStatus() === false)
                    throw new \CodeIgniter\Database\Exceptions\DatabaseException('Pembatalan gagal. Transaction exception occured');
                else {
                    $resp = resp_success('Data berhasil dibatalkan.', ['iddata' => encrypt_id($idLs)]);
                }
            } else {
                $resp = resp_error('Data gagal dibatalkan. Status / role tidak valid.', ['iddata' => encrypt_id($idLs)]);
            }
            return $this->response->setJSON($resp);
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!' . $e->getMessage());
            return $this->response->setJSON($resp);
        }
    }

    public function add_kalori()
    {
        try {
            $postdata = $this->request->getPost('postdata');
            $data = post_ajax_toarray($postdata, true, ['kal_seriBarang']);
            if ($data['idKalori'])
                $data['id'] = decrypt_id($data['idKalori']);
            unset($data['idKalori']);
            unset($data['table-kalori_length']);

            if (empty($data['kal_seriBarang'])) {
                $resp = resp_error('Silahkan pilih seri barang');
                return $this->response->setJSON($resp);
                exit;
            }
            $seriBarang = $data['kal_seriBarang'];

            $this->db->transStart();
            foreach ($seriBarang as $key => $seri) {
                $kalori = $data;
                $kalori['idLs'] = decrypt_id($this->request->getPost('idLs'));
                $kalori['idPosTarif'] = $seri;

                $kalori['calArb'] = insertNumber($data['calArb']);
                $kalori['calAdb'] = insertNumber($data['calAdb']);
                $kalori['tmArb'] = insertNumber($data['tmArb']);
                $kalori['tAsh'] = insertNumber($data['tAsh']);
                $kalori['tSulfur'] = insertNumber($data['tSulfur']);

                $kalori['keterangan'] = $data['kal_keterangan'];

                unset($kalori['kal_seriBarang']);
                unset($kalori['kal_keterangan']);
                $errMandatory = $this->cek_mandatory($kalori, 'KALORI');
                if (count($errMandatory) > 0) {
                    $textErr = implode('<br>', $errMandatory);
                    $resp = resp_error('Perhatikan isian berikut <br>' . $textErr);
                    return $this->response->setJSON($resp);
                    exit;
                } else {
                    $kaloriModel = model('tx_lseKalori');
                    $kaloriModel->upsert($kalori);
                }
            }
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new \CodeIgniter\Database\Exceptions\DatabaseException();
            } else {
                $resp = resp_success('Data berhasil disimpan');
                return $this->response->setJSON($resp);
            }
        } catch (\CodeIgniter\Database\Exceptions\DatabaseException $e) {
            qq($e);
            $resp = resp_error($e->getMessage());
            return $this->response->setJSON($resp);
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!');
            return $this->response->setJSON($resp);
        }
    }

    public function get_kalori()
    {
        try {
            $idLs = decrypt_id($this->request->getPost('idLs'));
            $dataLS = model('tx_lseHdr')->find($idLs);
            $dtlModel = model('tx_lseKalori');
            $dataKalori = $dtlModel->where('idLs', $idLs)->findAll();

            $html = '';
            foreach ($dataKalori as $key => $kalori) {

                $dtlHS = model('tx_lseDtlHs')->find($kalori->idPosTarif);
                $btnAct = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-danger me-2" onclick="del_kalori(\'' . encrypt_id($kalori->id) . '\')" title="Hapus"><i class="fa fa-trash"></i></button>
                                <button type="button" class="btn btn-sm btn-w-xs btn-icon btn-warning" onclick="edit_kalori(\'' . encrypt_id($kalori->id) . '\')" title="Edit"><i class="fa fa-edit"></i></button>';

                if (!in_array($dataLS->statusProses, ['PROCESS', 'REVIEW']))
                    $btnAct = '';
                else if (session()->get('sess_role') == 7 && !config('App')->spv_can_edit)
                    $btnAct = '';

                $html .= '<tr >
                            <td class="align-top text-center" width="5%">' . $dtlHS->seri . '</td>
                            <td class="align-top" width="5%">' . formatHS($dtlHS->postarif) . '</td>
                            <td class="align-top" width="15%">' . formatAngka($kalori->calArb) . '</td>
                            <td class="align-top" width="15%">' . formatAngka($kalori->calAdb) . '</td>
                            <td class="align-top" width="10%">' . formatAngka($kalori->tmArb) . '</td>
                            <td class="align-top" width="10%">' . formatAngka($kalori->tAsh) . '</td>
                            <td class="align-top" width="10%">' . formatAngka($kalori->tSulfur) . '</td>
                            <td class="align-top" width="10%">' . $kalori->klasifikasiBatubara . '</td>
                            <td class="align-top" width="10%">' . $kalori->keterangan . '</td>
                            <td class="align-top text-center" width="5%">
                                ' . $btnAct . '
                            </td>
                        </tr>';
            }
            $resp['content'] = $html;
            return $this->response->setJSON($resp);
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!' . $e->getMessage());
            return $this->response->setJSON($resp);
        }
    }

    public function edit_kalori()
    {
        try {
            $idKalori = decrypt_id($this->request->getPost('id'));
            $data = model('tx_lseKalori')->find($idKalori);
            $data->hs = model('tx_lseDtlHs')->find($data->idPosTarif);
            $data->hs->fhs = formatHS($data->hs->postarif);

            return $this->response->setJSON($data);
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!' . $e->getMessage());
            return $this->response->setJSON($resp);
        }
    }

    public function delete_kalori()
    {
        try {

            $id = decrypt_id($this->request->getPost('id'));
            $idLs = decrypt_id($this->request->getPost('idLs'));

            $this->db->transStart();
            $delete = model('tx_lseKalori')->delete($id);
            if ($delete) {
                $resp = resp_success('Kalori berhasil dihapus');
            } else {
                $resp = resp_error('Kalori gagal dihapus');
            }

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new \CodeIgniter\Database\Exceptions\DatabaseException('Gagal menghapus Kalori. Transaction exception occured');
            } else {
                return $this->response->setJSON($resp);
            }
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!');
            return $this->response->setJSON($resp);
        }
    }

    public function create_perubahan()
    {
        try {
            $idLs       = decrypt_id($this->request->getPost('idLs'));
            $lsModel    = model('tx_lseHdr');
            $dataLS     = $lsModel->find($idLs);
            $dataDetil  = model('tx_lseDtlHs')->where('idLS', $idLs)->findAll();
            $containers = model('tx_lseContainer')->where('idLS', $idLs)->findAll();
            $packages   = model('tx_lsePackage')->where('idLS', $idLs)->findAll();
            $referensis = model('tx_lseReferensi')->where('idLS', $idLs)->findAll();

            if ($dataLS) {
                $jnsLSModel             = model('jenisLs');
                $jnsLS                  = $jnsLSModel->find($dataLS->idJenisLS);
                $insert                 = $dataLS;
                $insert->draftIncrement = $lsModel->select('IFNULL(MAX(draftIncrement),0)+1 as noDraft', false)->where('YEAR(created)', DATE('Y'))->get()->getRow()->noDraft;
                $noDraft                = str_pad($insert->draftIncrement, 4, "0", STR_PAD_LEFT);
                $insert->draftNo        = $noDraft . '/' . PREFIX_NUMBERING . '-' . $jnsLS->kode . '/' . DATE('m') . '/' . DATE('Y');

                if ($dataLS->idJenisTerbit == 1) {
                    $insert->idReff      = $dataLS->id;
                    $insert->idPerubahan = $dataLS->id;
                } else {
                    $insert->idReff      = $dataLS->idReff;
                    $insert->idPerubahan = $dataLS->id;
                }

                $insert->idJenisTerbit  = 2;
                $insert->jenisTerbit    = 'Perubahan';
                $insert->statusDok      = 'PROSES';
                $insert->statusProses   = 'PROCESS';
                $insert->statusKirim    = 'KONSEP';
                $insert->userCreate     = decrypt_id(session()->get('sess_userid'));
                $insert->created        = date('Y-m-d H:i:s');

                unset($insert->id);
                unset($insert->lastUser);
                unset($insert->lastUpdate);
                unset($insert->notifKirim);
                unset($insert->issuedDateTime);
                unset($insert->idTtd);
                unset($insert->namaTtd);
                unset($insert->fileLS);
                unset($insert->fileUrl);

                unset($insert->idReqAlokasi);
                unset($insert->reqAlokasiNumber);
                unset($insert->reqAlokasiCount);

                $this->db->transStart();
                $this->db->table('tx_lsehdr')->insert($insert);
                $idHdr      = $this->db->insertID();

                if (($idHdr != 0) && $idHdr != '') {
                    $arrHsNtpn          = [];
                    foreach ($dataDetil as $key => $detil) {
                        $idPosTarif     = $detil->id;
                        unset($detil->id);
                        $detil->idLs    = $idHdr;
                        $this->db->table('tx_lsedtlhs')->insert($detil);
                        $idDetil        = $this->db->insertID();
                        $datahsntpn     = $this->db->table('tx_lsehsntpn')->where('idLs', $idLs)->where('idPosTarif', $idPosTarif)->get()->getResult();
                        foreach ($datahsntpn as $keyhs => $hsntpn) {
                            $arrHsNtpn[] = ['idPosTarifOld' => $idPosTarif, 'idNtpnOld' => $hsntpn->idNtpn, 'idPosTarif' => $idDetil];
                        }
                    }

                    foreach ($containers as $key => $container) {
                        unset($container->id);
                        $container->idLS = $idHdr;
                        $this->db->table('tx_lse_container')->insert($container);
                    }

                    foreach ($packages as $key => $package) {
                        unset($package->id);
                        $package->idLS = $idHdr;
                        $this->db->table('tx_lse_package')->insert($package);
                    }

                    foreach ($referensis as $key => $referensi) {
                        unset($referensi->id);
                        $referensi->idLS = $idHdr;
                        $this->db->table('tx_lse_referensi')->insert($referensi);
                    }

                    $logBefore = model('tx_lseHdr')->find($idLs);
                    model('tx_lseHdr')->update($idLs, ['statusDok' => 'PERUBAHAN']);

                    $dataLog['idLS']        = $idLs;
                    $dataLog['logAction']   = 'Perubahan LS';
                    $dataLog['note']        = 'Perubahan dengan nomor draft ' . $insert->draftNo;
                    save_log_process($dataLog, $logBefore);

                    $dataLog['idLS']        = $idHdr;
                    $dataLog['logAction']   = 'Create Perubahan LS';
                    $dataLog['note']        = 'Perubahan dari LS nomor ' . $dataLS->noLs;
                    save_log_process($dataLog, $logBefore);

                    $this->db->transComplete();

                    if ($this->db->transStatus() === false) {
                        throw new \CodeIgniter\Database\Exceptions\DatabaseException('Gagal membuat perubahan. Transaction exception occured');
                    } else {
                        $msg   = 'Data berhasil disimpan dengen nomor draft ' . $insert->draftNo . '. Klik \'Ok\' untuk beralih ke halaman LS Konsep';
                        $resp  = resp_success($msg, '');
                        return $this->response->setJSON($resp);
                    }
                } else {
                    $resp = resp_error("Gagal membuat perubahan.");
                    return $this->response->setJSON($resp);
                }
            }
        } catch (\CodeIgniter\Database\Exceptions\DatabaseException $e) {
            $resp = resp_error($e->getMessage());
            return $this->response->setJSON($resp);
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!' . $e->getMessage());
            return $this->response->setJSON($resp);
        }
    }

    public function cek_duplikasi($data, $tabel, $idData)
    {
        $errMandatory = "";

        foreach ($data as $field => $val) {
            $modelData      = model($tabel);
            $modelData->where('id !=', $idData);

            if ($tabel == 'tx_lseHdr')
                $modelData->where('statusProses !=', 'DELETED');

            if ((!empty($val['valNo'])) && ($val['valNo'] != '')) {
                $modelData->where($val['fieldNo'], $val['valNo']);
                // $modelData->where($val['fieldNAwal'], $val['valAwal']); 

                // if(!empty($val['fieldAkhir'])){
                //     $modelData->where($val['fieldAkhir'], $val['valAkhir']); 
                // }

                $recordsTotal   = $modelData->countAllResults(false);
                $arrData        = $modelData->first();
                $noDraft        = $arrData ? $arrData->draftNo : '';
                $note           = $val['note'];
                $note           = str_replace("#nomor#", $val['valNo'], $note);
                $note           = str_replace("#awal#", $val['valAwal'], $note);
                $note           = str_replace("#akhir#", $val['valAkhir'], $note);
                $note           = str_replace("#draft#", $noDraft, $note);

                if ($recordsTotal > 0) {
                    if ($idData != '') {
                        if ($idData != $arrData->id) {
                            $errMandatory   .= $note . "<br/>";
                        }
                    } else {
                        $errMandatory       .= $note . "<br/>";
                    }
                }
            }
        }

        return $errMandatory;
    }

    public function update_old_lse($idReff, $aksi, $note)
    {
        try {
            $dataLS         = model('tx_lseHdr')->find($idReff);
            $statusDok   = $dataLS->statusDok;

            if ($statusDok == 'PERUBAHAN') {

                $this->db->transStart();
                $setStatus              = ['statusDok' => 'TERBIT'];
                model('tx_lseHdr')->where('id', $idReff)->set($setStatus)->update();

                $dataLog['idLS']        = $idReff;
                $dataLog['logAction']   = $aksi;
                $dataLog['note']        = $note;
                save_log_process($dataLog, $dataLS);

                $this->db->transComplete();

                if ($this->db->transStatus() === false) {
                    throw new \CodeIgniter\Database\Exceptions\DatabaseException('Data gagal dihapus. Transaction exception occured');
                } else {
                }
            } else {
                $resp = resp_error('Gagal update status LS lama / data tidak valid.', ['iddata' => encrypt_id($idReff)]);
                return $this->response->setJSON($resp);
            }
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!' . $e->getMessage());
            return $this->response->setJSON($resp);
        }
    }

    public function get_mandatory_input()
    {
        $section = $this->request->getPost('section');
        $fields = $this->db->table('t_mandatory')->where('section', $section)->get()->getResult();
        foreach ($fields as $key => $value) {
            $mandatory[] = $value->fieldName;
        }
        return $this->response->setJSON($mandatory);
        //qq($mandatory);
    }

    // 2024-07-15  
    public function rollback()
    {
        try {
            $iddata         = decrypt_id($this->request->getPost('iddata'));
            $alasan         = $this->request->getPost('alasan');
            $dataLS         = model('tx_lseHdr')->find($iddata);
            $statusProses   = $dataLS->statusProses;

            if (session()->get('sess_role') == 6) {
                if ($dataLS->statusProses == 'ISSUED' && $dataLS->statusDok == 'TERBIT' && $dataLS->statusKirim == 'READY') {
                    $this->db->transStart();
                    $setStatus              = ['statusProses' => 'PROCESS', 'statusDok' => 'PROSES'];
                    model('tx_lseHdr')->where('id', $iddata)->set($setStatus)->update();

                    $dataLog['idLS']        = $iddata;
                    $dataLog['logAction']   = 'ROLLBACK';
                    $dataLog['note']        = $alasan;
                    save_log_process($dataLog, $dataLS);

                    $this->db->transComplete();

                    if ($this->db->transStatus() === false) {
                        throw new \CodeIgniter\Database\Exceptions\DatabaseException('Pengajuan gagal dikembalikan ke status Draft');
                    } else {
                        $resp = resp_success('Pengajuan berhasil dikembalikan ke status Draft', ['iddata' => encrypt_id($iddata)]);
                    }
                } else {
                    $resp = resp_error('Status pengajuan tidak dapat dikembalikan ke status Draft', ['iddata' => encrypt_id($iddata)]);
                }
            } else {
                $resp = resp_error('Role anda tidak dapat menolak pengajuan', ['iddata' => encrypt_id($iddata)]);
            }

            return $this->response->setJSON($resp);
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!' . $e->getMessage());
            return $this->response->setJSON($resp);
        }
    }
}
