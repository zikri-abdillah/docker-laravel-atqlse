<?php

namespace App\Controllers\Internal\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;


class Beranda extends BaseController
{

	public function index()
    {
        // $lsModel = model('tx_lseHdr');
        $coal_pengajuan = $this->db->table('tx_lsehdr')->whereIn('idJenisLS',[1])->where('MONTH(tglDraft)',DATE('m'))->where('YEAR(tglDraft)',DATE('Y'))->countAllResults();
        $coal_terbit = $this->db->table('tx_lsehdr')->whereIn('idJenisLS',[1])->where('MONTH(tglDraft)',DATE('m'))->where('YEAR(tglDraft)',DATE('Y'))->countAllResults();

        $mineral_pengajuan = $this->db->table('tx_lsehdr')->where('statusProses','ISSUED')->whereIn('idJenisLS',[2,3,4])->where('MONTH(tglLs)',DATE('m'))->where('YEAR(tglDraft)',DATE('Y'))->countAllResults();

        // $page = [
        //     'coal_pengajuan'       => $coal_pengajuan,
        //     'coal_terbit' => $breadcrumb_active,
        //     'coal_cabut'        => $dataFilter,
        // ];
        $page = [];
        $param['content'] = $this->render('ekspor.beranda', $page);
        return $this->render('layout.template', $param);
    }
}

?>