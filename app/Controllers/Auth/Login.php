<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;


class Login extends BaseController
{
    public function index(){ 
        $key    = hash('sha256', \CodeIgniter\Encryption\Encryption::createKey()); 
        $page   = [
                    'table_title'       => '', 
                  ];

        $param['content'] = $this->render('auth.login', $page); 
        
        $param['addJS'] = '<script src="https://cdn.jsdelivr.net/gh/mgalante/jquery.redirect@master/jquery.redirect.js"></script>';
        $param['addJS'] .= '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>'; 

        $this->session->setTempdata('sess_login_token', $key, 1800);

        return $this->render('layout.template-auth', $param); 
    }

    public function registrasi(){    
        $param['content'] = $this->render('auth.registrasi');             
        $param['addJS'] = '<script src="' . base_url() . '/assets/plugins/select2/select2.full.min.js?v='.date('YmdHis').'"></script>';
        $param['addJS'] .= '<script src="' . base_url() . '/assets/plugins/bootstrap-datepicker/bootstrap-datepicker.js?v='.date('YmdHis').'"></script>'; 
        $param['addJS'] .= '<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.inputmask/5.0.7/jquery.inputmask.min.js" integrity="sha512-jTgBq4+dMYh73dquskmUFEgMY5mptcbqSw2rmhOZZSJjZbD2wMt0H5nhqWtleVkyBEjmzid5nyERPSNBafG4GQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>'; 
        $param['addJS'] .= '<script src="https://cdn.jsdelivr.net/gh/mgalante/jquery.redirect@master/jquery.redirect.js"></script>'; 
        $param['addJS'] .= '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>'; 
        $param['addJS'] .= '<script src="' . base_url() . '/js/global/init.js?v='.date('YmdHis').'"></script>';

        return $this->render('layout.template-auth', $param); 
    } 

    public function login_act()
    {
        try {
            $postdata           = $this->request->getPost();
            $uname              = $postdata['uname'];
            $upass              = $postdata['upass'];
            $ucaptcha           = $postdata['ucaptcha'];
            $sess_login_token   = session('sess_login_token');
            $sess_captcha       = session('sess_captcha');
 
            if(empty(session('sess_login_failed')))
                $loginFailed    = 0;
            else
                $loginFailed    = session('sess_login_failed');

            if(($uname == "") || ($upass == "") || ($ucaptcha == "")){
                $empty_note     = "";

                if($uname == ""){
                    $empty_note     .= "- Username belum diisi<br>";
                }

                if($upass == ""){
                    $empty_note     .= "- Password belum diisi<br>";
                }

                if($ucaptcha == ""){
                    $empty_note     .= "- Captcha belum diisi<br>";
                }

                $loginFailed        +=1;
                $this->session->setTempdata('sess_login_failed', $loginFailed, 30);
                $respData['failed'] = $loginFailed;
                $resp               = resp_error($empty_note, $respData);
            } else {   
                if(empty($sess_login_token)){
                    $resp           = resp_error('Sesi kadaluarsa. Silahkan muat ulang halaman ini');
                }
                else if($loginFailed > 5){
                    $resp           = resp_error('Terlalu banyak kesalahan login.<br>Silahkan tunggu 5 menit untuk mencoba kembali');
                }
                else{  
                    if($sess_captcha == $ucaptcha)
                    {  
                        $user       = model('t_user')->where('username', $uname)->first();
                        
                        if(empty($user->id) || $user->password != hash_pass($upass)){
                            $loginFailed        +=1;
                            $this->session->setTempdata('sess_login_failed', $loginFailed, 30);
                            $respData['failed'] = $loginFailed;
                            $resp               = resp_error('Username / password tidak valid',$respData);
                        } else { 
                            if($user->isActive == 'Y'){ 
                                $roleInternal   = [3,4,6,7]; 
                                $homePage       = base_url().'beranda/internal';

                                if(in_array($user->idrole, $roleInternal)){
                                    $pegawai              = model('t_pegawai')->where('id',$user->idprofile)->first();
                                    $sess['sess_branch']  = $pegawai->idcabang;
                                    $sess['sess_nama']    = $pegawai->nama;
                                    $sess['sess_jabatan'] = $pegawai->jabatan;
                                    $this->session->set($sess);
                                    $homePage             = base_url().'beranda/internal';
                                }
                                else if($user->idrole == '10'){
                                    $homePage             = base_url().'beranda/client';
                                }
                                else if($user->idrole == '1'){
                                    $homePage             = base_url().'beranda/admin';
                                }

                                $sess = [
                                    'sess_userid'   => encrypt_id($user->id),
                                    'sess_username'  => $user->username,
                                    'sess_usertype'  => $user->usertype,
                                    'sess_role'      => $user->idrole,
                                    'sess_home_url'  => $homePage,
                                    'sess_loggedIn'  => 'Y',
                                ];
                                $this->session->set($sess);

                                $loginLog = [
                                    'userId'        => $user->id,
                                    'userRole'      => $user->idrole,
                                    'userType'      => $user->usertype,
                                    'username'      => $uname,
                                    'action'        => 'login',
                                    'logTime'       => date('Y-m-d H:i:s'),
                                    'ipAddress'     => $this->request->getIPAddress(),
                                    'loginFailed'   => $loginFailed,
                                    'loginSession'  => json_encode($this->session->get())
                                ];
                                model('t_loginLog')->save($loginLog);

                                $respData['to'] = $homePage;
                                $resp           = resp_success('Login berhasil',$respData); 
                            } else { 
                                $loginFailed        +=1;
                                $this->session->setTempdata('sess_login_failed', $loginFailed, 30);
                                $respData['failed'] = $loginFailed;
                                $resp               = resp_error('Hak akses anda tidak aktif! Silahkan hubungi admin', $respData);
                            } 
                        }
                    } else {
                        $loginFailed        +=1;
                        $this->session->setTempdata('sess_login_failed', $loginFailed, 30);
                        $respData['failed'] = $loginFailed;
                        $resp               = resp_error('Captcha tidak sesuai', $respData);
                    }
                }
            }

            return $this->response->setJSON($resp);   
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!'.$e->getMessage());
            return $this->response->setJSON($resp);
        }
    }

