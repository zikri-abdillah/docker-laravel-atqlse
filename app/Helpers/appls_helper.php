<?php
use CodeIgniter\HTTP\ResponseInterface;
// remove prefix underscore from array key & trimming value
function remove_prefix($arr)
{
	if(is_array($arr))
	{
		$newArr = [];
		foreach ($arr as $key => $item) {
			if(strpos($key, "_") !== false){
				$newKey = explode('_', $key);
				$newArr[$newKey[1]] = trim($item);
			}
			else{
				$newArr[$key] = trim($item);
			}
		}
		unset($arr);
		return $newArr;
	}
	else{
		if(is_string($arr)){
			if(strpos($arr, "_") === 1)
				return substr($arr, strpos($arr, "_")+1);
			else
				return $arr;
		}
	}
}

// remove all non numneric character
function numeric_only($string)
{
	return preg_replace("/[^0-9]/", "",$string);
}

// convert serialize post data to array
function post_ajax_toarray($jsonObj,$clean=true,$multiple=null)
{
	$arrpost = json_decode($jsonObj, true);
	$datapost = [];
	foreach ($arrpost as $key => $post) {
		if(!empty($multiple) && in_array($post['name'],$multiple))
		{
			if($clean)
				$datapost[remove_prefix($post['name'])][] = clean_string($post['value']);
			else
				$datapost[$post['name']][] = $post['value'];
		}
		else{
			if($clean)
				$datapost[remove_prefix($post['name'])] = clean_string($post['value']);
			else
				$datapost[$post['name']] = $post['value'];
		}
	}
	return $datapost;
}


function clean_string($string)
{
	// clean sting from :
	// tab
	// return null rather than empty string ''
	// return null rather than string 'null'
	$string = trim(preg_replace('/\t/', ' ', $string));
	if(empty($string) || strtolower($string) == 'null')
		$string = NULL;
	return $string;

}

function clean_npwp($string)
{
	return clean_string(str_replace('-', '', str_replace('.', '', str_replace('_', '', $string))));
} 

function resp_success($msg,$data='',$title='Sukses')
{
    $resp = new \stdClass();
    $resp->code = '00';
    $resp->text = $title;
    $resp->type = 'success';
    $resp->data = $data;
    $resp->msg = $msg;
    return $resp;
}

function resp_confirm($msg,$data='',$title='Konfirmasi')
{
    $resp = new \stdClass();
    $resp->code = '88';
    $resp->text = $title;
    $resp->type = 'warning';
    $resp->data = $data;
    $resp->msg = $msg;
    return $resp;
}

function resp_error($msg,$data='',$title='Gagal')
{
    $resp = new \stdClass();
    $resp->code = '99';
    $resp->text = $title;
    $resp->type = 'error';
    $resp->data = $data;
    $resp->msg = $msg;
    return $resp;
}

if (!function_exists('reverseDate'))
{
	function reverseDate($strdate = '',$separator='-'){
		if(!empty($strdate) && $strdate != '0000-00-00')
		{
			$return = '';
			$strdate = substr($strdate, 0,10);
			if(strlen(trim($strdate)) == 10)
			{
				$arrStr = explode('-', $strdate);
				$return = $arrStr[2].$separator.$arrStr[1].$separator.$arrStr[0];
			}
			return $return;
		}
		else
			return '-';
	}
}

if (!function_exists('reverseDateDB'))
{
	function reverseDateDB($strdate = '',$separator='-'){
		if($strdate != '0000-00-00' && !empty($strdate))
		{
			$return = '';
			$strdate = substr($strdate, 0,10);
			if(strlen(trim($strdate)) == 10)
			{
				$arrStr = explode('-', $strdate);
				$return = $arrStr[2].$separator.$arrStr[1].$separator.$arrStr[0];
				return $return;
			}
			else
				return NULL;
		}
		else
			return NULL;
	}
}

if (!function_exists('reverseDateTime'))
{
	function reverseDateTime($strdate = '',$separator='-'){
		//if($strdate != '0000-00-00')
		if(!empty($strdate) && !str_contains($strdate, '0000-00-00'))
		{
			$return = '';
			$strtime = trim(substr($strdate, 10));
			$strdate = substr($strdate, 0,10);
			if(strlen(trim($strdate)) == 10)
			{
				$arrStr = explode('-', $strdate);
				$return = $arrStr[2].$separator.$arrStr[1].$separator.$arrStr[0].' '.$strtime;
			}
			return $return;
		}
		else
			return '-';
	}
}

if (!function_exists('formatDateTime'))
{
	function formatDateTime($strdate = '',$separator='-'){
		if($strdate != '' && $strdate != '0000-00-00'){
			$return = '';
			$strdate = date_create($strdate);
			$return = date_format($strdate,"d-m-Y H:i:s");
			return strftime($return);
		}
	}
}

