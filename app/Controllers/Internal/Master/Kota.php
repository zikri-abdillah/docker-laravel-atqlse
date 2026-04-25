<?php

namespace App\Controllers\Internal\Master;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;


class Kota extends BaseController
{

	public function index()
    {  
        $param['addJS'] = '<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/datatable/js/dataTables.bootstrap5.js"></script>';  
        $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/datatable/dataTables.responsive.min.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/datatable/responsive.bootstrap5.min.js"></script>'; 
        $param['addJS'] .= '<script src="' . base_url() . '/js/master/kota.js?v='.date('YmdHis').'"></script>';

    	$param['content'] = $this->render('master.kota.index');

    	return $this->render('layout.template', $param);
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

    // functions untuk kota's CRUD
    public function list()
    {  
        $searchParam = $this->request->getPost('searchParam');
        $arrParam    = post_ajax_toarray($searchParam);
  
        $kotaMOdel   = model('kota');
        $kotaMOdel->select("m_kota.id as 'idKota', m_kota.kodeNegara, m_negara.nama, m_kota.kodeInatrade, m_kota.kodeUNLOCODE, m_kota.namaLengkap, m_kota.namaKota, m_kota.isActive");
        $arrData     = $kotaMOdel->join('m_negara', 'm_negara.kode = m_kota.kodeNegara')->where('isDelete', 'N');   
             
        if(!empty($arrParam['negara'])){ 
            $arrData->like('kodeNegara', $arrParam['negara']);
        } 

        if(!empty($arrParam['nama'])){ 
            $arrData->like('namaKota', $arrParam['nama']); 
        }

        if(!empty($arrParam['unlocode'])){ 
            $arrData->like('kodeUNLOCODE', $arrParam['unlocode']);
        } 

        if(!empty($arrParam['status'])){ 
            $arrData->like('m_kota.isActive', $arrParam['status']); 
        }
         
        $recordsTotal = $arrData->countAllResults(false);
        $arrData = $arrData->orderBy('namaKota', 'ASC')->findAll($this->request->getPost('length'), $this->request->getPost('start'));

        $row = [];
        $no = $this->request->getPost('start')+1;

        foreach ($arrData as $key => $data) { 
            $btnDelete = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-danger" onclick="del_kota(\''.encrypt_id($data->idKota).'\')" title="Hapus"><i class="fa fa-trash"></i></button> ';
            $btnEdit = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-warning" onclick="open_modal(\''.encrypt_id($data->idKota).'\')" title="Edit"><i class="fa fa-edit"></i></button> '; 

            if($data->isActive == 'Y'){
                $status = 'Active';
            } else {
                $status = 'Inactive';
            }

            $columns = [];
            $columns[] = $no++;
            $columns[] = '<span>'.$data->kodeNegara.' - '.$data->nama.'</span>'; 
            $columns[] = '<span>'.$data->kodeUNLOCODE.'</span>';  
            $columns[] = '<span>'.$data->kodeInatrade.'</span>';
            $columns[] = '<span>'.$data->namaLengkap.'</span>'; 
            $columns[] = '<span>'.$status.'</span>';  
            $columns[] = '<div class="btn-list text-nowrap">'.$btnDelete.$btnEdit.'</div>';
            $row[] = $columns;
        }

        $table['draw'] = $this->request->getPost('draw');
        $table['recordsTotal'] = $recordsTotal;
        $table['recordsFiltered'] = $recordsTotal;
        $table['data'] = $row;

        echo json_encode($table); 
    }
 
    
    public function save()
    {
        try {
            $postdata = $this->request->getPost('postdata');
            $data     = post_ajax_toarray($postdata); 
 
            if(!empty($data['idKota'])){ 
                $data['id'] = $data['idKota'];
            }
            unset($data['idKota']);
                 
            if(!empty($data['negara'])){ 
                $data['kodeNegara'] = $data['negara']; 
            }
            unset($data['negara']);

            if(!empty($data['unlocode'])){ 
                $data['kodeUNLOCODE'] = str_replace(' ', '', trim($data['unlocode']));  
            }
            unset($data['unlocode']);

            if(!empty($data['inatrade'])){ 
                $data['kodeInatrade'] = str_replace(' ', '', trim($data['inatrade']));  
            } 
            else {
                if($data['kodeNegara'] != 'ID'){
                    $data['kodeInatrade'] = $data['kodeUNLOCODE'];
                }
            }
            unset($data['inatrade']);

            if(!empty($data['lengkap'])){ 
                $data['namaLengkap'] = $data['lengkap'];
            }
            unset($data['lengkap']);

            if(!empty($data['nama'])){ 
                $data['namaKota'] = $data['nama'];
            }
            unset($data['nama']);

            if(!empty($data['status'])){ 
                $data['isActive'] = $data['status'];
            }
            unset($data['status']);
             
            $errMandatory = $this->cek_mandatory($data,'KOTA');
 
            if(count($errMandatory) == 0)
            {
                $kotaModel = model('kota'); 
                $kotaModel->upsert($data);
 
                $respData['id'] = encrypt_id($kotaModel->insertID());
                $resp = resp_success('Data kota berhasil disimpan',$respData);
            } else { 
                $textErr = implode('<br> - ', $errMandatory);
                $resp = resp_error('Perhatikan isian berikut <br>'.$textErr);
            }
            return $this->response->setJSON($resp);
        } catch (\Throwable $e) { 
            $resp = resp_error('An Exception has occured!'.$e->getMessage());
            return $this->response->setJSON($resp);
        }
    }

    public function edit()
    {
        try {
            $id = decrypt_id($this->request->getPost('idKota'));
 
            $kotaMOdel   = model('kota');
            $kotaMOdel->select("m_kota.id as 'idKota', m_kota.kodeNegara, m_negara.nama, m_kota.kodeInatrade, m_kota.kodeUNLOCODE, m_kota.namaLengkap, m_kota.namaKota, m_kota.isActive");
            $kotaMOdel->join('m_negara', 'm_negara.kode = m_kota.kodeNegara');  
            $arrKota     = $kotaMOdel->where('m_kota.id',$id)->first();
    
            return $this->response->setJSON($arrKota);
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!'.$e->getMessage());
            return $this->response->setJSON($resp);
        }
    } 

    public function delete()
    {
        try {
            $idKota = decrypt_id($this->request->getPost('id'));  

            $cek = model('kota')->where('id',$idKota)->findAll();
 
            if(count($cek) == 1)
            { 
                $timestamp = time();
                $currentDate = gmdate('Y-m-d h:i:s'); 
                $kotaModel = model('kota');   
                $update = $kotaModel->set('isDelete', 'Y')->where('id',$idKota)->update();
   
                if($update)
                {
                    $respData['id'] = encrypt_id($kotaModel->insertID());
                    $resp = resp_success('Kota berhasil dihapus',$respData); 
                } else {
                    $resp = resp_error('Kota gagal dihapus');
                }
            } else {
                $resp = resp_error('Kota tidak ada.');
            } 

            return $this->response->setJSON($resp);
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!'.$e->getMessage());
            return $this->response->setJSON($resp);
        }
    } 
}

?>