    public function logout()
    {
        $loginLog = [
            'userId'        => session('sess_userid')??0,
            'userRole'      => session('sess_role')??0,
            'userType'      => session('sess_usertype')??0,
            'username'      => session('sess_username')??0,
            'action'        => 'logout',
            'logTime'       => date('Y-m-d H:i:s'),
            'ipAddress'     => $this->request->getIPAddress(),
            'loginFailed'   => NULL,
            'loginSession'  => json_encode($this->session->get())
        ];
        model('t_loginLog')->save($loginLog);
        session()->destroy();
        return redirect()->to(base_url());
        exit;
    }

	public function get_captcha()
	{
		$cap    = captcha_login('sess_captcha');
		$image  = $cap['image']; 
        
        $resp = resp_success($image);
        return $this->response->setJSON($resp);
	}

    // 2024-02-22  
    public function save_registrasi_user(){   
        try {       
            $idUser     = $this->request->getPost('idUser') ? decrypt_id($this->request->getPost('idUser')): ''; 
            $postdata   = $this->request->getPost('postdata');
            $data       = post_ajax_toarray($postdata);  
            
            $sess_captcha           = session('sess_captcha');
            $captcha                = $data['ucaptcha'];

            die($sess_captcha." == ".$captcha);

            if($sess_captcha == $captcha){
                $arrPegawai = [];  
                $arrPegawai['nik']      = $data['nik'];
                $arrPegawai['idcabang'] = '111';
                $arrPegawai['nip']      = $data['nip'];
                $arrPegawai['jabatan']  = $data['jabatan']; 
                $arrPegawai['nama']     = $data['nama'];
                $arrPegawai['alamat']   = $data['alamat'];
                $arrPegawai['telp']     = $data['telp'];
                $arrPegawai['email']    = $data['email']; 
                $arrPegawai['idPersh']  = $data['idPersh']; 
                
                if(!empty($idPegawai)){ 
                    $arrPegawai['id'] = $idPegawai;
                } 
                
                $arrUser = [];
           
                $arrUser['idrole']      = 10;
                $arrUser['usertype']    = 1;
                $arrUser['username']    = $data['username'];
                $arrUser['isActive']    = 'W';
                $arrUser['idprofile']   = $data['idPersh']; 
                
                if($data['password'] !== NULL){ 
                    $password           = str_replace(" ","", $data['password']);
                    $arrUser['password'] = hash_pass($password); 
                }
                    
                $errMandatoryPegawai= cek_mandatory($arrPegawai, 'PEGAWAI');
                $errMandatoryUser   = cek_mandatory($arrUser, 'USER'); 
                $errMandatory       = array_merge($errMandatoryPegawai,$errMandatoryUser);
                $errorText          = '';
            
                if(count($errMandatory) == 0)  {

                    $arrDuplikasi              = array();
                    $arrDuplikasi['username']  = $arrUser['username']; 
                    $errDuplikasi              = $this->cek_duplikasi_user($arrDuplikasi, 't_user', $idUser);
    
                    if($errDuplikasi == '')  { 
                        $pegawaiModel = model('t_pegawai'); 
                        $pegawaiModel->upsert($arrPegawai); 
                        $idInsert     = $pegawaiModel->insertID() ? $pegawaiModel->insertID() : $arrPegawai['id'];  

                        if(!empty($idInsert)) {  
                            $arrUser['idprofile'] = $idInsert; 
                            $userModel = model('t_user');
    
                            if ($userModel->insert($arrUser) === false) { 
                                $arrError = $userModel->errors();
                                
                                foreach($arrError as $x => $val) { 
                                    $errorText .= $val;
                                }

                                $resp = resp_error("- ".$errorText);
                            } else { 
                                $respData['id'] = $userModel->insertID(); 
                                $resp = resp_success('Data user berhasil disimpan.<br> Silahkan tunggu email aktivasi hak akses anda', $respData); 
                            }  
                        } else { 
                            $resp = resp_error('Gagal menyimpan data pegawai');
                        } 
                    } else { 
                        $resp = resp_error($errDuplikasi); 
                    }
                } else { 
                    $textErr = implode('<br> - ', $errMandatory);
                    $resp = resp_error('Perhatikan isian berikut <br>'.$textErr);
                } 
            } else {
                $resp = resp_error('Captcha tidak sesuai <br>');
            } 
            return $this->response->setJSON($resp);

        } catch (\Throwable $e) { 
            $resp = resp_error('An Exception has occured!'.$e->getMessage());
            return $this->response->setJSON($resp);
        }
    } 
     
