<?php

namespace App\Models;

use CodeIgniter\Model;

class tx_lseHdr extends Model
{
    protected $table        = 'tx_lsehdr';
    protected $primaryKey   = 'id';
    protected $allowedFields = ['idProfile','idJenisLS','komoditi','idCabang','namaCabang','runningNo','idsi','nosi','tglsi','nopveb','tglpveb','noET','tglET','tglAkhirET','noPE','tglPE','tglAkhirPE','noConfirmationOrder','tglConfirmationOrder','idTtd','namaTtd','nols','tglls','tglAkhirLs','noet','tglet','tglAkhirEt','noSpe','tglSpe','tglAkhirSpe','nib','tglNib','npwp','bentukPersh','namaPersh','alamatPersh','kdProp','namaProp','kdKabupaten','namaKabupaten','emailPersh','telpPersh','faxPersh','kodepos','namaImportir','alamatImportir','kdNegaraImp','namaNegaraImp','kotaImportir','negaraImportirPrint','noIupOpkOlah','tglIupOpkOlah','noIupPkp2b','tglIupPkp2b','noIupAngkutJual','tglIupAngkutJual','namaAsuransiKapal','namaAsuransiKargo','noPolis','tglPolis','tglAkhirPolis','nilaiPolis','noPackingList','tglPackingList','grossWeight','nettWeight','satuanWeight','fobUSD','noInvoice','tglInvoice','jenisLC','noLC','tglLC','tglAkhirLC','noRoyalti','tglRoyalti','modeTransport','noPetiKemas','noSegel','tipeKargo','jenisKapal','kapasitasKapal','tipeMuat','benderaKapal','namaKapal','merkKemasan','tglMuat','tglAwalStuffing','tglAkhirStuffing','kdPortPeriksa','uraiPortPeriksa','tempatPeriksa','inspectionDate','tglPengapalan','kdPortMuat','uraiPortMuat','portMuatPrinted','negaraTujuan','kdPortDisch','uraiPortDisch','portDischPrinted','catatan','kesimpulan','statusProses','statusDok','statusKirim','cntkirim','reffnumbersend','reffnumberbooking','userInsert','tglInsert','userUpdate','lastUpdate'];
    protected $validationRules = [
        'idJenisLS' => 'required|numeric',
        'komoditi' => 'required',
        'npwp' => 'required|numeric|min_length[15]|max_length[16]',
    ];
    protected $validationMessages = [
        'idJenisLS' => [
            'required' => 'Jenis LS tidak boleh kosong'
        ],
        'komoditi' => [
            'required' => 'Komoditi tidak boleh kosong'
        ],
        'nib' => [
            'required' => 'NIB tidak boleh kosong',
            'numeric' => 'NIB hanya boleh angka'
        ],
        'npwp' => [
            'required' => 'NPWP tidak boleh kosong minimal 15 karakter',
            'numeric' => 'NPWP hanya boleh angka minimal 15 karakter',
            'min_length' => 'NPWP minimal 15 karakter',
            'max_length' => 'NPWP maksimal 16 karakter'
        ],
    ];
    protected $returnType    = 'object';
    protected $useTimestamps = true;
    protected $createdField  = 'tglInsert';
    protected $updatedField  = 'lastUpdate';
}