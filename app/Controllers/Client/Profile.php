<?php

namespace App\Controllers\Client;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;


class Profile extends BaseController
{   
    function __construct(){
        if(session()->get('sess_role') != 10)
        {
            // exit('Forbidden');
        }
    }
    
    public function index()
    {
        try {
            $id         = decrypt_id(session()->get('sess_userid')); 
            $userModel  = model('t_user');
            $usertype   = $userModel->where('id', $id)->first()->usertype;
  
            if($usertype == 1){ 
                $userModel->select("t_user.id AS 'idUser', t_user.idrole, m_role.role, t_user.usertype, m_user_type.uraian AS 'type', t_user.username, t_user.`password`, t_user.isActive as 'isActiveUser', m_perusahaan.*");    
                $userModel->join('m_role', 'm_role.id = t_user.idrole');  
                $userModel->join('m_user_type', 'm_user_type.id = t_user.usertype');     
                $userModel->join('m_perusahaan', 'm_perusahaan.id = t_user.idprofile', 'left');  
                $arrData =  $userModel->where('t_user.id', $id)->first(); 
                
                $page = [
                    'page_title'        => 'View Profile',
                    'breadcrumb_active' => 'View Profile',
                    'arrProfile'        => $arrData, 
                    'idUser'            => encrypt_id($arrData->idUser), 
                ];

                $param['content']   = $this->render('client.profile.view-pu', $page); 
            } else { 
                $userModel->select("t_user.id AS 'idUser', t_user.idrole,t_user.isActive, m_role.role, m_cabang.cabang, t_user.usertype, m_user_type.uraian AS 'type', t_user.username, t_user.`password`, m_pegawai.id AS 'idPegawai', m_pegawai.nik, m_pegawai.idcabang, m_pegawai.nip, m_pegawai.jabatan, m_pegawai.nama, m_pegawai.alamat, m_pegawai.email, m_pegawai.telp");
                $userModel->join('m_pegawai', 'm_pegawai.id = t_user.idprofile');  
                $userModel->join('m_cabang', 'm_cabang.id = m_pegawai.idcabang');  
                $userModel->join('m_role', 'm_role.id = t_user.idrole');  
                $userModel->join('m_user_type', 'm_user_type.id = t_user.usertype');  
                $arrData =  $userModel->where('t_user.id', $id)->first(); 
                
                $page = [
                    'page_title'        => 'View Profile',
                    'breadcrumb_active' => 'View Profile',
                    'arrProfile'        => $arrData, 
                    'idUser'            => encrypt_id($arrData->idUser), 
                ];

                $param['content']   = $this->render('client.profile.view', $page); 
            }
  
            $param['addJS']     = '<script src="' . base_url() . '/js/client/profile.js?v='.date('YmdHis').'"></script>'; 

            return $this->render('layout.template', $param);
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!'.$e->getMessage());
            return $this->response->setJSON($resp);
        }
    }

    public function edit()
    {
        try {
            $id         = decrypt_id(session()->get('sess_userid')); 
            $userModel  = model('t_user');
            $usertype   = $userModel->where('id', $id)->first()->usertype;
  
            if($usertype == 1){ 
                $userModel->select("t_user.id AS 'idUser', t_user.idrole, m_role.role, t_user.usertype, m_user_type.uraian AS 'type', t_user.username, t_user.`password`, t_user.isActive as 'isActiveUser', m_perusahaan.*");    
                $userModel->join('m_role', 'm_role.id = t_user.idrole');  
                $userModel->join('m_user_type', 'm_user_type.id = t_user.usertype');     
                $userModel->join('m_perusahaan', 'm_perusahaan.id = t_user.idprofile', 'left');  
                $arrData =  $userModel->where('t_user.id', $id)->first(); 
                
                $page = [
                    'page_title'            => 'Edit Profile',
                    'breadcrumb_active'     => 'Edit Profile',
                    'arrProfile'            => $arrData, 
                    'idUser'                => encrypt_id($arrData->idUser), 
                ];

                $param['content']   = $this->render('client.profile.create-pu', $page); 
            } else { 
                $userModel->select("t_user.id AS 'idUser', t_user.idrole,t_user.isActive, m_role.role, m_cabang.cabang, t_user.usertype, m_user_type.uraian AS 'type', t_user.username, t_user.`password`, m_pegawai.id AS 'idPegawai', m_pegawai.nik, m_pegawai.idcabang, m_pegawai.nip, m_pegawai.jabatan, m_pegawai.nama, m_pegawai.alamat, m_pegawai.email, m_pegawai.telp");
                $userModel->join('m_pegawai', 'm_pegawai.id = t_user.idprofile');  
                $userModel->join('m_cabang', 'm_cabang.id = m_pegawai.idcabang');  
                $userModel->join('m_role', 'm_role.id = t_user.idrole');  
                $userModel->join('m_user_type', 'm_user_type.id = t_user.usertype');  
                $arrData =  $userModel->where('t_user.id',$id)->first(); 
                
                $page = [
                    'page_title'        => 'Edit Profile',
                    'breadcrumb_active' => 'Edit Profile',
                    'arrProfile'        => $arrData, 
                    'idUser'            => encrypt_id($arrData->idUser), 
                ];

                $param['content']   = $this->render('client.profile.create', $page);  
            }
    
            $param['addJS']     = '<script src="' . base_url() . '/js/client/profile.js?v='.date('YmdHis').'"></script>'; 

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
           
            $arrUser['idrole']      = 100;
            $arrUser['usertype']    = 100;
            $arrUser['username']    = $data['username'];
            $arrUser['isActive']    = 'Y';
             
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
                    unset($arrUser['idrole']);
                    unset($arrUser['usertype']);
                    unset($arrUser['isActive']);

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

    public function savepu(){   
        try {       
            $idProfile      = $this->request->getPost('idProfile') ? decrypt_id($this->request->getPost('idProfile')): ''; 
            $idUser         = $this->request->getPost('idUser') ? decrypt_id($this->request->getPost('idUser')): '';
            $postdata       = $this->request->getPost('postdata');
            $data           = post_ajax_toarray($postdata); 
 
            $arrPerusahaan                  = $data;  
            $arrPerusahaan['id']            = $idProfile;
            $arrPerusahaan['npwp']          = clean_npwp($data['npwp']);  
            $arrPerusahaan['jenisIUP']      = $this->request->getPost('jenisIUP');
            $arrPerusahaan['tglIUP']        = reverseDateDB($arrPerusahaan['tglIUP']); 
            
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
                    $arrDuplikasi              = array();
                    $arrDuplikasi['username']  = $arrUser['username']; 
                    $errDuplikasi              = cek_duplikasi_user($arrDuplikasi, 't_user', $idUser);
        
                    if($errDuplikasi == '')  {      
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
}
?>