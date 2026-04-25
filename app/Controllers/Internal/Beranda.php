<?php

namespace App\Controllers\Internal;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;


class Beranda extends BaseController
{ 
	public function internal()
    { 
        $modelHdr   = model('tx_lseHdr');  
        $batuAju    =  $modelHdr->select("count(id) as 'jumlah_aju'")->where('YEAR(tglDraft)', date('Y'))->where('MONTH(tglDraft)', date('m'))->where('idJenisLS', '1')->where('statusProses !=', 'DELETED')->first();
        $batuTolak  =  $modelHdr->select("count(id) as 'jumlah_aju'")->where('YEAR(tglDraft)', date('Y'))->where('MONTH(tglDraft)', date('m'))->where('idJenisLS', '1')->where('statusProses', 'REFUSED')->first(); 
        $batuTerbit =  $modelHdr->select("count(id) as 'jumlah_aju'")->where('YEAR(tglDraft)', date('Y'))->where('MONTH(tglDraft)', date('m'))->where('idJenisLS', '1')->where('statusProses', 'ISSUED')->whereIn('statusDok', array('TERBIT', 'PERUBAHAN'))->first(); 
        $batuCabut  =  $modelHdr->select("count(id) as 'jumlah_aju'")->where('YEAR(tglDraft)', date('Y'))->where('MONTH(tglDraft)', date('m'))->where('idJenisLS', '1')->where('statusDok', 'DIBATALKAN')->first(); 
 
        $page = [ 
            'breadcrumb_active' => 'Beranda Internal', 
            'batu_aju' => $batuAju->jumlah_aju, 
            'batu_tolak' => $batuTolak->jumlah_aju, 
            'batu_terbit' => $batuTerbit->jumlah_aju, 
            'batu_cabut' => $batuCabut->jumlah_aju,   
        ];
        
        $param['content'] = $this->render('pages.beranda-internal', $page);

        $param['addJS'] = '<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/datatable/js/dataTables.bootstrap5.js"></script>';  
        $param['addJS'] .= '<script src="' . base_url() . '/js/pages/dashboard.js?v='.date('YmdHis').'"></script>';

        return $this->render('layout.template', $param);
    }
 
	public function admin()
    { 
        $modelHdr   = model('tx_lseHdr');  
        $batuAju    =  $modelHdr->select("count(id) as 'jumlah_aju'")->where('YEAR(tglDraft)', date('Y'))->where('MONTH(tglDraft)', date('m'))->where('idJenisLS', '1')->where('statusProses !=', 'DELETED')->first();
        $batuTolak  =  $modelHdr->select("count(id) as 'jumlah_aju'")->where('YEAR(tglDraft)', date('Y'))->where('MONTH(tglDraft)', date('m'))->where('idJenisLS', '1')->where('statusProses', 'REFUSED')->first(); 
        $batuTerbit =  $modelHdr->select("count(id) as 'jumlah_aju'")->where('YEAR(tglDraft)', date('Y'))->where('MONTH(tglDraft)', date('m'))->where('idJenisLS', '1')->where('statusProses', 'ISSUED')->whereIn('statusDok', array('TERBIT', 'PERUBAHAN'))->first(); 
        $batuCabut  =  $modelHdr->select("count(id) as 'jumlah_aju'")->where('YEAR(tglDraft)', date('Y'))->where('MONTH(tglDraft)', date('m'))->where('idJenisLS', '1')->where('statusDok', 'DIBATALKAN')->first(); 
 
        $page = [ 
            'breadcrumb_active' => 'Beranda Admin', 
            'batu_aju' => $batuAju->jumlah_aju, 
            'batu_tolak' => $batuTolak->jumlah_aju, 
            'batu_terbit' => $batuTerbit->jumlah_aju, 
            'batu_cabut' => $batuCabut->jumlah_aju,  
        ];
        
        $param['content'] = $this->render('pages.beranda-admin', $page);

        $param['addJS'] = '<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/datatable/js/dataTables.bootstrap5.js"></script>';  
        $param['addJS'] .= '<script src="' . base_url() . '/js/pages/dashboard.js?v='.date('YmdHis').'"></script>';

        return $this->render('layout.template', $param);
    }

