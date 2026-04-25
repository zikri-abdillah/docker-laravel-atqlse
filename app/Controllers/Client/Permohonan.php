<?php

namespace App\Controllers\Client;

use App\Controllers\BaseController;
use CodeIgniter\Files\File;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
// use Psr\Log\LoggerInterface;


class Permohonan extends BaseController
{
    function __construct(){
        if(session()->get('sess_role') != 10)
        {
            exit('Forbidden');
        }
    }
    
    public function index()
    {
        $req = $this->request->getGet();
        $page = [
            'table_title'   => 'Permohonan LSE Batubara - LNSW',
            'breadcrumb_active'   => 'Data Permohonan - LNSW'
        ];
        $param['content'] = $this->render('client.lse.permohonan.index', $page);

        $param['addJS'] = '<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . '/assets/plugins/datatable/js/dataTables.bootstrap5.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . '/assets/plugins/datatable/js/dataTables.buttons.min.js"></script>'; 
        $param['addJS'] .= '<script src="' . base_url() . '/assets/plugins/datatable/dataTables.responsive.min.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . '/assets/plugins/datatable/responsive.bootstrap5.min.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . '/js/client/ekspor/permohonan.js?v='.date('YmdHis').'"></script>';
        return $this->render('layout.template', $param);
    }

    public function list()
    {
        $userId         = decrypt_id(session()->get('sess_userid')); 
        $searchParam    = $this->request->getPost('searchParam');
        $arrParam       = post_ajax_toarray($searchParam); 
        $lsModel        = model('t_inswPermohonan'); 
        $arrData        = $lsModel->where('status <>',99); 

        $dokModel           = model('t_user');
        $dokModel->select("t_user.id AS 'idUser', m_perusahaan.id AS 'idPersh', m_perusahaan.bentukPersh, m_perusahaan.nama");
        $dokModel->join('m_pegawai', 'm_pegawai.id = t_user.idprofile');     
        $dokModel->join('m_perusahaan', 'm_perusahaan.id = m_pegawai.idPersh');   
        $arrUser            =  $dokModel->where('t_user.id', $userId)->first();  


        if(!empty($arrParam['idJenisTerbit'])){
            $arrData->where('idJenisTerbit', $arrParam['idJenisTerbit']);
        }

        if(!empty($arrParam['nomorAju'])){
            $arrData->like('nomorAju', $arrParam['nomorAju']);
        }

        if(!empty($arrParam['nomorPermohonan'])){
            $arrData->like('nomorPermohonan', $arrParam['nomorPermohonan']);
        }
  
        if(!empty($arrParam['nomorEt'])){
            $arrData->like('nomorEt', $arrParam['nomorEt']);
        }

        if(!empty($arrParam['namaAlatPengirim'])){
            $arrData->like('namaAlatPengirim', $arrParam['namaAlatPengirim']);
        }
 
        if(!empty($arrUser->idPersh)){
            $arrPerusahaan  = model('perusahaan')->where('id', $arrUser->idPersh)->first(); 
            $npwp           = $arrPerusahaan->npwp;
 
            $arrData->like('nomorIdentitas', $npwp); 
        }

        $recordsTotal   = $arrData->countAllResults(false);
        $arrData        = $arrData->findAll($this->request->getPost('length'), $this->request->getPost('start')); 
        $row            = [];
        $no             = $this->request->getPost('start')+1;

        foreach ($arrData as $key => $data) { 

            $btnView    = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-info" onclick="view(\''.encrypt_id($data->id).'\')" title="Lihat"><i class="fa fa-eye"></i></button> ';

            $perizinan  = model('tx_lseReferensi');
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

            $portMuat   = model('t_inswPermohonanPort')->where('kodeKegiatan',9);
            $portTujuan = model('t_inswPermohonanPort')->where('kodeKegiatan',5);

            $columns   = [];
            $columns[] = $no++;
            $columns[] = '<span>Jenis: '.$data->uraiJenisPengajuan.'</span><br><span>Aju: '.$data->nomorAju.'</span><br><span>Tgl: '.reverseDateTime($data->tanggalAju).'</span><br><span>No Perm: '.$data->nomorPermohonan.'</span><br><span>Tgl: '.reverseDateTime($data->tglPermohonan).'</span>';
            $columns[] = '<span>'.$data->namaPerusahaan.'</span><br><span>'.formatNPWP($data->nomorIdentitas).'</span><br><span>'.$data->namaLokasi.'</span>';
            $columns[] = '<span>'.$data->nomorEt.'</span>';
            $columns[] = '<span>Pel Muat: '.$portMuat->kodePelabuhan.' - '.$portMuat->namaPelabuhan.'</span><br><span>Pel Tujuan: '.$portTujuan->kodePelabuhan.' - '.$portTujuan->namaPelabuhan.'</span><br><span>Transport: '.$data->namaAlatPengirim.'</span>';
            $columns[] = $data->status;
            $columns[] = '<div class="btn-list text-nowrap">'.$btnView.'</div>';
            $row[]     = $columns;
        }

        $table['draw']            = $this->request->getPost('draw');
        $table['recordsTotal']    = $recordsTotal;
        $table['recordsFiltered'] = $recordsTotal;
        $table['data']            = $row;

        echo json_encode($table);

    }

    public function view()
    {
        $id         = decrypt_id($this->request->getPost('id'));
        $datals     = model('tx_lseHdr')->find($id);
        $packages   = model('tx_lsePackage')->where('idLS',$id)->findAll();
        $containers = model('tx_lseContainer')->where('idLS',$id)->findAll();

        $refModel   = model('tx_lseReferensi');
        $refModel->select('tx_lse_referensi.id as idref,t_dokpersh.*,m_negara.nama as negara');
        $refModel->join('t_dokpersh', 't_dokpersh.id = tx_lse_referensi.idDokPersh');
        $refModel->join('m_negara', 'm_negara.kode = t_dokpersh.negaraPenerbit');
        $refModel->where('tx_lse_referensi.idLS',$id);

        $references = $refModel->findAll();
        $royaltis   = model('tx_lseNtpn')->where('idLS',$id)->findAll();
        $komoditas  = model('tx_lseDtlHs')->where('idLS',$id)->findAll();

        $pengajuan  = model('t_inswPermohonan')->find($id);
        $pelabuhans = model('t_inswPermohonanPort')->where('idPermohonan',$id)->findAll();
        $references = model('t_inswPermohonanDok')->where('idPermohonan',$id)->orderBy('seriDokumen', 'ASC')->findAll();
        $komoditi   = model('t_inswPermohonanBrg')->where('idPermohonan',$id)->findAll();
  
        $page = [
            'page_title'    => 'Data Pengajuan Online LSE Batubara',
            'pengajuan'     => $pengajuan,
            'pelabuhans'    => $pelabuhans,
            'references'    => $references,
            'komoditi'      => $komoditi, 
            'datals'        => $datals,
            'packages'      => $packages,
            'containers'    => $containers,
            'references'    => $references,
            'royaltis'      => $royaltis,
            'komoditas'     => $komoditas
        ];

        $param['addJS'] = '<script src="' . base_url() . '/assets/plugins/formwizard/jquery.smartWizard.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . '/assets/plugins/formwizard/fromwizard.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . '/assets/plugins/jquery-steps/jquery.steps.min.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . '/assets/plugins/parsleyjs/parsley.min.js"></script>'; 
        $param['addJS'] .= '<script src="' . base_url() . '/js/client/ekspor/permohonan.js?v='.date('YmdHis').'"></script>';

        $param['content'] = $this->render('client.lse.permohonan.view', $page);
        return $this->render('layout.template', $param);
    } 
}