if (!function_exists('formatDate'))
{
	function formatDate($strdate = '',$separator='-'){
		if($strdate != '' && $strdate != '0000-00-00'){
			$return = '';
			$strdate = date_create($strdate);
			$return = date_format($strdate,"d M Y");
			return $return;
		}
	}
}

if (!function_exists('FormatNPWP'))
{
	function FormatNPWP($varnpwp){
		$varresult = '';
		if(!empty($varnpwp)){

			if(strlen($varnpwp) === 15){  
				$varresult = substr($varnpwp,0,2).".".substr($varnpwp,2,3).".".substr($varnpwp,5,3).".".substr($varnpwp,8,1)."-".substr($varnpwp,9,3).".".substr($varnpwp,12,3);
 			} 

			if(strlen($varnpwp) === 16){  
				$varresult = substr($varnpwp,0,4).".".substr($varnpwp,4,4).".".substr($varnpwp,8,4).".".substr($varnpwp,12,4);
			} 
		}
		return $varresult;
	}
}

if (!function_exists('FormatHS'))
{
	function FormatHS($varnohs)
	{
		$varnohs = $varnohs;
		$digit = strlen(trim($varnohs));
		if (!is_null($varnohs) && $digit == 10)
		{
			$varresult = '';
			$varresult = substr($varnohs,0,4).".".substr($varnohs,4,2).".".substr($varnohs,6,2).".".substr($varnohs,8,2);
			return $varresult;
		}
		elseif (!is_null($varnohs) && $digit == 8){
			$varresult = '';
			$varresult = substr($varnohs,0,4).".".substr($varnohs,4,2).".".substr($varnohs,6,2);
			return $varresult;
		}
		else
		{
			return '<span class="text-danger">HS '.$varnohs.' Tidak Valid</span>';
		}
	}
}

function insertNumber($numb)
{
	if(!empty($numb)){
	    $n = str_replace(',', '', $numb);
	    return str_replace(',', '', $n);
    }
}


function formatAngka($value = '',$dec='')
{
	if(!empty($value))
	{
	    $arrValue = explode('.', $value);
	    if(trim($arrValue[0]) != '')
	    {
	        $bulat = $arrValue[0];
	        if(isset($arrValue[1]))
	            $desimal = $arrValue[1];
	        else
	            $desimal = '';
	        $string1 = number_format($bulat,0,".",",");

	        if(is_int($dec)){
	            $desimal = str_pad($desimal, $dec, "0",STR_PAD_RIGHT);
	        }

	        if($desimal == '')
	            return $string1;
	        else
	            return $string1.'.'.$desimal;
	    }
	}
}

if ( ! function_exists('obj_flatten'))
{
	function obj_flatten($param, $val)
	{
		$flatten = [];
		foreach ($param as $key => $value) {
            $flatten[] = $value->{$val};
        }
        return $flatten;
	}
}

function save_log_simbara($idPermohonan,$kodeProses,$uraiProses,$mark='',$keterangan='',$statusKirimNSW='READY')
{
	$db      = \Config\Database::connect();
	$dataLog['idPermohonan'] = $idPermohonan;
	$dataLog['kodeProses'] = $kodeProses;
	$dataLog['uraiProses'] = $uraiProses;
	$dataLog['mark'] = $mark;
	$dataLog['keterangan'] = $keterangan;
	$dataLog['statusKirimNSW'] = $statusKirimNSW;
	if($statusKirimNSW == 'SENT'){
		$dataLog['waktuKirimNSW'] = date('Y-m-d H:i:s');
	}
	if(!empty(session()->get('sess_userid')))
		$dataLog['userAct'] = decrypt_id(session()->get('sess_userid'));
	else
		$dataLog['userAct'] = 'SYSTEM';
	$dataLog['logTime'] = date('Y-m-d H:i:s');
	$db->table('t_log_simbara')->insert($dataLog);
}