	public function client()
    { 
        $userId             = decrypt_id(session()->get('sess_userid'));
        $arrUser            = model('t_user')->where('id', $userId)->first(); 
 
        $modelHdr   = model('tx_lseHdr');   
        $mineralAju    =  $modelHdr->select("count(id) as 'jumlah_aju'")->where('id', $arrUser->idprofile)->where('YEAR(tglDraft)', date('Y'))->where('MONTH(tglDraft)', date('m'))->whereIn('idJenisLS', array('2', '3', '4'))->where('statusProses !=', 'DELETED')->first(); 
        $mineralTolak  =  $modelHdr->select("count(id) as 'jumlah_aju'")->where('id', $arrUser->idprofile)->where('YEAR(tglDraft)', date('Y'))->where('MONTH(tglDraft)', date('m'))->whereIn('idJenisLS', array('2', '3', '4'))->where('statusProses', 'REFUSED')->first(); 
        $mineralTerbit =  $modelHdr->select("count(id) as 'jumlah_aju'")->where('id', $arrUser->idprofile)->where('YEAR(tglDraft)', date('Y'))->where('MONTH(tglDraft)', date('m'))->whereIn('idJenisLS', array('2', '3', '4'))->where('statusProses', 'ISSUED')->whereIn('statusDok', array('TERBIT', 'PERUBAHAN'))->first(); 
        $mineralCabut  =  $modelHdr->select("count(id) as 'jumlah_aju'")->where('id', $arrUser->idprofile)->where('YEAR(tglDraft)', date('Y'))->where('MONTH(tglDraft)', date('m'))->whereIn('idJenisLS', array('2', '3', '4'))->where('statusDok', 'DIBATALKAN')->first(); 
 
        $page = [ 
            'breadcrumb_active' => 'Beranda Client',  
            'mineral_aju' => $mineralAju->jumlah_aju, 
            'mineral_tolak' => $mineralTolak->jumlah_aju, 
            'mineral_terbit' => $mineralTerbit->jumlah_aju, 
            'mineral_cabut' => $mineralCabut->jumlah_aju, 
        ];
        
        $param['content'] = $this->render('pages.beranda-internal', $page);

        $param['addJS'] = '<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/datatable/js/dataTables.bootstrap5.js"></script>';  
        $param['addJS'] .= '<script src="' . base_url() . '/js/pages/dashboard.js?v='.date('YmdHis').'"></script>';

        return $this->render('layout.template', $param);
    }

    public function get_pengajuan_terakhir()
    {      
        $hdrModel       = model('tx_lseHdr');
        $hdrModel->select("id, draftNo, tglDraft, bentukPersh, namaPersh, statusDok, statusProses, statusKirim");
        $hdrModel->where('YEAR(tglDraft)', date('Y'))->where('MONTH(tglDraft)', date('m'));
        $hdrModel->where('statusProses !=', 'DELETED');    
        
        if(session()->get('sess_role') == 10){
            $userId             = decrypt_id(session()->get('sess_userid'));
            $arrUser            = model('t_user')->where('id', $userId)->first(); 

            $hdrModel->where('idPersh', $arrUser->idprofile);
        }
        
        $arrData        = $hdrModel->limit(10)->orderBy('tglDraft', 'DESC')->find();  
        $recordsTotal   = 10;
        $row            = [];
        $no             = 1;
        $status         = '';

        // echo "<pre>";
        // print_r($hdrModel->getLastQuery());
        // echo "</pre>";
        // die();

        foreach ($arrData as $key => $data) {  
            if($data->statusProses == 'ISSUED'){
                $status = '<p class="fw-bold text-success">'.ucwords(strtolower($data->statusProses)).'</p>';
            } else if($data->statusProses == 'PROCESS'){
                $status = '<p class="fw-bold text-info">'.ucwords(strtolower($data->statusProses)).'</p>';
            } else if($data->statusProses == 'REFUSED'){
                $status = '<p class="fw-bold text-danger">'.ucwords(strtolower($data->statusProses)).'</p>';
            } else if($data->statusProses == 'DRAFT'){
                $status = '<p class="fw-bold text-warning">'.ucwords(strtolower($data->statusProses)).'</p>';
            }

            $columns    = [];
            $columns[]  = $no++; 
            $columns[]  = '<p class="fw-bold" onclick="view(\''.encrypt_id($data->id).'\')">'.$data->draftNo.'</p>';
            $columns[]  = reverseDate($data->tglDraft);  
            $columns[]  = $data->bentukPersh." ".ucwords(strtolower($data->namaPersh));   
            $columns[]  = $status;   

            $row[]      = $columns;
        }

        $table['draw']              = $this->request->getPost('draw');
        $table['recordsTotal']      = $recordsTotal;
        $table['recordsFiltered']   = $recordsTotal;
        $table['data']              = $row;

        echo json_encode($table); 
    }
    
