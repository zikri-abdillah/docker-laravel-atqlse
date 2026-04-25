<?php

namespace App\Controllers\Internal\Master;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;


class Client extends BaseController
{

	public function index()
    {  
        $param['addJS'] = '<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/datatable/js/dataTables.bootstrap5.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/datatable/js/dataTables.buttons.min.js"></script>'; 
        $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/datatable/dataTables.responsive.min.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/datatable/responsive.bootstrap5.min.js"></script>';
         
        $param['addJS'] .= '<script src="' . base_url() . '/js/master/client.js?v='.date('YmdHis').'"></script>';

    	$param['content'] = $this->render('master.client.index');

    	return $this->render('layout.template', $param);
    }

    public function profile()
    {
    	$param['content'] = $this->render('master.client.profile');

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

    // functions untuk client's CRUD
    public function list()
    { 
        $searchParam = $this->request->getPost('searchParam');
        $arrParam = post_ajax_toarray($searchParam);
 
        $arrData = model('tx_perusahaan');    
        $arrData->where('isDelete', 'N');
        
        if(!empty($arrParam['npwp'])){  
            $npwp = clean_npwp($arrParam['npwp']); 
            $arrData->like('npwp', $npwp,'after');
        }
        
        if(!empty($arrParam['nama'])){ 
            $arrData->like('nama', $arrParam['nama']);
        } 

        if(!empty($arrParam['nib'])){ 
            $arrData->like('nib', $arrParam['nib'],'after');
        } 
        
        if(!empty($arrParam['idJenisIup'])){   
            $arrData->where('idJenisIup', $arrParam['idJenisIup']);
        } 
        
  
        $recordsTotal = $arrData->countAllResults(false);
        $arrData = $arrData->orderBy('id', 'DESC')->findAll($this->request->getPost('length'), $this->request->getPost('start'));

        $row = [];
        $no = $this->request->getPost('start')+1;

        foreach ($arrData as $key => $data) {  
            if ($data->isActive == 'Y'){
                $status = 1;
            } elseif ($data->isActive == 'N') {
                $status = 2;
            } elseif ($data->isActive == 'W') {
                $status = 3;
            } 
            
            $btnView    = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-info" onclick="detail(\''.encrypt_id($data->id).'\')" title="View Client"><i class="fa fa-eye"></i></button> ';
            $btnDelete  = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-danger" onclick="del_perusahaan(\''.encrypt_id($data->id).'\')" title="Hapus Client"><i class="fa fa-trash"></i></button> ';
            $btnEdit    = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-warning" onclick="edit(\''.encrypt_id($data->id).'\')" title="Edit Client"><i class="fa fa-edit"></i></button> '; 
            $btnStatus  = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-success" onclick="change_status(\''.encrypt_id($data->id).'\''.','.'\''.$status.'\')" title="Edit Client"><i class="ion ion-ios-ribbon"></i></button> '; 

            $columns = [];
            $columns[] = $no++;
            $columns[] = '<span>'.$data->bentukPersh.'. '.$data->nama.'</span>';
            $columns[] = '<span>NPWP : '.formatNPWP($data->npwp).'</span><br><span>NIB : '.$data->nib.'</span><br><span>NITKU : '.$data->nitku.'</span>';
            $columns[] = '<span>Telp : '.$data->telp.'</span><br><span>Fax : '.$data->fax.'</span><br><span>Email : '.$data->email.'</span>';
            $columns[] = $data->jenisIUP;
            $columns[] = '<div class="btn-list text-nowrap">'.$btnView.$btnDelete.$btnEdit.'</div>';
            $row[] = $columns;
        }

        $table['draw'] = $this->request->getPost('draw');
        $table['recordsTotal'] = $recordsTotal;
        $table['recordsFiltered'] = $recordsTotal;
        $table['data'] = $row;

        echo json_encode($table); 
    }

    public function add()
    {  
        $param['addJS'] = '<script src="' . base_url() . '/js/master/client.js?v='.date('YmdHis').'"></script>';
        $param['addJS'] .= '<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>';  
        $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/datatable/js/dataTables.bootstrap5.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/datatable/dataTables.responsive.min.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/datatable/responsive.bootstrap5.min.js"></script>';

        $page = [
            'page_title'   => 'Input Data Client', 
        ];

    	$param['content'] = $this->render('master.client.create', $page); 
    	return $this->render('layout.template', $param);
    }
    
    public function edit()
    {
        try {
            $id = decrypt_id($this->request->getPost('id'));
            $arrPerusahaan = model('tx_perusahaan')->where('id',$id)->first(); 
            
            $page = [
                'page_title'   => 'Edit Data Client',
                'arrPerusahaan'   => $arrPerusahaan, 
            ];
 
            $param['addJS'] = '<script src="' . base_url() . '/js/master/client.js?v='.date('YmdHis').'"></script>';  
            $param['addJS'] .= '<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>';  
            $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/datatable/js/dataTables.bootstrap5.js"></script>';
            // $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/datatable/dataTables.responsive.min.js"></script>';
            // $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/datatable/responsive.bootstrap5.min.js"></script>'; 

 
            $param['content'] = $this->render('master.client.create', $page);
            return $this->render('layout.template', $param);
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!'.$e->getMessage());
            return $this->response->setJSON($resp);
        }
    } 

    public function detail()
    {
        try {
            $id             = decrypt_id($this->request->getPost('id'));
            $arrPerusahaan  = model('tx_perusahaan')->where('id',$id)->first(); 
            
            $page               = [
                'page_title'    => 'View Data Client',
                'arrPerusahaan' => $arrPerusahaan, 
                'idPerusahaan'  => encrypt_id($arrPerusahaan->id), 
            ];
 
            $param['addJS'] = '<script src="' . base_url() . '/js/master/client.js?v='.date('YmdHis').'"></script>';   
            $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/datatable/js/dataTables.bootstrap5.js"></script>';
            $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/datatable/dataTables.responsive.min.js"></script>';
            $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/datatable/responsive.bootstrap5.min.js"></script>';
            $param['addJS'] .= '<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>';  
            $param['content'] = $this->render('master.client.detail', $page);

            return $this->render('layout.template', $param);
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!'.$e->getMessage());
            return $this->response->setJSON($resp);
        }
    } 

    public function save()
    {
        try { 
            $postdata = $this->request->getPost('postdata');
            $data     = post_ajax_toarray($postdata);
            $idData   = $data['idData'] ? decrypt_id($data['idData']) : '';
 
            if(!empty($data['idData'])){
                $data['id'] = decrypt_id($data['idData']);
            }
            unset($data['idData']);
 
            $data['npwp']           = clean_npwp($data['npwp']); 
            $data['npwp16']         = clean_npwp($data['npwp16']); 
            $data['jenisIUP']       = $this->request->getPost('jenisIUP');

            if(!empty($data['kodeProp'])){
                $propEks            = model('propinsi')->where('id', $data['kodeProp'])->first(); 
                $data['idProp']     = $data['kodeProp'];
                $data['kodeProp']     = $propEks->kodeInatrade;
                $data['namaProp']   = $propEks->namaPropinsi;
            }

            if(!empty($data['kodeKab'])){
                $kotaEks         = model('kota')->where('id',$data['kodeKab'])->first(); 
                $data['idKab']   = $data['kodeKab'];
                $data['kodeKab'] = $kotaEks->kodeInatrade;
                $data['namaKab'] = $kotaEks->namaKota;
            }
 
            $data['tglIUP']         = reverseDateDB($data['tglIUP']); 
            $errMandatory           = $this->cek_mandatory($data,'CLIENT');
            $errorText              = '';

            if(count($errMandatory) == 0)  { 
                $validation		= \Config\Services::validation(); 
                $validationRule = [ 
                    'email'   		=> [
                        'rules'		=> [
                            'required', 'valid_email',
                        ],
                        'errors'	=> [
                            'required'      => 'Email kosong',
                            'valid_email'	=> 'Email Perusahaan tidak valid', 
                        ]
                    ],
                    'picEmail'   	=> [
                        'rules' 	=> [ 
                            'required', 'valid_email',
                        ],
                        'errors' 	=> [ 
                            'required'      => 'Email kosong',
                            'valid_email'	=> 'Email PIC tidak valid', 
                        ]
                    ],
                ];

                $validation->setRules($validationRule);

                if (! $validation->run($data)) {
                    $errAuth 	= []; 
                    foreach ($validation->getErrors() as $key => $val) {
                        $errAuth[] = $val;
                    }
                    
                    $textErr = implode('<br> - ', $errAuth);
                    $resp = resp_error('Perhatikan catatan berikut : <br>- '.$textErr);

                } else {   
                    $dataDuplikasi          = array();
                    $dataDuplikasi['npwp']  = $data['npwp']; 
                    $errDuplikasi           = cek_status_npwp($dataDuplikasi, 'tx_perusahaan', $idData);
        
                    if($errDuplikasi == '')  { 
                        $clientModel = model('tx_perusahaan');

                        if ($clientModel->upsert($data) !== false) {  
                            if($idData == '')
                                $respData['id'] = encrypt_id($clientModel->insertID());
                            else
                                $respData['id'] = encrypt_id($idData);

                            $resp = resp_success('Data berhasil disimpan',$respData);
                        } else {  
                            $arrError = $clientModel->errors();
                            
                            foreach($arrError as $x => $val) { 
                                $errorText .= "<br> - ".$val;
                            } 
                            $resp = resp_error('Perhatikan pesan berikut:'.$errorText);
                        }
                    } else {  
                        $resp = resp_error($errDuplikasi); 
                    }
                    return $this->response->setJSON($resp);
                }
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
  
    public function delete()
    {
        try {
            $idPerusahaan = decrypt_id($this->request->getPost('id')); 
            $cek = model('tx_perusahaan')->where('id',$idPerusahaan)->findAll();
 
            if(count($cek) == 1)
            { 
                $timestamp = time();
                $currentDate = gmdate('Y-m-d h:i:s'); 
                $clientModel = model('tx_perusahaan');   
                $update = $clientModel->set('isDelete', 'Y')->where('id',$idPerusahaan)->update();
  
                if($update)
                {
                    $respData['id'] = encrypt_id($clientModel->insertID());
                    $resp = resp_success('Perusahaan berhasil dihapus',$respData); 
                } else {
                    $resp = resp_error('Perusahaan gagal dihapus');
                }
            } else {
                $resp = resp_error('Data perusahaan tidak ada.');
            } 

            return $this->response->setJSON($resp);
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!'.$e->getMessage());
            return $this->response->setJSON($resp);
        }
    }
 
    // functions for document's CRUD
    public function uploadDok()
    {
        try {  
            $postdata       = $this->request->getPost('postdata'); 
            $data           = post_ajax_toarray($postdata); 
            $tnggalSekarang = date('Y-m-d'); 
            $tnggalAkhir    = reverseDateDB($data['tglAkhirDokumen']); 
            
            // pengecekan tanggal akhir pada penerbitan LS, apakah tanggal akhir sudah kadalursa atau belum
            if($tnggalAkhir > $tnggalSekarang){ 
                $validationRule = [
                    'fileDok' => [
                        'rules' => [
                            'uploaded[fileDok]',
                            'mime_in[fileDok,image/jpg,image/jpeg,image/png,image/webp,application/pdf]',
                            'max_size[fileDok,5120]',
                        ],
                        'errors' => [
                            'uploaded' => 'File belum di pilih',
                            'mime_in' => 'File yang diupload harus berupa image atau pdf dengan format jpg/png/pdf',
                            'max_size' => 'Ukuran file maksimal 5 MB'
                        ]
                    ],
                ];

                if(!empty($this->request->getPost('idPerusahaan'))){
                    $data['idPersh'] = decrypt_id($this->request->getPost('idPerusahaan'));
                }

                $errMandatory = $this->cek_mandatory($data,'DOCUMENT');

                if(count($errMandatory) == 0)
                {
                    if (! $this->validate($validationRule)) {
                        $resp = resp_error($this->validator->getError(),'','Upload Gagal');
                        return $this->response->setJSON($resp);
                    } else {
                        $fileUpload = $this->request->getFile('fileDok');

                        if (! $fileUpload->hasMoved()) {
                            $pathFile               = $fileUpload->store('pendukung/'.date('Ymd')); 
                            $data['npwp']           = clean_npwp($this->request->getPost('npwp'));
                            $data['tglDokumen']     = reverseDate($data['tglDokumen']);
                            $data['tglAkhirDokumen']= reverseDate($data['tglAkhirDokumen']);
                            $data['jenisDok']       = model('jenisDokumen')->where('id',$data['idJenisDok'])->get()->getRow()->jenisDokumen;
                            $data['pathFile']       = $pathFile; 
                            $dokModel               = model('t_dokpersh')->builder();
                            $dokModel->insert($data);

                            $resp = resp_success('Data berhasil disimpan'); 
                        }
                        else{
                            throw new \Exception('Gagal upload.');
                        }
                    }
                } else { 
                    $textErr = implode('<br> - ', $errMandatory);
                    $resp    = resp_error('Perhatikan isian berikut <br>'.$textErr);
                } 
                return $this->response->setJSON($resp);
            } else { 
                $resp = resp_error('Tanggal akhir dokumen harus lebih dari tanggal sekarang');
                return $this->response->setJSON($resp);
            }
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!'.$e->getMessage());
            return $this->response->setJSON($resp);
        }
    } 
  
    public function updeteDok()
    {
        try {  
            $postdata       = $this->request->getPost('postdata'); 
            $data           = post_ajax_toarray($postdata); 
            $idDok          = decrypt_id($data['idDok']);
            $tnggalSekarang = date('Y-m-d'); 
            $tnggalAkhir    = reverseDateDB($data['tglAkhirDokumen']); 
            
            if(!empty($this->request->getPost('idPerusahaan'))){
                $data['idPersh'] = decrypt_id($this->request->getPost('idPerusahaan'));
            }
            
            // pengecekan tanggal akhir pada penerbitan LS, apakah tanggal akhir sudah kadalursa atau belum
            if($tnggalAkhir > $tnggalSekarang){ 
                $validationRule = [
                    'fileDok' => [
                        'rules' => [
                            'uploaded[fileDok]',
                            'mime_in[fileDok,image/jpg,image/jpeg,image/png,image/webp,application/pdf]',
                            'max_size[fileDok,5120]',
                        ],
                        'errors' => [
                            'uploaded' => 'File belum diunggah',
                            'mime_in'  => 'File yang diupload harus berupa image atau pdf dengan format jpg/png/pdf',
                            'max_size' => 'Ukuran file maksimal 5 MB'
                        ]
                    ],
                ];
 
                $errMandatory = $this->cek_mandatory($data,'DOCUMENT');
   
                if(count($errMandatory) == 0)
                { 
                    $validasiUpload = "";
                    if (! $this->validate($validationRule)){
                        $validasiUpload = $this->validator->getError();
                    }

                    if($validasiUpload == "" || $validasiUpload = "File belum di pilih"){ 
                        $data['npwp']           = clean_npwp($this->request->getPost('npwp'));
                        $data['tglDokumen']     = reverseDate($data['tglDokumen']);
                        $data['tglAkhirDokumen']= reverseDate($data['tglAkhirDokumen']);
                        $data['jenisDok']       = model('jenisDokumen')->where('id',$data['idJenisDok'])->get()->getRow()->jenisDokumen;
   
                        unset($data['idDok']);
                        
                        if($this->request->getFile('fileDok') === NULL){  
                            $dokModel               = model('t_dokpersh')->builder();  
                            $dokModel->update($data, ['id' => $idDok]);

                            $resp = resp_success('Data berhasil disimpan.');  
                        } else {
                            $fileUpload = $this->request->getFile('fileDok');

                            if (! $fileUpload->hasMoved()) {
                                $pathFile               = $fileUpload->store('pendukung/'.date('Ymd'));
                                $data['pathFile']       = $pathFile; 
                                $dokModel               = model('t_dokpersh')->builder();  
                                $dokModel->update($data, ['id' => $idDok]);
    
                                $resp = resp_success('Data berhasil disimpan'); 
                            }
                            else{
                                throw new \Exception('Gagal upload.');
                            } 
                        }  
                    } else {
                        $resp = resp_error($validasiUpload,'','Upload Gagal');
                        return $this->response->setJSON($resp);
                    } 
                } else { 
                    $textErr = implode('<br> - ', $errMandatory);
                    $resp    = resp_error('Perhatikan isian berikut <br>'.$textErr);
                } 
                return $this->response->setJSON($resp);
            } else { 
                $resp = resp_error('Tanggal akhir dokumen harus lebih dari tanggal sekarang');
                return $this->response->setJSON($resp);
            }
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!'.$e->getMessage());
            return $this->response->setJSON($resp);
        }
    } 

    public function listDok()
    {
        $idPer      = decrypt_id($this->request->getPost('idPerusahaan')); 
        $dataModel  = model('t_dokpersh'); 
        $dataModel->where('idPersh',$idPer)->where('isDelete', 'N')->orderBy(' jenisDok, tglDokumen', 'ASC'); 
        $arrData    = $dataModel->findAll(); 
        $html       = '';

        foreach ($arrData as $key => $data) { 
            $modelRef       = model('tx_lseReferensi');
            $modelRef->select('id');  
            $dataRef        = $modelRef->where('idDokPersh', $data->id);
            $banyak_data    = $dataRef->countAllResults(false); 
            $dataRef        = $dataRef->findAll(); 
            $btnEdit        = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-warning me-1" onclick="edit_dok_persh(\''.encrypt_id($data->id).'\')" title="Edit"><i class="fa fa-edit"></i></button>'; 
            $btnDelete      = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-danger me-1" onclick="del_dok_persh(\''.encrypt_id($data->id).'\')" title="Hapus"><i class="fa fa-trash"></i></button> '; 
            $btnView        = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-info" onclick="view_dok_persh(\''.encrypt_id($data->id).'\')" title="Lihat File"><i class="fa fa-eye"></i></button> '; 
            $btnAct         = $btnEdit.$btnDelete.$btnView;

            if($banyak_data > 0){
                $btnAct     = $btnView;
            }
              
            $html .= '<tr >
                        <td class="align-top text-nowrap text-center" width="5%">'.($key+1).'</td>
                        <td class="align-top" width="30%">'.$data->jenisDok.'</td>
                        <td class="align-top" width="20%">'.$data->noDokumen.'</td>
                        <td class="align-top" width="13%">'.reverseDate($data->tglDokumen).'</td>
                        <td class="align-top" width="12%">'.reverseDate($data->tglAkhirDokumen).'</td>
                        <td class="align-top" width="12%">'.model("negara")->where("kode",$data->negaraPenerbit)->first()->nama.'</td>
                        <td class="align-top text-nowrap text-center" width="13%">'.$btnAct.'</td>
                    </tr>';
        }

        $resp['content'] = $html;
        return $this->response->setJSON($resp);
    }
    
    public function deleteDok()
    {
        try { 
            $idDok  = decrypt_id($this->request->getPost('idDok')); 
            $cek    = model('t_dokpersh')->where('id',$idDok)->findAll();
 
            if(count($cek) == 1)
            { 
                $timestamp   = time();
                $currentDate = gmdate('Y-m-d h:i:s'); 
                $clientModel = model('t_dokpersh');   
                $update = $clientModel->set('isDelete', 'Y')->where('id',$idDok)->update();
  
                if($update)
                {
                    $respData['id'] = encrypt_id($clientModel->insertID());
                    $resp = resp_success('Dokumen berhasil dihapus',$respData); 
                } else {
                    $resp = resp_error('Dokumen gagal dihapus');
                }
            } else {
                $resp = resp_error('Data dokumen tidak ada.');
            } 

            return $this->response->setJSON($resp); 
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!'.$e->getMessage());
            return $this->response->setJSON($resp);
        }
    } 
    
    // private function cek_status_npwp($data, $tabel, $idData)
    // {
    //     $errMandatory = "";

    //     foreach ($data as $key => $val) {  
            
    //         $arrData      = model($tabel); 
    //         $arrData->where($key, $val); 
    //         $recordsTotal = $arrData->countAllResults(false);
    //         $arrData      = $arrData->findAll();
 
    //         if($recordsTotal > 0){ 
    //             $pete = $arrData[0]->bentukPersh.". ".$arrData[0]->nama;

    //             if($idData != ''){   
    //                 if($idData != $arrData[0]->id){
    //                     $errMandatory  .= 'NPWP nomor '.formatNPWP($val).' sudah digunakan oleh '.$pete; 
    //                 } 
    //             } else {
    //                 $errMandatory  .= 'NPWP nomor '.formatNPWP($val).' sudah digunakan oleh '.$pete; 
    //             }
    //         }  
    //     } 

    //     return $errMandatory;
    // }
    
    // 2024-03-11  
    public function change_status()
    {
        try {  
            $status         = $this->request->getPost('status'); 
            $idPerusahaan   = decrypt_id($this->request->getPost('idPerusahaan')); 
            // $arrPerusahaan  = model('tx_perusahaan')->where('id', $idPerusahaan)->first();  
              
            if(!empty($idPerusahaan)){ 
                $perusahaanModel  = model('tx_perusahaan'); 
                $arrPerusahaan    = []; 

                if($status == '1'){
                    $arrPerusahaan['isActive']      = 'N';
                } else {
                    $arrPerusahaan['isActive']      = 'Y';
                }

                if ($perusahaanModel->update(['id' => $idPerusahaan], $arrPerusahaan) === false) { 
                    $arrError = $perusahaanModel->errors();
                    
                    foreach($arrError as $x => $val) { 
                        $errorText .= $val;
                    }

                    $resp = resp_error("-- ".$errorText); 
                } else { 
                    $respData['id'] = $perusahaanModel->insertID(); 

                    if($status !== '3'){
                        $resp = resp_success('Status user berhasil dirubah', $respData); 
                    } else { 
                        $dokModel = model('t_user');
                        $dokModel->select("t_user.id AS 'idUser', t_user.username, t_user.`password`, t_user.isActive,
                            m_perusahaan.bentukPersh, m_perusahaan.nama, m_perusahaan.picNama, m_perusahaan.email, m_perusahaan.picEmail");
                        $dokModel->join('m_role', 'm_role.id = t_user.idrole');  
                        $dokModel->join('m_user_type', 'm_user_type.id = t_user.usertype');    
                        $dokModel->join('m_perusahaan', 'm_perusahaan.id = t_user.idProfile', 'left'); 
                        $dataUser =  $dokModel->where('m_perusahaan.id', $idPerusahaan)->first(); 

                        if(email_konf_registrasi($dataUser) == 1){
                            $resp = resp_success('Status user berhasil dirubah', $respData);  
                        } else { 
                            $resp = resp_error('Gagal mengirim email'); 
                        }
                    } 
                }  
            } else {
                $resp = resp_error('ID data kosong');
            }

            return $this->response->setJSON($resp); 
        } catch (\Throwable $e) { 
            $resp = resp_error('An Exception has occured!'.$e->getMessage());
            return $this->response->setJSON($resp);
        }
    }
}

?>