<?php

namespace App\Controllers\Resources;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use \Firebase\JWT\JWT;
use Config\Database;


class File extends ResourceController
{
	use ResponseTrait;

	public function lse($param=false)
    {
    	try{
            $model  = model('App\Models\tx_lseHdr');
            $dataLS = $model->where('fileUrl',$param)->first();

            if(!empty($dataLS->fileLS)){
            	$path = WRITEPATH.'uploads/'.$dataLS->fileLS;
	            if(file_exists($path))
		            return $this->response->download($path,null,true)->inline();
		        else
		        	return redirect()->to('filenotfound');
	        }
	        else
	        	return redirect()->to('filenotfound');
        } catch (\CodeIgniter\Files\Exceptions\FileNotFoundException $e) {
            return redirect()->to('filenotfound');
        } catch (\Throwable $e) {
            $resp = 'An Exception has occured at Resources Dokpersh'.$e->getMessage();
            return $this->response->setJSON($resp);
        }
    }

    public function pendukung($param=false)
    {
    	try{
            $model  = model('App\Models\t_dokpersh');
            $data = $model->where('url',$param)->first();

            if(!empty($data->pathFile)){
            	$path = WRITEPATH.'uploads/'.$data->pathFile;
	            if(file_exists($path))
		            return $this->response->download($path,null,true)->inline();
		        else
		        	return redirect()->to('filenotfound');
	        }
	        else
	        	return redirect()->to('filenotfound');
        } catch (\CodeIgniter\Files\Exceptions\FileNotFoundException $e) {
            return redirect()->to('filenotfound');
        } catch (\Throwable $e) {
            $resp = 'An Exception has occured at Resources Dokpersh'.$e->getMessage();
            return $this->response->setJSON($resp);
        }
    }

    public function file_v1($param=false)
    {
        try{
            $dbV1 = Database::connect('v1');
            $dataLS = $dbV1->table('tbllshdr')->where('pathUrl','docfile/'.$param)->get()->getRow();

            if(!empty($dataLS->pathLS)){
                $dir = '/home/u626290708/domains/atq-ls.com/backup/v1/appls/application';
                $path = $dir.'/'.$dataLS->pathLS;
                if(file_exists($path))
                    return $this->response->download($path,null,true)->inline();
                else{
                    return redirect()->to('filenotfound');
                }
            }
            else{
                return redirect()->to('filenotfound');
            }
        } catch (\CodeIgniter\Files\Exceptions\FileNotFoundException $e) {
            return redirect()->to('filenotfound');
        } catch (\Throwable $e) {
            $resp = 'An Exception has occured at Resources Dokpersh'.$e->getMessage();
            return $this->response->setJSON($resp);
        }
    }

    public function file_rekap_v1($param=false)
    {
        try{
            $dir = '/home/u626290708/domains/atq-ls.com/backup/v1/appls/summary';
            $path = $dir.'/'.$param;

            if(!empty($param)){
                if(file_exists($path))
                    return $this->response->download($path,null,true)->inline();
                else{
                    return redirect()->to('filenotfound');
                }
            }
            else{
                return redirect()->to('filenotfound');
            }
        } catch (\CodeIgniter\Files\Exceptions\FileNotFoundException $e) {
            return redirect()->to('filenotfound');
        } catch (\Throwable $e) {
            $resp = 'An Exception has occured at Resources Dokpersh'.$e->getMessage();
            return $this->response->setJSON($resp);
        }
    }

    public function log_file($param=false,$param2=false,$param3=false)
    {
        $path = WRITEPATH.$param.'/'.$param2.'/'.$param3;
        if(is_file($path))
            echo file_get_contents($path);
    }

    public function laporan_bulanan($param=false)
    {
        try{
            $db = Database::connect();
            $data = $db->table('t_laporan_bulanan')->where('urlFile',$param)->get()->getRow();

            if(!empty($data->pathFile)){
                $path = WRITEPATH.'uploads/'.$data->pathFile;
                if(file_exists($path))
                    return $this->response->download($path,null,true)->inline();
                else
                    return redirect()->to('filenotfound');
            }
            else
                return redirect()->to('filenotfound');
        } catch (\CodeIgniter\Files\Exceptions\FileNotFoundException $e) {
            return redirect()->to('filenotfound');
        } catch (\Throwable $e) {
            $resp = 'An Exception has occured at Resources Dokpersh'.$e->getMessage();
            return $this->response->setJSON($resp);
        }
    }

    public function coa($param=false)
    {
        try{
            $db = Database::connect();
            $data = $db->table('tx_coa')->where('url_coa', $param)->get()->getRow();

            if(!empty($data->pathFile)){
                $path = WRITEPATH.'uploads/'.$data->pathFile;
                if(file_exists($path))
                    return $this->response->download($path,null,true)->inline();
                else
                    return redirect()->to('filenotfound');
            }
            else
                return redirect()->to('filenotfound');
        } catch (\CodeIgniter\Files\Exceptions\FileNotFoundException $e) {
            return redirect()->to('filenotfound');
        } catch (\Throwable $e) {
            $resp = 'An Exception has occured at Resources COA'.$e->getMessage();
            return $this->response->setJSON($resp);
        }
    }

    public function cow($param=false)
    {
        try{
            $db = Database::connect();
            $data = $db->table('tx_cow')->where('url_cow', $param)->get()->getRow();

            if(!empty($data->pathFile)){
                $path = WRITEPATH.'uploads/'.$data->pathFile;
                if(file_exists($path))
                    return $this->response->download($path,null,true)->inline();
                else
                    return redirect()->to('filenotfound');
            }
            else
                return redirect()->to('filenotfound');
        } catch (\CodeIgniter\Files\Exceptions\FileNotFoundException $e) {
            return redirect()->to('filenotfound');
        } catch (\Throwable $e) {
            $resp = 'An Exception has occured at Resources COW'.$e->getMessage();
            return $this->response->setJSON($resp);
        }
    }
}