function save_log_process($dataLog,$dataBefore)
{
	helper('filesystem');
	$db      = \Config\Database::connect();
	$builder = $db->table('t_log_process');

	$dirLog = '/process_log/'.date('Ym').'/';
	if ( !is_dir( WRITEPATH.$dirLog ) ) {
	    mkdir( WRITEPATH.$dirLog );
	}

	$dataUpdated = $db->table('tx_lsehdr')->where('id',$dataLog['idLS'])->get()->getRow();
	if(empty($dataBefore))
		$dataBefore = $dataUpdated;

	$dataLog['idJenisLS'] = $dataBefore->idJenisLS;
	$dataLog['currentStatus'] = $dataBefore->statusProses;
    $dataLog['setStatus'] = $dataUpdated->statusProses;;
    $dataLog['currentStatusLS'] = $dataBefore->statusDok;
    $dataLog['setStatusLS'] = $dataUpdated->statusDok;

	$batch = uniqid();
	$logBefore = 'before_'.$dataLog['idLS'].'_'.$batch.'.json';
	$pathLogBefore = $dirLog.$logBefore;
	if (!write_file(WRITEPATH.$pathLogBefore,json_encode($dataBefore)) )
		$dataLog['dataBefore'] .= 'Unable to write file' . WRITEPATH.$pathLogBefore;
	else
		$dataLog['dataBefore'] = $pathLogBefore;

	$logUpdated = 'updated_'.$dataLog['idLS'].'_'.$batch.'.json';
	$pathLogUpdated = $dirLog.$logUpdated;
	if (!write_file(WRITEPATH.$pathLogUpdated,json_encode($dataUpdated)) )
		$dataLog['dataCurrent'] .= 'Unable to write file' . WRITEPATH.$pathLogUpdated;
	else
		$dataLog['dataCurrent'] = $pathLogUpdated;
 	if(!empty(session()->get('sess_userid')))
		$dataLog['userAct'] = decrypt_id(session()->get('sess_userid'));
	else
		$dataLog['userAct'] = 0;
	$dataLog['logTime'] = date('Y-m-d H:i:s');
	$builder->insert($dataLog);
}

if ( ! function_exists('qq'))
{
	function qq($param)
	{
		print_r($param);exit;
	}
}

// 2024-02-25 
if ( ! function_exists('cek_mandatory'))
{
	function cek_mandatory($data, $section)
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

if ( ! function_exists('cek_duplikasi_user'))
{
	function cek_duplikasi_user($data, $tabel, $idData)
	{
		$errMandatory = "";
		foreach ($data as $key => $val) {  
			
			$arrData      = model($tabel); 
			$arrData->where($key, $val); 

			$recordsTotal = $arrData->countAllResults(false); 
			$arrData      = $arrData->findAll();

			if($recordsTotal > 0){   
				if($idData != ''){   
					if($idData != $arrData[0]->id){
						$errMandatory  .= 'Username x <i>'.$val.'</i> sudah digunakan.'; 
					} 
				} else {
					$errMandatory  .= 'Username <i>'.$val.'</i> sudah digunakan.'; 
				}
			}  
		} 

		return $errMandatory;
	}
}

if ( ! function_exists('cek_status_npwp'))
{
	function cek_status_npwp($data, $tabel, $idData)
	{
		$errMandatory = "";

		foreach ($data as $key => $val) {   
			$arrData      = model($tabel); 
			$arrData->where($key, $val); 
			$recordsTotal = $arrData->countAllResults(false);
			$arrData      = $arrData->findAll();

			if($recordsTotal > 0){ 
				$pete = $arrData[0]->bentukPersh.". ".$arrData[0]->nama;

				if($idData != ''){   
					if($idData != $arrData[0]->id){
						$errMandatory  .= 'NPWP nomor '.formatNPWP($val).' sudah digunakan oleh '.$pete; 
					} 
				} else {
					$errMandatory  .= 'NPWP nomor '.formatNPWP($val).' sudah digunakan oleh '.$pete; 
				}
			}  
		} 

		return $errMandatory;
	}
}


if ( ! function_exists('nama_bulan'))
{
	function nama_bulan($intBulan)
	{
		$intBulan = intval($intBulan);
		$bulan = array("Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
		return $bulan[$intBulan];
	}
}

if ( ! function_exists('pengajuan_simbara'))
{
	function pengajuan_simbara($idPermohonan)
	{
		$db      = \Config\Database::connect();
		$pengajuan = $db->table('tblPermohonan_pinsw')->where('id',$idPermohonan)->get()->getRow();
		return $pengajuan;
	}
}

if ( ! function_exists('status_kirim'))
{
	function status_kirim($status)
	{
		// DRAFT = DEFAULT (MASIH PROSES)
		// READY = ANTRIAN SCHEDULLER
		// PROCESS = PROSES PENGIRIMAN
		// SENT = BERHASIL TERKIRIM
		// FAILED = GAGAL TERKIRIM
		$arrStatus = ['DRAFT'=>'DRAFT','READY'=>'READY','PROCESS'=>'PROSES','SENT'=>'TERKRIM INATRADE','FAILED'=>'GAGAL KIRIM INATRADE'];
		return $arrStatus[$status];
	}
}

if ( ! function_exists('cek_karakter'))
{
	function cek_karakter($name, $string) {

		$arr_karakter = ["`", "'", '"', "‛", "‟", "’", "”", "‘", "“", "＂"];  
		$err_note 	  = "";

		foreach ($arr_karakter as $val) { 
			if (strpos($string, $val) !== false) {
				$err_note .= "$name tidak boleh memuat tanda $val.<br>";
			} 
		}
 
	    return $err_note;
	}
}
