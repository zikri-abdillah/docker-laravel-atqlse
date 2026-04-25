<?php

namespace App\Controllers\Client;

use App\Controllers\BaseController;
use CodeIgniter\Files\File;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
// use Psr\Log\LoggerInterface;
 
class Lse extends BaseController
{
    protected $jenisPenerbitanModel;
    protected $cabangModel;
    protected $penandatanganModel;
    protected $userModel;
    protected $idJenisLS;
    protected $jenisLS;
    protected $uri;
    protected $validation;

    function __construct(){
    
        if(session()->get('sess_role') != 10)
        {
            // exit('Forbidden');
            //  $page   = [
            //             'page_title'       => 'Anda Dilarang Masuk', 
            //           ];
      
            // $this->render('pages.error-page', $page);
 
            // session()->destroy();
        //    redirect()->to(base_url().'/beranda/client'); 
        }

        $this->idJenisLS            = 1;
        $jenisLsModel               = model('jenisLs');
        $this->jenisLS              = $jenisLsModel->where('id', $this->idJenisLS)->first()->jenis;
        $this->jenisPenerbitanModel = model('jenisPenerbitan');
        $this->cabangModel          = model('cabang');
        $this->penandatanganModel   = model('penandatangan');
        $this->userModel            = model('t_user');
        $this->uri                  = service('uri');
        $this->validation           = \Config\Services::validation();
        helper('filesystem');
    }

    public function index($dataFilter)
    {
        $req                    = $this->request->getGet();

        if($dataFilter == 'konsep'){
            $table_title        = 'Data LSE - Konsep ';
            $breadcrumb_active  = 'Konsep';
        } else if($dataFilter == 'proses'){
            $table_title        = 'Data LSE - Proses';
            $breadcrumb_active  = 'Proses';
        } else if($dataFilter == 'terbit'){
            $table_title        = 'Data LSE - Selesai Proses';
            $breadcrumb_active  = 'Selesai Proses';
        }else if($dataFilter == 'draft'){
            $table_title        = 'Data LSE - Konsep';
            $breadcrumb_active  = 'Konsep';
        }

        $page = [
            'table_title'       => $table_title,
            'breadcrumb_active' => $breadcrumb_active,
            'dataFilter'        => $dataFilter,
        ];

        $param['content']   = $this->render('client.lse.index', $page); 
        $param['addJS']     = '<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>';
        $param['addJS']     .= '<script src="' . base_url() . 'assets/plugins/datatable/js/dataTables.bootstrap5.js"></script>'; 
        $param['addJS']     .= '<script src="' . base_url() . 'assets/plugins/datatable/dataTables.responsive.min.js"></script>';
        $param['addJS']     .= '<script src="' . base_url() . 'assets/plugins/datatable/responsive.bootstrap5.min.js"></script>';
        $param['addJS']     .= '<script src="' . base_url() . '/js/client/ekspor/lse.js?v='.date('YmdHis').'"></script>';

        return $this->render('layout.template', $param);
    }
       
