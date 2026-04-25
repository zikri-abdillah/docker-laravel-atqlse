<?php

namespace App\Controllers\Internal\Master;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;


class Cabang extends BaseController
{

	public function index()
    {  
        $param['addJS'] = '<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/datatable/js/dataTables.bootstrap5.js"></script>'; 
        $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/datatable/dataTables.responsive.min.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/datatable/responsive.bootstrap5.min.js"></script>'; 
        $param['addJS'] .= '<script src="' . base_url() . '/js/master/cabang.js?v='.date('YmdHis').'"></script>';

    	$param['content'] = $this->render('master.cabang.index');

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

    // functions untuk cabang's CRUD
    public function list()
    {  
        $searchParam = $this->request->getPost('searchParam');
        $arrParam = post_ajax_toarray($searchParam);
 
        $arrData = model('cabang');
        $arrData->where('isDelete', 'N');

        if(!empty($arrParam['cabang'])){ 
            $arrData->like('cabang', $arrParam['cabang']); 
        }
        
        if(!empty($arrParam['kodeCabang'])){ 
            $arrData->like('kodeCabang', $arrParam['kodeCabang']);
        } 

        $recordsTotal = $arrData->countAllResults(false);
        $arrData = $arrData->orderBy('cabang', 'ASC')->findAll($this->request->getPost('length'), $this->request->getPost('start'));

        $row = [];
        $no = $this->request->getPost('start')+1;

        foreach ($arrData as $key => $data) { 
            $btnDelete = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-danger" onclick="del_cabang(\''.encrypt_id($data->id).'\')" title="Hapus"><i class="fa fa-trash"></i></button> ';
            $btnEdit = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-warning" onclick="open_modal(\''.encrypt_id($data->id).'\')" title="Edit"><i class="fa fa-edit"></i></button> '; 

            if($data->isActive == 'Y'){
                $status = 'Active';
            } else {
                $status = 'Inactive';
            }

            $columns = [];
            $columns[] = $no++;
            $columns[] = '<span>'.$data->cabang.'</span>'; 
            $columns[] = '<span>'.$data->kodeCabang.'</span>'; 
            $columns[] = '<span>'.$status.'</span>'; 
            // $columns[] = '';
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
            $data = post_ajax_toarray($postdata);
 
            if(!empty($data['idCabang'])){
                // $data['id'] = decrypt_id($data['idCabang']);
                $data['id'] = $data['idCabang'];
            }
            unset($data['idCabang']);
                 
            $errMandatory = $this->cek_mandatory($data,'CABANG');
 
            if(count($errMandatory) == 0)
            {
                $cabangModel = model('cabang'); 
                $cabangModel->upsert($data);
 
                $respData['id'] = encrypt_id($cabangModel->insertID());
                $resp = resp_success('Data cabang berhasil disimpan',$respData);
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
            $id = decrypt_id($this->request->getPost('idCabang'));
            $arrCabang = model('cabang')->where('id',$id)->first();   

            return $this->response->setJSON($arrCabang);
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!'.$e->getMessage());
            return $this->response->setJSON($resp);
        }
    } 

    public function delete()
    {
        try {
            $idCabang = decrypt_id($this->request->getPost('id'));  

            $cek = model('cabang')->where('id',$idCabang)->findAll();
 
            if(count($cek) == 1)
            { 
                $timestamp = time();
                $currentDate = gmdate('Y-m-d h:i:s'); 
                $cabangModel = model('cabang');   
                $update = $cabangModel->set('isDelete', 'Y')->where('id',$idCabang)->update();
  
                if($update)
                {
                    $respData['id'] = encrypt_id($cabangModel->insertID());
                    $resp = resp_success('Cabang berhasil dihapus',$respData); 
                } else {
                    $resp = resp_error('Cabang gagal dihapus');
                }
            } else {
                $resp = resp_error('Cabang tidak ada.');
            } 

            return $this->response->setJSON($resp);
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!'.$e->getMessage());
            return $this->response->setJSON($resp);
        }
    } 
}

?>