    public function get_penerbitan_terakhir()
    {     
        $hdrModel       = model('tx_lseHdr');
        $hdrModel->select("tx_lsehdr.id, tx_lsehdr.draftNo, tx_lsehdr.tglDraft, tx_lsehdr.noLs, tx_lsehdr.tglLs, tx_lsehdr.bentukPersh, tx_lsehdr.namaPersh, tx_lsehdr.statusDok, tx_lsehdr.statusProses, tx_lsehdr.statusKirim");    
        $hdrModel->where('YEAR(tglDraft)', date('Y'))->where('MONTH(tglDraft)', date('m'));
        $hdrModel->where('statusProses !=', 'DELETED');    

        if(session()->get('sess_role') == 10){
            $userId             = decrypt_id(session()->get('sess_userid'));
            $arrUser            = model('t_user')->where('id', $userId)->first(); 

            $hdrModel->where('idPersh', $arrUser->idprofile);
        }
        
        $hdrModel->where('statusProses', 'ISSUED');
        $hdrModel->whereIn('statusDok', array('TERBIT', 'PERUBAHAN'));
        $arrData        = $hdrModel->orderBy('tx_lsehdr.tglLs', 'DESC')->limit(10)->find();  
        $recordsTotal   = 10;
        $row            = [];
        $no             = 1;
        $status         = '';

        foreach ($arrData as $key => $data) {   
            if($data->statusKirim == 'SENT'){
                $status = '<p class="fw-bold text-success">'.ucwords(strtolower($data->statusKirim)).'</p>';
            } else {
                $status = '<p class="fw-bold text-info">'.ucwords(strtolower($data->statusKirim)).'</p>';
            }
 
            $columns    = [];
            $columns[]  = $no++;
            $columns[]  = '<p class="fw-bold" onclick="view(\''.encrypt_id($data->id).'\')">'.$data->noLs.'</p>';
            $columns[]  = reverseDate($data->tglLs);  
            $columns[]  = $data->bentukPersh." ".ucwords(strtolower($data->namaPersh));  
            $columns[]  = $status;  

            $row[]      = $columns;
        }

        $table['draw']              = $this->request->getPost('draw');
        $table['recordsTotal']      = $recordsTotal;
        $table['recordsFiltered']   = $recordsTotal;
        $table['data']              = $row;

        echo json_encode($table); 
    }

    public function get_user_waiting()
    {     
        $perModel       = model('tx_perusahaan');
        $arrData        = $perModel->select("t_user.id AS 'idUser', m_perusahaan.bentukPersh, m_perusahaan.nama, m_perusahaan.picNama, m_perusahaan.isActive AS 'isActivePersh', m_perusahaan.isActive");    
        $perModel->join('t_user', 't_user.idprofile = m_perusahaan.id');
        $arrData->where('t_user.isActive', 'W');
        $arrData->where('t_user.idrole', '10');
 
        $recordsTotal   = $arrData->countAllResults(false);
        $arrData        = $arrData->orderBy('m_perusahaan.id', 'DESC')->limit(10)->find();  
        $row            = [];
        $no             = 1;
        $html           = '';

        foreach ($arrData as $key => $data) {   
            $html .= '
                <li class="list-group-item align-items-center d-flex justify-content-between pt-3">
                    <div class="media"> 
                        <img src="'. base_url() .'assets/images/users/user-'.$no.'.jpg" height="30" class="me-3 align-self-center rounded" alt="...">
                        <div class="media-body align-self-center"> 
                            <h6 class="m-0 fw-bold">'.$data->bentukPersh.' '.$data->nama.'</h6>
                            <p class="mb-0 text-muted">'.$data->picNama.'</p>                                                                                           
                        </div><!--end media body-->
                    </div>
                    <div class="align-self-center"> 
                        <button type="button" class="btn btn-sm btn-soft-primary" onclick="detail(\''.encrypt_id($data->idUser).'\')" title="View User"><i class="fa fa-eye"></i></button> 
                    </div>                                            
                </li> ';

            $no++;
        }

        $resp['content'] = $html;
        return $this->response->setJSON($resp);
    } 
    
    public function view_lse()
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
}

?>