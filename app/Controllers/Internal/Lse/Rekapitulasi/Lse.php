<?php
 
namespace App\Controllers\Internal\Lse\Rekapitulasi;

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
    protected $idJenisLS;
    protected $jenisLS;
    protected $uri;
    protected $validation;

    function __construct(){
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
        $req                    = $this->request->getGet();
 
            $table_title        = 'Data LSE - Terbit';
            $breadcrumb_active  = 'Terbit';
        

        $page = [
            'table_title'       => 'Rekapitulasi Penerbitan LS Ekspor',
            'breadcrumb_active' => 'Penerbitan LS Ekspor',
            'dataFilter'        => 'terbit',
        ];
 
        $param['content']   = $this->render('ekspor.rekapitulasi.ls.index', $page);
        $param['addJS']     = '<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>';
        $param['addJS']     .= '<script src="' . base_url() . 'assets/plugins/datatable/js/dataTables.bootstrap5.js"></script>'; 
        $param['addJS']     .= '<script src="' . base_url() . 'assets/plugins/datatable/dataTables.responsive.min.js"></script>';
        $param['addJS']     .= '<script src="' . base_url() . 'assets/plugins/datatable/responsive.bootstrap5.min.js"></script>'; 
        $param['addJS'] .= '<script src="' . base_url() . '/js/ekspor/rekapitulasi/lse.js?v='.date('YmdHis').'"></script>';

        return $this->render('layout.template', $param);
    }
  
    public function list()
    {
        $searchParam    = $this->request->getPost('searchParam');
        $arrParam       = post_ajax_toarray($searchParam);
        $castDataFilter = ['konsep'=>['PROCESS','REFUSED'],'proses'=>['REVIEW'],'terbit'=>['ISSUED']];
        $lsModel        = model('tx_lseHdr');
        $arrData        = $lsModel->where('statusProses <>','DELETED')->whereIn('statusProses', $castDataFilter['terbit']);
 
        if(!empty($arrParam['idJenisTerbit'])){
            $arrData->where('idJenisTerbit', $arrParam['idJenisTerbit']);
        }

        if(!empty($arrParam['noSi'])){
            $arrData->like('ajuNSW', $arrParam['noSi']);
        }

        if(!empty($arrParam['draftNo'])){
            $arrData->like('draftNo', $arrParam['draftNo']);
        }

        if(!empty($arrParam['namaPersh'])){
            $arrData->like('namaPersh', $arrParam['namaPersh']);
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

        $recordsTotal   = $arrData->countAllResults(false);
        $arrData        = $arrData->orderBy('created', 'desc')->findAll($this->request->getPost('length'), $this->request->getPost('start'));
        $row            = [];

        $no             = $this->request->getPost('start')+1;
        $masterStatus   = $this->db->table('m_status_proses')->get()->getResult();

        foreach ($arrData as $key => $data) {
            $arrStatus  = array_column($masterStatus, 'html', 'status');
            $btnEdit    = '';
            $btnCabut   = '';
            $btnLog     = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-success" onclick="view_log(\''.encrypt_id($data->id).'\')" title="Log"><i class="fa fa-history" aria-hidden="true"></i></button> ';

            if(in_array(session()->get('sess_role'),[1,6,7])) {
                $btnView = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-info" onclick="view(\''.encrypt_id($data->id).'\',\''.$data->idJenisLS.'\')" title="Lihat"><i class="fa fa-eye"></i></button> ';
                $btnCetak = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-green" onclick="print(\''.encrypt_id($data->id).'\')" title="Cetak"><i class="fa fa-print"></i></button> ';
                $btnDelete = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-danger" onclick="del(\''.encrypt_id($data->id).'\')" title="Hapus"><i class="fa fa-trash"></i></button> ';
                $btnEdit = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-warning" onclick="edit(\''.encrypt_id($data->id).'\')" title="Edit"><i class="fa fa-edit"></i></button> ';
            }

            if(session()->get('sess_role') == 6) // operator
            {
                if($data->statusProses == 'PROCESS')
                    $btnView = '';
                else if($data->statusProses == 'REVIEW' || $data->statusProses == 'ISSUED')
                    $btnDelete = $btnEdit = '';

                if($data->statusDok == 'TERBIT' && $data->statusProses == 'ISSUED' && $data->statusKirim == 'SENT'){
                    $btnCabut = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-danger" onclick="cabut(\''.encrypt_id($data->id).'\')" title="Pembatalan"><i class="fa fa-ban"></i></button> ';
                    $btnDelete  = $btnEdit = '';
                }
            }
            else if(session()->get('sess_role') == 7) // supervisor
            {
                if(!config('App')->spv_can_edit)
                {
                    $btnDelete  = $btnEdit = '';
                }
                else{
                    $btnDelete      =  '';
                    if($data->statusProses == 'PROCESS' || $data->statusProses == 'REFUSED')
                        $btnEdit    = '';
                    else if($data->statusProses == 'ISSUED')
                        $btnDelete  = $btnEdit = '';
                }

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

                $badgeStatusKirim = '<hr><span class="text-warning">Pengiriman Inatrade</span>';
                $badgeStatusKirim .= '<br><span class="badge rounded-pill '.$bgKirim.' my-1">'.status_kirim($data->statusKirim).'</span>';
            }

            $badgeJenisTerbit       = '';
            if($data->idJenisTerbit  == 1)
                $badgeJenisTerbit = '<span class="badge bg-info">'.$data->jenisTerbit.'</span>';
            else
                $badgeJenisTerbit = '<span class="badge bg-danger">'.$data->jenisTerbit.'</span>';

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

            $noAju = '';
            $pengajuan = pengajuan_simbara($data->idPermohonanNSW);
            if(isset($pengajuan->nomorAju)){
                $noAju = '<br>Aju : '.$pengajuan->nomorAju;
                $noAju .= '<br>Tgl Aju : '.reverseDate($pengajuan->tanggalAju);
            }

            $btnKirimInatrade = '';
            if($data->statusDok == 'TERBIT' && $data->statusProses == 'ISSUED' && $data->statusKirim == 'READY'){
                $btnKirimInatrade = '<div class="mt-4"><button type="button" class="btn btn-danger" data-iddata="'.encrypt_id($data->id).'" id="btn-xml-inatrade" ><i class="fa fa-cloud me-2" aria-hidden="true"></i>KIRIM INATRADE</button></div>';
            }
            $jenisKomoditi = $this->db->table('m_jenisls')->where('id',$data->idJenisLS)->get()->getRow()->markJenis;
            //qq($pengajuan->nomorAju);
            $columns = [];
            $columns[] = $no++;
            $columns[] = '<span>'.$badgeJenisTerbit.'<br><span style="color:#3bff30;">'.$data->draftNo.'</span></span>'.$noAju.'<br><span style="color:#ff9324;">'.$jenisKomoditi.'</span><hr><span>SI No: '.$data->noSi.'</span><br><span>Cabang : '.$data->namaCabang.'</span><br><span>Created : '.reverseDateTime($data->created).'</span>';
            $columns[] = '<span id="no-ls">No. LS: <span class="fw-semibold" style="color:#ffc938;">'.$data->noLs.'</span></span><br><span>Tgl LS: <span style="color:#00fff1;">'.reverseDate($data->tglLs).'</span></span><br><span>Tgl Akhir LS: <span style="color:#00fff1;">'.reverseDate($data->tglAkhirLs).'</span></span>';
            $columns[] = '<span>'.$data->namaPersh.'</span><br><span>'.formatNPWP($data->npwp).'</span><br><span>'.$data->namaKota.'</span><hr><span>No ET: '.$noET.'</span><br><span>Tgl Awal: '.$tglET.'</span><br><span>Tgl Akhir: '.$tglAkhirET.'</span>';
            $columns[] = '<span><i>Pel Muat:</i><br><span style="color:#ffc938;">'.$data->kodePortMuat.' - '.$data->portMuat.'</span></span><br><span><i>Pel Tujuan:</i><br><span style="color:#94ff45;">'.$data->kodePortTujuan.' - '.$data->portTujuan.'</span></span><br><span><i>Transport:</i><br>'.$data->namaTransport.'</span>';
            $columns[] = $arrStatus[$data->statusProses].$badgeStatusDok.$badgeStatusKirim;
            $columns[] = '<div class="btn-list text-nowrap">'.$btnLog.$btnCabut.$btnDelete.$btnEdit.$btnView.$btnCetak.'</div>'.$btnKirimInatrade;
            $row[] = $columns;
        }

        $table['draw']              = $this->request->getPost('draw');
        $table['recordsTotal']      = $recordsTotal;
        $table['recordsFiltered']   = $recordsTotal;
        $table['data']              = $row;

        echo json_encode($table);
    } 
}