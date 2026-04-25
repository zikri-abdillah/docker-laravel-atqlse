<?php

namespace App\Controllers\Resources;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\ResponseInterface;

class Dokpersh extends ResourceController
{
    public function view()
    {
        try{
            $model      = model('App\Models\t_dokpersh');
            $idRef      = decrypt_id($this->request->getPost('idRef'));
            $dokpersh   = model('t_dokpersh')->where('id',$idRef)->first();
            $path       = WRITEPATH.'uploads/'.$dokpersh->pathFile;
            return $this->response->download($path,null,true)->inline();
        } catch (\CodeIgniter\Files\Exceptions\FileNotFoundException $e) {
            return redirect()->to('filenotfound');
        } catch (\Throwable $e) {
            $resp = 'An Exception has occured at Resources Dokpersh';
            return $this->response->setJSON($resp);
        }
    }
 
    public function delete($id = NULL)
    {
        try{ 
            $modelDok   = model('t_dokpersh'); 
            $idDok      = decrypt_id($this->request->getPost('idDok')); 
            $withdata   = $this->request->getPost('withdata'); 
            $dokpersh   = $modelDok->where('id', $idDok)->first();
 
            if($dokpersh->id !== NULL && $dokpersh->id !== ''){  
                if($modelDok->delete($idDok)){
                    $data               = [];
                    // if($withdata){
                    //     $data['html']   = $this->list($dokpersh->npwp);
                    // }
                    $resp               = resp_success('Dokumen berhasil dihapus',$data);
                } else {
                    $resp               = resp_error('Dokumen gagal dihapus');
                } 
            } else { 
                $resp   = resp_error('Tidak ada dokumen terkait');
            }
 
            return $this->response->setJSON($resp);
        } catch (\Throwable $e) {
            dd($e);
            $resp = 'An Exception has occured at Resources Dokpersh';
            return $this->response->setJSON($resp);
        }
    }

    public function get_list()
    {
        try{
            $npwp       = clean_npwp($this->request->getPost('npwp'));

            if($this->request->getPost('idLs'))
            {
                $idLs       = decrypt_id($this->request->getPost('idLs'));
                $dokModel   = model('tx_lseReferensi')->select('idDokPersh')->where('idLs',$idLs)->findAll();
                $seleted    = obj_flatten($dokModel,'idDokPersh'); 
                $this->list($npwp, true, $seleted);
            }

            // $resp = resp_success('Success',$data);
            // return $this->response->setJSON($resp);
        } catch (\Throwable $e) {
            //dd($e);
            $resp = 'An Exception has occured at Resources Dokpersh';
            return $this->response->setJSON($resp);
        }
    }
 
