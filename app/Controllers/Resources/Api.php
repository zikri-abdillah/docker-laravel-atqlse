<?php

namespace App\Controllers\Resources;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use \Firebase\JWT\JWT;


class Api extends ResourceController
{
	use ResponseTrait;

	public function check_izin()
    {
    	$request = \Config\Services::request();
    	// $json = $request->getJSON();
    	//$apiKey = $request->header();
    	$apiKey = $request->getHeaderLine('X-GATEWAY-APIKEY');


    	if($apiKey == '123'){
    		// $jsons = file_get_contents('php://input');
	    	// $json = json_decode($jsons,true);
	    	// $json['server'] = 'server';
    		$json = '{"header":{"nib":"0000000000000","npwp":"111111111111111","nama_perusahaan":"PERCOBAAN JAYA","no_izin":"03.ET-04.23.0000","kd_izin":"01012A9","status_izin":"01","tgl_izin":"2023-09-05","tgl_awal":"2023-09-05","tgl_akhir":"2023-10-15"},"kepatuhan":{"kode":"A01","keterangan":"Request Sukses"},"komoditas":[]}';
    	}
    	else
    		$json = 'Invalid api key';

        return $this->response->setJSON($json);
    }

    public function rekap_mkt($token,$start_date,$end_date)
    {
    	if($token != 'mKmejygQilbL0FUXW45kWrVU2BcXT3KG')
    	{
    		echo 'Invalid token';
    		exit;
    	}

    	$db         = \Config\Database::connect();
    	$arrData      = $db->table('tx_lsehdr')
                        ->join('tx_lsedtlhs', 'tx_lsedtlhs.idLs = tx_lsehdr.id','left')
                        ->select(['"LSE BATUBARA" as jenis','namaPersh','noLs','tglLs','sum(tx_lsedtlhs.jumlahBarang) as qty'])
                        ->where('statusProses','ISSUED')->where('tglLs >=', $start_date)->where('tglLs <=', $end_date)
                        ->groupBy('tx_lsehdr.id')
                        ->orderBy('tx_lsehdr.tglLs')
                        ->orderBy('tx_lsehdr.noLs')
						->get()->getResult();

		$html = '<table>';
		$html .= '<tr>';
		$html .= '<th>NO</th>';
		$html .= '<th>JENIS LS</th>';
		$html .= '<th>NAMA PERUSAHAAN</th>';
		$html .= '<th>NO LS</th>';
		$html .= '<th>TGL LS</th>';
		$html .= '<th>VOLUME</th>';
		$html .= '</tr>';

		foreach ($arrData as $key => $data) {
			$html .= '<tr>';
			$html .= '<td>'.($key+1).'</td>';
			$html .= '<td>'.$data->jenis.'</td>';
			$html .= '<td>'.$data->namaPersh.'</td>';
			$html .= '<td>'.$data->noLs.'</td>';
			$html .= '<td>'.reverseDate($data->tglLs).'</td>';
			$html .= '<td>'.$data->qty.'</td>';
			$html .= '</tr>';
		}

		$html .= '</table>';
		echo $html;
		exit;
    }
}
