<?php

namespace App\Controllers\Internal\Master;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;


class Npwp extends BaseController
{

	public function index()
    {  
        $param['addJS'] = '<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/datatable/js/dataTables.bootstrap5.js"></script>'; 
        $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/datatable/dataTables.responsive.min.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/datatable/responsive.bootstrap5.min.js"></script>'; 
        $param['addJS'] .= '<script src="' . base_url() . '/js/master/npwp.js?v='.date('YmdHis').'"></script>';

    	$param['content'] = $this->render('master.npwp.index');

    	return $this->render('layout.template', $param);
    }


    // functions untuk npwp's CRUD
    public function list()
    {  
        $searchParam = $this->request->getPost('searchParam');
        $arrParam = post_ajax_toarray($searchParam);
 
        $arrData = model('npwp');

        if(!empty($arrParam['npwp15'])){
            $arrData->like('npwp15', clean_npwp($arrParam['npwp15']));
        }

        if(!empty($arrParam['nama'])){
            $arrData->like('nama', $arrParam['nama']);
        }
        
        if(!empty($arrParam['npwp16'])){
            $arrData->like('npwp16', clean_npwp($arrParam['npwp16']));
        } 

        $recordsTotal = $arrData->countAllResults(false);
        $arrData = $arrData->orderBy('id', 'DESC')->findAll($this->request->getPost('length'), $this->request->getPost('start'));

        $row = [];
        $no = $this->request->getPost('start')+1;

        foreach ($arrData as $key => $data) { 
            $btnDelete = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-danger" onclick="del_npwp(\''.encrypt_id($data->id).'\')" title="Hapus"><i class="fa fa-trash"></i></button> ';
            $btnEdit = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-warning" onclick="open_modal(\''.encrypt_id($data->id).'\')" title="Edit"><i class="fa fa-edit"></i></button> ';

            $columns = [];
            $columns[] = $no++;
            $columns[] = '<span>'.$data->nama.'</span>';
            $columns[] = '<span>'.FormatNPWP($data->npwp15).'</span>';
            $columns[] = '<span>'.FormatNPWP($data->npwp16).'</span>';
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
 
            if(!empty($data['idNpwp'])){
                // $data['id'] = decrypt_id($data['idCabang']);
                $data['id'] = $data['idNpwp'];
            }
            unset($data['idNpwp']);
            $data['npwp15'] = clean_npwp($data['npwp15']);
            $data['npwp16'] = clean_npwp($data['npwp16']);


            if(!empty($data['id']))
            {
                $cek = model('npwp')->where('id <>',$data['id'])->where('npwp15 <>','NULL')->where('npwp16 <>','NULL')->groupStart()->where('npwp15',clean_npwp($data['npwp15']))->orwhere('npwp16',clean_npwp($data['npwp16']))->groupEnd();
            }
            else
            {
                $cek = model('npwp')->where('npwp15',clean_npwp($data['npwp15']))->orwhere('npwp16',clean_npwp($data['npwp16']));
            }
            $errMandatory = $cek->get()->getRow();

            if(!$errMandatory)
            {
                $cabangModel = model('npwp');
                $cabangModel->upsert($data);
 
                $respData['id'] = encrypt_id($cabangModel->insertID());
                $resp = resp_success('Data NPWP berhasil disimpan',$respData);
            } else {
                $resp = resp_error('Npwp sudah ada dengan nama '.$errMandatory->nama);
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
            $id = decrypt_id($this->request->getPost('idNpwp'));
            $arrNpwp = model('npwp')->where('id',$id)->first();

            return $this->response->setJSON($arrNpwp);
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!'.$e->getMessage());
            return $this->response->setJSON($resp);
        }
    } 

    public function delete()
    {
        try {
            $idNpwp = decrypt_id($this->request->getPost('id'));

            $cek = model('npwp')->where('id',$idNpwp)->findAll();
 
            if(count($cek) == 1)
            { 
                $timestamp = time();
                $currentDate = gmdate('Y-m-d h:i:s'); 
                $model = model('npwp');
                $delete = $model->where('id',$idNpwp)->delete();
  
                if($delete)
                {
                    $respData['id'] = encrypt_id($model->insertID());
                    $resp = resp_success('Npwp berhasil dihapus',$respData);
                } else {
                    $resp = resp_error('Npwp gagal dihapus');
                }
            } else {
                $resp = resp_error('Npwp tidak ada.');
            } 

            return $this->response->setJSON($resp);
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!'.$e->getMessage());
            return $this->response->setJSON($resp);
        }
    } 
}

?>