    public function list($npwp, $showcbx = null, $checked = null)
    {
        $search     = $this->request->getPost('search');  
        $dokModel   = model('t_dokpersh'); 
        $dokModel->select("t_dokpersh.id, t_dokpersh.idJenisDok, t_dokpersh.jenisDok, t_dokpersh.noDokumen, t_dokpersh.tglDokumen, t_dokpersh.tglAkhirDokumen, t_dokpersh.pathFile, t_dokpersh.negaraPenerbit, CONCAT(m_negara.kode, ' - ',m_negara.nama) AS 'nama_negara'");
        $dokModel->join('m_negara', 't_dokpersh.negaraPenerbit = m_negara.kode');  
        $dokModel->where('t_dokpersh.npwp', $npwp);
        $dokModel->groupStart();
        $dokModel->where('t_dokpersh.tglAkhirDokumen >=', date('Y-m-d'))->orwhere('t_dokpersh.tglAkhirDokumen=', '0000-00-00');
        $dokModel->groupEnd();

        if(!empty($search['value'])){ 
            $dokModel->like('t_dokpersh.jenisDok', $search['value']);
            $dokModel->orLike('t_dokpersh.noDokumen', $search['value']);
        }

        $recordsTotal   = $dokModel->countAllResults(false); 
        $arrData        = $dokModel->orderBy(' t_dokpersh.id ', 'DESC')->findAll($this->request->getPost('length'), $this->request->getPost('start'));
        $html           = '';
        $cbx            = ''; 
        $idCheckbox     = '';
        $idAll          = '';
        $row            = [];

        foreach ($checked as $key){
            $idCheckbox .=  encrypt_id($key).',';
        }

        foreach ($arrData as $key => $data) {  
            $modelRef       = model('tx_lseReferensi');
            $modelRef->select('id');  
            $dataRef        = $modelRef->where('idDokPersh', $data->id);
            $banyak_data    = $dataRef->countAllResults(false); 
            $dataRef        = $dataRef->findAll();
            $idAll          .= encrypt_id($data->id).",";
			$btnView        = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-info" onclick="view_dok_persh(\''.encrypt_id($data->id).'\')" title="Lihat File"><i class="fa fa-eye"></i></button>'; 
			$btnEdit        = "";
            $btnDelete      = "";
 
            if($showcbx){
                $isChecked = '';
                if(in_array($data->id,$checked))
                    $isChecked = 'checked';

                $cbx = '<label class="ckbox" for="ckbox-dok_'.$key.'"><input '.$isChecked.' type="checkbox" class="ckbox-dok" data-iddata="'.encrypt_id($data->id).'" id="ckbox-dok_'.$key.'" onclick="ckbox_dok(\''.$key.'\')"><span></span></label>';
            }
            
            if($banyak_data == 0){
                $btnEdit    = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-warning me-1" onclick="edit_dok_persh(\''.encrypt_id($data->id).'\')" title="Edit"><i class="fa fa-edit"></i></button>'; 
                $btnDelete      = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-danger me-1" onclick="del_dok_persh(\''.encrypt_id($data->id).'\')" title="Hapus"><i class="fa fa-trash"></i></button> '; 
            }
 
            $columns    = [];
            $columns[]  = $key+1;
            $columns[]  = $cbx;
            $columns[]  = $data->jenisDok;
            $columns[]  =  $data->nama_negara; 
            $columns[]  =  $data->noDokumen; 
            $columns[]  =  reverseDate($data->tglDokumen); 
            $columns[]  =  reverseDate($data->tglAkhirDokumen); 
            $columns[]  =  $btnEdit.$btnDelete.$btnView;  
            $row[]      = $columns;
        }

        $table['draw']              = $this->request->getPost('draw');
        $table['recordsTotal']      = $recordsTotal;
        $table['recordsFiltered']   = $recordsTotal;
        $table['data']              = $row;
        $table['idCheckbox']        = $idCheckbox;
        $table['idAll']             = $idAll;
         
        echo json_encode($table);
    }

    public function check_dok()
    { 
        $status      = $this->request->getPost('status');
        $idDok       = $this->request->getPost('idDok') ? decrypt_id($this->request->getPost('idDok')) : '';
        $idChecked   = $this->request->getPost('idChecked');
        $arrChecked  = explode(",", $idChecked);
        $string      = '';
        $id          = '';
 
        if($status == 'cek'){
            $postdata   = array_map('decrypt_id', $arrChecked); 

            if(in_array($idDok, $postdata)){
                echo 1;
            } else { 
                echo 0;
            }

        } else {
            if($idChecked !== ''){ 
                $arrChecked   = array_map('decrypt_id', $arrChecked); 
 
                if($status == 'hapus'){
                    $var = (array)$idDok;
                    $arrChecked   = array_diff($arrChecked, $var); 
                }
 
                if($status == 'tambah'){ 
                    array_push($arrChecked, $idDok); 
                }
  
                foreach ($arrChecked as $key => $val) {
                    if($val !== ''){
                        $id .= encrypt_id($val).',';   
                    }
                }   

                // foreach ($arrChecked as $key => $val) {
                //     $string .= decrypt_id($val).',';   
                // } 
    
                // if($status == 'hapus'){
                //     $string = str_replace($idDok.",", "", $string);
                // }
                
                // if($status == 'tambah'){
                //     $string = $string.$idDok.",";
                // }
      
                // $arrString  = explode(",", $string);
    
                // foreach ($arrString as $key => $val) {
                //     if($val !== ''){
                //         $id .= encrypt_id($val).',';   
                //     }
                // }  
            } else {
                $id     = encrypt_id($idDok).','; 
            }
     
            echo json_encode($id); 
        }
       
    }

    public function list_old($npwp, $showcbx = null, $checked = null)
    {
        $jenisDok   = $this->request->getPost('idJenisDok');
        $noDok      = $this->request->getPost('noDok');
         
        $dokModel   = model('t_dokpersh'); 
        $dokModel->select("t_dokpersh.id, t_dokpersh.idJenisDok, t_dokpersh.jenisDok, t_dokpersh.noDokumen, t_dokpersh.tglDokumen, t_dokpersh.tglAkhirDokumen, t_dokpersh.pathFile, t_dokpersh.negaraPenerbit, CONCAT(m_negara.kode, ' - ',m_negara.nama) AS 'nama_negara'");
        $dokModel->join('m_negara', 't_dokpersh.negaraPenerbit = m_negara.kode'); 
        
        $dokModel->where('t_dokpersh.npwp',$npwp);
        $dokModel->where('t_dokpersh.tglAkhirDokumen >=',date('Y-m-d'));

        if(!empty($jenisDok)){
            $dokModel->where('t_dokpersh.idJenisDok',$jenisDok);
        }
  
        if(!empty($noDok)){
            $dokModel->where('t_dokpersh.noDokumen',$noDok);
        }

        $arrData    =  $dokModel->orderBy(' t_dokpersh.id ', 'DESC')->findAll(); 
        
        $html       = $cbx = ''; 
        
        foreach ($arrData as $key => $data) {
            if($showcbx){
                $isChecked = '';
                if(in_array($data->id,$checked))
                    $isChecked = 'checked';

                $cbx = '<td class="align-midlle text-nowrap text-center"><label class="ckbox" for="ckbox-dok_'.$key.'"><input '.$isChecked.' type="checkbox" class="ckbox-dok" data-iddata="'.encrypt_id($data->id).'" id="ckbox-dok_'.$key.'"><span></span></label></td>';
            }

            $modelRef       = model('tx_lseReferensi');
            $modelRef->select('id');  
            $dataRef        = $modelRef->where('idDokPersh', $data->id);
            $banyak_data    = $dataRef->countAllResults(false); 
            $dataRef        = $dataRef->findAll(); 
            $btnEdit        = "";
            $btnDelete      = "";

            if($banyak_data == 0){
                $btnEdit    = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-warning me-1" onclick="edit_dok_persh(\''.encrypt_id($data->id).'\')" title="Edit"><i class="fa fa-edit"></i></button>'; 
                $btnDelete  = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-danger me-1" onclick="del_dok_persh(\''.encrypt_id($data->id).'\')" title="Hapus"><i class="fa fa-trash"></i></button> '; 
            }

            $btnView        = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-info" onclick="view_dok_persh(\''.encrypt_id($data->id).'\')" title="Lihat File"><i class="fa fa-eye"></i></button>'; 
 
            $html .= '<tr>
                        <td class="align-midlle">'.($key+1).'.</td>
                        '.$cbx.'
                        <td class="align-midlle">'.$data->jenisDok.'</td>
                        <td class="align-midlle">'.$data->nama_negara.'</td>
                        <td class="align-midlle text-nowrap">'.$data->noDokumen.'</td>
                        <td class="align-midlle text-nowrap">'.reverseDate($data->tglDokumen).'</td>
                        <td class="align-midlle text-nowrap">'.reverseDate($data->tglAkhirDokumen).'</td>
                        <td class="align-midlle text-nowrap text-center">'.$btnEdit.$btnDelete.$btnView.'</td>
                    </tr>';
        }
        return $html;
    }

    public function edit_dok()
    {
        try {
            $id         = decrypt_id($this->request->getPost('id'));  

            $modelRef       = model('tx_lseReferensi');
            $modelRef->select('id');  
            $dataRef        = $modelRef->where('idDokPersh', $id);
            $banyak_data    = $dataRef->countAllResults(false); 
            $dataRef        = $dataRef->findAll(); 

            if($banyak_data == 0){
                $dokModel   = model('t_dokpersh');
                $dokModel->select("t_dokpersh.id, t_dokpersh.idJenisDok, t_dokpersh.jenisDok, t_dokpersh.noDokumen, t_dokpersh.tglDokumen, t_dokpersh.tglAkhirDokumen, t_dokpersh.pathFile, t_dokpersh.negaraPenerbit, CONCAT(m_negara.kode, ' - ',m_negara.nama) AS 'nama_negara'");
                $dokModel->join('m_negara', 't_dokpersh.negaraPenerbit = m_negara.kode'); 
                $arrData    =  $dokModel->where('t_dokpersh.id', $id)->first();  
                $arrData->id = encrypt_id($arrData->id);

                return $this->response->setJSON($arrData);
            } else {
                $resp = resp_error('Dokumen sudah digunakan pada Draft LSE');
                return $this->response->setJSON($resp);
            }
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!'.$e->getMessage());
            return $this->response->setJSON($resp);
        }
    }

    public function uploadDok()
    {
        try {
            $postdata       = $this->request->getPost('postdata'); 
            $data           = post_ajax_toarray($postdata);
            $tnggalSekarang = date('Y-m-d');
            $tnggalAkhir    = reverseDateDB($data['tglAkhirDokumen']);
            $status_pilih   = $this->request->getPost('status_pilih');
            
            if(!empty($this->request->getPost('idData'))){
                $idData     = decrypt_id($this->request->getPost('idData'));
            }

            // id perusahaan yang dikirim dari form draft LSE
            if(!empty($this->request->getPost('idPerusahaan'))){
                $data['idPersh'] = $this->request->getPost('idPerusahaan');
            }

            // id perusahaan yang dikirim dari form client
            if(!empty($this->request->getPost('idClient'))){
                $data['idPersh'] = decrypt_id($this->request->getPost('idClient'));
            }
            
            unset($data['idData']);
            unset($data['idDok']);
 
            // pengecekan tanggal akhir pada penerbitan LS, apakah tanggal akhir sudah kadalursa atau belum
            //if($tnggalAkhir > $tnggalSekarang){
                $validationRule = [
                    'fileDok'   => [
                        'rules' => [
                            'uploaded[fileDok]',
                            'mime_in[fileDok,image/jpg,image/jpeg,image/png,image/webp,application/pdf]',
                            'max_size[fileDok,5120]',
                        ],
                        'errors' => [
                            'uploaded'  => 'File belum di pilih',
                            'mime_in'   => 'File yang diupload harus berupa image atau pdf dengan format jpg/png/pdf',
                            'max_size'  => 'Ukuran file maksimal 5 MB'
                        ]
                    ],
                ];

                $errMandatory = $this->cek_mandatory($data,'DOCUMENT');
   
                if(count($errMandatory) == 0)
                {  
                    if (! $this->validate($validationRule)) {
                        $resp   = resp_error($this->validator->getError(),'','Upload Gagal');
                        return $this->response->setJSON($resp);
                    } else {
                        $fileUpload = $this->request->getFile('fileDok');

                        if (! $fileUpload->hasMoved()) {
                            $pathFile                   = $fileUpload->store('pendukung/'.date('Ymd'));
                            $data['npwp']           = clean_npwp($this->request->getPost('npwp'));
                            $data['tglDokumen']     = reverseDateDB($data['tglDokumen']);
                            $data['tglAkhirDokumen']= reverseDateDB($data['tglAkhirDokumen']);
                            $data['jenisDok']       = model('jenisDokumen')->where('id',$data['idJenisDok'])->get()->getRow()->jenisDokumen;
                            $data['pathFile']       = $pathFile;
                            $data['url']            = hash('SHA256', random_bytes(32));
                            // $dokModel                   = model('t_dokpersh')->builder();
                            $dokModel                   = model('t_dokpersh');
                            
                            if ($dokModel->insert($data) !== false) {
                                $idDok 	                = $dokModel->insertID(); 
                                
                                if($status_pilih == 1){  
                                    $rowDok['idLS']         =  $idData;
                                    $rowDok['idJenisDok']   =  $data['idJenisDok'];
                                    $rowDok['idDokPersh']   =  $idDok;
                                    $rowDok['created']      =  date('Y-m-d H:i:s');
                                    $reffModel  			= model('tx_lseReferensi');
                                    
                                    if ($reffModel->insert($rowDok) !== false) {

                                        $noET       = $tglET = $tglAkhirET = NULL;
                                        $perizinan = model('tx_lseReferensi');
                                        $perizinan->select('tx_lse_referensi.id as idref,t_dokpersh.*');
                                        $perizinan->join('t_dokpersh', 't_dokpersh.id = tx_lse_referensi.idDokPersh');
                                        $perizinan->where('tx_lse_referensi.idJenisDok',1)->where('tx_lse_referensi.idLS',$idData);
                                        $arrET      = $perizinan->first();
                                        if(isset($arrET->noDokumen))
                                        {
                                            $noET       = $arrET->noDokumen;
                                            $tglET      = $arrET->tglDokumen;
                                            $tglAkhirET = $arrET->tglAkhirDokumen;
                                        }

                                        $setET = ['noET'=>$noET,'tglET'=>$tglET,'tglAkhirET'=>$tglAkhirET];
                                        model('tx_lseHdr')->where('id',$idData)->set($setET)->update();

                                        $noPE       = $tglPE = $tglAkhirPE = NULL;
                                        $perizinan = model('tx_lseReferensi');
                                        $perizinan->select('tx_lse_referensi.id as idref,t_dokpersh.*');
                                        $perizinan->join('t_dokpersh', 't_dokpersh.id = tx_lse_referensi.idDokPersh');
                                        $perizinan->where('tx_lse_referensi.idJenisDok',4)->where('tx_lse_referensi.idLS',$idData);
                                        $arrPE      = $perizinan->first();
                                        if(isset($arrPE->noDokumen))
                                        {
                                            $noPE       = $arrPE->noDokumen;
                                            $tglPE      = $arrPE->tglDokumen;
                                            $tglAkhirPE = $arrPE->tglAkhirDokumen;
                                        }

                                        $setET = ['noPE'=>$noPE,'tglPE'=>$tglPE,'tglAkhirPE'=>$tglAkhirPE];
                                        model('tx_lseHdr')->where('id',$idData)->set($setET)->update();


                                        $resp = resp_success('Data berhasil disimpan',['noET'=>$noET,'tglET'=>reverseDate($tglET),'tglAkhirET'=>reverseDate($tglAkhirET),'noPE'=>$noPE,'tglPE'=>reverseDate($tglPE),'tglAkhirPE'=>reverseDate($tglAkhirPE)]);
                                        return $this->response->setJSON($resp);
                                    } else {
                                        $arrError = $reffModel->errors();
                                            
                                        foreach($arrError as $x => $val) { 
                                            $errorText .= "<br> - ".$val;
                                        } 
                                        $resp = resp_error($errorText);
                                        
                                        return $this->response->setJSON($resp);
                                    }
                                } else {
                                    $resp = resp_success('Data berhasil disimpan');
                                    return $this->response->setJSON($resp);
                                } 
                            } else {
                                $arrError = $reffModel->errors();
                                    
                                foreach($arrError as $x => $val) { 
                                    $errorText .= "<br> - ".$val;
                                } 
                                $resp = resp_error($errorText);
                                
                                return $this->response->setJSON($resp);
                            } 
                        } else {
                            throw new \Exception('Gagal upload.');
                        }
                    } 
                } else { 
                    $textErr = implode('<br> - ', $errMandatory);
                    $resp    = resp_error('Perhatikan isian berikut <br>'.$textErr);
                    return $this->response->setJSON($resp); 
                }  
            // } else {
            //     $resp = resp_error('Tanggal akhir dokumen harus lebih dari tanggal sekarang');
            //     return $this->response->setJSON($resp);
            // }
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
            $status_pilih   = $this->request->getPost('status_pilih');
   
            if(!empty($this->request->getPost('idData'))){
                $idData     = decrypt_id($this->request->getPost('idData'));
            }

            // id perusahaan yang dikirim dari form draft LSE
            if(!empty($this->request->getPost('idPerusahaan'))){
                $data['idPersh'] = $this->request->getPost('idPerusahaan');
            }

            // id perusahaan yang dikirim dari form client
            if(!empty($this->request->getPost('idClient'))){
                $data['idPersh'] = decrypt_id($this->request->getPost('idClient'));
            }

            unset($data['idData']);
            unset($data['idDok']);

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
                    $validasiUpload             = "";
                    if (! $this->validate($validationRule)){
                        $validasiUpload         = $this->validator->getError();
                    }
 
                    if($validasiUpload == "" || $validasiUpload = "File belum di pilih"){ 
                        $data['npwp']           = clean_npwp($this->request->getPost('npwp'));
                        $data['tglDokumen']     = reverseDateDB($data['tglDokumen']);
                        $data['tglAkhirDokumen']= reverseDateDB($data['tglAkhirDokumen']);
                        $data['jenisDok']       = model('jenisDokumen')->where('id',$data['idJenisDok'])->get()->getRow()->jenisDokumen;
                        $status_insert          = 0;

                        if($this->request->getFile('fileDok') === NULL){  
                            $dokModel           = model('t_dokpersh')->builder();  
                            $dokModel->update($data, ['id' => $idDok]);  
                            $status_insert      = 1; 
                        } else {
                            $fileUpload = $this->request->getFile('fileDok');
 
                            if (! $fileUpload->hasMoved()) {
                                $pathFile               = $fileUpload->store('pendukung/'.date('Ymd'));
                                $data['pathFile']       = $pathFile; 
                                $dokModel               = model('t_dokpersh')->builder();  
                                $dokModel->update($data, ['id' => $idDok]); 
                                $status_insert          = 1;  
                            } else {
                                throw new \Exception('Gagal upload.');
                            } 
                        }
                        
                        if($status_insert == 1){ 
                            if($status_pilih == 1){  
                                $rowDok['idLS']         =  $idData;
                                $rowDok['idJenisDok']   =  $data['idJenisDok'];
                                $rowDok['idDokPersh']   =  $idDok;
                                $rowDok['created']      =  date('Y-m-d H:i:s');
                                $reffModel  			= model('tx_lseReferensi'); 
                                 
                                if ($reffModel->insert($rowDok) !== false) {
                                    $resp = resp_success('Data berhasil disimpan'); 
                                } else {
                                    $arrError = $reffModel->errors();
										
                                    foreach($arrError as $x => $val) { 
                                        $errorText .= "<br> - ".$val;
                                    } 
                                    $resp = resp_error($errorText);
                                    
                                    return $this->response->setJSON($resp);
                                }
                            } else {
                                $resp = resp_success('Data berhasil disimpan'); 
                            }
                        } else {
                            $resp = resp_error($validasiUpload,'','Gagal save / update data');
                            return $this->response->setJSON($resp);
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
}
