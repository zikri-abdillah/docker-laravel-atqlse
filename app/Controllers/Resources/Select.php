<?php

namespace App\Controllers\Resources;

use CodeIgniter\RESTful\ResourceController;


class Select extends ResourceController
{
    public function cabang()
    {
        $model = model('App\Models\cabang');

        $q = $this->request->getPost('q');
        if ($q) {
            $model->like('cabang', $q, 'both');
        }
        $arrData = $model->select('id, cabang as text')->where('isActive', 'Y')->where('isDelete', 'N')->orderBy('cabang', 'ASC')->findAll();
        $return['data'] = $arrData;
        return $this->response->setJSON($return);
    }

    public function ttd()
    {
        $model = model('App\Models\penandatangan');

        $q = $this->request->getPost('q');
        if ($q) {
            $model->like('nama', $q, 'both');
        }

        $arrData = $model->select('id, nama as text')->where('isActive', 'Y')->orderBy('nama', 'ASC')->findAll();
        $return['data'] = $arrData;
        return $this->response->setJSON($return);
    }

    public function perusahaan()
    {
        $model = model('App\Models\perusahaan');

        $q = $this->request->getPost('q');
        if ($q) {
            $model->like('nama', $q, 'both');
        }

        $arrData = $model->select('*, nama as text')->where('isActive', 'Y')->findAll();
        $return['data'] = $arrData;
        return $this->response->setJSON($return);
    }

    public function jenisiup()
    {
        $model = model('App\Models\jenisIup');

        $q = $this->request->getPost('q');
        if ($q) {
            $model->like('jenis', $q, 'both');
        }

        $arrData = $model->select('id, jenis as text')->where('isActive', 'Y')->orderBy('jenis', 'ASC')->findAll();
        $return['data'] = $arrData;
        return $this->response->setJSON($return);
    }

    public function propinsi()
    {
        $model = model('App\Models\propinsi');

        $q = $this->request->getPost('q');
        if ($q) {
            $model->like('namaPropinsi', $q, 'both');
        }

        $arrData = $model->select('id, namaPropinsi as text, kodeInatrade')->where('isActive', 'Y')->orderBy('namaPropinsi', 'ASC')->findAll();
        $return['data'] = $arrData;
        return $this->response->setJSON($return);
    }

    public function kota()
    {
        $model = model('App\Models\kota');

        $q = $this->request->getPost('q');
        $filter = $this->request->getPost('filter');
        if ($q) {
            $model->like('kodeUNLOCODE', $q, 'both')->orLike('namaKota', $q, 'both');
        }

        if ($filter == 'ln') {
            $model->where('kodeNegara !=', 'ID');
            $arrData = $model->select('id, CONCAT(kodeUNLOCODE," - ",namaKota) as text, kodeUNLOCODE as kodeInatrade, namaKota')->where('isActive', 'Y')->findAll();
        } else {
            $arrData = $model->select('id, namaKota as text, kodeInatrade, namaKota')->where('isActive', 'Y')->findAll();
        }
        $return['data'] = $arrData;
        return $this->response->setJSON($return);
    }

    public function negara()
    {
        $model = model('App\Models\negara');

        $q = $this->request->getPost('q');
        if ($q) {
            $model->like('nama', $q, 'both')->orLike('kode', $q, 'both');
        }

        $arrData = $model->select('kode as id, CONCAT(kode," - ",nama) as text, nama')->where('isActive', 'Y')->findAll();
        $return['data'] = $arrData;
        return $this->response->setJSON($return);
    }

    public function incoterm()
    {
        $model = model('App\Models\incoterm');

        $q = $this->request->getPost('q');
        if ($q) {
            $model->like('kode', $q, 'both')->orLike('uraian', $q, 'both');
        }

        $arrData = $model->select('kode as id, CONCAT(kode," - ",uraian) as text, uraian')->where('isActive', 'Y')->findAll();
        $return['data'] = $arrData;
        return $this->response->setJSON($return);
    }

    public function port()
    {
        $model = model('App\Models\port');

        $q = $this->request->getPost('q');
        $filter = $this->request->getPost('filter');
        if ($q) {
            $model->like('kode', $q, 'both')->orLike('uraian', $q, 'both');
        }

        if ($filter == 'ln') {
            $model->groupStart();
            $model->where('kdNegara !=', 'ID')->orWhere('kdNegara is null');
            $model->groupEnd();
        } else if ($filter == 'id') {
            $model->where('kdNegara', 'ID');
        }

        $arrData = $model->select('kode as id, CONCAT(kode," - ",uraian) as text, uraian as namaPort')->where('isActive', 'Y')->findAll(0, 100);
        $return['data'] = $arrData;
        return $this->response->setJSON($return);
    }

