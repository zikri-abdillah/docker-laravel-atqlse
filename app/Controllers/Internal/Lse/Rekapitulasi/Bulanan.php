<?php

namespace App\Controllers\Internal\Lse\Rekapitulasi;

use App\Controllers\BaseController;
use CodeIgniter\Files\File;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Database\RawSql;
// use Psr\Log\LoggerInterface;


class Bulanan extends BaseController
{
    public function index()
    {
        $req                = $this->request->getGet();
        $bulan = array("Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
        $page = [
            'table_title'       => 'Laporan Bulanan Penerbitan LS Ekspor',
            'breadcrumb_active' => 'Penerbitan LS Ekspor',
            'arrBulan' => $bulan
        ];

        $param['content']   = $this->render('ekspor.rekapitulasi.ls.rekap_bulanan', $page);
        $param['addJS']     = '<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>';
        $param['addJS']     .= '<script src="' . base_url() . 'assets/plugins/datatable/js/dataTables.bootstrap5.js"></script>';
        $param['addJS']     .= '<script src="' . base_url() . 'assets/plugins/datatable/dataTables.responsive.min.js"></script>';
        $param['addJS']     .= '<script src="' . base_url() . 'assets/plugins/datatable/responsive.bootstrap5.min.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . '/js/ekspor/rekapitulasi/laporan.js?v='.date('YmdHis').'"></script>';

        return $this->render('layout.template', $param);
    }

    public function list()
    {
        $searchParam    = $this->request->getPost('searchParam');
        $arrParam       = post_ajax_toarray($searchParam);

        // $lsModel        = model('tx_lseHdr');
        //$arrData        = $lsModel->where('statusProses <>','DELETED')->whereIn('statusProses', 'ISSUED');
        $arrData = $this->db->table('t_laporan_bulanan');

        // if(!empty($arrParam['idJenisLs'])){
        //     $arrData->where('idJenisLS', $arrParam['idJenisLs']);
        // }

        // if(!empty($arrParam['idJenisTerbit'])){
        //     $arrData->where('idJenisTerbit', $arrParam['idJenisTerbit']);
        // }

        // if(!empty($arrParam['idCabang'])){
        //     $arrData->where('idCabang', $arrParam['idCabang']);
        // }

        // if(!empty($arrParam['draftNo'])){
        //     $arrData->like('draftNo', $arrParam['draftNo']);
        // }

        // if(!empty($arrParam['noSi'])){
        //     $arrData->like('noSi', $arrParam['noSi']);
        // }

        // if(!empty($arrParam['namaPersh'])){
        //     $arrData->like('namaPersh', $arrParam['namaPersh']);
        // }

        // if(!empty($arrParam['noLs'])){
        //     $arrData->like('noLs', $arrParam['noLs']);
        // }

        // if(!empty(trim($arrParam['tglLs'])) && !empty(trim($arrParam['tglAkhirLs']))){
        //     $arrData    = $lsModel->groupStart()
        //                 ->where("tglLs BETWEEN '".reverseDateDB($arrParam["tglLs"])."' AND '".reverseDateDB($arrParam["tglAkhirLs"])."'")
        //                     ->groupEnd();
        // }

        // if(!empty(trim($arrParam['tglLs'])) && empty(trim($arrParam['tglAkhirLs']))){
        //     $arrData->where('tglLs >=', reverseDateDB($arrParam['tglLs']));
        // }

        // if(empty(trim($arrParam['tglLs'])) && !empty(trim($arrParam['tglAkhirLs']))){
        //     $arrData->where('tglLs <=', reverseDateDB($arrParam['tglAkhirLs']));
        // }

        $recordsTotal   = $arrData->countAllResults(false);
        $arrData        = $arrData->orderBy('id', 'desc')->limit($this->request->getPost('length'), $this->request->getPost('start'))->get()->getResult();
        $row            = [];

        $no             = $this->request->getPost('start')+1;
        $masterStatus   = $this->db->table('m_status_proses')->get()->getResult();

        foreach ($arrData as $key => $data) {

            // $arrRs = $this->db->table('t_laporan_bulanandtl')->select('statusDok,COUNT(id) as jml')->where('idJenisLS','1')->where('statusProses','ISSUED')->where('MONTH(tglLs)',$data->bulan)->where('YEAR(tglLs)',$data->tahun)->groupBy('statusDok')->get()->getResult();
            // $summaryCoal['TERBIT'] = "-";
            // $summaryCoal['PERUBAHAN'] = "-";
            // $summaryCoal['DIBATALKAN'] = "-";
            // foreach ($arrRs as $key => $rs) {
            //     $summaryCoal[$rs->statusDok] = $rs->jml;
            // }

            $arrSummary["TERBIT"] = $this->db->table('t_laporan_bulanandtl')->where('idLaporan',$data->id)->whereNotIn('statusDok',['DIHAPUS'])->where('idJenisTerbit',1)->where('statusProses','ISSUED')->where('MONTH(tglLs)',$data->bulan)->where('YEAR(tglLs)',$data->tahun)->countAllResults();
            $arrSummary["PERUBAHAN"] = $this->db->table('t_laporan_bulanandtl')->where('idLaporan',$data->id)->whereNotIn('statusDok',['DIHAPUS'])->where('idJenisTerbit',2)->where('statusProses','ISSUED')->where('MONTH(tglLs)',$data->bulan)->where('YEAR(tglLs)',$data->tahun)->countAllResults();
            $arrSummary["DIBATALKAN"] = $this->db->table('t_laporan_bulanandtl')->where('idLaporan',$data->id)->whereNotIn('statusDok',['DIHAPUS'])->where('statusDok','DIBATALKAN')->where('statusProses','ISSUED')->where('MONTH(tglLs)',$data->bulan)->where('YEAR(tglLs)',$data->tahun)->groupBy('noLs')->countAllResults();


            $btnDelete     = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-danger" onclick="delete_laporan(\''.encrypt_id($data->id).'\')" title="Hapus"><i class="fa fa-trash"></i></button> ';
            //$btnCabut    = '';
            //$btnLog      = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-success" onclick="view_log(\''.encrypt_id($data->id).'\')" title="Log"><i class="fa fa-history" aria-hidden="true"></i></button> ';
            $btnEdit   = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-warning" onclick="edit(\''.encrypt_id($data->id).'\')" title="Edit"><i class="fa fa-edit"></i></button> ';
            $btnView   = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-info" onclick="list_ls(\''.encrypt_id($data->id).'\')" title="Lihat Daftar LS"><i class="fa fa-eye"></i></button> ';
            $btnCetak  = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-secondary" onclick="print_laporan(\''.encrypt_id($data->id).'\')" title="Cetak Laporan"><i class="fa fa-print"></i></button> ';
            $btnKirimInatrade = '<div class="mt-4"><button type="button" class="btn btn-danger btn-kirim-inatrade" data-iddata="'.encrypt_id($data->id).'" ><i class="fa fa-cloud me-2" aria-hidden="true"></i>KIRIM LAPORAN</button></div>';

            if($data->statusProses == 'TERKIRIM' || $data->statusKirim == 'SENT'){
                $btnDelete = '';
                $btnEdit = '';
                $btnKirimInatrade = '';
            }

            if($data->jenis == "BARU")
                $badgeJenis = '<span class="badge bg-primary">'.$data->jenis.'</span>';
            else
                $badgeJenis = '<span class="badge bg-warning">'.$data->jenis.'</span>';


            $columns = [];
            $columns[] = $no++;
            $columns[] = $badgeJenis.'<br>'.$data->noLaporan;
            $columns[] = reverseDate($data->tglLaporan);
            $columns[] = strtoupper(nama_bulan(($data->bulan-1))).' - '.$data->tahun;
            $columns[] = '<div>Terbit : '.$arrSummary["TERBIT"].'</div><div>Perubahan : '.$arrSummary["PERUBAHAN"].'</div><div>Pembatalan : '.$arrSummary["DIBATALKAN"].'</div>';
            $columns[] = $data->statusProses.'<br><small>Created : '.reverseDateTime($data->createdate).'</small>';
            $columns[] = '<div class="btn-list text-nowrap">'.$btnDelete.$btnEdit.$btnView.$btnCetak.'</div>'.$btnKirimInatrade;
            $row[] = $columns;
        }

        $table['draw']              = $this->request->getPost('draw');
        $table['recordsTotal']      = $recordsTotal;
        $table['recordsFiltered']   = $recordsTotal;
        $table['data']              = $row;

        echo json_encode($table);
    }

    function draft_summary()
    {
        $bulan = $this->request->getPost('bulan');
        if($bulan != '')
        {
            $bulan = $bulan + 1;
            $bulan = str_pad($bulan,2,"0",STR_PAD_LEFT);
        }
        $tahun = $this->request->getPost('tahun');
        $arrData["TERBIT"] = $this->db->table('tx_lsehdr')->whereNotIn('statusDok',['DIBATALKAN','DIHAPUS'])->where('idJenisTerbit',1)->where('statusProses','ISSUED')->where('MONTH(tglLs)',$bulan)->where('YEAR(tglLs)',$tahun)->countAllResults();
        $arrData["PERUBAHAN"] = $this->db->table('tx_lsehdr')->whereNotIn('statusDok',['DIBATALKAN','DIHAPUS'])->where('idJenisTerbit',2)->where('statusProses','ISSUED')->where('MONTH(tglLs)',$bulan)->where('YEAR(tglLs)',$tahun)->countAllResults();
        $arrData["DIBATALKAN"] = $this->db->table('tx_lsehdr')->whereNotIn('statusDok',['DIHAPUS'])->where('statusDok','DIBATALKAN')->where('statusProses','ISSUED')->where('MONTH(tglLs)',$bulan)->where('YEAR(tglLs)',$tahun)->groupBy('noLs')->countAllResults();


        $html = '<table class="table table-bordered border-bottom w-100 text-center">';
        $html .= '<tr>';
        $html .= '<th>PENERBITAN</th>';
        $html .= '<th>PERUBAHAN</th>';
        $html .= '<th>PEMBATALAN</th>';
        $html .= '</tr>';

        $html .= '<tr>';
        $html .= '<td>'.$arrData["TERBIT"].'</td>';
        $html .= '<td>'.$arrData["PERUBAHAN"].'</td>';
        $html .= '<td>'.$arrData["DIBATALKAN"].'</td>';
        $html .= '</tr>';

        $html .= '<table>';
        $html .= '<small class="text-primary fw-bold">Untuk mengetahui detail LS dari summary diatas bisa menggunakan menu rekapitulasi</small>';

        $resp['data'] = $html;
        return $this->response->setJSON($resp);
    }

    function save_check()
    {
        if(!empty($this->request->getPost('idLaporan')))
            $idLaporan = decrypt_id($this->request->getPost('idLaporan'));

        $bulan = $this->request->getPost('bulan');
        if($bulan != '')
        {
            $bulan = $bulan + 1;
        }
        $tahun = $this->request->getPost('tahun');

        if(isset($idLaporan)){
            $konsep = $this->db->table('t_laporan_bulanan')->where('id !=',$idLaporan)->where('bulan',$bulan)->where('tahun',$tahun)->where('statusProses','KONSEP')->countAllResults();
            $terkirim = $this->db->table('t_laporan_bulanan')->where('id !=',$idLaporan)->where('bulan',$bulan)->where('tahun',$tahun)->where('statusProses','TERKIRIM')->countAllResults();
        }
        else{
            $konsep = $this->db->table('t_laporan_bulanan')->where('bulan',$bulan)->where('tahun',$tahun)->where('statusProses','KONSEP')->countAllResults();
            $terkirim = $this->db->table('t_laporan_bulanan')->where('bulan',$bulan)->where('tahun',$tahun)->where('statusProses','TERKIRIM')->countAllResults();
        }

        if(!isset($idLaporan))
        {
            if($konsep > 0)
            {
                $resp = resp_error('Gagal membuat laporan. Ada konsep laporan untuk periode bulan dan tahun yang dipilih. Silahkan edit atau hapus data terlebih dahulu');
            }
            else if($terkirim > 0)
            {
                $resp['code'] = '01';
                $resp['msg'] = '';
            }
        }
        elseif(isset($idLaporan))
        {
            $resp = resp_success('Success');
        }

        if($konsep == 0 && $terkirim == 0)
        {
            $resp = resp_success('Success');
        }
        return $this->response->setJSON($resp);
        exit();

    }

    function save_draft()
    {
        if(!empty($this->request->getPost('idLaporan')))
            $idLaporan = decrypt_id($this->request->getPost('idLaporan'));

        $noLaporan = $this->request->getPost('noLaporan');
        $tglLaporan = $this->request->getPost('tglLaporan');
        $bulan = $this->request->getPost('bulan');
        if($bulan != '')
        {
            $bulan = $bulan + 1;
            $bulan = str_pad($bulan,2,"0",STR_PAD_LEFT);
        }
        $tahun = $this->request->getPost('tahun');
        $act = $this->request->getPost('act');

        if(empty($bulan) || empty($tahun))
        {
            $resp = resp_error('Bulan & tahun wajib di isi');
            return $this->response->setJSON($resp);
            exit;
        }

        $fileUpload = $this->request->getFile('fileLaporan');
        if($fileUpload){
            $validationRule = [
                'fileLaporan' => [
                    'rules' => [
                        'uploaded[fileLaporan]',
                        'mime_in[fileLaporan,image/jpg,image/jpeg,image/png,image/webp,application/pdf]',
                        'max_size[fileLaporan,5120]',
                    ],
                    'errors' => [
                        'uploaded' => 'File belum di pilih',
                        'mime_in' => 'File yang diupload harus berupa image atau pdf dengan format jpg/png/pdf',
                        'max_size' => 'Ukuran file maksimal 5 MB'
                    ]
                ],
            ];
            if (! $this->validate($validationRule)) {
                $resp = resp_error($this->validator->getError(),'','Upload Gagal');
                return $this->response->setJSON($resp);
            }
            else{

                if (! $fileUpload->hasMoved()) {
                    $pathFile = $fileUpload->store('laporan');
                }
                else{
                    throw new \Exception('Gagal upload.');
                }
            }
        }

        if($act == "PERUBAHAN")
        {
            $dataLaporan = $this->db->table('t_laporan_bulanan')->where('bulan',($bulan+0))->where('tahun',$tahun)->where('statusProses','TERKIRIM')->get()->getRow();
            $noLaporan = $dataLaporan->noLaporan;
            $tglLaporan = reverseDate($dataLaporan->tglLaporan); // di reverese date krn ketika insert akan di reversedatedb
        }

        $laporan = [
            'jenis'       => $act,
            'bulan'       => $bulan,
            'tahun'        => $tahun,
            'noLaporan'        => $noLaporan,
            'tglLaporan' => reverseDateDB($tglLaporan),
            'statusProses' => 'KONSEP',
            'statusKirim' => 'READY',
            'createdate' => date('Y-m-d H:i:s')
        ];

        if(isset($pathFile))
            $laporan['pathFile'] = $pathFile;

        $this->db->transBegin();

        if(isset($idLaporan))
        {
            $data = $this->db->table('t_laporan_bulanan')->where('id',$idLaporan)->get()->getRow();
            if(empty($data->urlFile))
                $laporan['urlFile'] = hash('SHA256',pack('H*', uniqid()));

            $this->db->table('t_laporan_bulanandtl')->where('idLaporan',$idLaporan)->delete();
            $this->db->table('t_laporan_bulanan')->where('id',$idLaporan)->update($laporan);
        }
        else
        {
            $laporan['id'] = new RawSql('DEFAULT');
            $laporan['urlFile'] = hash('SHA256',pack('H*', uniqid()));
            $this->db->table('t_laporan_bulanan')->insert($laporan);
            $idLaporan = $this->db->insertID();
        }

        $arrDtl = $this->db->table('tx_lsehdr')->select('NULL as id, '.$idLaporan.' as idLaporan ,id as idLs, idJenisTerbit, concat(bentukPersh,". ",namaPersh) as namaPerusahaan,noLs,tglLs,idJenisLS,statusDok,statusProses,statusKirim')->where('statusProses','ISSUED')->where('MONTH(tglLs)',$bulan)->where('YEAR(tglLs)',$tahun)->get()->getResult();
        $insert = [];
        foreach ($arrDtl as $row) {
            $insert[] = (array)$row;
        }

        if(count($insert) > 0)
            $save = $this->db->table('t_laporan_bulanandtl')->insertBatch($insert);

        if ($this->db->transStatus() === false) {
            $this->db->transRollback();
            $resp = resp_error('Gagal membuat laporan ');
        } else {
            $this->db->transCommit();
            $resp = resp_success('Draft laporan berhasil dibuat');
        }
        return $this->response->setJSON($resp);
        exit;
    }

    function edit()
    {
        try {
            $idLaporan = decrypt_id($this->request->getPost('idLaporan'));
            $laporan = $this->db->table('t_laporan_bulanan')->where('id',$idLaporan)->get()->getRow();

            $laporan->id = encrypt_id($laporan->id);
            $resp['code'] = '00';
            $resp['data'] = $laporan;

            if(is_file(WRITEPATH.'uploads/'.$laporan->pathFile))
                $resp['isFile'] = 1;

            return $this->response->setJSON($resp);
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!'.$e->getMessage());
            return $this->response->setJSON($resp);
        }
    }

    function delete()
    {
        try {
            $idLaporan = decrypt_id($this->request->getPost('idLaporan'));

            $this->db->transBegin();
            $laporan = $this->db->table('t_laporan_bulanan')->where('id',$idLaporan)->get()->getRow();

            $this->db->table('t_laporan_bulanan')->where('id',$idLaporan)->delete();
            $this->db->table('t_laporan_bulanandtl')->where('idLaporan',$idLaporan)->delete();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                $resp = resp_error('Gagal menghapus laporan');
            } else {
                if(!empty($laporan->pathFile) && is_file(WRITEPATH.'uploads/'.$laporan->pathFile))
                    unlink(WRITEPATH.'uploads/'.$laporan->pathFile);

                $this->db->transCommit();
                $resp = resp_success('Laporan berhasil dihapus');
            }
            return $this->response->setJSON($resp);
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!'.$e->getMessage());
            return $this->response->setJSON($resp);
        }
    }

    function list_detail()
    {
        $idLaporan = decrypt_id($this->request->getPost('idLaporan'));

        $laporan = $this->db->table('t_laporan_bulanan')->where('id',$idLaporan)->get()->getRow();
        //$detail = $this->db->table('t_laporan_bulanandtl')->where('idLaporan',$idLaporan)->get()->getRow();

        $arrData = $this->db->table('t_laporan_bulanandtl')->where('idLaporan',$idLaporan);

        $searchParam    = $this->request->getPost('searchParam');
        if($searchParam)
            $arrParam       = post_ajax_toarray($searchParam);

        if(!empty($arrParam['jenisls'])){
            if($arrParam['jenisls'] == "COAL")
                $arrData->where('idJenisLS', '1');
            else if($arrParam['jenisls'] == "MINERAL")
                $arrData->where('idJenisLS !=', '1');
            else
                $arrData->where($arrParam['jenisls']);
        }

        if(!empty($arrParam['jeniterbit'])){
            if($arrParam['jeniterbit'] == "BARU")
                $arrData->where('idJenisTerbit', 1);
            else if($arrParam['jeniterbit'] == "PERUBAHAN")
                $arrData->where('idJenisTerbit', 2);
            else if($arrParam['jeniterbit'] == "PEMBATALAN")
                $arrData->where('statusDok', 'DIBATALKAN');
        }

        if(!empty($arrParam['nols'])){
            $arrData->like('noLs', $arrParam['nols']);
        }

        if(!empty($arrParam['namapersh'])){
            $arrData->like('namaPerusahaan', $arrParam['namapersh']);
        }


        $recordsTotal   = $arrData->countAllResults(false);
        $arrData        = $arrData->orderBy('idJenisLS')->orderBy('tglLs')->orderBy('noLs')->limit($this->request->getPost('length'), $this->request->getPost('start'))->get()->getResult();
        $row            = [];

        $no             = $this->request->getPost('start')+1;
        $masterJenisLs   = $this->db->table('m_jenisls')->get()->getResult();
        $arrJenisLs  = array_column($masterJenisLs, 'markJenis','id');

        $arrJenisTerbit = [1=>"BARU",2=>"PERUBAHAN"];

        foreach ($arrData as $key => $data) {

            $columns = [];
            $columns[] = $no++;
            $columns[] = $arrJenisLs[$data->idJenisLS];
            $columns[] = $data->namaPerusahaan;
            $columns[] = $data->noLs;
            $columns[] = reverseDate($data->tglLs);
            if($data->statusDok == "DIBATALKAN")
                $columns[] = $arrJenisTerbit[$data->idJenisTerbit].'<br><span class="text-danger">PEMBATALAN</span>';
            else
                $columns[] = $arrJenisTerbit[$data->idJenisTerbit];
            $row[] = $columns;
        }

        $table['draw']              = $this->request->getPost('draw');
        $table['recordsTotal']      = $recordsTotal;
        $table['recordsFiltered']   = $recordsTotal;
        $table['data']              = $row;
        $table['laporan']   = $laporan;
        if(is_file(WRITEPATH.'uploads/'.$laporan->pathFile))
            $table['isFile']   = 1;
        else
            $table['isFile']   = 0;

        echo json_encode($table);
    }

    function delete_file()
    {
        try {
            $idLaporan = decrypt_id($this->request->getPost('idLaporan'));

            $this->db->transBegin();
            $laporan = $this->db->table('t_laporan_bulanan')->where('id',$idLaporan)->get()->getRow();
            $pathFile = $laporan->pathFile;

            $this->db->table('t_laporan_bulanan')->set('pathFile', NULL)->where('id',$idLaporan)->update();

            if ($this->db->transStatus() === false) {
                $this->db->transRollback();
                $resp = resp_error('File gagal dihapus');
            } else {
                if(!empty($pathFile) && is_file(WRITEPATH.'uploads/'.$pathFile))
                    unlink(WRITEPATH.'uploads/'.$pathFile);

                $this->db->transCommit();
                $resp = resp_success('File berhasil dihapus');
            }
            return $this->response->setJSON($resp);
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!'.$e->getMessage());
            return $this->response->setJSON($resp);
        }
    }

    public function view_file()
    {
        try{
            $idLaporan = decrypt_id($this->request->getPost('idLaporan'));
            $laporan = $this->db->table('t_laporan_bulanan')->where('id',$idLaporan)->get()->getRow();

            $path = WRITEPATH.'uploads/'.$laporan->pathFile;
            if(is_file($path))
                return $this->response->download($path,null,true)->inline();
            else
                return redirect()->to('filenotfound');
        } catch (\Throwable $e) {
            $resp = 'An Exception has occured at view file';
            return $this->response->setJSON($resp);
        }

    }
}