    public function list($dataFilter)
    {    
        $userId             = decrypt_id(session()->get('sess_userid'));
        $arrUser            = $this->userModel->where('id', $userId)->first(); 
          
        $searchParam    = $this->request->getPost('searchParam');
        $arrParam       = post_ajax_toarray($searchParam);
        $castDataFilter = ['konsep'=>['DRAFT','REJECT'],'proses'=>['PROCESS','REVIEW'],'terbit'=>['ISSUED']];
        $lsModel        = model('tx_lseHdr');
        $arrData        = $lsModel->whereIn('idJenisLS',[2,3,4])->where('statusProses <>','DELETED')->whereIn('statusProses', $castDataFilter[$dataFilter]);
 
        if(!empty($arrParam['idJenisLs'])){
            $arrData->where('idJenisLS', $arrParam['idJenisLs']);
        }

        if(!empty($arrParam['idJenisTerbit'])){
            $arrData->where('idJenisTerbit', $arrParam['idJenisTerbit']);
        }

        if(!empty($arrParam['noSi'])){
            $arrData->like('noSi', $arrParam['noSi']);
        }

        if(!empty($arrParam['draftNo'])){
            $arrData->like('draftNo', $arrParam['draftNo']);
        }

        if(!empty($arrParam['idCabang'])){
            $arrData->where('idCabang', $arrParam['idCabang']);
        }

        if(!empty($arrParam['noLs'])){
            $arrData->like('noLs', $arrParam['noLs']);
        }

		if(!empty(trim($arrParam['tglLs'])) && !empty(trim($arrParam['tglAkhirLs']))){
            $arrData    = $lsModel->groupStart()
                        ->where("tglLs BETWEEN '".reverseDateDB($arrParam["tglLs"])."' AND '".reverseDateDB($arrParam["tglAkhirLs"])."'")
                        ->groupEnd();
        }

		if(!empty(trim($arrParam['tglLs'])) && empty(trim($arrParam['tglAkhirLs']))){
            $arrData->where('tglLs >=', reverseDateDB($arrParam['tglLs']));
		}

		if(empty(trim($arrParam['tglLs'])) && !empty(trim($arrParam['tglAkhirLs']))){
            $arrData->where('tglLs <=', reverseDateDB($arrParam['tglAkhirLs']));
		}

        if(!empty($arrUser->idprofile)){
            $arrData->like('idPersh', $arrUser->idprofile);
        }

        $recordsTotal   = $arrData->countAllResults(false);
        $arrData        = $arrData->orderBy('created', 'desc')->findAll($this->request->getPost('length'), $this->request->getPost('start'));
        $row            = [];

        $no             = $this->request->getPost('start')+1;
        $masterStatus   = $this->db->table('m_status_proses')->get()->getResult();

        foreach ($arrData as $key => $data) {
            $arrStatus  = array_column($masterStatus, 'html', 'status');
            $btnDelete  = '';
            $btnEdit    = '';
            $btnLog     = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-success" onclick="view_log(\''.encrypt_id($data->id).'\')" title="Log"><i class="fa fa-history" aria-hidden="true"></i></button> ';
            $btnView    = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-info" onclick="view(\''.encrypt_id($data->id).'\')" title="Lihat"><i class="fa fa-eye"></i></button> ';
            $btnCetak   = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-green" onclick="print(\''.encrypt_id($data->id).'\')" title="Cetak"><i class="fa fa-print"></i></button> ';
            
            if(session()->get('sess_role') == 10 && ($data->statusProses  == 'DRAFT' || $data->statusProses  == 'REJECT')) // pelaku usaha
            {
                $btnDelete  = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-danger" onclick="del(\''.encrypt_id($data->id).'\')" title="Hapus"><i class="fa fa-trash"></i></button> ';
                $btnEdit    = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-warning" onclick="edit(\''.encrypt_id($data->id).'\')" title="Edit"><i class="fa fa-edit"></i></button> ';
                $btnCetak   = '';
            }

            $badgeStatusDok         = '';
            
            if($data->statusProses  == 'ISSUED'){
                if($data->statusDok == 'DIBATALKAN' || $data->statusDok == 'DIUBAH')
                    $badgeStatusDok = '<br><span class="badge rounded-pill bg-danger my-1">'.$data->statusDok.'</span>';
                else if($data->statusDok == 'PERUBAHAN')
                    $badgeStatusDok = '<br><span class="badge rounded-pill bg-warning my-1">PROSES PERUBAHAN</span>';
            }

            $badgeStatusKirim       = '';
            if($data->statusProses  == 'ISSUED'){

                if($data->statusKirim == 'SENT')
                    $bgKirim = 'bg-success';
                else if($data->statusKirim == 'FAILED')
                    $bgKirim = 'bg-danger';
                else
                    $bgKirim = 'bg-dark';

                $badgeStatusKirim = '<br><span class="badge rounded-pill '.$bgKirim.' my-1">'.$data->statusKirim.'</span>';
            }

            $badgeJenisTerbit       = '';
            if($data->idJenisTerbit  == 1)
                $badgeJenisTerbit = '<span class="badge bg-info">Jenis Pengajuan : '.$data->jenisTerbit.'</span>';
            else
                $badgeJenisTerbit = '<span class="badge bg-danger">Jenis Pengajuan : '.$data->jenisTerbit.'</span>';

            $perizinan = model('tx_lseReferensi');
            $perizinan->select('tx_lse_referensi.id as idref,t_dokpersh.*');
            $perizinan->join('t_dokpersh', 't_dokpersh.id = tx_lse_referensi.idDokPersh');
            $perizinan->where('tx_lse_referensi.idJenisDok',1)->where('tx_lse_referensi.idLS',$data->id);
            $arrET      = $perizinan->first();
            $noET       = $tglET = $tglAkhirET = '-';

            if(isset($arrET->noDokumen))
            {
                $noET       = $arrET->noDokumen;
                $tglET      = reverseDate($arrET->tglDokumen);
                $tglAkhirET = reverseDate($arrET->tglAkhirDokumen);
            }

            $jnsLS      = model('jenisLs')->find($data->idJenisLS);
            $columns = [];
            $columns[] = $no++;
            $columns[] = '<span>'.$badgeJenisTerbit.'</span><br><span style="color:#ff9324;">'.$jnsLS->markJenis.'</span><br><span style="color:#3bff30;">'.$data->draftNo.'</span><br><span>SI No: '.$data->noSi.'</span><br><span>Cabang : '.$data->namaCabang.'</span><br><span>'.reverseDateTime($data->created).'</span>';
            if($dataFilter == 'terbit')
                $columns[] = '<span id="no-ls">LS No: '.$data->noLs.'</span><br><span>Tgl LS: '.reverseDate($data->tglLs).'</span><br><span>Tgl Akhir LS: '.reverseDate($data->tglAkhirLs).'</span>';
            $columns[] = '<span>'.$data->namaPersh.'</span><br><span>'.formatNPWP($data->npwp).'</span><br><span>'.$data->namaKota.'</span>';
            $columns[] = '<span><i>Pel Muat:</i><br></span><span style="color:#ffc938;">'.$data->kodePortMuat.' - '.$data->portMuat.'</span><br><i>Pel Tujuan:</i><br><span style="color:#94ff45;">'.$data->kodePortTujuan.' - '.$data->portTujuan.'</span><br><span><i>Transport:</i><br>'.$data->namaTransport.'</span>';
            $columns[] = $arrStatus[$data->statusProses].$badgeStatusDok.$badgeStatusKirim;
            $columns[] = '<div class="btn-list text-nowrap">'.$btnDelete.$btnEdit.$btnView.$btnCetak.$btnLog.'</div>';
            $row[] = $columns;
        }

        $table['draw']              = $this->request->getPost('draw');
        $table['recordsTotal']      = $recordsTotal;
        $table['recordsFiltered']   = $recordsTotal;
        $table['data']              = $row;

        echo json_encode($table);
    }
 
    public function view()
    {
        $id                 = decrypt_id($this->request->getPost('id'));
        $datals             = model('tx_lseHdr')->find($id);
        $packages           = model('tx_lsePackage')->where('idLS',$id)->findAll();
        $containers         = model('tx_lseContainer')->where('idLS',$id)->findAll();
        
        $refModel           = model('tx_lseReferensi');
        $refModel->select('tx_lse_referensi.id as idref,t_dokpersh.*,m_negara.nama as negara');
        $refModel->join('t_dokpersh', 't_dokpersh.id = tx_lse_referensi.idDokPersh');
        $refModel->join('m_negara', 'm_negara.kode = t_dokpersh.negaraPenerbit');
        $refModel->where('tx_lse_referensi.idLS',$id);
        $references         = $refModel->findAll(); 

        $royaltis           = model('tx_lseNtpn')->where('idLS',$id)->findAll();
        $komoditas          = model('tx_lseDtlHs')->where('idLS',$id)->findAll();
 
        $modelKal           = model('tx_lseKalori');
        $modelKal->select("tx_lse_kalori.*, tx_lsedtlhs.seri,  tx_lsedtlhs.postarif");
        $modelKal->join('tx_lsedtlhs', 'tx_lsedtlhs.id = tx_lse_kalori.idPosTarif');   
        $kalori             =  $modelKal->where('tx_lse_kalori.idLS', $id)->findAll();
        $dataperubahan      = [];

        if ($datals->idJenisTerbit == '2'){
            $idPerubahan    = $datals->idPerubahan;
            $dataperubahan  = model('tx_lseHdr')->where('id', $idPerubahan)->first(); 
        }

        $page = [
            'page_title'    => $datals->jenisLS,
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
 
        $param['addJS'] .= '<script src="' . base_url() . '/js/client/ekspor/lse.js?v='.date('YmdHis').'"></script>';

        if($datals->idJenisLS == '1'){
            $param['content'] = $this->render('client.lse.view-bb', $page); 
        } else {
            $param['content'] = $this->render('client.lse.view', $page); 
        }

        return $this->render('layout.template', $param);
    }
           
    public function view_file()
    {
        try{
            $idLS = decrypt_id($this->request->getPost('id'));
            $dataLS = model('tx_lseHdr')->find($idLS);
            $path = WRITEPATH.'uploads/'.$dataLS->fileLS;
            if(file_exists($path))
                return $this->response->download($path,null,true)->inline();
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
        if(file_exists(WRITEPATH.'uploads/'.$dataLS->fileLS))
            unlink(WRITEPATH.'uploads/'.$dataLS->fileLS);

        if(model('tx_lseHdr')->where('id',$idLS)->set(['fileLS'=>NULL,'fileUrl'=>NULL,'lastUpdate'=>date('Y-m-d H:i:s')])->update())
            $resp = resp_success('File berhasil dihapus');
        else
            $resp = resp_error('File gagal dihapus');

        return $this->response->setJSON($resp);
    }
    
    public function input()
    {
        $userId             = decrypt_id(session()->get('sess_userid')); 
        $arrUser            = $this->userModel->where('id', $userId)->first(); 
        $arrJenisPenerbitan = $this->jenisPenerbitanModel->where('isActive', 'Y')->findAll();
        $arrCabang          = $this->cabangModel->where('isActive', 'Y')->findAll();
        $arrPenandatangan   = $this->penandatanganModel->where('isActive', 'Y')->findAll();
        $arrPerusahaan      = [];  

        if(!empty($arrUser->idprofile)){
            $arrPerusahaan     = model('perusahaan')->where('id', $arrUser->idprofile)->first(); 
        }

        $req = $this->request->getPost();
        $page = [
            'page_title'            => 'Pengajuan LSE Mineral',
            'arrJenisPenerbitan'    => $arrJenisPenerbitan,
            'arrPenandatangan'      => $arrPenandatangan,
            'arrCabang'             => $arrCabang,
            'arrPerusahaan'         => $arrPerusahaan
        ];

        $param['addJS'] = '<script src="' . base_url() . 'assets/plugins/formwizard/jquery.smartWizard.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/formwizard/fromwizard.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/jquery-steps/jquery.steps.min.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/parsleyjs/parsley.min.js"></script>';
        $param['addJS'] .= '<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/datatable/js/dataTables.bootstrap5.js"></script>'; 
        $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/datatable/dataTables.responsive.min.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/datatable/responsive.bootstrap5.min.js"></script>';

        $param['addJS'] .= '<script src="' . base_url() . '/js/client/ekspor/input.js?v='.date('YmdHis').'"></script>';

        $param['content'] = $this->render('client.lse.input', $page);
        return $this->render('layout.template', $param);
    }

    public function edit()
    {
        try {
            $id                 = decrypt_id($this->request->getPost('id'));
            $userId             = decrypt_id(session()->get('sess_userid')); 
            $arrUser            = $this->userModel->where('id', $userId)->first(); 
            $arrJenisPenerbitan = $this->jenisPenerbitanModel->where('isActive', 'Y')->findAll();
            $arrCabang          = $this->cabangModel->where('isActive', 'Y')->findAll();
            $arrPenandatangan   = $this->penandatanganModel->where('isActive', 'Y')->findAll(); 
            $datals             = model('tx_lseHdr')->where('id',$id)->first();
            $komoditas          = model('tx_lseDtlHs')->where('idLS',$id)->findAll();
            $references         = model('tx_lseReferensi')->where('idLS',$id)->findAll();
            $royaltis           = model('tx_lseNtpn')->where('idLS',$id)->findAll(); 

            if(!empty($arrUser->idprofile)){
                $arrPerusahaan     = model('perusahaan')->where('id', $arrUser->idprofile)->first(); 
            }
    
            $page = [
                'page_title'            => 'Edit Pengajuan LSE',
                'arrJenisPenerbitan'    => $arrJenisPenerbitan,
                'arrPenandatangan'      => $arrPenandatangan,
                'arrCabang'             => $arrCabang,
                'arrPerusahaan'         => $arrPerusahaan,
                'datals'                => $datals,
                'komoditas'             => $komoditas,
                'references'            => $references,
                'royaltis'              => $royaltis,
            ];

            if(!empty($datals->currencyInvoice))
                $page['currencyInvoice'] = model('currency')->where('kode',$datals->currencyInvoice)->first()->uraian;

            $param['addJS'] = '<script src="' . base_url() . 'assets/plugins/formwizard/jquery.smartWizard.js"></script>';
            $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/formwizard/fromwizard.js"></script>';
            $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/jquery-steps/jquery.steps.min.js"></script>';
            $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/parsleyjs/parsley.min.js"></script>';
            $param['addJS'] .= '<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>';
            $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/datatable/js/dataTables.bootstrap5.js"></script>'; 
            $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/datatable/dataTables.responsive.min.js"></script>';
            $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/datatable/responsive.bootstrap5.min.js"></script>';
 
            $param['addJS'] .= '<script src="' . base_url() . '/js/client/ekspor/input.js?v='.date('YmdHis').'"></script>';

            $param['content'] = $this->render('client.lse.input', $page);
            return $this->render('layout.template', $param);
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!'.$e->getMessage());
            return $this->response->setJSON($resp);
        }
    }

    public function save()
    { 
        try {        
            $fileUpload = $this->request->getFile('fileLS');
            if($fileUpload){
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
                    $resp = resp_error($this->validator->getError(),'','Upload Gagal');
                    return $this->response->setJSON($resp);
                }
                else{

                    if (! $fileUpload->hasMoved()) {
                        $pathFile = $fileUpload->store('filels/'.date('Ym'));
                    }
                    else{
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
 
            if($act == 'SEND')
                $data['statusProses']   = 'PROCESS'; 

            if($errExpDate == ''){
                $logBefore                  = NULL;

                if(!empty($data['idData'])){
                    $data['id']             = decrypt_id($data['idData']);
                    $logBefore              = model('tx_lseHdr')->find($data['id']);
                    $dataLog['logAction']   = $act;
                    $data['lastUser']       = decrypt_id(session()->get('sess_userid'));
                    $data['lastUpdate']     = date('Y-m-d H:i:s');

                    if(empty($data['statusProses']))
                        $data['statusProses'] = $logBefore->statusProses;
                }
                else{
                    $dataLog['logAction']   = 'CREATE';
                    $data['statusDok']      = 'KONSEP';
                    $data['userCreate']     = decrypt_id(session()->get('sess_userid'));
                    $data['created']        = date('Y-m-d H:i:s');

                    if($act != 'SEND'){
                        $data['statusProses']   = 'DRAFT';
                    }
                }
 
                $masterStatus = $this->db->table('m_status_proses')->where('status',$data['statusProses'])->get()->getRow();

                if(empty($act) || empty($masterStatus)){
                    $resp = resp_error('Invalid Action');
                    return $this->response->setJSON($resp);exit;
                }

                unset($data['idData']);

                if(!empty($data['id']))
                {
                    $dokModel   = model('tx_lseReferensi');
                    $dokModel->select('t_dokpersh.*');
                    $dokModel->join('t_dokpersh', 't_dokpersh.id = tx_lse_referensi.idDokPersh');
                    $dokModel->where('tx_lse_referensi.idLS',$data['id'])->where('t_dokpersh.npwp <> ',clean_npwp($data['npwp']));
                    $arrData    = $dokModel->findAll();

                    if(count($arrData) > 0)
                    {
                        $resp = resp_error('Tidak dapat menyimpan. Terdapat dokumen referensi yang berbeda dengan NPWP eksportir');
                        return $this->response->setJSON($resp);exit;
                    }
                }

                $lsModel        = model('tx_lseHdr');
                if(!empty($data['idJenisLS'])){
                    $jnsLSModel = model('jenisLs');
                    $jnsLS      = $jnsLSModel->find($data['idJenisLS']);

                    if(empty($data['id'])){
                        $data['draftIncrement'] = $lsModel->select('IFNULL(MAX(draftIncrement),0)+1 as noDraft',false)->where('YEAR(created)',DATE('Y'))->get()->getRow()->noDraft;
                        $noDraft                = str_pad($data['draftIncrement'],4,"0",STR_PAD_LEFT);
                        $data['draftNo']        = $noDraft.'/'.PREFIX_NUMBERING.'-'.$jnsLS->kode.'/'.DATE('m').'/'.DATE('Y');
                    }
                    $data['jenisLS']            = $jnsLS->jenis;
                    $data['tglDraft']           = date('Y-m-d');
                }

                if(!empty($data['idPersh'])){
                    $perusahaan             = model('perusahaan')->where('id',$data['idPersh'])->first();
                    $data['bentukPersh']    = $perusahaan->bentukPersh;
                    $data['namaPersh']      = $perusahaan->nama;
                    $data['emailPersh']     = $perusahaan->email;
                    $data['idJnsIUP']       = $perusahaan->idJenisIup;
                    $data['jenisIUP']       = $perusahaan->jenisIUP;
                }

                $data['flagOnline']     = 'Y';
                $data['namaCabang']     = $this->request->getPost('namaCabang');
                $data['namaTtd']        = $this->request->getPost('namaTtd');
                $data['tglSi']          = reverseDateDB($data['tglSi']);
                $data['tglPveb']        = reverseDateDB($data['tglPveb']); 
                $data['jenisTerbit']    =  model('jenisPenerbitan')->find($data['idJenisTerbit'])->jenis;
                $data['npwp']           = clean_npwp($data['npwp']); 
                $data['tglIUP']         = reverseDateDB($data['tglIUP']);

                if(!empty($data['kdProp'])){
                    $propEks                = model('propinsi')->where('id',$data['kdProp'])->first();
                    $data['kdPropInatrade'] = $propEks->kodeInatrade;
                    $data['namaProp']       = $propEks->namaPropinsi;
                }

                if(!empty($data['kdKota'])){
                    $kotaEks                = model('kota')->where('id',$data['kdKota'])->first();
                    $data['kdKotaInatrade'] = $kotaEks->kodeInatrade;
                    $data['namaKota']       = $kotaEks->namaKota;
                }

                if(!empty($data['kdKotaImportir'])){
                    $kotaImp                        = model('kota')->where('id',$data['kdKotaImportir'])->first();
                    $data['kdKotaImportirInatrade'] = $kotaImp->kodeUNLOCODE;
                    $data['kotaImportir']           = $kotaImp->namaKota;
                }

                if(!empty($data['kdNegaraImportir'])){
                    $negaraImp              = model('negara')->where('kode',$data['kdNegaraImportir'])->first();
                    $data['negaraImportir'] = $negaraImp->nama;
                }

                $data['incoterm']           = $this->request->getPost('incoterm');
                $data['tglPackingList']     = reverseDateDB($data['tglPackingList']);
                $data['tglLc']              = reverseDateDB($data['tglLc']);
                $data['tglInvoice']         = reverseDateDB($data['tglInvoice']);
                $data['qtyNetto']           = insertNumber($data['qtyNetto']);
                $data['satuanNetto']        = 'KGM';
                $data['qtyBruto']           = insertNumber($data['qtyBruto']);
                $data['satuanBruto']        = 'KGM';
                $data['nilaiInvoice']       = insertNumber($data['nilaiInvoice']);
                $data['nilaiInvoiceIDR']    = insertNumber($data['nilaiInvoiceIDR']);
                $data['nilaiInvoiceUSD']    = insertNumber($data['nilaiInvoiceUSD']);
                $data['modaTransport']      = $this->request->getPost('modaTransport');

                if(!empty($data['kodeLokasiPeriksa'])){
                    $port                   = model('port')->where('kode',$data['kodeLokasiPeriksa'])->first();
                    $data['lokasiPeriksa']  = $port->uraian;
                }

                if(!empty($data['kodePortMuat'])){
                    $port                   = model('port')->where('kode',$data['kodePortMuat'])->first();
                    $data['portMuat']       = $port->uraian;
                }

                if(!empty($data['kodePortTransit'])){
                    $port                   = model('port')->where('kode',$data['kodePortTransit'])->first();
                    $data['portTransit']    = $port->uraian;
                }

                if(!empty($data['kodePortTujuan'])){
                    $port                   = model('port')->where('kode',$data['kodePortTujuan'])->first();
                    $data['portTujuan']     = $port->uraian;
                }

                if(!empty($data['kodeBenderaKapal'])){
                    $negaraImp              = model('negara')->where('kode',$data['kodeBenderaKapal'])->first();
                    $data['benderaKapal']   = $negaraImp->nama;
                }

                if(isset($data['tglPeriksa']))
                    $data['tglPeriksa']         = reverseDateDB($data['tglPeriksa']);

                $data['tglMuat']            = reverseDateDB($data['tglMuat']);
                $data['tglBerangkat']       = reverseDateDB($data['tglBerangkat']);

                if(!empty($data['kodeNegaraTransit'])){
                    $negaraImp              = model('negara')->where('kode',$data['kodeNegaraTransit'])->first();
                    $data['negaraTransit']  = $negaraImp->nama;
                }

                if(!empty($data['kodeNegaraTujuan'])){
                    $negaraImp              = model('negara')->where('kode',$data['kodeNegaraTujuan'])->first();
                    $data['negaraTujuan']   = $negaraImp->nama;
                }

                if(isset($pathFile)){
                    $data['fileLS'] = $pathFile;
                    $data['fileUrl'] = md5($pathFile);
                }

                $errMandatory = $this->cek_mandatory($data,'HEADER');

                if(count($errMandatory) == 0) {
 
                    $this->db->transStart();
                    $lsModel->upsert($data);
                    if(!empty($lsModel->insertID()))
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
                     
                    $this->db->transComplete();

                    if ($this->db->transStatus() === false){
                        $resp    = resp_error('Data gagal disimpan (Transaction failed)');
                    } else {
                        if($act == 'SEND'){  
                            if(email_pengajuan($dataLS) == 1){
                                $msg = 'Data berhasil disimpan dan dikirim ke Surveyor'; 
                            } else {
                                $msg = 'Gagal mengirim email'; 
                            }
                        } else {
                            $msg = 'Data berhasil disimpan';
                        }

                        $resp    = resp_success($msg,$respData);
                    }
                } else {
                    $textErr = implode('<br>', $errMandatory);
                    $resp    = resp_error('Perhatikan isian berikut <br>'.$textErr);
                }
                return $this->response->setJSON($resp);
            } else {
                $resp = resp_error($errExpDate);
                return $this->response->setJSON($resp);
            }  
        } catch (\Throwable $e) {
            if(isset($pathFile))
                unlink(WRITEPATH.'uploads/'.$pathFile);
            $resp = resp_error('An Exception has occured!'.$e->getMessage());
            return $this->response->setJSON($resp);
        }
    }

    public function create_perubahan()
    {
        try {
            $idLs       = decrypt_id($this->request->getPost('idLs'));
            $lsModel    = model('tx_lseHdr');
            $dataLS     = $lsModel->find($idLs);
            $dataDetil  = model('tx_lseDtlHs')->where('idLS',$idLs)->findAll();
            $containers = model('tx_lseContainer')->where('idLS',$idLs)->findAll();
            $packages   = model('tx_lsePackage')->where('idLS',$idLs)->findAll(); 
            $royaltis   = model('tx_lseNtpn')->where('idLS',$idLs)->findAll();
            $referensis = model('tx_lseReferensi')->where('idLS',$idLs)->findAll();
            $hsntpn     = model('tx_hsNtpn')->where('idLS',$idLs)->findAll();

            if($dataLS)
            {
                $jnsLSModel             = model('jenisLs');
                $jnsLS                  = $jnsLSModel->find($dataLS->idJenisLS);
                $insert                 = $dataLS;
                $insert->draftIncrement = $lsModel->select('IFNULL(MAX(draftIncrement),0)+1 as noDraft',false)->where('YEAR(created)',DATE('Y'))->get()->getRow()->noDraft;
                $noDraft                = str_pad($insert->draftIncrement,4,"0",STR_PAD_LEFT);
                $insert->draftNo        = $noDraft.'/'.PREFIX_NUMBERING.'-'.$jnsLS->kode.'/'.DATE('m').'/'.DATE('Y');

                if($dataLS->idJenisTerbit == 1){
                    $insert->idReff      = $dataLS->id;
                    $insert->idPerubahan = $dataLS->id;
                } else {
                    $insert->idReff      = $dataLS->idReff;
                    $insert->idPerubahan = $dataLS->id;
                }

                $insert->idJenisTerbit  = 2;
                $insert->jenisTerbit    = 'Perubahan';
                $insert->statusDok      = 'PROSES';
                $insert->statusProses   = 'DRAFT';
                $insert->statusKirim    = 'KONSEP';
                $insert->flagOnline     = 1;

                $insert->userCreate = decrypt_id(session()->get('sess_userid'));
                $insert->created    = date('Y-m-d H:i:s');

                unset($insert->id);
                unset($insert->lastUser);
                unset($insert->lastUpdate);
                unset($insert->notifKirim);
                unset($insert->issuedDateTime);
                unset($insert->idTtd);
                unset($insert->namaTtd);
                unset($insert->fileLS);
                unset($insert->fileUrl);

                $this->db->transStart();

                $this->db->table('tx_lsehdr')->insert($insert);
                $idHdr = $this->db->insertID();

                $arrHsNtpn = [];
                foreach ($dataDetil as $key => $detil) {
                    $idPosTarif     = $detil->id;
                    unset($detil->id);
                    $detil->idLs    = $idHdr;
                    $this->db->table('tx_lsedtlhs')->insert($detil);
                    $idDetil        = $this->db->insertID();
                    $datahsntpn     = $this->db->table('tx_lsehsntpn')->where('idLs',$idLs)->where('idPosTarif',$idPosTarif)->get()->getResult();
                    foreach ($datahsntpn as $keyhs => $hsntpn) {
                        $arrHsNtpn[] = ['idPosTarifOld'=>$idPosTarif,'idNtpnOld'=>$hsntpn->idNtpn,'idPosTarif'=>$idDetil];
                    }
                }

                foreach ($royaltis as $key => $royalti) {
                    $idRoyalti      = $royalti->id;
                    unset($royalti->id);
                    $royalti->idLs  = $idHdr;
                    $this->db->table('tx_lse_ntpn')->insert($royalti);
                    $idNtpn         = $this->db->insertID();
                    foreach ($arrHsNtpn as $key => $hsntpn) {
                        if($idRoyalti == $hsntpn['idNtpnOld']){
                            $arrHsNtpn[$key]['idNtpn'] = $idNtpn;
                        }
                    }
                }

                foreach ($arrHsNtpn as $key => $hsntpn) {
                    unset($hsntpn['idPosTarifOld']);
                    unset($hsntpn['idNtpnOld']);
                    $hsntpn['idLs'] = $idHdr;
                    $this->db->table('tx_lsehsntpn')->insert($hsntpn);
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
                model('tx_lseHdr')->update($idLs, ['statusDok'=>'PERUBAHAN']);

                $dataLog['idLS']        = $idLs;
                $dataLog['logAction']   = 'Perubahan LS';
                $dataLog['note']        = 'Perubahan dengan nomor draft '.$insert->draftNo;
                save_log_process($dataLog,$logBefore);

                $dataLog['idLS']        = $idHdr;
                $dataLog['logAction']   = 'Create Perubahan LS';
                $dataLog['note']        = 'Perubahan dari LS nomor '.$dataLS->noLs;
                save_log_process($dataLog,$logBefore);

                $this->db->transComplete();

                if ($this->db->transStatus() === false) {
                    throw new \CodeIgniter\Database\Exceptions\DatabaseException('Gagal membuat perubahan. Transaction exception occured');
                }
                else{
                    $msg = 'Data berhasil disimpan dengen nomor draft '.$insert->draftNo.'. Klik \'Ok\' untuk beralih ke halaman LS Konsep';
                    $resp    = resp_success($msg,'');
                    return $this->response->setJSON($resp);
                }
            }
        } catch (\CodeIgniter\Database\Exceptions\DatabaseException $e) {
            $resp = resp_error($e->getMessage());
            return $this->response->setJSON($resp);
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!'.$e->getMessage());
            return $this->response->setJSON($resp);
        }
    }

    public function delete()
    {
        try
        {
            $idLs           = decrypt_id($this->request->getPost('idLs'));
            $dataLS         = model('tx_lseHdr')->find($idLs);
            $statusProses   = $dataLS->statusProses;
            $idJenisTerbit  = $dataLS->idJenisTerbit;
            $idPerubahan    = $dataLS->idPerubahan;
            $draftNo        = $dataLS->draftNo;
             
            if($statusProses == 'DRAFT' && session()->get('sess_role') == 10){ 
                $this->db->transStart();
                $setStatus              = ['statusDok'=>'DIHAPUS','statusProses'=>'DELETED'];
                model('tx_lseHdr')->where('id',$idLs)->set($setStatus)->update();
 
                $dataLog['idLS']        = $idLs;
                $dataLog['logAction']   = 'Hapus LS';
                $dataLog['note']        = ''; 
                save_log_process($dataLog,$dataLS);

                $this->db->transComplete();
   
                $this->update_old_lse($idPerubahan, 'DELETED', 'Hapus draft LS Ekspor nomor '.$draftNo);

                if ($this->db->transStatus() === false)
                    throw new \CodeIgniter\Database\Exceptions\DatabaseException('Data gagal dihapus. Transaction exception occured');
                else{
                    $resp = resp_success('Data berhasil dihapus',['iddata'=>encrypt_id($idLs)]);
                }
            }
            else{
                $resp = resp_error('Data gagal dihapus. Status / role tidak valid.',['iddata'=>encrypt_id($idLs)]);
            }
            return $this->response->setJSON($resp);
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!'.$e->getMessage());
            return $this->response->setJSON($resp);
        }
    }

    private function cek_mandatory($data,$section)
    {
        $errMandatory = [];
        $mandatories = model('mandatory')->where('idJenisLS',1)->where('section',$section)->findAll();
        foreach ($mandatories as $key => $mandatory) {
            $mandatory = (object) $mandatory;
            if(empty($data[$mandatory->fieldName])){
                $errMandatory[] = $mandatory->fieldLabel.' tidak boleh kosong';
            }
            if(!empty($mandatory->maxLength)){
                if(strlen($data[$mandatory->fieldName]) > $mandatory->maxLength){
                    $errMandatory[] = $mandatory->fieldLabel.' maksimal '.$mandatory->maxLength.' karakter';
                }
            }
        }
        return $errMandatory;
    }
    
    public function cek_duplikasi($data, $tabel, $idData)
    {
        $errMandatory = "";
 
        foreach ($data as $field => $val) {   
            $modelData      = model($tabel);  
            $modelData->where('id !=', $idData); 
            $modelData->where($val['fieldNo'], $val['valNo']); 
            $modelData->where($val['fieldNAwal'], $val['valAwal']); 

            if(!empty($val['fieldAkhir'])){
                $modelData->where($val['fieldAkhir'], $val['valAkhir']); 
            }
 
            $recordsTotal   = $modelData->countAllResults(false); 
            $arrData        = $modelData->first();
            // $arrData        = $modelData->findAll();

            $note           = $val['note'];
            $note           = str_replace("#nomor#", $val['valNo'], $note);
            $note           = str_replace("#awal#", $val['valAwal'], $note);
            $note           = str_replace("#akhir#", $val['valAkhir'], $note);
 
            // foreach ($arrData as $field => $value) {  
                $noDraft    = $arrData ? $arrData->draftNo : '';
                $note       = str_replace("#draft#", $noDraft, $note);
 
                if($recordsTotal > 0){   
                    if($idData != ''){   
                        if($idData != $arrData->id){
                            $errMandatory   .= $note."<br/>"; 
                        } 
                    } else {
                        $errMandatory       .= $note."<br/>"; 
                    }
                }   
            // } 
        }    
  
        return $errMandatory;
    }

    public function update_old_lse($idReff, $aksi, $note)
    { 
        try
        { 
            $dataLS         = model('tx_lseHdr')->find($idReff);
            $statusDok   = $dataLS->statusDok; 
             
            if($statusDok == 'PERUBAHAN'){  
                
                $this->db->transStart();
                $setStatus              = ['statusDok'=>'TERBIT'];
                model('tx_lseHdr')->where('id',$idReff)->set($setStatus)->update();
 
                $dataLog['idLS']        = $idReff;
                $dataLog['logAction']   = $aksi;
                $dataLog['note']        = $note; 
                save_log_process($dataLog,$dataLS);

                $this->db->transComplete();

                if ($this->db->transStatus() === false) {
                    throw new \CodeIgniter\Database\Exceptions\DatabaseException('Data gagal dihapus. Transaction exception occured');
                } else{
                    
                }
            }
            else{
                $resp = resp_error('Gagal update status LS lama / data tidak valid.',['iddata'=>encrypt_id($idReff)]);
                return $this->response->setJSON($resp);
            }
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!'.$e->getMessage());
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
        $dataModel->where('tx_lse_referensi.idLS',$idLS);
        $arrData = $dataModel->findAll();

        $html = '';

        if(!empty($dataLS->idPermohonanNSW))
        {
            $dokReffINSW = model('t_inswPermohonanDok')->where('idPermohonan',$dataLS->idPermohonanNSW)->findAll();
            foreach ($dokReffINSW as $key => $data) {

                $btnEdit = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-warning me-2" onclick="showUploadDokNsw(\''.encrypt_id($data->id).'\')" title="Edit"><i class="fa fa-pencil"></i></button>';

                if(!in_array($dataLS->statusProses, ['DRAFT']))
                    $btnEdit = ''; 

                $html .= '<tr >
                        <td class="align-top text-nowrap text-center">'.($key+1).'</td>
                        <td class="align-top">'.$data->uraiNegaraPenerbit.'</td>
                        <td class="align-top">'.$data->namaDokumen.'</td>
                        <td class="align-top">'.$data->nomorDokumen.'</td>
                        <td class="align-top">'.reverseDate($data->tanggalDokumen).'</td>
                        <td class="align-top">'.reverseDate($data->tglAkhirDokumen).'</td>
                        <td class="align-top text-nowrap text-center">
                        '.$btnEdit.'
                        <button type="button" class="btn btn-sm btn-w-xs btn-icon btn-info" onclick="view_dok_persh(\''.encrypt_id($data->id).'\')" title="Lihat File"><i class="fa fa-eye"></i></button>
                        </td>
                    </tr>';
            }
        }
 
        foreach ($arrData as $key => $data) {
            $btnDelete      = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-danger me-2" onclick="del_dok_ref(\''.encrypt_id($data->idref).'\')" title="Hapus"><i class="fa fa-trash"></i></button>';

            // if(session()->get('sess_role') == 7 && !config('App')->spv_can_edit)
            //     $btnDelete  = '';

            if($dataLS->statusProses != 'DRAFT')
                $btnDelete  = '';

            $inputET        = '';
            if($data->idJenisDok == '1')
            {
                $inputET = '<input type="hidden" id="noET" value="'.$data->noDokumen.'"></input>
                            <input type="hidden" id="tglET" value="'.reverseDate($data->tglDokumen).'"></input>
                            <input type="hidden" id="tglAkhirET" value="'.reverseDate($data->tglAkhirDokumen).'"></input>
                ';
            }
            $html .= '<tr >
                        <td class="align-top text-nowrap text-center">'.($key+1).'</td>
                        <td class="align-top">'.model("negara")->where("kode",$data->negaraPenerbit)->first()->nama.'</td>
                        <td class="align-top">'.$data->jenisDok.'</td>
                        <td class="align-top">'.$data->noDokumen.'</td>
                        <td class="align-top">'.reverseDate($data->tglDokumen).'</td>
                        <td class="align-top">'.reverseDate($data->tglAkhirDokumen).'</td>
                        <td class="align-top text-nowrap text-center">
                        '.$btnDelete.'
                        <button type="button" class="btn btn-sm btn-w-xs btn-icon btn-info" onclick="view_dok_persh(\''.encrypt_id($data->id).'\')" title="Lihat File"><i class="fa fa-eye"></i></button>
                        </td>
                    </tr>
                    '.$inputET.'
                    ';
        }

        $resp['content'] = $html;
        return $this->response->setJSON($resp);
    }

    public function save_dok_pilih()
    {
        try {
            $reffModel  = model('tx_lseReferensi');
            $idData     = decrypt_id($this->request->getPost('idData'));
            // $postdata   = json_decode($this->request->getPost('dokpilih')); 
            $dokpilih   = $this->request->getPost('dokpilih');
            $postdata   = explode(",",  $dokpilih); 
            $postdata   = array_map('decrypt_id', $postdata); 
            $seleted    = $reffModel->select('idDokPersh')->where('idLS',$idData)->find();
            $seleted    = obj_flatten($seleted,'idDokPersh');
            $unselect   = array_diff($seleted,$postdata);

            if(!empty($unselect))
            {
                $reffModel->whereIn('idDokPersh',$unselect)->delete();
            }

            foreach ($postdata as $key => $idDok) {
                $dokPilih                   = model('t_dokpersh')->find($idDok);
                if(!in_array($idDok,$seleted))
                {
                    $rowDok['idLS']         =  $idData;
                    $rowDok['idJenisDok']   =  $dokPilih->idJenisDok;
                    $rowDok['idDokPersh']   =  $idDok;
                    $rowDok['created']      =  date('Y-m-d H:i:s');
                    $reffModel->insert($rowDok);
                }
            }
            $resp = resp_success('Data berhasil disimpan',['iddata'=>encrypt_id($idData)]);
            return $this->response->setJSON($resp);
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!'.$e->getMessage());
            return $this->response->setJSON($resp);
        }
    }

    public function delete_dok_ref()
    {
        try {

            $idRef = decrypt_id($this->request->getPost('idRef'));
            $delete = model('tx_lseReferensi')->delete($idRef);
            if($delete)
            {
                $resp = resp_success('Referensi berhasil dihapus');
            }
            else{
                $resp = resp_error('Referensi gagal dihapus');
            }
            return $this->response->setJSON($resp);

        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!'.$e->getMessage());
            return $this->response->setJSON($resp);
        }
    }

    public function add_package()
    {
        try{
            $data['idLs'] = decrypt_id($this->request->getPost('idLs'));
            $data['unit'] = $this->request->getPost('p_unit');
            $data['jml'] = insertNumber($this->request->getPost('p_jml'));
            $data['packageInfo'] = $this->request->getPost('p_packageInfo');

            if(!empty($this->request->getPost('idPackage')))
                $data['id'] = decrypt_id($this->request->getPost('idPackage'));

            if(!empty($data['unit'])){
                 $unit = model('package')->where('kode',$data['unit'])->first();
                 $data['unit'] = $unit->kode;
                 $data['uraiUnit'] = $unit->uraian;
            }

            $errMandatory = $this->cek_mandatory($data,'PACKAGE');

            if(count($errMandatory) > 0)
            {
                $textErr = implode('<br>', $errMandatory);
                $resp = resp_error('Perhatikan isian berikut <br>'.$textErr);
            }
            else{
                $model = model('tx_lsePackage');
                $model->upsert($data);
                $resp = resp_success('Data berhasil disimpan');
            }

            $idData = $model->insertID();
            $this->update_seri_package($data['idLs']);

            return $this->response->setJSON($resp);
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!'.$e->getMessage());
            return $this->response->setJSON($resp);
        }
    }

    public function get_package()
    {
        try{
            $idLs = decrypt_id($this->request->getPost('idLs'));
            $dataLS = model('tx_lseHdr')->find($idLs);
            $packageModel = model('tx_lsePackage');
            $dataPackage = $packageModel->where('idLs',$idLs)->findAll();

            $html = '';
            foreach ($dataPackage as $key => $package) {

                $btnAct ='<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-danger me-2" onclick="del_package(\''.encrypt_id($package->id).'\')" title="Hapus"><i class="fa fa-trash"></i></button>
                                <button type="button" class="btn btn-sm btn-w-xs btn-icon btn-warning" onclick="edit_package(\''.encrypt_id($package->id).'\')" title="Edit"><i class="fa fa-edit"></i></button>';

                if(!in_array($dataLS->statusProses, ['DRAFT']))
                    $btnAct = '';
                // else if(session()->get('sess_role') == 7 && !config('App')->spv_can_edit)
                //     $btnAct = '';

                $html .= '<tr >
                            <td class="align-top text-nowrap text-center">'.($key+1).'</td>
                            <td class="align-top">'.$package->packageInfo.'</td>
                            <td class="align-top text-nowrap">'.formatAngka($package->jml).'</td>
                            <td class="align-top">'.$package->uraiUnit.' - '.$package->unit.'</td>
                            <td class="align-top text-nowrap text-center">
                                '.$btnAct.'
                            </td>
                        </tr>';
            }
            $resp['content'] = $html;
            return $this->response->setJSON($resp);
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!'.$e->getMessage());
            return $this->response->setJSON($resp);
        }
    }

    public function del_package()
    {
         try {

            $id = decrypt_id($this->request->getPost('id'));
            $idLs = decrypt_id($this->request->getPost('idLs'));

            $delete = model('tx_lsePackage')->delete($id);
            if($delete)
            {
                $resp = resp_success('Package berhasil dihapus');
            }
            else{
                $resp = resp_error('Package gagal dihapus');
            }
            return $this->response->setJSON($resp);

        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!'.$e->getMessage());
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
            $resp = resp_error('An Exception has occured!'.$e->getMessage());
            return $this->response->setJSON($resp);
        }
    }

    public function add_container()
    {
        try{
            $data['idLs'] = decrypt_id($this->request->getPost('idLs'));
            $data['nomor'] = $this->request->getPost('cnt_nomor');
            $data['idJenis'] = $this->request->getPost('cnt_jenis');

            if(!empty($this->request->getPost('idContainer')))
                $data['id'] = decrypt_id($this->request->getPost('idContainer'));

            if(!empty($data['idJenis'])){
                $container = model('jenisContainer')->where('id',$data['idJenis'])->first();
                $data['kode'] = $container->kode;
                $data['keterangan'] = $container->keterangan;
                $data['panjang'] = $container->panjang_Ft;
                $data['tinggi'] = $container->tinggi_Ft;

            }

            $errMandatory = $this->cek_mandatory($data,'CONTAINER');

            if(count($errMandatory) > 0)
            {
                $textErr = implode('<br>', $errMandatory);
                $resp = resp_error('Perhatikan isian berikut <br>'.$textErr);
            }
            else{
                $model = model('tx_lseContainer');
                $model->upsert($data);
                $resp = resp_success('Data berhasil disimpan');
            }

            $idData = $model->insertID();
            $this->update_seri_container($data['idLs']);

            return $this->response->setJSON($resp);
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!'.$e->getMessage());
            return $this->response->setJSON($resp);
        }
    }

    public function get_container()
    {
        try{
            $idLs = decrypt_id($this->request->getPost('idLs'));
            $dataLS = model('tx_lseHdr')->find($idLs);
            $containerModel = model('tx_lseContainer');
            $dataContainer = $containerModel->where('idLs',$idLs)->findAll();

            $html = '';
            foreach ($dataContainer as $key => $container) {

                $btnAct ='<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-danger me-2" onclick="del_container(\''.encrypt_id($container->id).'\')" title="Hapus"><i class="fa fa-trash"></i></button>
                            <button type="button" class="btn btn-sm btn-w-xs btn-icon btn-warning" onclick="edit_container(\''.encrypt_id($container->id).'\')" title="Edit"><i class="fa fa-edit"></i></button>';

                if(!in_array($dataLS->statusProses, ['DRAFT']))
                    $btnAct = '';
                // else if(session()->get('sess_role') == 7 && !config('App')->spv_can_edit)
                //     $btnAct = '';

                $html .= '<tr >
                            <td class="align-top text-nowrap text-center">'.($key+1).'</td>
                            <td class="align-top">'.$container->kode.' - '.$container->keterangan.'<br>'.$container->panjang.' X '.$container->tinggi.' Ft</td>
                            <td class="align-top text-nowrap">'.$container->nomor.'</td>
                            <td class="align-top text-nowrap text-center">
                                '.$btnAct.'
                            </td>
                        </tr>';
            }
            $resp['content'] = $html;
            return $this->response->setJSON($resp);
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!'.$e->getMessage());
            return $this->response->setJSON($resp);
        }
    }

    public function del_container()
    {
         try {

            $id = decrypt_id($this->request->getPost('id'));
            $idLs = decrypt_id($this->request->getPost('idLs'));

            $delete = model('tx_lseContainer')->delete($id);
            if($delete)
            {
                $resp = resp_success('Container berhasil dihapus');
            }
            else{
                $resp = resp_error('Container gagal dihapus');
            }
            return $this->response->setJSON($resp);

        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!'.$e->getMessage());
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
            $resp = resp_error('An Exception has occured!'.$e->getMessage());
            return $this->response->setJSON($resp);
        }
    }


    public function add_komoditas()
    {
        try{
            $idLs       = decrypt_id($this->request->getPost('idLs'));
            $postdata   = $this->request->getPost('postdata');
            $data       = post_ajax_toarray($postdata,true,['k_ntpn']);
            
            if($data['idPosTarif'])
                $data['id'] = decrypt_id($data['idPosTarif']);
            unset($data['idPosTarif']);

            if(isset($data['ntpn'])){
                $ntpns = $data['ntpn'];
                unset($data['ntpn']);
            }
 
            $data['idLs']           = $idLs;
            $data['postarif']       = numeric_only($data['postarif']);
            $data['jumlahBarang']   = insertNumber($data['jumlahBarang']);
            $data['beratBersih']    = insertNumber($data['beratBersih']);
            $data['hargaBarang']    = insertNumber($data['hargaBarang']);
            $data['hargaBarangIdr'] = insertNumber($data['hargaBarangIdr']);
            $data['hargaBarangUsd'] = insertNumber($data['hargaBarangUsd']);
            $data['tglIup']         = reverseDateDB($data['tglIup']);
            
            if(!empty($data['kdSatuanBarang']))
                $data['uraiSatuanBarang']   = model('satuan')->where('kodeSatuan',$data['kdSatuanBarang'])->first()->uraiSatuan;
            if(!empty($data['kdNegaraAsal']))
                $data['negaraAsal']         = model('negara')->where('kode',$data['kdNegaraAsal'])->first()->nama;

            $errMandatory = $this->cek_mandatory($data,'KOMODITAS');
            if(count($errMandatory) > 0)
            {
                $textErr = implode('<br>', $errMandatory);
                $resp    = resp_error('Perhatikan isian berikut <br>'.$textErr);
                return $this->response->setJSON($resp);exit;
            }

            $this->db->transStart();
            $dtlModel = model('tx_lseDtlHs');
            $dtlModel->upsert($data);
            
            $idHs = $dtlModel->insertID();
            
            if(isset($data['id'])) { 
                $idHs = $data['id'];
            } else { 
                $idHs = $dtlModel->insertID();
            }
  
            $this->update_seri_hs($idLs);
  
            if(isset($idHs))
            {  
                if(isset($ntpns))
                {
                    // $idHs       = $data['id'];
                    $ntpnModel  = model('tx_hsNtpn');
                    $ntpnModel->where('idPosTarif', $idHs)->delete();
                    $seleted    = $ntpnModel->select('idNtpn')->where('idPosTarif', $idHs)->find();
                    $seleted    = obj_flatten($seleted,'idNtpn');
                    $unselect   = array_diff($seleted,$ntpns);

                    if(!empty($unselect))
                    {
                        $ntpnModel->whereIn('idDokPersh',$unselect)->delete();
                    }

                    foreach ($ntpns as $key => $ntpn) {
                        $hsntpn['idLs']         = $idLs;
                        $hsntpn['idPosTarif']   = $idHs;
                        $hsntpn['idNtpn']       = $ntpn;
                        $ntpnModel->upsert($hsntpn);
                    }
                }
            }

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new \CodeIgniter\Database\Exceptions\DatabaseException('Gagal menambahkan / update komoditas. Transaction exception occured');
            }
            else{
                $resp = resp_success('Data berhasil disimpan');
                return $this->response->setJSON($resp);
            }
        } catch (\CodeIgniter\Database\Exceptions\DatabaseException $e) {
            $resp = resp_error($e->getMessage());
            return $this->response->setJSON($resp);
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!'.$e->getMessage());
            return $this->response->setJSON($resp);
        } 
    }

    public function get_komoditas()
    {
        try{
            $idLs = decrypt_id($this->request->getPost('idLs'));
            $dataLS = model('tx_lseHdr')->find($idLs);
            $dtlModel = model('tx_lseDtlHs');
            $dataHS = $dtlModel->where('idLs',$idLs)->findAll();

            $html = '';
            foreach ($dataHS as $key => $hs) {

                $ntpnModel = model('tx_hsNtpn');
                $ntpnModel->join('tx_lse_ntpn', 'tx_lse_ntpn.id = tx_lsehsntpn.idNtpn');
                $ntpnModel->where('tx_lsehsntpn.idPosTarif',$hs->id);
                $ntpns = $ntpnModel->findAll();
                $nptn = array_column($ntpns, 'noNtpn');
                $ntpnHS = implode('<br>', $nptn);

                $btnAct = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-danger me-2" onclick="del_hs(\''.encrypt_id($hs->id).'\')" title="Hapus"><i class="fa fa-trash"></i></button>
                                <button type="button" class="btn btn-sm btn-w-xs btn-icon btn-warning" onclick="edit_hs(\''.encrypt_id($hs->id).'\')" title="Edit"><i class="fa fa-edit"></i></button>';

                if(!in_array($dataLS->statusProses, ['DRAFT','REFUSED']))
                    $btnAct = '';
                // else if(session()->get('sess_role') == 7 && !config('App')->spv_can_edit)
                //     $btnAct = '';

                $html .= '<tr >
                            <td class="align-top text-nowrap text-center">'.$hs->seri.'</td>
                            <td class="align-top text-nowrap">'.formatHS($hs->postarif).'<br>'.formatAngka($hs->jumlahBarang).' '.$hs->uraiSatuanBarang.' ('.$hs->kdSatuanBarang.') '.'</td>
                            <td class="align-top">Uraian:<br>'.$hs->uraianBarang.'<br>Spesifikasi:<br>'.$hs->sepesifikasi.'</td>
                            <td class="align-top">'.$ntpnHS.'<hr>'.$hs->noIup.'<br>Tgl IUP: '.$hs->tglIup.'</td>
                            <td class="align-top text-nowrap">
                                <span>Berat Bersih: '.formatAngka($hs->beratBersih).'</span><br>
                                <span>Negara Asal: '.$hs->negaraAsal.'</span><br>
                                <span>Harga Barang: '.formatAngka($hs->hargaBarang).' '.$hs->currencyHargaBarang.'</span><br>
                                <span>Harga IDR: '.formatAngka($hs->hargaBarangIdr).'</span><br>
                                <span>Harga USD: '.formatAngka($hs->hargaBarangUsd).'</span><br>
                            </td>
                            <td class="align-top text-nowrap text-center">
                                '.$btnAct.'
                            </td>
                        </tr>';
            }
            $resp['content'] = $html;
            return $this->response->setJSON($resp);
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!'.$e->getMessage());
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
            $currency = model('currency')->where('kode',$data->currencyHargaBarang)->first();
            if($currency)
                $data->uraiCurrency = $currency->uraian;

            $ntpnModel = model('tx_hsNtpn');
            $ntpnModel->join('tx_lse_ntpn', 'tx_lse_ntpn.id = tx_lsehsntpn.idNtpn');
            $ntpnModel->where('tx_lsehsntpn.idPosTarif',$idHs);
            $data->ntpns = $ntpnModel->findAll();

            return $this->response->setJSON($data);
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!'.$e->getMessage());
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
            if($delete)
            {
                $resp = resp_success('komoditas berhasil dihapus');
            }
            else{
                $resp = resp_error('komoditas gagal dihapus');
            }
            $this->update_seri_hs($idLs);

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new \CodeIgniter\Database\Exceptions\DatabaseException('Gagal menghapus komoditas. Transaction exception occured');
            }
            else{
                return $this->response->setJSON($resp);
            }

        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!'.$e->getMessage());
            return $this->response->setJSON($resp);
        }
    }

    protected function update_seri_hs($idLs){
        $dtlModel   = model('tx_lseDtlHs');
        $dataHS     = $dtlModel->where('idLs',$idLs)->findAll();
        foreach ($dataHS as $key => $hs) {
            $dtlModel->update($hs->id, ['seri'=>($key+1)]);
        }
    }

    protected function update_seri_package($idLs){
        $model = model('tx_lsePackage');
        $data = $model->where('idLs',$idLs)->findAll();
        foreach ($data as $key => $item) {
            $model->update($item->id, ['seri'=>($key+1)]);
        }
    }

    protected function update_seri_container($idLs){
        $model = model('tx_lseContainer');
        $data = $model->where('idLs',$idLs)->findAll();
        foreach ($data as $key => $item) {
            $model->update($item->id, ['seri'=>($key+1)]);
        }
    } 
}