    public function moda()
    {
        $model = model('App\Models\modaTransport');

        $q = $this->request->getPost('q');
        if ($q) {
            $model->like('nama', $q, 'both');
        }

        $arrData = $model->select('kdinatrade as id, nama as text')->where('isActive', 'Y')->findAll();
        $return['data'] = $arrData;
        return $this->response->setJSON($return);
    }

    public function currency()
    {
        $model = model('App\Models\currency');

        $q = $this->request->getPost('q');
        if ($q) {
            $model->like('kode', $q, 'both')->orLike('uraian', $q, 'both');
        }

        $arrData = $model->select('kode as id, CONCAT(kode," - ",uraian) as text')->where('isActive', 'Y')->findAll();
        $return['data'] = $arrData;
        return $this->response->setJSON($return);
    }

    public function jenisdok()
    {
        $model = model('App\Models\jenisDokumen');

        $q = $this->request->getPost('q');
        if ($q) {
            $model->like('jenisDokumen', $q, 'both');
        }

        $arrData = $model->select('id, jenisDokumen as text')->where('isActive', 'Y')->findAll();
        $return['data'] = $arrData;
        return $this->response->setJSON($return);
    }

    public function satuan()
    {
        $model = model('App\Models\satuan');

        $q = $this->request->getPost('q');
        if ($q) {
            $model->like('kodeSatuan', $q, 'both')->orLike('uraiSatuan', $q, 'both');
        }

        $arrData = $model->select('kodeSatuan as id, CONCAT(kodeSatuan," - ",uraiSatuan) as text, uraiSatuan')->where('isActive', 'Y')->findAll();
        $return['data'] = $arrData;
        return $this->response->setJSON($return);
    }

    public function hs()
    {
        $npwpPE     = $this->request->getPost('npwpPE');

        if (!empty($this->request->getPost('idData')))
            $idLS   = decrypt_id($this->request->getPost('idData'));
        $q          = $this->request->getPost('q');
        $jenisLS    = $this->request->getPost('filter');

        if (!empty($jenisLS)) {
            $lsNeedIzinPersetujuan = model('jenisLs')->find($jenisLS)->isIzinPersetujuan;

            if ($lsNeedIzinPersetujuan == 'Y') {
                if (isset($idLS)) {
                    $db         = \Config\Database::connect();
                    $dokPE      = $db->table('tx_lse_referensi')
                        ->join('t_dokpersh', 'tx_lse_referensi.idDokPersh = t_dokpersh.id')
                        ->where('idLS', $idLS)->where('tx_lse_referensi.idJenisDok', 4)
                        ->get()->getRow();
                    $npwpPE     = $dokPE ? $dokPE->npwp : '';
                    $noPE       = $dokPE ? $dokPE->noDokumen : '';
                    $tglPE      = $dokPE ? $dokPE->tglDokumen : '';
                    $tglAkhirPE = $dokPE ? $dokPE->tglAkhirDokumen : '';
                }
            }
        }

        $userRole = session('sess_role');

        // if role pelaku usaha
        if ($userRole == 10)
            $lsNeedIzinPersetujuan = 'N';

        if (empty($jenisLS)) {
            $err = '-- Silahkan pilih Jenis Komoditi LS --';
        } else {
            if ($lsNeedIzinPersetujuan == 'Y' && (empty($npwpPE) || empty($noPE) || empty($tglPE) || empty($tglAkhirPE))) {
                $err = '-- Silahkan upload dokumen PE --';
            } else {

                if ($lsNeedIzinPersetujuan == 'Y' && !empty($noPE) && !empty($tglPE) && !empty($tglAkhirPE)) {
                    $izin = $db->table('t_izin_inatradeHdr')->where('npwp', clean_npwp($npwpPE))->where('no_izin', $noPE)->where('tgl_izin', $tglPE)->where('tgl_akhir', $tglAkhirPE)->get()->getRow();
                    if ($izin) {
                        $arrData = $db->table('t_izin_inatradeKomoditas')->select('pos_tarif as id, pos_tarif as text,seri, ur_barang as uraiHs,jml_volume,jns_satuan,uraiSatuan')
                            ->join('m_satuan', 't_izin_inatradeKomoditas.jns_satuan = m_satuan.kodeSatuan', 'LEFT')
                            ->where('idHdr', $izin->id)->get()->getResult();
                    } else {
                        $err = '-- Data SPE ' . $dokPE->noDokumen . ' tidak ditemukan. Pastikan sudah melakukan check izin --';
                    }
                } else {
                    $model = model('App\Models\hs');
                    $model->select('hs as id, hs as text, uraiHs')->where('isActive', 'Y')->where('idJnsLs', $jenisLS);
                    if ($q) {
                        $model->like('hs', $q, 'both')->orLike('uraiHs', $q, 'both');
                    }

                    // if($jenisLS) {
                    //     $model->where('idJnsLs',$jenisLS);
                    // }
                    $arrData = $model->findAll();
                }
            }
        }

        if (isset($arrData)) {
            foreach ($arrData as $key => $data) {
                if ($lsNeedIzinPersetujuan == 'Y') {
                    $data->text = 'Seri Izin ' . $data->seri . ' - ' . FormatHS($data->text) . ' - ' . $data->uraiHs;
                    $data->seriIzin = $data->seri;
                } else
                    $data->text = FormatHS($data->text) . ' ' . $data->uraiHs;
            }
            $return['data'] = $arrData;
        } else {
            $return['data'][0]['id'] = 0;
            $return['data'][0]['text'] = $err;
        }

        return $this->response->setJSON($return);
    }

