<?php

namespace App\Controllers\Resources;

use CodeIgniter\RESTful\ResourceController; 
use \Firebase\JWT\JWT;
 
class Log extends ResourceController
{  
    public function view_log()
    {
        $idLs = decrypt_id($this->request->getPost('idLs'));  
        $modelLog        = model('t_log_process');        
        $modelLog->select("t_log_process.idLog, ls.jenisLS, ls.draftNo, ls.noLs, t_log_process.currentStatus, t_log_process.setStatus, t_log_process.currentStatusLS, t_log_process.setStatusLS, t_log_process.note, t_log_process.userAct,  t_log_process.logAction, t_log_process.logTime, t_log_process.logTimeServer");
        $modelLog->join('tx_lsehdr AS ls', 'ls.id = t_log_process.idLs');  

        $arrData        = $modelLog->where('idLs',$idLs); 
        $recordsTotal   = $arrData->countAllResults(false);
        $arrData        = $arrData->orderBy('t_log_process.logTime', 'ASC')->findAll();
        $html           = ''; 
        $no             = 1;  

        foreach ($arrData as $key => $data) {     
            $modelUser  = model('t_user')->where('id', $data->userAct)->first();
            $username   = $modelUser ? $modelUser->username : ""; 
          
            $html .= '<tr>
                        <td width="5%" align="middle">'.($key+1).'.</td>  
                        <td width="15%" align="middle">'.$data->setStatus.'</td>
                        <td width="10%" align="middle">'.$data->setStatusLS.'</td>
                        <td width="15%" align="middle">'.$data->logAction.'</td> 
                        <td width="10%" align="middle">'.$username.'</td>
                        <td width="20%">'.$data->logTime.'</td> 
                        <td width="25%">'.$data->note.'</td> 
                    </tr>';
        }
 
        $table['jenisLS']= $arrData ? $arrData[0]->jenisLS : '';
        $table['draftNo']= $arrData ? $arrData[0]->draftNo : ''; 
        $table['lsNo']   = $arrData ? $arrData[0]->noLs : ''; 
        $table['html']   = $html;

        echo json_encode($table);
    }
    
    public function view_log_lnsw()
    {
        $idPermohonan   = decrypt_id($this->request->getPost('idLs'));  
        $modelLog       = model('t_log_simbara');   
         
        $modelLog->select("lnsw.nomorAju, lnsw.nomorPermohonan, t_log_simbara.uraiProses, t_log_simbara.mark, t_log_simbara.keterangan, t_log_simbara.logTime, t_log_simbara.statusKirimNSW, t_log_simbara.waktuKirimNSW, t_log_simbara.userAct");
        $modelLog->join('tblPermohonan_pinsw AS lnsw', 'lnsw.id = t_log_simbara.idPermohonan');  
        $arrData        = $modelLog->where('idPermohonan', $idPermohonan); 
        $recordsTotal   = $arrData->countAllResults(false);
        $arrData        = $arrData->orderBy('t_log_simbara.logTime', 'ASC')->findAll();
        $html           = ''; 
        $no             = 1;  

        foreach ($arrData as $key => $data) {     
            $modelUser  = model('t_user')->where('id', $data->userAct)->first();
            $username   = $modelUser ? $modelUser->username : $data->userAct; 
          
            $html .= '<tr>
                        <td width="5%" align="middle">'.($key+1).'.</td>  
                        <td width="15%" align="middle">'.$data->uraiProses.'</td>
                        <td width="15%" align="middle">'.$data->mark.'</td>
                        <td width="20%" align="middle">'.$data->keterangan.'</td> 
                        <td width="15%">'.$data->logTime.'</td> 
                        <td width="10%">'.$data->statusKirimNSW.'</td> 
                        <td width="20%" align="middle">'.$data->waktuKirimNSW.'</td>
                    </tr>';
        }
  
        $table['nomorAju']          = $arrData ? $arrData[0]->nomorAju : ''; 
        $table['nomorPermohonan']   = $arrData ? $arrData[0]->nomorPermohonan : ''; 
        $table['html']              = $html;

        echo json_encode($table);
    }
}
