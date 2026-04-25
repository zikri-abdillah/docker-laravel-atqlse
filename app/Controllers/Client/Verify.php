<?php

namespace App\Controllers\Client;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;


class Verify extends BaseController
{
    public function index($token=false)
    {
        if($token)
        {
            $qrdata = $this->db->table('t_lse_qrcode')->where('token',$token)->orderBy('id desc')->get()->getRow();
  
            if(!empty($qrdata)){ 
                $idData           = $qrdata->idls;
                $hdrModel         = model('tx_lseHdr');   
                $hdrModel->select('tx_lsehdr.*, SUM(b.jumlahBarang) AS totalMuat, b.kdSatuanBarang');
                $hdrModel->join('tx_lsedtlhs b', 'b.idLs = tx_lsehdr.id', 'left');    
                $hdrModel->where('tx_lsehdr.id', $idData); 
                $hdrModel->wherein('tx_lsehdr.statusDok', ['TERBIT','PERUBAHAN']);
                $hdrModel->where('tx_lsehdr.statusProses', 'ISSUED');
                $data             = $hdrModel->first();
 
                if(!empty($data)){
                    $param['data']    = $data;
                    $param['code']    = "200";
                } else {
                    $param['data']    = [];
                    $param['code']    = "500";
                }
            } else {
                $param['data']    = [];
                $param['code']    = "400";
            }
          
            return $this->render('layout.template-verify', $param);  
        }
    }

    public function index5($token=false,$key=false)
    {
        if(hash('sha256',$key) != $token)
            echo 'invalid token';
        else{
            $idData             = 1;
            $hdrModel           = model('tx_lseHdr');
            $hdrModel->select('tx_lsehdr.*, SUM(b.jumlahBarang) AS totalMuat, b.kdSatuanBarang');
            $hdrModel->join('tx_lsedtlhs b', 'b.idLs = tx_lsehdr.id', 'left');
            $hdrModel->where('tx_lsehdr.id', $idData);
            $hdrModel->where('tx_lsehdr.statusDok', 'TERBIT');
            $hdrModel->where('tx_lsehdr.statusProses', 'ISSUED');
            $data               = $hdrModel->first();
            $param['data']    = $data; 
            $param['code']    = "200";

            return $this->render('layout.template-verify', $param);
        }
    }
}