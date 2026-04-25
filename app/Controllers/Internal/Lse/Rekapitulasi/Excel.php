<?php

namespace App\Controllers\Internal\Lse\Rekapitulasi;

use App\Controllers\BaseController;
use CodeIgniter\Files\File;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Helpers\SpreadsheetHelper;

class Excel extends BaseController
{
    protected $pageTitle;

    public function index()
    {
    	$searchParam    = $this->request->getPost('searchParam');
        $arrParam       = post_ajax_toarray($searchParam);
        $castDataFilter = ['konsep'=>['PROCESS','REFUSED'],'proses'=>['REVIEW'],'terbit'=>['ISSUED']];
        $lsModel        = model('tx_lseHdr');
        $arrData        = $lsModel->where('statusProses <>','DELETED')->whereIn('statusProses', $castDataFilter[$arrParam['dataFilter']]);

        if(!empty($arrParam['idJenisTerbit'])){
            $arrData->where('idJenisTerbit', $arrParam['idJenisTerbit']);
        }

        if(!empty($arrParam['idCabang'])){
            $arrData->where('idCabang', $arrParam['idCabang']);
        }
 
        if(!empty($arrParam['noInsw'])){
            $arrData->like('ajuNSW', $arrParam['noInsw']);
        }

        if(!empty($arrParam['noLs'])){
            $arrData->like('noLs', $arrParam['noLs']);
        }

        if(!empty($arrParam['draftNo'])){
            $arrData->like('draftNo', $arrParam['draftNo']);
        }
 
		if(!empty(trim($arrParam['tglLs'])) && !empty(trim($arrParam['tglAkhirLs']))){
            $arrData    = $lsModel->groupStart()
                        ->where("tglLs BETWEEN '".reverseDateDB($arrParam["tglLs"])."' AND '".reverseDateDB($arrParam["tglAkhirLs"])."'")
                            ->groupEnd();
        }

		if(!empty(trim($arrParam['tglLs'])) && empty(trim($arrParam['tglAkhirLs']))){
            $arrData->where('tglLs >=', reverseDateDB($arrParam['tglLs']));
		}

		if(empty(trim($arrParam['tglLs'])) && !empty(trim($arrParam['tglAkhirLs']))){
            $arrData->where('tglLs <=', reverseDateDB($arrParam['tglAkhirLs']));
		}

        if(!empty($arrParam['namaPersh'])){
            $arrData->like('namaPersh', $arrParam['namaPersh']);
        }

        if(!empty($arrParam['noSi'])){
            $arrData->like('noSi', $arrParam['noSi']);
        }

        $arrData = $arrData->orderBy('tglLs', 'asc')->orderBy('id', 'asc')->findAll();
        //qq($arrData);exit;

        // Headers for the spreadsheet
        $headers = [
            'No', 'Cabang', 'Jenis LS', 'Jenis Komoditi', 'No LS', 'Tanggal LS', 'No SI', 'Tgl SI',
            'NPWP', 'Nama Perusahaan', 'Alamat Perusahaan', 'Telp', 'Email', 'No IT/ET', 'Tgl IT/ET',
            'No SPE/SPI', 'Tgl SPE/SPI', 'Inspection Place', 'Tipe Muat', 'Tgl Muat', 'Moda',
            'Loading Port', 'Discharging Port', 'Jenis LC', 'No LC', 'Tgl LC', 'Vessel', 'Voyage',
            'No Invoice', 'Tgl Invoice', 'No BL', 'Tgl BL', 'Cal.(Kkal/Kg-arb)', 'Cal.(Kkal/kg.adb)',
            'TM (%-arb)', 'T.ash (%-adb)', 'T.sulfur (%-adb)', 'Klasifikasi Batubara (adb)', 'Ket',
            'Pos Tarif/HS', 'Uraian Barang', 'Nett Volume', 'Satuan Net Volume', 'Nett Weight',
            'Satuan Net Weight', 'FOB (USD)', 'FOB', 'Kurs FOB', 'Package', 'Negara', 'Perusahaan Asal',
            'No Royalti', 'Tgl Royalti', 'Nilai Royalti', 'Kurs Royalti'
        ];
 
        // Data to be exported
        $data = [];
        foreach ($arrData as $key => $value) {
        	$data[$key][] = ($key+1);
        	$data[$key][] = $value->namaCabang;
        	$data[$key][] = 'Batubara';
        	$data[$key][] = '';
        	$data[$key][] = $value->noLs;
        	$data[$key][] = reverseDate($value->tglLs);
        	$data[$key][] = $value->noSi;
        	$data[$key][] = reverseDate($value->tglSi);

        	$data[$key][] = "'".$value->npwp;
        	$data[$key][] = $value->bentukPersh.'. '.$value->namaPersh;
        	$data[$key][] = $value->alamatPersh;
        	$data[$key][] = $value->telpPersh;
        	$data[$key][] = $value->emailPersh;

        	$data[$key][] = $value->noET;
        	$data[$key][] = reverseDate($value->tglET);
        	$data[$key][] = $value->noPE;
        	$data[$key][] = reverseDate($value->tglPE);

        	$data[$key][] = $value->lokasiPeriksaPrint??$value->lokasiPeriksa;
        	$data[$key][] = $value->tipeMuat;
        	$data[$key][] = reverseDate($value->tglMuat);
        	$data[$key][] = $value->modaTransport;
        	$data[$key][] = $value->portMuat;
        	$data[$key][] = $value->portTujuan;

        	$data[$key][] = '';
        	$data[$key][] = $value->noLc;
        	$data[$key][] = reverseDate($value->tglLc);
        	$data[$key][] = $value->namaTransport;
        	$data[$key][] = $value->voyage;
        	$data[$key][] = $value->noInvoice;
        	$data[$key][] = reverseDate($value->tglInvoice);
        	$data[$key][] = ''; // no bl
        	$data[$key][] = ''; // tgl bl
        	$barangs = $this->get_komoditas($value->id);
        	foreach ($barangs as $key2 => $barang) {
        		$data[$key][] = $barang->calArb;
        		$data[$key][] = $barang->calAdb;
        		$data[$key][] = $barang->tmArb;
        		$data[$key][] = $barang->tAsh;
        		$data[$key][] = $barang->tSulfur;
        		$data[$key][] = $barang->klasifikasiBatubara;
        		$data[$key][] = $barang->keterangan;

        		$data[$key][] = formatHS($barang->postarif);
        		$data[$key][] = $barang->uraianBarang;
        		$data[$key][] = $barang->jumlahBarang;
        		$data[$key][] = $barang->kdSatuanBarang.' - '.$barang->uraiSatuanBarang;
        		$data[$key][] = ''; // Nett Weight
        		$data[$key][] = ''; // Satuan Net Weight
        		$data[$key][] = $barang->hargaBarangUsd; // FOB USD
        		$data[$key][] = $barang->hargaBarang;
        		$data[$key][] = $barang->currencyHargaBarang;
        		$data[$key][] = ''; // Package
        		$data[$key][] = $barang->negaraAsal;
        		$data[$key][] = $this->get_royalti($value->id,$barang->id);
        	}
        }
        // Generate Excel file
        SpreadsheetHelper::createExcelFile($data, $headers, WRITEPATH . 'excel/report.xlsx');

        return $this->response->download(WRITEPATH . 'excel/report.xlsx', null);
    }

    private function get_komoditas($idLs)
    {
    	$barangModel = $this->db->table('tx_lsedtlhs');
    	$barangModel->join('tx_lse_kalori', 'tx_lse_kalori.idPosTarif = tx_lsedtlhs.id','left');
    	$barangModel->where('tx_lsedtlhs.idLs',$idLs);
    	$barang = $barangModel->get()->getResult();
    	return $barang;
    }

    private function get_royalti($idLs,$idDtl)
    {
    	$ntpnModel = model('tx_hsNtpn');
    	$ntpnModel->select('tx_lse_ntpn.nama, tx_lse_ntpn.noNtpn, DATE_FORMAT(tx_lse_ntpn.tglNtpn, "%d-%m-%Y"), tx_lse_ntpn.royalti, tx_lse_ntpn.currency');
        $ntpnModel->join('tx_lse_ntpn', 'tx_lse_ntpn.id = tx_lsehsntpn.idNtpn');
        $ntpnModel->where('tx_lsehsntpn.idPosTarif',$idDtl);
        $ntpns = $ntpnModel->asArray()->findAll();
        return $ntpns;
    }
}
