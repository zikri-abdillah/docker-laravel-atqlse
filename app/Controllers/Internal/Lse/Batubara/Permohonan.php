<?php

namespace App\Controllers\Internal\Lse\Batubara;

use App\Controllers\BaseController;
use CodeIgniter\Files\File;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
// use Psr\Log\LoggerInterface;


class Permohonan extends BaseController
{
    public function index()
    {
        $req = $this->request->getGet();
        $page = [
            'table_title'   => 'Permohonan LSE Batubara - LNSW',
            'breadcrumb_active'   => 'Data Permohonan - LNSW'
        ];
        $param['content'] = $this->render('ekspor.batubara.permohonan.index', $page);

        $param['addJS'] = '<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . '/assets/plugins/datatable/js/dataTables.bootstrap5.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . '/assets/plugins/datatable/js/dataTables.buttons.min.js"></script>'; 
        $param['addJS'] .= '<script src="' . base_url() . '/assets/plugins/datatable/dataTables.responsive.min.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . '/assets/plugins/datatable/responsive.bootstrap5.min.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . '/js/ekspor/coal/permohonan.js?v='.date('YmdHis').'"></script>';
        return $this->render('layout.template', $param);
    }

    public function list()
    {
        $searchParam    = $this->request->getPost('searchParam');
        $arrParam       = post_ajax_toarray($searchParam); 
        $lsModel        = model('t_inswPermohonan'); 
        $arrData        = $lsModel->where('status <>',99);

        if(!empty($arrParam['idJenisTerbit'])){
            $arrData->where('jnspengajuan', $arrParam['idJenisTerbit']);
        }

        if(!empty($arrParam['nomorAju'])){
            $arrData->like('nomorAju', $arrParam['nomorAju']);
        }

        if(!empty($arrParam['nomorPermohonan'])){
            $arrData->like('nomorPermohonan', $arrParam['nomorPermohonan']);
        }

        if(!empty($arrParam['namaPerusahaan'])){
            $arrData->like('namaPerusahaan', $arrParam['namaPerusahaan']);
        }

        if(!empty($arrParam['nomorEt'])){
            $arrData->like('nomorEt', $arrParam['nomorEt']);
        }

        if(!empty($arrParam['namaAlatPengirim'])){
            $arrData->like('namaAlatPengirim', $arrParam['namaAlatPengirim']);
        }

        $recordsTotal   = $arrData->countAllResults(false);
        $arrData        = $arrData->orderBy('id','desc')->findAll($this->request->getPost('length'), $this->request->getPost('start'));
        $row            = [];
        $no             = $this->request->getPost('start')+1;

        foreach ($arrData as $key => $data) { 

            $btnView    = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-info" onclick="view(\''.encrypt_id($data->id).'\')" title="Lihat"><i class="fa fa-eye"></i></button> ';
            $btnLog     = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-success" onclick="view_log_lnsw(\''.encrypt_id($data->id).'\')" title="Log"><i class="fa fa-history" aria-hidden="true"></i></button> ';
            $btnPengembalian     = '<button type="button" class="btn btn-sm btn-w-xs btn-icon btn-danger" onclick="pengembalian(\''.encrypt_id($data->id).'\')" title="Log"><i class="fa fa-ban" aria-hidden="true"></i></button> ';

            if($data->statusInsw == '040' || $data->statusInsw == '045' || $data->statusInsw == '050')
                $btnPengembalian = '';

            $perizinan  = model('tx_lseReferensi');
            $perizinan->select('tx_lse_referensi.id as idref,t_dokpersh.*');
            $perizinan->join('t_dokpersh', 't_dokpersh.id = tx_lse_referensi.idDokPersh');
            $perizinan->where('tx_lse_referensi.idJenisDok',1)->where('tx_lse_referensi.idLS',$data->id);
            $arrET      = $perizinan->first(); 
            $noET       = $tglET = $tglAkhirET = NULL;

            if(isset($arrET->noDokumen))
            {
                $noET       = $arrET->noDokumen;
                $tglET      = reverseDate($arrET->tglDokumen);
                $tglAkhirET = reverseDate($arrET->tglAkhirDokumen);
            }

            $portMuat   = model('t_inswPermohonanPort')->where('idPermohonan',$data->id)->where('kodeKegiatan',9)->first();
            if(empty($portMuat->kodePelabuhan))
                $portMuat   = model('t_inswPermohonanPort')->where('idPermohonan',$data->id)->where('kodeKegiatan',2)->first();
            $portTujuan = model('t_inswPermohonanPort')->where('idPermohonan',$data->id)->where('kodeKegiatan',5)->first();

            $columns   = [];
            $columns[] = $no++;
            $columns[] = '<span>Jenis Perngajuan: '.$data->uraiJenisPengajuan.'</span><br><span style="color:blue">AJU: '.$data->nomorAju.'</span><br><span>Tgl: '.reverseDateTime($data->tanggalAju).'</span><br><span style="color:#03a9f4;">No Permohonan: '.$data->nomorPermohonan.'</span><br><span>Tgl: '.reverseDateTime($data->tglPermohonan).'<br><span>Diterima : '.reverseDateTime($data->created).'</span>';
            $columns[] = '<span>'.$data->namaPerusahaan.'</span><br><span>'.formatNPWP($data->nomorIdentitas).'</span><br><span>'.$data->namaLokasi.'</span>';
            $columns[] = '<span>'.$data->nomorEt.'</span>';
            $columns[] = '<span>Pel Muat: '.$portMuat->kodePelabuhan.' - '.$portMuat->namaPelabuhan.'</span><br><span>Pel Tujuan: '.$portTujuan->kodePelabuhan.' - '.$portTujuan->namaPelabuhan.'</span><br><span>Transport: '.$data->namaAlatPengirim.'</span>';
            $columns[] = $data->status;
            $columns[] = '<div class="btn-list text-nowrap">'.$btnView.$btnLog.$btnPengembalian.'</div>';
            $row[]     = $columns;
        }

        $table['draw']            = $this->request->getPost('draw');
        $table['recordsTotal']    = $recordsTotal;
        $table['recordsFiltered'] = $recordsTotal;
        $table['data']            = $row;

        echo json_encode($table);

    }

    public function view()
    {
        $id         = decrypt_id($this->request->getPost('id'));
        $datals     = model('tx_lseHdr')->find($id);
        $packages   = model('tx_lsePackage')->where('idLS',$id)->findAll();
        $containers = model('tx_lseContainer')->where('idLS',$id)->findAll();

        $refModel   = model('tx_lseReferensi');
        $refModel->select('tx_lse_referensi.id as idref,t_dokpersh.*,m_negara.nama as negara');
        $refModel->join('t_dokpersh', 't_dokpersh.id = tx_lse_referensi.idDokPersh');
        $refModel->join('m_negara', 'm_negara.kode = t_dokpersh.negaraPenerbit');
        $refModel->where('tx_lse_referensi.idLS',$id);

        //$references = $refModel->findAll();
        $royaltis   = model('tx_lseNtpn')->where('idLS',$id)->findAll();
        $komoditas  = model('tx_lseDtlHs')->where('idLS',$id)->findAll();

        $pengajuan  = model('t_inswPermohonan')->find($id);
        $pelabuhans = model('t_inswPermohonanPort')->where('idPermohonan',$id)->findAll();
        $references = model('t_inswPermohonanDok')->where('idPermohonan',$id)->orderBy('id', 'ASC')->findAll();
        $komoditi   = model('t_inswPermohonanBrg')->where('idPermohonan',$id)->findAll();

        $page = [
            'page_title'    => 'Data Pengajuan Online LSE Batubara',
            'pengajuan'     => $pengajuan,
            'pelabuhans'    => $pelabuhans,
            'komoditi'      => $komoditi, 
            'datals'        => $datals,
            'packages'      => $packages,
            'containers'    => $containers,
            'references'    => $references,
            'royaltis'      => $royaltis,
            'komoditas'     => $komoditas
        ];

        $param['addJS'] = '<script src="' . base_url() . '/assets/plugins/formwizard/jquery.smartWizard.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . '/assets/plugins/formwizard/fromwizard.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . '/assets/plugins/jquery-steps/jquery.steps.min.js"></script>';
        $param['addJS'] .= '<script src="' . base_url() . '/assets/plugins/parsleyjs/parsley.min.js"></script>'; 
        $param['addJS'] .= '<script src="' . base_url() . '/js/ekspor/coal/permohonan.js?v='.date('YmdHis').'"></script>';

        $param['content'] = $this->render('ekspor.batubara.permohonan.view', $page);
        return $this->render('layout.template', $param);
    }

    public function create_ls()
    {
        try {
            $id = decrypt_id($this->request->getPost('idPermohonan'));

            $permohonan = model('t_inswPermohonan')->find($id);

            $pelabuhans = model('t_inswPermohonanPort')->where('idPermohonan',$id)->findAll();
            $references = model('t_inswPermohonanDok')->where('idPermohonan',$id)->findAll();
            $komoditi   = model('t_inswPermohonanBrg')->where('idPermohonan',$id)->findAll();
            $portLoad   = model('t_inswPermohonanPort')->where('idPermohonan',$id)->where('kodeKegiatan', 9)->first();
            if(empty($portLoad))
                $portLoad   = model('t_inswPermohonanPort')->where('idPermohonan',$id)->where('kodeKegiatan', 2)->first();
            $portDisch  = model('t_inswPermohonanPort')->where('idPermohonan',$id)->where('kodeKegiatan', 5)->first();

            $this->db->transStart();

            // $eksportir = model('perusahaan')->where('npwp',clean_npwp($permohonan->npwp))->first();
            // if(empty($eksportir)){
            //     $resp = resp_error('NPWP (15 DIGIT) eksportir tidak ditemukan, silahkan input pada menu Data Client ');
            //     return $this->response->setJSON($resp);exit;
            // }


            if(strlen(clean_npwp($permohonan->nomorIdentitas)) == 15)
                $eksportir = model('perusahaan')->where('npwp',clean_npwp($permohonan->nomorIdentitas))->first();
            else
                $eksportir = model('perusahaan')->where('npwp16',clean_npwp($permohonan->nomorIdentitas))->first();

            if(empty($eksportir)){
                if(strlen(clean_npwp($permohonan->nomorIdentitas)) == 15)
                    $resp = resp_error('NPWP (15 DIGIT) eksportir tidak ditemukan, silahkan input pada menu Data Client ');
                else if(strlen(clean_npwp($permohonan->nomorIdentitas)) == 16)
                    $resp = resp_error('NPWP (16 DIGIT) eksportir tidak ditemukan, silahkan input pada menu Data Client ');
                else
                    $resp = resp_error('NPWP eksportir tidak ditemukan, silahkan input pada menu Data Client ');

                return $this->response->setJSON($resp);exit;
            }
            
            $dataLS['idJenisTerbit'] = $permohonan->jnspengajuan;
            $dataLS['jenisTerbit'] = $permohonan->uraiJenisPengajuan;
            $dataLS['idJenisLS'] = 1;
            $dataLS['jenisLS'] = 'LS Ekspor Batubara dan Produk Batubara';
            $dataLS['idPermohonanNSW']   = $permohonan->id;
            $dataLS['idPersh'] = $eksportir->id;
            $dataLS['nib'] = $eksportir->nib;

            $dataLS['kdProp'] = $eksportir->idProp;
            $dataLS['kdPropInatrade'] = $eksportir->kodeProp;
            $dataLS['namaProp'] = $eksportir->namaProp;

            $dataLS['kdKota'] = $eksportir->idKab;
            $dataLS['kdKotaInatrade'] = $eksportir->kodeKab;
            $dataLS['namaKota'] = $eksportir->namaKab;

            $dataLS['kodepos'] = $eksportir->kodePos;

            $dataLS['npwp'] = $permohonan->npwp;
            $dataLS['npwp16'] = $permohonan->npwp16;
            $dataLS['bentukPersh'] = $eksportir->bentukPersh;
            $dataLS['namaPersh'] = $permohonan->namaPerusahaan;
            $dataLS['alamatPersh'] = $permohonan->alamatPerusahaan;
            $dataLS['telpPersh'] = $permohonan->teleponPerusahaan;
            $dataLS['faxPersh']  = $permohonan->faxPerusahaan;
            $dataLS['emailPersh']    = $permohonan->emailPerusahaan;
            $dataLS['noET']  = $permohonan->nomorEt;

            $lsModel        = model('tx_lseHdr');
            $jnsLSModel = model('jenisLs');
            $jnsLS      = $jnsLSModel->find($dataLS['idJenisLS']);
            $dataLS['draftIncrement'] = $lsModel->select('IFNULL(MAX(draftIncrement),0)+1 as noDraft',false)->where('YEAR(created)',DATE('Y'))->get()->getRow()->noDraft;
            $noDraft                = str_pad($dataLS['draftIncrement'],4,"0",STR_PAD_LEFT);
            $dataLS['draftNo']      = DATE('y').'/'.DATE('m').'/'.PREFIX_NUMBERING.'/'.$noDraft;
            $dataLS['tglDraft']     = date('Y-m-d');

            $dataLS['ajuNSW'] = $permohonan->nomorAju;
            $dataLS['noSi'] = $permohonan->nomorPermohonan;
            $dataLS['tglSi']    = $permohonan->tglPermohonan;
            if(!empty($permohonan->jenisIup))
            {
                $iup = $this->db->table('m_jenis_iup')->where('kdInsw',$permohonan->jenisIup)->get()->getRow();
                $dataLS['idJnsIUP']    = $iup->id;
                $dataLS['jenisIUP']    = $iup->jenis;
            }


            $dataLS['namaImportir'] = $permohonan->namaImportir;
            $dataLS['alamatImportir'] = $permohonan->alamatImportir;
            $dataLS['kdNegaraImportir']   = $permohonan->negaraImportir;

            if(!empty($permohonan->negaraImportir)){
                $negaraImp = model('negara')->where('kode',$permohonan->negaraImportir)->first();
                $dataLS['negaraImportir'] = $negaraImp->nama;
            }

            $dataLS['namaAsuransiKapal']   = $permohonan->nmPerusahaanAsuransiKapal;
            $dataLS['namaAsuransiKargo']   = $permohonan->nmPerusahaanAsuransiCargo;
            $dataLS['namaTransport']    = $permohonan->namaAlatPengirim;
            $dataLS['kodeIncoterm']    = $permohonan->namaAlatPengirim;

            $incoterm = $this->db->table('tblPenjualan_pinsw')->where('kode',$permohonan->penjualan)->get()->getRow();
            if(isset($incoterm->id) && !empty($incoterm->id)){
                $dataLS['kodeIncoterm']= $incoterm->kodeInatrade;
                $dataLS['incoterm']    = $incoterm->kodeInatrade.' - '.$incoterm->uraianInatrade;
            }

            if(!empty($portLoad))
            {
                $dataLS['kodePortMuat']     = $portLoad->kodePelabuhan;
                $dataLS['portMuat']         = $portLoad->namaPelabuhan;
            }
            if(!empty($portDisch))
            {
                $dataLS['kodePortTujuan']   = $portDisch->kodePelabuhan;
                $dataLS['portTujuan']       = $portDisch->namaPelabuhan;
                $dataLS['kodeNegaraTujuan'] = substr($portDisch->kodePelabuhan,0,2);
                $dataLS['negaraTujuan']     = $portDisch->namaNegara;
            }

            $dataLS['statusProses']     = 'PROCESS';
            $dataLS['userCreate']       = decrypt_id(session()->get('sess_userid'));
            $dataLS['created']          = date('Y-m-d H:i:s');

            $this->db->table('tx_lsehdr')->insert($dataLS);
            $idLS = $this->db->insertID();

            $this->db->table('tx_lsedtlhs')->where('idLS',$idLS)->delete();
            $this->db->table('tx_lse_ntpn')->where('idLS',$idLS)->delete();
            foreach ($komoditi as $key => $barang) {
                $dataKomoditi['idLs']               = $idLS;
                $dataKomoditi['seri']               = ($key+1);
                $dataKomoditi['postarif']           = $barang->kodeHs;
                $dataKomoditi['uraianBarang']       = $barang->uraianBarang;
                $dataKomoditi['jumlahBarang']       = $barang->jumlahTonase;
                $dataKomoditi['kdSatuanBarang']     = 'TNE';
                $dataKomoditi['uraiSatuanBarang']   = 'METRIK TON';
                $dataKomoditi['hargaBarang']        = $barang->fob;

                $this->db->table('tx_lsedtlhs')->insert($dataKomoditi);


                $idDtl = $this->db->insertID();

                $kerjasamas = model('t_inswPermohonanBrgKerjasama')->where('idPermohonan',$id)->where('idBarang',$barang->id)->findAll();
                foreach ($kerjasamas as $key => $kerjasama) {
                    $dataNTPN['idLs'] = $idLS;
                    $dataNTPN['idNtpnNsw'] = $kerjasama->id;
                    $dataNTPN['noNtpn'] = $kerjasama->nomorNtpn;
                    $dataNTPN['tglNtpn'] = $kerjasama->tanggalNtpn;
                    $dataNTPN['volume'] = $kerjasama->tonaseEkspor;

                    $this->db->table('tx_lse_ntpn')->insert($dataNTPN);
                    $idNTPN = $this->db->insertID();

                    $dataHSNTPN['idPosTarif'] = $idDtl;
                    $dataHSNTPN['idNtpn'] = $idNTPN;
                    $this->db->table('tx_lsehsntpn')->insert($dataHSNTPN);
                }
            }
            if($idLS)
            {
                $dataLog['logAction'] = 'CREATE';
                $dataLog['idLS']      = $idLS;
                $dataLog['note']      = 'Create LS dari pengajuan SIMBARA AJU '. $permohonan->nomorAju;

                $LSinsert = model('tx_lseHdr')->find($idLS);

                $this->db->table('tblPermohonan_pinsw')->set(['status'=>'PROSES','statusInsw'=>'030','uraiStatusInsw'=>'Proses Pemeriksaan','timeStatusInsw'=>DATE('Y-m-d H:i:s')])->where('id', $id)->update();
                save_log_process($dataLog,$LSinsert);
                save_log_simbara($permohonan->id,'030','Proses pemeriksaan','Create Draft LS','ID LS '.$idLS);
            }

            $this->db->transComplete();
            if ($this->db->transStatus() === false)
                throw new \CodeIgniter\Database\Exceptions\DatabaseException();
            else{
                $respData['id'] = $idLS;

                // $resp = resp_success('Berhasil membuat draft LS. Data dapat dilihat pada menu LS Konsep',$respData);
                $resp = resp_success('Data berhasil disimpan dengen nomor draft '. $permohonan->nomorPermohonan.'_'.uniqid() .', dapat dilihat pada menu LS Konsep',$respData);
            }
            return $this->response->setJSON($resp);
        } catch (\CodeIgniter\Database\Exceptions\DatabaseException $e) {
            var_dump($e);exit;
        } catch (\Throwable $e) {
            var_dump($e);exit('aaa');
            $resp = resp_error('An Exception has occured!'.$e->getMessage().' Line '.$e->getLine());
            return $this->response->setJSON($resp);
        }

    }
}
