<?php

namespace App\Controllers\Internal\Master;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;


class User extends BaseController
{  
    function __construct(){
        if(session()->get('sess_role') != 1)
        {
            exit('Forbidden');
        }
    }

	public function index()
    {  
        $param['addJS'] = '<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/datatable/js/dataTables.bootstrap5.js"></script>'; 
        $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/datatable/dataTables.responsive.min.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . 'assets/plugins/datatable/responsive.bootstrap5.min.js"></script>';
         
        $param['addJS'] .= '<script src="' . base_url() . '/js/master/user.js?v='.date('YmdHis').'"></script>';

    	$param['content'] = $this->render('master.user.index');

    	return $this->render('layout.template', $param);
    }
 
    // functions untuk user's CRUD
    public function list()
    {  
        $searchParam = $this->request->getPost('searchParam');
        $arrParam = post_ajax_toarray($searchParam);
         
        $arrData = model('t_user');
        $arrData->select("t_user.id AS 'idUser', t_user.idrole, t_user.usertype, t_user.username, t_user.`password`, t_user.isActive, m_pegawai.id AS 'idPegawai', m_pegawai.nama, m_perusahaan.picNama");
        $arrData->join('m_pegawai', 'm_pegawai.id = t_user.idprofile', 'LEFT');  
        $arrData->join('m_perusahaan', 'm_perusahaan.id = t_user.idprofile', 'LEFT'); 
        $arrData->where('t_user.isDelete', 'N');
        
        // echo "<pre>";
        // print_r($arrData->getLastQuery());
        // echo "</pre>";
        // die();
           
        if(!empty($arrParam['role'])){  
            $arrData->like('idrole', $arrParam['role']);
        }
        
        if(!empty($arrParam['type'])){ 
            $arrData->like('usertype', $arrParam['type']);
        } 

        $recordsTotal = $arrData->countAllResults(false);
        $arrData = $arrData->orderBy('idUser', 'DESC')->findAll($this->request->getPost('length'), $this->request->getPost('start'));

        $row = [];
        $no = $this->request->getPost('start')+1;

        foreach ($arrData as $key => $data) { 
            $btnDelete  = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-danger" onclick="del_user(\''.encrypt_id($data->idUser).'\')" title="Hapus"><i class="fa fa-trash"></i></button> ';
            $btnEdit    = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-warning" onclick="edit(\''.encrypt_id($data->idUser).'\')" title="Edit"><i class="fa fa-edit"></i></button> '; 
            $btnView    = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-info" onclick="detail(\''.encrypt_id($data->idUser).'\')" title="View User"><i class="fa fa-eye"></i></button> ';
            
            if($data->isActive == 'Y'){
                $status = 'Active';
            } else if($data->isActive == 'W'){
                $status = 'Waiting';
            } else {
                $status = 'Inactive';
            }

            $columns = [];
            $columns[] = $no++; 
            $columns[] = $data->nama ? '<span>'.$data->nama.'</span>' : '<span>'.$data->picNama.'</span>'; 
            $columns[] = '<span>'.$data->username.'</span>'; 
            $columns[] = '<span>'.model("t_role")->where("id", $data->idrole)->first()->role.'</span>';
            $columns[] = '<span>'.model("t_userType")->where("id", $data->usertype)->first()->uraian.'</span>';
            $columns[] = '<span>'.$status.'</span>'; 
            // $columns[] = '';
            $columns[] = '<div class="btn-list text-nowrap">'.$btnDelete.$btnEdit.$btnView.'</div>';
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
        $param['addJS'] = '<script src="' . base_url() . '/js/master/user.js?v='.date('YmdHis').'"></script>';
        $param['addJS'] .= '<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>'; 

        $page = [
            'page_title'   => 'Input Data User', 
        ];

    	$param['content'] = $this->render('master.user.create', $page); 
 
    	return $this->render('layout.template', $param);
    }
 
    public function edit()
    {
        try {
            $id         = decrypt_id($this->request->getPost('id')); 
            $userModel  = model('t_user');
            $usertype   = $userModel->where('id', $id)->first()->usertype;
  
            if($usertype == 1){ 
                $userModel->select("t_user.id AS 'idUser', t_user.idrole, m_role.role, t_user.usertype, m_user_type.uraian AS 'type', t_user.username, t_user.`password`, t_user.isActive as 'isActiveUser', m_perusahaan.*");    
                $userModel->join('m_role', 'm_role.id = t_user.idrole');  
                $userModel->join('m_user_type', 'm_user_type.id = t_user.usertype');     
                $userModel->join('m_perusahaan', 'm_perusahaan.id = t_user.idprofile', 'left');  
                $arrData =  $userModel->where('t_user.id',$id)->first(); 
                
                $page = [
                    'page_title'            => 'Edit Data User (Pelaku Usaha)',
                    'breadcrumb_active'     => 'Edit Data User (Pelaku Usaha)',
                    'arrPerusahaan'         => $arrData,  
                ];

                $param['content'] = $this->render('master.user.create-pu', $page);
            } else { 
                $userModel->select("t_user.id AS 'idUser', t_user.idrole,t_user.isActive, m_role.role, m_cabang.cabang, t_user.usertype, m_user_type.uraian AS 'type', t_user.username, t_user.`password`, m_pegawai.id AS 'idPegawai', m_pegawai.nik, m_pegawai.idcabang, m_pegawai.nip, m_pegawai.jabatan, m_pegawai.nama, m_pegawai.alamat, m_pegawai.email, m_pegawai.telp");
                $userModel->join('m_pegawai', 'm_pegawai.id = t_user.idprofile');  
                $userModel->join('m_cabang', 'm_cabang.id = m_pegawai.idcabang');  
                $userModel->join('m_role', 'm_role.id = t_user.idrole');  
                $userModel->join('m_user_type', 'm_user_type.id = t_user.usertype');  
                $arrData =  $userModel->where('t_user.id',$id)->first(); 

                $page = [
                    'page_title'            => 'Edit Data User',
                    'breadcrumb_active'     => 'Edit Data User',
                    'arrUser'               => $arrData,  
                ];

                $param['content'] = $this->render('master.user.create', $page);
            }
  
            $param['addJS'] = '<script src="' . base_url() . '/js/master/user.js?v='.date('YmdHis').'"></script>'; 
            $param['addJS'] .= '<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>';

            return $this->render('layout.template', $param);
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!'.$e->getMessage());
            return $this->response->setJSON($resp);
        }
    }

    public function save()
    {
        try {  
            $idPegawai  = $this->request->getPost('idProfile') ? decrypt_id($this->request->getPost('idProfile')) : '';  
            $idUser     = $this->request->getPost('idUser') ? decrypt_id($this->request->getPost('idUser')): '';   
            $postdata   = $this->request->getPost('postdata');
            $data       = post_ajax_toarray($postdata);  
            $arrPegawai = []; 

 
            $arrPegawai['nik']      = $data['nik'];
            $arrPegawai['idcabang'] = $data['cabang'];
            $arrPegawai['nip']      = $data['nip'];
            $arrPegawai['jabatan']  = $data['jabatan']; 
            $arrPegawai['nama']     = $data['nama'];
            $arrPegawai['alamat']   = $data['alamat'];
            $arrPegawai['telp']     = $data['telp'];
            $arrPegawai['email']    = $data['email']; 
             
            if(!empty($idPegawai)){ 
                $arrPegawai['id'] = $idPegawai;
            } 
            
            $arrUser = [];
           
            $arrUser['idrole']      = $data['role'];
            //$arrUser['usertype']    = $data['type'];
            $arrUser['usertype']    = 2;
            $arrUser['username']    = $data['username'];
            $arrUser['isActive']    = $data['isActive'];
             
            if($data['password'] !== NULL){ 
                $password           = str_replace(" ","", $data['password']);
                $arrUser['password'] = hash_pass($password); 
            }
               
            if(!empty($idUser)){ 
                $arrUser['id'] = $idUser;
            }    

            $errMandatoryPegawai= cek_mandatory($arrPegawai,'PEGAWAI');
            $errMandatoryUser   = cek_mandatory($arrUser,'USER'); 
            $errMandatory       = array_merge($errMandatoryPegawai,$errMandatoryUser);
            $errorText          = '';
            
            if(count($errMandatory) == 0)  {  
                $validation		= \Config\Services::validation(); 
                $validationRule = [
                    'username'   	=> [
                        'rules' 	=> [
                            'required',
                            'min_length[5]',
                        ],
                        'errors' 	=> [
                            'required'      => 'Username kosong',
                            'min_length'    => 'Username minimal 5 Karakter', 
                        ]
                    ], 
                    'email'   		=> [
                        'rules'		=> [
                            'required',
                            'valid_email',
                        ],
                        'errors'	=> [
                            'required'      => 'Email kosong',
                            'valid_email'	=> 'Email tidak valid', 
                        ]
                    ] 
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
                    $arrDuplikasi              = array();
                    $arrDuplikasi['username']  = $arrUser['username']; 
                    $errDuplikasi              = cek_duplikasi_user($arrDuplikasi, 't_user', $idUser);
    
                    if($errDuplikasi == '')  { 
                        $pegawaiModel = model('t_pegawai'); 
                        $pegawaiModel->upsert($arrPegawai); 
                        $idInsert     = $pegawaiModel->insertID() ? $pegawaiModel->insertID() : $arrPegawai['id'];  

                        if(!empty($idInsert)) {  
                            $arrUser['idprofile'] = $idInsert;    
                            $userModel            = model('t_user'); 
                            $userModel->upsert($arrUser);
                            
                            if(!empty($userModel->insertID()))
                                $idData = $userModel->insertID();
                            else
                                $idData = $idUser;
        
                            if(!empty($idData)){ 
                                $respData['id'] = $idData; 
                                $resp = resp_success('Data user berhasil disimpan', $respData);  
                            } else {
                                $resp = resp_error('Gagal menyimpan data user. '.$idData);
                            } 
                        } else {
                            $resp = resp_error('Gagal menyimpan data pegawai.');
                        } 
                    } else { 
                        $resp = resp_error($errDuplikasi); 
                    }
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
   
    public function save_pu(){   
        try {       
            $idProfile      = $this->request->getPost('idProfile') ? decrypt_id($this->request->getPost('idProfile')): ''; 
            $idUser         = $this->request->getPost('idUser') ? decrypt_id($this->request->getPost('idUser')): '';
            $postdata       = $this->request->getPost('postdata');
            $data           = post_ajax_toarray($postdata); 
 
            $arrPerusahaan                  = $data;  
            $arrPerusahaan['id']            = $idProfile;
            $arrPerusahaan['npwp']          = clean_npwp($data['npwp']);  
            $arrPerusahaan['jenisIUP']      = $this->request->getPost('jenisIUP');
            $arrPerusahaan['tglIUP']        = reverseDateDB($data['tglIUP']); 
            
            if(!empty($data['kodeProp'])){
                $propEks                    = model('propinsi')->where('id', $data['kodeProp'])->first(); 
                $arrPerusahaan['idProp']    = $data['kodeProp']; 
                $arrPerusahaan['kodeProp']  = $propEks->kodeInatrade;
                $arrPerusahaan['namaProp']  = $propEks->namaPropinsi;
            }

            if(!empty($data['kodeKab'])){
                $kotaEks                    = model('kota')->where('id',$data['kodeKab'])->first();  
                $arrPerusahaan['idKab']     = $data['kodeKab']; 
                $arrPerusahaan['kodeKab']   = $kotaEks->kodeInatrade;
                $arrPerusahaan['namaKab']   = $kotaEks->namaKota;
            }
 
            unset($arrPerusahaan['username']);
            unset($arrPerusahaan['password']);

            $arrUser = [];  
            $arrUser['idrole']      = 10;
            $arrUser['usertype']    = 1;
            $arrUser['id']          = $idUser;
            $arrUser['username']    = $data['username']; 
            
            if($data['password'] !== NULL){
                $password           = str_replace(" ","", $data['password']);
                $arrUser['password'] = hash_pass($password);  
            }
              
            $errMandatoryPerus      = cek_mandatory($arrPerusahaan, 'CLIENT');
            $errMandatoryUser       = cek_mandatory($arrUser, 'USER'); 
            $errMandatory           = array_merge($errMandatoryPerus,$errMandatoryUser);
            $errorText              = '';
  
            if(count($errMandatory) == 0)  {  
                $arrDuplikasi              = array();
                $arrDuplikasi['username']  = $arrUser['username']; 
                $errDuplikasi              = cek_duplikasi_user($arrDuplikasi, 't_user', $idUser);
    
                if($errDuplikasi == '')  { 
                    $validation		= \Config\Services::validation(); 
                    $validationRule = [
                        'username'   	=> [
                            'rules' 	=> [
                                'required', 'min_length[5]',
                            ],
                            'errors' 	=> [
                                'required'      => 'Username kosong',
                                'min_length'    => 'Username minimal 5 Karakter', 
                            ]
                        ], 
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
                        $perusModel = model('tx_perusahaan'); 
                        $perusModel->upsert($arrPerusahaan);
        
                        if(!empty($perusModel->insertID())){
                            $idProfile = $perusModel->insertID();
                        }
                        
                        if(!empty($idProfile)){  
                            $arrUser['idprofile'] = $idProfile; 
                            $userModel            = model('t_user');

                            if ($userModel->upsert($arrUser) === false) { 
                                $arrError = $userModel->errors();
                                
                                foreach($arrError as $x => $val) { 
                                    $errorText .= $val;
                                }

                                $resp = resp_error("- ".$errorText);
                            } else {  
                                if(!empty($userModel->insertID())){
                                    $idUser     = $userModel->insertID();
                                }

                                $respData['idUser']     = encrypt_id($idUser); 
                                $respData['idProfile']  = encrypt_id($idProfile); 
                                $resp = resp_success('Data user berhasil disimpan.', $respData); 
                            }  
                        } else {
                            $resp = resp_error('Gagal menyimpan data perusahaan.'.$idProfile);
                        } 
                    }
                } else {
                    $resp = resp_error($errDuplikasi); 
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
            $idUser = decrypt_id($this->request->getPost('id'));  
            $cek = model('t_user')->where('id',$idUser)->findAll();
 
            if(count($cek) == 1)
            { 
                $timestamp = time();
                $currentDate = gmdate('Y-m-d h:i:s'); 
                $userModel = model('t_user');   
                $update = $userModel->set('isDelete', 'Y')->where('id',$idUser)->update();
  
                if($update)
                {
                    $respData['id'] = encrypt_id($userModel->insertID());
                    $resp = resp_success('User berhasil dihapus',$respData); 
                } else {
                    $resp = resp_error('User gagal dihapus');
                }
            } else {
                $resp = resp_error('User tidak ada.');
            } 

            return $this->response->setJSON($resp);
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!'.$e->getMessage());
            return $this->response->setJSON($resp);
        }
    } 
 
    public function detail()
    {
        try {
            $id         = decrypt_id($this->request->getPost('id')); 
            $userModel  = model('t_user');
            $usertype   = $userModel->where('id', $id)->first()->usertype;
  
            if($usertype == 1){ 
                $userModel->select("t_user.id AS 'idUser', t_user.idrole, m_role.role, t_user.usertype, m_user_type.uraian AS 'type', t_user.username, t_user.`password`, t_user.isActive as 'isActiveUser', m_perusahaan.*");    
                $userModel->join('m_role', 'm_role.id = t_user.idrole');  
                $userModel->join('m_user_type', 'm_user_type.id = t_user.usertype');     
                $userModel->join('m_perusahaan', 'm_perusahaan.id = t_user.idprofile', 'left');  
                $arrData =  $userModel->where('t_user.id',$id)->first(); 
                
                $page = [
                    'page_title'    => 'View Data User',
                    'arrPerusahaan' => $arrData, 
                    'idUser'        => encrypt_id($arrData->idUser), 
                ];

                $param['content'] = $this->render('master.user.view-pu', $page);
            } else { 
                $userModel->select("t_user.id AS 'idUser', t_user.idrole,t_user.isActive as 'isActiveUser', m_role.role, m_cabang.cabang, t_user.usertype, m_user_type.uraian AS 'type', t_user.username, t_user.`password`, m_pegawai.id AS 'idPegawai', m_pegawai.nik, m_pegawai.idcabang, m_pegawai.nip, m_pegawai.jabatan, m_pegawai.nama, m_pegawai.alamat, m_pegawai.email, m_pegawai.telp");
                $userModel->join('m_pegawai', 'm_pegawai.id = t_user.idprofile');  
                $userModel->join('m_cabang', 'm_cabang.id = m_pegawai.idcabang');  
                $userModel->join('m_role', 'm_role.id = t_user.idrole');  
                $userModel->join('m_user_type', 'm_user_type.id = t_user.usertype');  
                $arrData =  $userModel->where('t_user.id',$id)->first(); 

                $page = [
                    'page_title'    => 'View Data User',
                    'arrUser'       => $arrData, 
                    'idUser'        => encrypt_id($arrData->idUser), 
                ];

                $param['content'] = $this->render('master.user.view', $page);
            }
  
            $param['addJS'] = '<script src="' . base_url() . '/js/master/user.js?v='.date('YmdHis').'"></script>'; 

            return $this->render('layout.template', $param);
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!'.$e->getMessage());
            return $this->response->setJSON($resp);
        }
    }

    // 2024-02-26  
    public function change_status()
    {
        try {  
            $idUser     = decrypt_id($this->request->getPost('idUser'));
            $status     = $this->request->getPost('status'); 
 
            $dokModel = model('t_user');
            $dokModel->select("t_user.id AS 'idUser', t_user.username, t_user.`password`, t_user.isActive,
                m_perusahaan.bentukPersh, m_perusahaan.nama, m_perusahaan.picNama, m_perusahaan.email, m_perusahaan.picEmail");
          
            $dokModel->join('m_role', 'm_role.id = t_user.idrole');  
            $dokModel->join('m_user_type', 'm_user_type.id = t_user.usertype');    
            $dokModel->join('m_perusahaan', 'm_perusahaan.id = t_user.idProfile', 'left');   

            $dataUser =  $dokModel->where('t_user.id', $idUser)->first(); 
             
            if(!empty($idUser)){ 
                $userModel  = model('t_user'); 
                $arrUser    = []; 

                if($status == '1'){
                    $arrUser['isActive']      = 'N';
                } else {
                    $arrUser['isActive']      = 'Y';
                }

                if ($userModel->update(['id' => $idUser], $arrUser) === false) { 
                    $arrError = $userModel->errors();
                    
                    foreach($arrError as $x => $val) { 
                        $errorText .= $val;
                    }

                    $resp = resp_error("-- ".$errorText); 
                } else { 
                    $respData['id'] = $userModel->insertID(); 

                    if($status !== '3'){
                        $resp = resp_success('Status user berhasil dirubah', $respData); 
                    } else {
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