    public function ntpn()
    {
        try {
            $model = model('App\Models\tx_lseNtpn');

            $q = $this->request->getPost('q');
            if ($q) {
                $model->like('noNtpn', $q, 'both');
            }

            $filter = decrypt_id($this->request->getPost('filter'));
            $model->where('idLs', $filter);
            $arrData = $model->select('id, CONCAT(noNtpn, " | ", FORMAT(volume, 0), " - ",IFNULL(nama,"{NAMA PERUSAHAAN}")) as text')->findAll();

            $return['data'] = $arrData;
            return $this->response->setJSON($return);
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!' . $e->getMessage());
            return $this->response->setJSON($resp);
        }
    }

    public function package()
    {
        $model = model('App\Models\package');

        $q = $this->request->getPost('q');
        if ($q) {
            $model->like('kode', $q, 'both')->orLike('uraian', $q, 'both');
        }

        $arrData = $model->select('kode as id, CONCAT(kode," - ",uraian) as text, uraian')->where('isActive', 'Y')->findAll();
        $return['data'] = $arrData;
        return $this->response->setJSON($return);
    }

    public function container()
    {
        $model = model('App\Models\jenisContainer');

        $q = $this->request->getPost('q');
        if ($q) {
            $model->like('kode', $q, 'both')->orLike('keterangan', $q, 'both')->orLike('panjang_Ft', $q, 'both');
        }

        $arrData = $model->select('id as id, CONCAT(kode," - ",keterangan," - ",panjang_Ft,"X",tinggi_Ft," Ft") as text, keterangan')->where('isActive', 'Y')->findAll();
        $return['data'] = $arrData;
        return $this->response->setJSON($return);
    }

    // 2023-10-02
    public function role()
    {
        $model = model('App\Models\t_role');

        $q = $this->request->getPost('q');
        if ($q) {
            $model->like('role', $q, 'both');
        }

        $model->where('id !=', 1)->where('userType', 2)->where('isActive', 'Y');

        $arrData = $model->select('id, role as text, role')->findAll();
        $return['data'] = $arrData;
        return $this->response->setJSON($return);
    }

    public function type()
    {
        $model = model('App\Models\t_userType');

        $q = $this->request->getPost('q');
        if ($q) {
            $model->like('role', $q, 'uraian');
        }

        $filter = $this->request->getPost('filter');
        if (empty($filter)) {
            $model->where('id !=', 1);
        } else {
            $model->where('id =', 1);
        }

        $arrData = $model->select('id, uraian as text, uraian')->findAll();
        $return['data'] = $arrData;
        return $this->response->setJSON($return);
    }

    public function seribarang()
    {
        try {
            $model = model('App\Models\tx_lseDtlHs');

            $q = $this->request->getPost('q');
            if ($q) {
                $model->like('seri', $q, 'both')->orLike('postarif', $q, 'both');
            }

            $filter = decrypt_id($this->request->getPost('filter'));
            $model->where('idLs', $filter);

            $arrData = $model->select('id, seri, postarif')->findAll();
            if (isset($arrData)) {
                foreach ($arrData as $key => $data) {
                    $data->text = $data->seri . ' - ' . FormatHS($data->postarif);
                }
                $return['data'] = $arrData;
            }

            // $return['data'] = $arrData;

            return $this->response->setJSON($return);
        } catch (\Throwable $e) {
            $resp = resp_error('An Exception has occured!' . $e->getMessage());
            return $this->response->setJSON($resp);
        }
    }

    public function jenisls()
    {
        $model = model('App\Models\jenisLs');

        $q = $this->request->getPost('q');
        $filter = $this->request->getPost('filter');
        if ($q) {
            $model->like('id', $q, 'both');
        }
        if ($filter) {
            $model->where('kode', $filter);
        }

        $arrData = $model->select('id, markJenis as text, isIzinPersetujuan')->where('isActive', 'Y')->where('kode', 'PPHPP')->findAll();
        $return['data'] = $arrData;
        return $this->response->setJSON($return);
    }
}