    public function save_registrasi(){   
        try {       
            // $idUser         = $this->request->getPost('idUser') ? decrypt_id($this->request->getPost('idUser')): ''; 
            $idUser         = ''; 
            $postdata       = $this->request->getPost('postdata');
            $data           = post_ajax_toarray($postdata);  
            $sess_captcha   = session('sess_captcha');
            $captcha        = $data['ucaptcha'];

            if($captcha == $sess_captcha){
                $arrPerusahaan                  = $data;  
                $arrPerusahaan['npwp']          = clean_npwp($data['npwp']);  
                $arrPerusahaan['jenisIUP']      = $this->request->getPost('jenisIUP'); 
                $arrPerusahaan['isActive']      = 'W';
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

                unset($arrPerusahaan['ucaptcha']);
                unset($arrPerusahaan['username']);
                unset($arrPerusahaan['password']);

                $arrUser = []; 
                $arrUser['idrole']      = 10;
                $arrUser['usertype']    = 1;
                $arrUser['username']    = $data['username'];
                $arrUser['isActive']    = 'W'; 
                
    
                $errMandatoryPerus  = cek_mandatory($arrPerusahaan, 'CLIENT');
                $errMandatoryUser   = cek_mandatory($arrUser, 'USER'); 
                $errMandatory       = array_merge($errMandatoryPerus,$errMandatoryUser);
                $errorText          = '';
   
                if($data['password'] !== NULL){
                    $password           = str_replace(" ","", $data['password']);
                    $arrUser['password'] = hash_pass($password); 
                }

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
                        'password'   	=> [
                            'rules' 	=> [
                                'required', 'min_length[5]',
                            ],
                            'errors' => [
                                'required'      => 'Password kosong',
                                'min_length'    => 'Password minimal 5 Karakter', 
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
                            $cekData               = model('tx_perusahaan')->where('npwp', $arrPerusahaan['npwp'])->first(); 
                            $statCek               = 1; 
                            
                            if(!empty($cekData)){
                                $idPersh           = $cekData->id; 
                                $cekUser           = model('t_user')->where('idprofile', $arrPerusahaan['npwp'])->first();
 
                                $dokModel = model('t_user');
                                $dokModel->select("t_user.id AS 'idUser', t_user.isActive, m_perusahaan.bentukPersh, m_perusahaan.nama, m_perusahaan.npwp, m_perusahaan.email, m_perusahaan.picEmail");    
                                $dokModel->join('m_perusahaan', 'm_perusahaan.id = t_user.idProfile'); 
                                $dataUser =  $dokModel->where('m_perusahaan.id', $idPersh)->first(); 
                
                                $idUser = $dataUser ? $dataUser->idUser : '';
                          
                                if(!empty($idUser)){
                                    $statCek = 0;
                                    $resp    = resp_error('NPWP nomor '.formatNPWP($dataUser->npwp).' a.n '.$dataUser->bentukPersh.' '.$dataUser->nama.' statusnya sudah terdaftar.'); 
                                } else {
                                    $statCek = 1; 
                                } 
                            }  
             
                            if($statCek == 1){
                                $perusModel = model('tx_perusahaan'); 
                                $perusModel->upsert($arrPerusahaan);
                
                                if(!empty($perusModel->insertID()))
                                    $idProfile = $perusModel->insertID();
                                else
                                    $idProfile = $idPersh;
                 
                                if(!empty($idProfile)){ 
                                    $arrUser['idprofile'] = $idProfile; 
                                    $userModel            = model('t_user');

                                    if ($userModel->insert($arrUser) === false) { 
                                        $arrError = $userModel->errors();
                                        
                                        foreach($arrError as $x => $val) { 
                                            $errorText .= $val;
                                        }

                                        $resp = resp_error("- ".$errorText);
                                    } else {   
                                        $fileNPWP = $this->request->getFile('fileNPWP');
                                        $fileNIB  = $this->request->getFile('fileNIB');
                                        $fileKTP  = $this->request->getFile('fileKTP');
                        
                                        $status_upload_NPWP = 0;
                                        $status_upload_NIB = 0;
                                        $status_upload_KTP = 0;

                                        if($fileNPWP){
                                            $validationRule = [
                                                'fileNPWP' => [
                                                    'rules' => [
                                                        'uploaded[fileNPWP]',
                                                        'mime_in[fileNPWP,image/jpg,image/jpeg,image/png,image/webp,application/pdf]',
                                                        'max_size[fileNPWP,5120]',
                                                    ],
                                                    'errors' => [
                                                        'uploaded' => 'File belum di pilih',
                                                        'mime_in' => 'File yang diupload harus berupa image atau pdf dengan format jpg/png/pdf',
                                                        'max_size' => 'Ukuran file maksimal 5 MB'
                                                    ]
                                                ],
                                            ];
                        
                                            if (! $this->validate($validationRule)) {
                                                $resp = resp_error($this->validator->getError(),'','Gagal upload file NPWP!');
                                                return $this->response->setJSON($resp);
                                            } else { 
                                                if (! $fileNPWP->hasMoved()) { 
                                                    $pathFile                  = $fileNPWP->store('pendukung/'.date('Ymd'));
                                                    $dataUpl['pathFile']       = $pathFile;
                                                    $dataUpl['idPersh']        = $idProfile;
                                                    $dataUpl['npwp']           = $arrPerusahaan['npwp'];
                                                    $dataUpl['idJenisDok']     = '18';
                                                    $dataUpl['jenisDok']       = 'NPWP';
                                                    $dataUpl['noDokumen']      = $arrPerusahaan['npwp']; 
                                                    $dataUpl['tglDokumen']     = '0000-00-00';
                                                    $dataUpl['tglAkhirDokumen']= '0000-00-00';
                                                    $dataUpl['negaraPenerbit'] = 'ID';
                                                    
                        
                                                    $dokModel                   = model('t_dokpersh')->builder();
                                                    $dokModel->insert($dataUpl); 
                                                    
                                                    $status_upload_NPWP = 1;
                                                    
                                                } else {
                                                    // throw new \Exception('Gagal upload file NPWP!!');
                                                    $resp = resp_error($this->validator->getError(),'','Gagal upload file NPWP!!');
                                                    return $this->response->setJSON($resp);
                                                }
                                            }
                                        }
 
                                        if($fileNIB){
                                            $validationRule = [
                                                'fileNIB' => [
                                                    'rules' => [
                                                        'uploaded[fileNIB]',
                                                        'mime_in[fileNIB,image/jpg,image/jpeg,image/png,image/webp,application/pdf]',
                                                        'max_size[fileNIB,5120]',
                                                    ],
                                                    'errors' => [
                                                        'uploaded' => 'File belum di pilih',
                                                        'mime_in' => 'File yang diupload harus berupa image atau pdf dengan format jpg/png/pdf',
                                                        'max_size' => 'Ukuran file maksimal 5 MB'
                                                    ]
                                                ],
                                            ];
                        
                                            if (! $this->validate($validationRule)) {
                                                $resp = resp_error($this->validator->getError(),'','Gagal upload file NIB!');
                                                return $this->response->setJSON($resp);
                                            } else { 
                                                if (! $fileNIB->hasMoved()) { 
                                                    $pathFile                  = $fileNIB->store('pendukung/'.date('Ymd'));
                                                    $dataUpl['pathFile']       = $pathFile;
                                                    $dataUpl['idPersh']        = $idProfile;
                                                    $dataUpl['npwp']           = $arrPerusahaan['npwp'];
                                                    $dataUpl['idJenisDok']     = '18';
                                                    $dataUpl['jenisDok']       = 'NIB';
                                                    $dataUpl['noDokumen']      = $arrPerusahaan['nib']; 
                                                    $dataUpl['tglDokumen']     = '0000-00-00';
                                                    $dataUpl['tglAkhirDokumen']= '0000-00-00';
                                                    $dataUpl['negaraPenerbit'] = 'ID';
                                                     
                                                    $dokModel                   = model('t_dokpersh')->builder();
                                                    $dokModel->insert($dataUpl); 
                                                    
                                                    $status_upload_NIB = 1;
                                                    
                                                } else {
                                                    // throw new \Exception('Gagal upload file NIB!!');
                                                    $resp = resp_error($this->validator->getError(),'','Gagal upload file NIB!!');
                                                    return $this->response->setJSON($resp);
                                                }
                                            }
                                        }

                                        if($fileKTP){
                                            $validationRule = [
                                                'fileKTP' => [
                                                    'rules' => [
                                                        'uploaded[fileKTP]',
                                                        'mime_in[fileKTP,image/jpg,image/jpeg,image/png,image/webp,application/pdf]',
                                                        'max_size[fileKTP,5120]',
                                                    ],
                                                    'errors' => [
                                                        'uploaded' => 'File belum di pilih',
                                                        'mime_in' => 'File yang diupload harus berupa image atau pdf dengan format jpg/png/pdf',
                                                        'max_size' => 'Ukuran file maksimal 5 MB'
                                                    ]
                                                ],
                                            ];
                        
                                            if (! $this->validate($validationRule)) {
                                                $resp = resp_error($this->validator->getError(),'','Gagal upload file KTP!');
                                                return $this->response->setJSON($resp);
                                            } else { 
                                                if (! $fileKTP->hasMoved()) { 
                                                    $pathFile                  = $fileKTP->store('pendukung/'.date('Ymd'));
                                                    $dataUpl['pathFile']       = $pathFile;
                                                    $dataUpl['idPersh']        = $idProfile;
                                                    $dataUpl['npwp']           = $arrPerusahaan['npwp'];
                                                    $dataUpl['idJenisDok']     = '18';
                                                    $dataUpl['jenisDok']       = 'KTP';
                                                    $dataUpl['noDokumen']      = ''; 
                                                    $dataUpl['tglDokumen']     = '0000-00-00';
                                                    $dataUpl['tglAkhirDokumen']= '0000-00-00';
                                                    $dataUpl['negaraPenerbit'] = 'ID';
                                                     
                                                    $dokModel                   = model('t_dokpersh')->builder();
                                                    $dokModel->insert($dataUpl); 
                                                    
                                                    $status_upload_KTP = 1;
                                                    
                                                } else {
                                                    // throw new \Exception('Gagal upload file KTP!!');
                                                    $resp = resp_error($this->validator->getError(),'','Gagal upload file KTP!!');
                                                    return $this->response->setJSON($resp);
                                                }
                                            }
                                        }

                                        if($status_upload_NPWP == 1 && $status_upload_NIB == 1 && $status_upload_KTP == 1) {
                                            $respData['id'] = $userModel->insertID(); 
                                            $resp = resp_success('Data user berhasil disimpan.<br> Silahkan tunggu email aktivasi hak akses anda', $respData); 
                                        } 
                                     }  
                                } else {
                                    $resp = resp_error('Gagal menyimpan data perusahaan.'.$idProfile);
                                } 
                            }
                        } else {
                            $resp = resp_error($errDuplikasi); 
                        }
                    }
                } else { 
                    $textErr = implode('<br> - ', $errMandatory);
                        $resp = resp_error('Perhatikan catatan berikut : <br>- '.$textErr);
                }

            } else if(empty($captcha)) {
                $resp = resp_error('Captcha kosong <br>');
            } else {
                $resp = resp_error('Captcha tidak sesuai <br>');
            }

            return $this->response->setJSON($resp);  

        } catch (\Throwable $e) { 
            $resp = resp_error('An Exception has occured!'.$e->getMessage());
            return $this->response->setJSON($resp);
        }
    } 
       
	public function forgot_password()
	{ 
        $param['content'] = $this->render('auth.reset-password');   
        $param['addJS'] = '<script src="' . base_url() . '/js/auth/login.js?v='.date('YmdHis').'"></script>';  
        $param['addJS'] .= '<script src="' . base_url() . '/assets/plugins/select2/select2.full.min.js?v='.date('YmdHis').'"></script>';
        $param['addJS'] .= '<script src="https://cdn.jsdelivr.net/gh/mgalante/jquery.redirect@master/jquery.redirect.js"></script>'; 
        $param['addJS'] .= '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>'; 

        return $this->render('layout.template-auth', $param); 
    }
}

?>