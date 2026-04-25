<?php

namespace App\Controllers\Internal\Master;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;


class Penandatangan extends BaseController
{

	public function index()
    {  
        $param['addJS'] = '<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/datatable/js/dataTables.bootstrap5.js"></script>';  
        $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/datatable/dataTables.responsive.min.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/datatable/responsive.bootstrap5.min.js"></script>'; 
        $param['addJS'] .= '<script src="' . base_url() . '/js/master/penandatangan.js?v='.date('YmdHis').'"></script>';

    	$param['content'] = $this->render('master.penandatangan.index');

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

    // functions untuk penandatangan's CRUD
    public function list()
    {  
        $searchParam = $this->request->getPost('searchParam');
        $arrParam = post_ajax_toarray($searchParam);
 
        $arrData = model('penandatangan');
        $arrData->where('isDelete', 'N');

        if(!empty($arrParam['cabang'])){ 
            $arrData->like('idCabang', $arrParam['cabang']); 
        }
        
        if(!empty($arrParam['identitas'])){ 
            $arrData->like('noIdentitas', $arrParam['identitas']);
        } 

        if(!empty($arrParam['nama'])){ 
            $arrData->like('nama', $arrParam['nama']); 
        }
        
        if(!empty($arrParam['jabatan'])){  
            $arrData->like('jabatan', $arrParam['jabatan']); 
        } 

        $recordsTotal = $arrData->countAllResults(false);
        $arrData = $arrData->orderBy('nama', 'ASC')->findAll($this->request->getPost('length'), $this->request->getPost('start'));

        $row = [];
        $no = $this->request->getPost('start')+1;

        foreach ($arrData as $key => $data) { 
            $btnDelete = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-danger" onclick="del_penandatangan(\''.encrypt_id($data->id).'\')" title="Hapus"><i class="fa fa-trash"></i></button> ';
            $btnEdit = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-warning" onclick="open_modal(\''.encrypt_id($data->id).'\')" title="Edit"><i class="fa fa-edit"></i></button> '; 

            if($data->isActive == 'Y'){
                $status = 'Active';
            } else {
                $status = 'Inactive';
            }

            $columns = [];
            $columns[] = $no++;
            $columns[] = '<span>'.$data->nama.'</span>'; 
            $columns[] = '<span>'.$data->noIdentitas.'</span>';
            $columns[] = '<span>'.$data->namaCabang.'</span>';  
            $columns[] = '<span>'.$data->jabatan.'</span>'; 
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
            $data = post_ajax_toarray($postdata);
 
            if(!empty($data['idPenandatangan'])){ 
                $data['id'] = $data['idPenandatangan'];
            }
            unset($data['idPenandatangan']);
                 
            if(!empty($data['identitas'])){ 
                $data['noIdentitas'] = $data['identitas'];
            }
            unset($data['identitas']);

            if(!empty($data['cabang'])){ 
                $data['idCabang'] = $data['cabang'];
            }
            unset($data['cabang']);

            $data['namaCabang'] = $this->request->getPost('namaCabang');
 
            $errMandatory = $this->cek_mandatory($data,'PENANDATANGAN');
 
            if(count($errMandatory) == 0)
            {
                $penandatanganModel = model('penandatangan'); 
                $penandatanganModel->upsert($data);
 
                $respData['id'] = encrypt_id($penandatanganModel->insertID());
                $resp = resp_success('Data penandatangan berhasil disimpan',$respData);
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
            $id = decrypt_id($this->request->getPost('idPenandatangan'));
            $arrPenandatangan = model('penandatangan')->where('id',$id)->first();   

            return $this->response->setJSON($arrPenandatangan);
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!'.$e->getMessage());
            return $this->response->setJSON($resp);
        }
    } 

    public function delete()
    {
        try {
            $idPenandatangan = decrypt_id($this->request->getPost('id'));  

            $cek = model('penandatangan')->where('id',$idPenandatangan)->findAll();
 
            if(count($cek) == 1)
            { 
                $timestamp = time();
                $currentDate = gmdate('Y-m-d h:i:s'); 
                $penandatanganModel = model('penandatangan');   
                $update = $penandatanganModel->set('isDelete', 'Y')->where('id',$idPenandatangan)->update();
  
                if($update)
                {
                    $respData['id'] = encrypt_id($penandatanganModel->insertID());
                    $resp = resp_success('Penandatangan berhasil dihapus',$respData); 
                } else {
                    $resp = resp_error('Penandatangan gagal dihapus');
                }
            } else {
                $resp = resp_error('Penandatangan tidak ada.');
            } 

            return $this->response->setJSON($resp);
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!'.$e->getMessage());
            return $this->response->setJSON($resp);
        }
    } 
}

?>