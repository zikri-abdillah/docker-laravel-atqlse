<div class="card">   
    <div class="card-body">
    <h5 class="card-title fw-semibold mb-3">{{ $page_title }}</h5>
        <ul class="nav tabs-menu-form">
            <li>
                <a href="#tabUmum" data-bs-toggle="tab" class="active" id="buttonUmum">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-info" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M11 14h1v4h1" /><path d="M12 11h.01" /></svg>
                    Informasi Umum
                </a>
            </li>
            <li>
                <a href="#tabContainer" data-bs-toggle="tab" id="buttonContainer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-package" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 3l8 4.5l0 9l-8 4.5l-8 -4.5l0 -9l8 -4.5" /><path d="M12 12l8 -4.5" /><path d="M12 12l0 9" /><path d="M12 12l-8 -4.5" /><path d="M16 5.25l-8 4.5" /></svg>
                    Package & Container
                </a>
            </li> 
            <li>
                <a href="#tabReferensi" data-bs-toggle="tab" id="buttonReferensi">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-invoice" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 7l1 0" /><path d="M9 13l6 0" /><path d="M13 17l2 0" /></svg>
                    Dokumen Referensi
                </a>
            </li>  
            <li>
                <a href="#tabKomoditas" data-bs-toggle="tab" id="buttonKomoditas">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-diamond" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M6 5h12l3 5l-8.5 9.5a.7 .7 0 0 1 -1 0l-8.5 -9.5l3 -5"></path>
                        <path d="M10 12l-2 -2.2l.6 -1"></path>
                    </svg>
                    Komoditas 
                </a>
            </li>  
        </ul> 
 
        <div class="tab-content">
            <div class="tab-pane active" id="tabUmum">  
                <div class="row">
                    <form id="form-main" name="form-main">
                        <div aria-multiselectable="true" class="accordion-input mb-2" id="tab_main" role="tablist">
                            <div class="card mb-0 mt-2">
                                <div class="card-header border-bottom-0" id="head_main" role="tab">
                                    <a class="accor-style2 collapsed" aria-controls="collapse2" aria-expanded="false" data-bs-toggle="collapse" href="#main_collapse"><i class="fe fe-chevrons-down me-2"></i>DATA LS</a>
                                </div>
                                <div aria-labelledby="head_main" class="collapse" data-bs-parent="#tab_main" id="main_collapse" role="tabpanel">
                                    <div class="card-body">
                                        <div class="form-horizontal">
                                            <div class="row">
                                                <div class="col">
                                                    <div class="row mb-3">
                                                        <label class="col-md-4 form-label">Jenis Komoditi</label>
                                                        <div class="form-group col-md-8">
                                                            @if (isset($datals) && $datals->idJenisTerbit != '1')
                                                                <label class="form-label">{{ $datals->jenisLS }}</label>
                                                                <input type="hidden" id="i_idJenisLS" name="i_idJenisLS" value="{{ $datals->idJenisLS }}">
                                                            @else
                                                                <select class="form-control select2-show-search form-select" id="i_idJenisLS" name="i_idJenisLS" data-placeholder="-- Pilih --" style="width: 100%;">
                                                                    @php
                                                                        $logam = $silika = $bauksit = '';
                                                                        if(isset($datals)){
                                                                            if($datals->idJenisLS == '2')
                                                                                $logam = 'selected';
                                                                            else if($datals->idJenisLS == '3')
                                                                                $silika = 'selected';
                                                                            else if($datals->idJenisLS == '4')
                                                                                $bauksit = 'selected';
                                                                        }
                                                                    @endphp
                                                                    <option value="">-- Pilih --</option>
                                                                    <option value="2" data-izin="N" {{ $logam }}>PPHPP - Logam dan Bukan Logam</option>
                                                                    <option value="3" data-izin="N" {{ $silika }}>PPHPP - Silika, Kuarsa, Konsentrat, Lumpur Anoda</option>
                                                                    <option value="4" data-izin="Y" {{ $bauksit }}>PPHPP - Bauksit</option>
                                                                </select>
                                                            @endif
                                                            <span id="lbl-lse-spe" class="text-info" style="font-size: smaller;"></span>
                                                        </div>
                                                    </div> 
                                                    <div class="row mb-3">
                                                        <label class="col-md-4 form-label">Jenis Penerbitan</label>
                                                        <div class="form-group col-md-8">
                                                            <select class="form-control form-select" id="i_idJenisTerbit" name="i_idJenisTerbit" data-placeholder="Jenis Penerbitan" style="width: 100%;">
                                                                @if (!empty($datals->idJenisTerbit))
                                                                    <option value="{{ $datals->idJenisTerbit }}" selected>{{ $datals->jenisTerbit }}</option>
                                                                @else
                                                                    <option value="1">Baru</option>
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label class="col-md-4 form-label">Nomor SI</label>
                                                        <div class="col-md-5">
                                                            <input class="form-control" id="i_noSi" name="i_noSi" type="text" placeholder="Nomor SI" value="{{ $datals->noSi }}">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input class="form-control bs-datepicker" id="i_tglSi" name="i_tglSi" type="text" placeholder="Tanggal SI" value="{{ reverseDate($datals->tglSi??'') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="row mb-3">
                                                        <label class="col-md-4 form-label">Cabang Penerbit</label>
                                                        <div class="form-group col-md-8 mb-0">
                                                            <select class="form-control select2-show-search form-select select-cabang" id="i_idCabang" name="i_idCabang" data-placeholder="-- Silahkan Pilih --" data-allow-clear="true" style="width: 100%;">
                                                                <option label="-- Silahkan Pilih --"></option>
                                                                @if (!empty($datals->idCabang))
                                                                    <option value="{{ $datals->idCabang }}" selected>{{ $datals->namaCabang }}</option>
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div> 
                                                    <div class="row mb-3">
                                                        <label class="col-md-4 form-label">Nomor WO/PVEB</label>
                                                        <div class="col-md-5">
                                                            <input class="form-control" id="i_noPveb" name="i_noPveb" type="text" placeholder="Nomor WO/PVEB" value="{{ $datals->noPveb }}">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input class="form-control bs-datepicker" id="i_tglPveb" name="i_tglPveb" type="text" placeholder="Tanggal WO/PVEB" value="{{ reverseDate($datals->tglPveb??'') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div aria-multiselectable="true" class="accordion-input mb-2" id="tab_eksportir" role="tablist">
                            <div class="card mb-0 mt-2">
                                <div class="card-header border-bottom-0" id="head_eksportir" role="tab">
                                    <a class="accor-style2 collapsed" aria-controls="collapse2" aria-expanded="false" data-bs-toggle="collapse" href="#eksportir_collapse"><i class="fe fe-chevrons-down me-2"></i>EKSPORTIR</a>
                                </div>
                                <div aria-labelledby="head_eksportir" class="collapse" data-bs-parent="#tab_eksportir" id="eksportir_collapse" role="tabpanel">
                                    <div class="card-body">
                                        <div class="form-horizontal">
                                            <div class="row">
                                                <div class="col">
                                                    <div class="row mb-3">
                                                        <label class="col-md-4 form-label">Nama Eksportir</label>
                                                        <div class="col-md-8">
                                                            <label class="form-label">{{ $arrPerusahaan->bentukPersh.' - '.$arrPerusahaan->nama }}</label>
                                                            <input class="d-none" id="i_idPersh" name="i_idPersh" type="hidden" value="{{ $arrPerusahaan->id }}">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label class="col-md-4 form-label">NPWP</label>
                                                        <div class="col-md-8">
                                                            <label class="form-label">{{ FormatNPWP($arrPerusahaan->npwp??'') }}</label>
                                                            <input class="d-none mask-npwp" id="i_npwp" name="i_npwp" type="hidden" value="{{ FormatNPWP($arrPerusahaan->npwp??'') }}">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label class="col-md-4 form-label">NIB</label>
                                                        <div class="col-md-8">
                                                            <label class="form-label">{{ $arrPerusahaan->nib }}</label>
                                                            <input class="d-none" id="i_nib" name="i_nib" type="hidden" value="{{ $arrPerusahaan->nib }}">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label class="col-md-4 form-label">Jenis IUP</label>
                                                        <div class="form-group col-md-8">
                                                            <label class="form-label">{{ $arrPerusahaan->jenisIUP }}</label>
                                                            <input class="d-none" id="i_idJnsIUP" name="i_idJnsIUP" type="hidden" value="{{ $arrPerusahaan->idJenisIup }}">
                                                            <input class="d-none" id="jenisIUP" name="jenisIUP" type="hidden" value="{{ $arrPerusahaan->jenisIUP }}">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label class="col-md-4 form-label">Nomor IUP</label>
                                                        <div class="col-md-8">
                                                            <label class="form-label">{{ $arrPerusahaan->noIUP }}</label>
                                                            <input class="d-none" id="i_noIUP" name="i_noIUP" type="hidden" value="{{ $arrPerusahaan->noIUP }}">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label class="col-md-4 form-label">Tanggal IUP</label>
                                                        <div class="col-md-8">
                                                            <label class="form-label">{{ $arrPerusahaan->tglIUP }}</label>
                                                            <input class="d-none" id="i_tglIUP" name="i_tglIUP" type="hidden" value="{{ reverseDate($arrPerusahaan->tglIUP) }}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="row mb-3">
                                                        <label class="col-md-4 form-label">NITKU</label>
                                                        <div class="col-md-8">
                                                            <label class="form-label">{{ $arrPerusahaan->nitku }}</label>
                                                            <input class="d-none" id="i_nitku" name="i_nitku" type="hidden" value="{{ $arrPerusahaan->nitku }}"> 
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label class="col-md-4 form-label">Alamat Eksportir</label>
                                                        <div class="col-md-8">
                                                            <textarea class="form-control" id="i_alamatPersh" name="i_alamatPersh" rows="3" placeholder="Alamat Eksportir">{{ $arrPerusahaan->alamat }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label class="col-md-4 form-label">Propinsi</label>
                                                        <div class="form-group col-md-8">
                                                            <label class="form-label">{{ $arrPerusahaan->namaProp }}</label>
                                                            <input class="d-none" id="i_kdProp" name="i_kdProp" type="hidden" value="{{ $arrPerusahaan->idProp }}">
                                                            <input class="d-none" name="kdPropInatrade" type="hidden" value="{{ $arrPerusahaan->kodeProp }}">
                                                            <input class="d-none" name="namaProp" type="hidden" value="{{ $arrPerusahaan->namaProp }}">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label class="col-md-4 form-label">Kab/Kota</label>
                                                        <div class="form-group col-md-8">
                                                            <label class="form-label">{{ $arrPerusahaan->namaKab }}</label>
                                                            <input class="d-none" id="i_kdKota" name="i_kdKota" type="hidden" value="{{ $arrPerusahaan->idKab }}">
                                                            <input class="d-none" name="kdKotaInatrade" type="hidden" value="{{ $arrPerusahaan->kodeKab }}">
                                                            <input class="d-none" name="namaKota" type="hidden" value="{{ $arrPerusahaan->namaKab }}">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label class="col-md-4 form-label">Kode Pos</label>
                                                        <div class="col-md-8">
                                                             <label class="form-label">{{ $arrPerusahaan->kodePos }}</label>
                                                            <input class="d-none" id="i_kodepos" name="i_kodepos" type="hidden" value="{{ $arrPerusahaan->kodePos }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div aria-multiselectable="true" class="accordion-input mb-2" id="tab_importir" role="tablist">
                            <div class="card mb-0 mt-2">
                                <div class="card-header border-bottom-0" id="head_importir" role="tab">
                                    <a class="accor-style2 collapsed" aria-controls="collapse2" aria-expanded="false" data-bs-toggle="collapse" href="#importir_collapse"><i class="fe fe-chevrons-down me-2"></i>IMPORTIR</a>
                                </div>
                                <div aria-labelledby="head_importir" class="collapse" data-bs-parent="#tab_importir" id="importir_collapse" role="tabpanel">
                                    <div class="card-body">
                                        <div class="form-horizontal">
                                            <div class="row">
                                                <div class="col">
                                                    <div class="row mb-3">
                                                        <label class="col-md-4 form-label">Nama Importir</label>
                                                        <div class="col-md-8">
                                                            <input class="form-control" id="i_namaImportir" name="i_namaImportir" type="text" placeholder="Nama Importir" value="{{ $datals->namaImportir }}">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label class="col-md-4 form-label">Alamat Importir</label>
                                                        <div class="col-md-8">
                                                            <textarea class="form-control" id="i_alamatImportir" name="i_alamatImportir" rows="3" placeholder="Alamat Importir">{{ $datals->alamatImportir }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="row mb-3">
                                                        <label class="col-md-4 form-label">Negara</label>
                                                        <div class="form-group col-md-8">
                                                            <select class="form-control select2-show-search form-select select-negara" id="i_kdNegaraImportir" name="i_kdNegaraImportir" data-placeholder="--Pilih--" style="width: 100%;">
                                                                <option label="--Pilih--"></option>
                                                                @if (!empty($datals->kdNegaraImportir))
                                                                    <option value="{{ $datals->kdNegaraImportir }}" selected>{{ $datals->negaraImportir }}</option>
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label class="col-md-4 form-label">Kota</label>
                                                        <div class="form-group col-md-8">
                                                            <select class="form-control select2-show-search form-select select-kota-ln" id="i_kdKotaImportir" name="i_kdKotaImportir" data-placeholder="--Pilih--" style="width: 100%;">
                                                                <option label="--Pilih--"></option>
                                                                @if (!empty($datals->kdKotaImportir))
                                                                    <option value="{{ $datals->kdKotaImportir }}" selected>{{ $datals->kotaImportir }}</option>
                                                                @endif
                                                            </select>
                                                            <small>Kota dapat ditambakan pada menu admin. <a href="https://unece.org/trade/cefact/unlocode-code-list-country-and-territory" target="_blank" rel="no-referrer">Referensi</a></small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div aria-multiselectable="true" class="accordion-input mb-2" id="tab_barang" role="tablist">
                            <div class="card mb-0 mt-2">
                                <div class="card-header border-bottom-0" id="head_importir" role="tab">
                                    <a class="accor-style2 collapsed" aria-controls="collapse2" aria-expanded="false" data-bs-toggle="collapse" href="#barang_collapse"><i class="fe fe-chevrons-down me-2"></i>BARANG</a>
                                </div>
                                <div aria-labelledby="head_barang" class="collapse" data-bs-parent="#tab_barang" id="barang_collapse" role="tabpanel">
                                    <div class="card-body">
                                        <div class="form-horizontal">
                                            <div class="row">
                                                <div class="col">
                                                    <div class="row mb-3">
                                                        <label class="col-md-4 form-label">Nomor Packing List</label>
                                                        <div class="col-md-8">
                                                            <input class="form-control" id="i_noPackingList" name="i_noPackingList" type="text" placeholder="Nomor Packing List" value="{{ $datals->noPackingList }}">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label class="col-md-4 form-label">Tanggal Packing List</label>
                                                        <div class="col-md-8">
                                                            <input class="form-control bs-datepicker" id="i_tglPackingList" name="i_tglPackingList" type="text" placeholder="Tanggal Packing List" value="{{ reverseDate($datals->tglPackingList??'') }}">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label class="col-md-4 form-label">Nomor LC</label>
                                                        <div class="col-md-8">
                                                            <input class="form-control" id="i_noLc" name="i_noLc" type="text" placeholder="Nomor LC" value="{{ $datals->noLc }}">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label class="col-md-4 form-label">Tanggal LC</label>
                                                        <div class="col-md-8">
                                                            <input class="form-control bs-datepicker" id="i_tglLc" name="i_tglLc" type="text" placeholder="Tanggal LC" value="{{ reverseDate($datals->tglLc??'') }}">
                                                        </div>
                                                    </div>
                                                    <div class="row  mb-3">
                                                        <label class="col-md-4 form-label mt-0">Jumlah Netto</label>
                                                        <div class="col-md-5">
                                                            <input class="form-control numberaja" id="i_qtyNetto" name="i_qtyNetto" type="text" placeholder="Jumlah Dalam Kilogram" value="{{ $datals->qtyNetto }}">
                                                        </div>
                                                        <label class="col-md-3 text-start form-label mt-2">(KILOGRAM)</label>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label class="col-md-4 form-label mt-0">Jumlah Bruto</label>
                                                        <div class="col-md-5">
                                                            <input class="form-control numberaja" id="i_qtyBruto" name="i_qtyBruto" type="text" placeholder="Jumlah Dalam Kilogram" value="{{ $datals->qtyBruto }}">
                                                        </div>
                                                        <label class="col-md-3 text-start form-label mt-2">(KILOGRAM)</label>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label class="col-md-4 form-label">Incoterm</label>
                                                        <div class="col-md-8">
                                                            <select class="form-control select2-show-search form-select select-incoterm" id="i_kodeIncoterm" name="i_kodeIncoterm" data-placeholder="--Pilih--" style="width: 100%;">
                                                                <option label="--Pilih--"></option>
                                                                @if (!empty($datals->kodeIncoterm))
                                                                    <option value="{{ $datals->kodeIncoterm }}" selected>{{ $datals->incoterm }}</option>
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="row mb-3">
                                                        <label class="col-md-4 form-label">Nilai Invoice</label>
                                                        <div class="col-md-4">
                                                            <input class="form-control numberaja" id="i_nilaiInvoice" name="i_nilaiInvoice" type="text" placeholder="Nilai invoice" value="{{ $datals->nilaiInvoice }}">
                                                        </div>
                                                        <div class="col-md-4">
                                                            <select class="form-control select2-show-search form-select select-currency" id="i_currencyInvoice" name="i_currencyInvoice" data-placeholder="--Pilih--" style="width: 100%;">
                                                                <option label="--Pilih--"></option>
                                                                @if (!empty($datals->currencyInvoice))
                                                                    <option value="{{ $datals->currencyInvoice }}" selected>{{ $datals->currencyInvoice.' - '.$currencyInvoice }}</option>
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label class="col-md-4 form-label">Nilai Invoice (IDR)</label>
                                                        <div class="col-md-8">
                                                            <input class="form-control numberaja" id="i_nilaiInvoiceIDR" name="i_nilaiInvoiceIDR" type="text" placeholder="Nilai invoice dalam IDR" value="{{ $datals->nilaiInvoiceIDR }}">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label class="col-md-4 form-label">Nilai Invoice (USD)</label>
                                                        <div class="col-md-8">
                                                            <input class="form-control numberaja" id="i_nilaiInvoiceUSD" name="i_nilaiInvoiceUSD" type="text" placeholder="Nilai invoice dalam USD" value="{{ $datals->nilaiInvoiceUSD }}">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label class="col-md-4 form-label">Tanggal Pemeriksaan</label>
                                                        <div class="col-md-8">
                                                            <input class="form-control bs-datepicker" id="i_tglPeriksa" name="i_tglPeriksa" type="text" placeholder="Tanggal Pemeriksaan" value="{{ reverseDate($datals->tglPeriksa??'') }}">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label class="col-md-4 form-label">Tempat Pemeriksaan</label>
                                                        <div class="col-md-8">
                                                            <select class="form-control select2-show-search form-select select-port-id" id="i_kodeLokasiPeriksa" name="i_kodeLokasiPeriksa" data-placeholder="--Pilih--" style="width: 100%;">
                                                                <option label="--Pilih--"></option>
                                                                @if (!empty($datals->kodeLokasiPeriksa))
                                                                    <option value="{{ $datals->kodeLokasiPeriksa }}" selected>{{ $datals->kodeLokasiPeriksa.' - '.$datals->lokasiPeriksa }}</option>
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label class="col-md-4 form-label">Catatan Pemeriksaan</label>
                                                        <div class="col-md-8">
                                                            <textarea class="form-control" id="i_catatanPeriksa" name="i_catatanPeriksa" rows="2" placeholder="Catatan Pemeriksaan" >{{ $datals->catatanPeriksa }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label class="col-md-4 form-label">Kesimpulan Pemeriksaan</label>
                                                        <div class="col-md-8">
                                                            <textarea class="form-control" id="i_kesimpulanPeriksa" name="i_kesimpulanPeriksa" rows="2" placeholder="Kesimpulan Pemeriksaan">{{ $datals->kesimpulanPeriksa }}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div aria-multiselectable="true" class="accordion-input mb-2" id="tab_transport" role="tablist">
                            <div class="card mb-0 mt-2">
                                <div class="card-header border-bottom-0" id="head_transport" role="tab">
                                    <a class="accor-style2 collapsed" aria-controls="collapse2" aria-expanded="false" data-bs-toggle="collapse" href="#transport_collapse"><i class="fe fe-chevrons-down me-2"></i>TRANSPORT</a>
                                </div>
                                <div aria-labelledby="head_transport" class="collapse" data-bs-parent="#tab_transport" id="transport_collapse" role="tabpanel">
                                    <div class="card-body">
                                        <div class="form-horizontal">
                                            <div class="row">
                                                <div class="col">
                                                    <div class="row mb-3">
                                                        <label class="col-md-4 form-label">Moda Transportasi</label>
                                                        <div class="form-group col-md-8">
                                                            <select class="form-control select2-show-search form-select select-moda" id="i_kodeModaTransport" name="i_kodeModaTransport" data-placeholder="--Pilih--" style="width: 100%;">
                                                                <option label="--Pilih--"></option>
                                                                @if (!empty($datals->kodeModaTransport))
                                                                    <option value="{{ $datals->kodeModaTransport }}" selected>{{ $datals->modaTransport }}</option>
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label class="col-md-4 form-label">Tipe Kargo</label>
                                                        <div class="form-group col-md-8">
                                                            <input class="form-control" id="i_cargoType" name="i_cargoType" type="text" placeholder="Tipe Kargo" value="{{ $datals->cargoType }}">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label class="col-md-4 form-label">Tipe Muatan</label>
                                                        <div class="form-group col-md-8">
                                                            <input class="form-control" id="i_tipeMuat" name="i_tipeMuat" type="text" placeholder="Tipe Muatan" value="{{ $datals->tipeMuat }}">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label class="col-md-4 form-label">Pelabuhan Muat</label>
                                                        <div class="form-group col-md-8">
                                                            <select class="form-control select2-show-search form-select select-port-id" id="i_kodePortMuat" name="i_kodePortMuat" data-placeholder="--Pilih--" style="width: 100%;">
                                                                <option label="--Pilih--"></option>
                                                                @if (!empty($datals->kodePortMuat))
                                                                    <option value="{{ $datals->kodePortMuat }}" selected>{{ $datals->kodePortMuat.' - '.$datals->portMuat }}</option>
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label class="col-md-4 form-label">Pelabuhan Transit</label>
                                                        <div class="form-group col-md-8">
                                                            <select class="form-control select2-show-search form-select select-port" id="i_kodePortTransit" name="i_kodePortTransit" data-placeholder="--Pilih--" style="width: 100%;">
                                                                <option label="--Pilih--"></option>
                                                                @if (!empty($datals->kodePortTransit))
                                                                    <option value="{{ $datals->kodePortTransit }}" selected>{{ $datals->kodePortTransit.' - '.$datals->portTransit }}</option>
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label class="col-md-4 form-label">Negara Transit</label>
                                                        <div class="form-group col-md-8">
                                                            <select class="form-control select2-show-search form-select select-negara" id="i_kodeNegaraTransit" name="i_kodeNegaraTransit" data-placeholder="--Pilih--" style="width: 100%;">
                                                                <option label="--Pilih--"></option>
                                                                @if (!empty($datals->kodeNegaraTransit))
                                                                    <option value="{{ $datals->kodeNegaraTransit }}" selected>{{ $datals->kodeNegaraTransit.' - '.$datals->negaraTransit }}</option>
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label class="col-md-4 form-label">Pelabuhan Tujuan</label>
                                                        <div class="form-group col-md-8">
                                                            <select class="form-control select2-show-search form-select select-port-ln" id="i_kodePortTujuan" name="i_kodePortTujuan" data-placeholder="--Pilih--" style="width: 100%;">
                                                                <option label="--Pilih--"></option>
                                                                @if (!empty($datals->kodePortTujuan))
                                                                    <option value="{{ $datals->kodePortTujuan }}" selected>{{ $datals->kodePortTujuan.' - '.$datals->portTujuan }}</option>
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label class="col-md-4 form-label">Negara Tujuan</label>
                                                        <div class="form-group col-md-8">
                                                            <select class="form-control select2-show-search form-select select-negara" id="i_kodeNegaraTujuan" name="i_kodeNegaraTujuan" data-placeholder="--Pilih--" style="width: 100%;">
                                                                <option label="--Pilih--"></option>
                                                                @if (!empty($datals->kodeNegaraTujuan))
                                                                    <option value="{{ $datals->kodeNegaraTujuan }}" selected>{{ $datals->kodeNegaraTujuan.' - '.$datals->negaraTujuan }}</option>
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="row mb-3">
                                                        <label class="col-md-4 form-label">Nama Transportasi</label>
                                                        <div class="col-md-8">
                                                            <input class="form-control" id="i_namaTransport" name="i_namaTransport" type="text" placeholder="Nama Transportasi" value="{{ $datals->namaTransport }}">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label class="col-md-4 form-label">Voyage Out / Flight Number</label>
                                                        <div class="col-md-8">
                                                            <input class="form-control" id="i_voyage" name="i_voyage" type="text" placeholder="Voyage Out / Flight Number" value="{{ $datals->voyage }}">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label class="col-md-4 form-label">Bendera Kapal</label>
                                                        <div class="col-md-8">
                                                            <select class="form-control select2-show-search form-select select-negara" id="i_kodeBenderaKapal" name="i_kodeBenderaKapal" data-placeholder="--Pilih--" style="width: 100%;">
                                                                <option label="--Pilih--"></option>
                                                                @if (!empty($datals->kodeBenderaKapal))
                                                                    <option value="{{ $datals->kodeBenderaKapal }}" selected>{{ $datals->kodeBenderaKapal.' - '.$datals->benderaKapal }}</option>
                                                                @endif
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label class="col-md-4 form-label">Kapasitas Kapal</label>
                                                        <div class="form-group col-md-8">
                                                            <input class="form-control" id="i_kapasitasKapal" name="i_kapasitasKapal" type="text" placeholder="Tipe Muatan" value="{{ $datals->tipeMuat }}">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label class="col-md-4 form-label">Tanggal Muat</label>
                                                        <div class="col-md-8">
                                                            <input class="form-control bs-datepicker" id="i_tglMuat" name="i_tglMuat" type="text" placeholder="Tanggal Muat" value="{{ reverseDate($datals->tglMuat??'') }}">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label class="col-md-4 form-label">Tanggal Berangkat</label>
                                                        <div class="col-md-8">
                                                            <input class="form-control bs-datepicker" id="i_tglBerangkat" name="i_tglBerangkat" type="text" placeholder="Tanggal Berangkat" value="{{ reverseDate($datals->tglBerangkat??'') }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" id="idData" name="idData" value="{{ isset($datals->id)?encrypt_id($datals->id):'' }}"> 
                    </form>
                </div>
            </div>
            
            <div class="tab-pane" id="tabContainer">      
                <div class="row">
                    <div class="col-md-6"> 
                        <div class="card"> 
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-2">
                                        <h5 class="card-title fw-semibold mb-4">Package Data</h5> 
                                    </div>
                                    <div class="col-md-6 mb-2" align="right">
                                        <a class="btn btn-outline-primary m-1 btn-sm" href="javascript:void(0)" onclick="showModalPackage()">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-pencil-plus" width="15" height="15" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4"></path>
                                                <path d="M13.5 6.5l4 4"></path>
                                                <path d="M16 19h6"></path>
                                                <path d="M19 16v6"></path>
                                            </svg> 
                                            Input Data
                                        </a>
                                    </div> 
                                    
                                    <div class="mb-12 mb-4" align="right">
                                        <table class="table table-bordered" id="table-package">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Package Info</th>
                                                    <th>Jumlah</th>
                                                    <th>Unit</th>
                                                    <th class="text-center">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table> 
                                    </div> 
                                </div>  
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6"> 
                        <div class="card"> 
                            <div class="card-body"> 
                                <div class="row">
                                    <div class="col-md-6 mb-2"> 
                                        <h5 class="card-title fw-semibold mb-4">Container Data</h5> 
                                    </div>
                                    <div class="col-md-6 mb-2" align="right">
                                        <a class="btn btn-outline-primary m-1 btn-sm" href="javascript:void(0)" onclick="showModalContainer()">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-pencil-plus" width="15" height="15" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4"></path>
                                                <path d="M13.5 6.5l4 4"></path>
                                                <path d="M16 19h6"></path>
                                                <path d="M19 16v6"></path>
                                            </svg> 
                                            Input Data
                                        </a>
                                    </div> 
                                    
                                    <div class="mb-12 mb-4" align="right">
                                        <table class="table table-bordered" id="table-container">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Kode / Jenis</th>
                                                    <th>Nomor</th>
                                                    <th class="text-center">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div> 
                                </div>  
                            </div>
                        </div>
                    </div> 
                </div>
            </div>
            
            <div class="tab-pane" id="tabReferensi"> 
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <h5 class="card-title fw-semibold mb-4">Dokumen Referensi</h5> 
                            </div>
                            <div class="col-md-6 mb-4" align="right">
                                <a class="btn btn-outline-primary m-1 btn-sm" href="javascript:void(0)" onclick="showUploadDok()">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-upload" width="15" height="15" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2"></path>
                                        <path d="M7 9l5 -5l5 5"></path>
                                        <path d="M12 4l0 12"></path>
                                    </svg> 
                                    Upload Dokumen
                                </a>
                                <a class="btn btn-outline-primary m-1 btn-sm" href="javascript:void(0)" onclick="showPilihDok()">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-click" width="15" height="15" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M3 12l3 0"></path>
                                        <path d="M12 3l0 3"></path>
                                        <path d="M7.8 7.8l-2.2 -2.2"></path>
                                        <path d="M16.2 7.8l2.2 -2.2"></path>
                                        <path d="M7.8 16.2l-2.2 2.2"></path>
                                        <path d="M12 12l9 3l-4 2l-2 4l-3 -9"></path>
                                    </svg>
                                    Pilih Dokumen
                                </a>
                            </div> 
                            
                            <div class="mb-12 mb-4" align="right">
                                <table class="table table-bordered" id="tab-referensi">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Negara Penerbit</th>
                                            <th>Jenis Dokumen</th>
                                            <th>No Dokumen</th>
                                            <th>Tgl Dokumen</th>
                                            <th>Tgl Akhir</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div> 
                        </div> 
                    </div> 
                </div> 
            </div> 
            
            <div class="tab-pane" id="tabKomoditas">   
                @if ( session()->get('sess_role') == 10 ) 
                    <div class="card mb-0 mt-2"> 
                        <div class="card-body"> 
                            <form action="javascript:void(0)" id="form-komoditas" name="form-komoditas" class="mt-4">  
                                <div class="row">
                                    <div class="col">
                                        <div class="row mb-3">
                                            <label class="col-md-4 form-label">Pos Tarif / HS</label>
                                            <div class="col-md-8">
                                                <select id="k_postarif" name="k_postarif" class="form-control select2-show-search form-select" data-placeholder="-- Pilih --" style="width: 100%;">
                                                <option label="-- Pilih --"></option>
                                                </select>									
                                            </div>
                                        </div> 
                                        <div class="row mb-3">
                                            <label class="col-md-4 form-label">Jumlah Barang</label>
                                            <div class="col-md-8">
                                                <input type="text" id="k_jumlahBarang" name="k_jumlahBarang" class="form-control numberaja" placeholder="Jumlah Barang">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-md-4 form-label">Satuan</label>
                                            <div class="col-md-8">
                                                <select class="form-control select2-show-search form-select select-satuan" id="k_kdSatuanBarang" name="k_kdSatuanBarang" data-placeholder="--Pilih--" style="width: 100%;">
                                                    <option label="--Pilih--"></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-md-4 form-label">Berat Bersih (Kilogram)</label>
                                            <div class="col-md-8">
                                                <input type="text" id="k_beratBersih" name="k_beratBersih" class="form-control numberaja" placeholder="Berat Bersih (Kilogram)">
                                                <input type="hidden" id="k_kdSatuanBeratBersih" name="k_kdSatuanBeratBersih" class="form-control" value="KGM">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-md-4 form-label">Uraian Barang</label>
                                            <div class="col-md-8">
                                                <textarea class="form-control" id="k_uraianBarang" name="k_uraianBarang" placeholder="Uraian Barang" rows="2"></textarea>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-md-4 form-label">Spesifikasi</label>
                                            <div class="col-md-8">
                                                <textarea class="form-control" id="k_sepesifikasi" name="k_sepesifikasi" placeholder="Spesifikasi" rows="2"></textarea>
                                            </div>
                                        </div> 
                                        <div class="row mb-3">
                                            <label class="col-md-4 form-label">Nomor IUP</label>
                                            <div class="col-md-8">
                                                <input type="text" id="k_noIup" name="k_noIup" class="form-control" placeholder="Nomor IUP">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-md-4 form-label">Tangal IUP</label>
                                            <div class="col-md-8">
                                                <input type="text" id="k_tglIup" name="k_tglIup" class="form-control bs-datepicker" placeholder="Tangal IUP">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col"> 
                                        <div class="row mb-3">
                                            <label class="col-md-4 form-label">Negara Asal</label>
                                            <div class="col-md-8">
                                                <select class="form-control select2-show-search form-select select-negara" id="k_kdNegaraAsal" name="k_kdNegaraAsal" data-placeholder="--Pilih--" style="width: 100%;">
                                                    <option label="--Pilih--"></option>
                                                </select>									
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-md-4 form-label">Harga Barang</label>
                                            <div class="col-md-8">
                                            <input type="text" id="k_hargaBarang" name="k_hargaBarang" class="form-control numberaja" placeholder="Harga Barang">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-md-4 form-label">Currency</label>
                                            <div class="col-md-8">
                                                <select class="form-control select2-show-search form-select select-currency" id="k_currencyHargaBarang" name="k_currencyHargaBarang" data-placeholder="--Pilih--" style="width: 100%;">
                                                    <option label="--Pilih--"></option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-md-4 form-label">Harga Barang (IDR)</label>
                                            <div class="col-md-8">
                                                <input type="text" id="k_hargaBarangIdr" name="k_hargaBarangIdr" class="form-control numberaja" placeholder="Harga Barang (IDR)">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-md-4 form-label">Harga Barang (USD)</label>
                                            <div class="col-md-8">
                                                <input type="text" id="k_hargaBarangUsd" name="k_hargaBarangUsd" class="form-control numberaja" placeholder="Harga Barang (USD)">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-md-4 form-label">CAS Number</label>
                                            <div class="col-md-8">
                                                <input type="text" id="k_casNo" name="k_casNo" class="form-control numberaja" placeholder="CAS Number">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-md-4 form-label">ICUMSA</label>
                                            <div class="col-md-8">
                                                <input type="text" id="k_icumsasNo" name="k_icumsasNo" class="form-control numberaja" placeholder="ICUMSA">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-md-4 form-label">ASHRAE</label>
                                            <div class="col-md-8">
                                                <input type="text" id="k_ashraeNo" name="k_ashraeNo" class="form-control numberaja" placeholder="ASHRAE">
                                            </div>
                                        </div>
                                    </div>
                                </div>  
                                <div class="row mt-4">
                                    <div class="btn-list text-center"> 
                                        @if (!empty($datals->idPermohonanNSW)) 
                                            <b class="text-danger btn-save-hs mb-3">Permohonan SIMBARA tidak dapat menambahkan komoditas</b>
                                        @endif
                                        
                                        @if (empty($datals->idPermohonanNSW))
                                            <button type="reset" class="btn d-w-md btn-danger btn-save-hs btn-sm" id="reset-form-komoditas" onclick="resetFormKomoditas()">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eraser" width="15" height="15" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                    <path d="M19 20h-10.5l-4.21 -4.3a1 1 0 0 1 0 -1.41l10 -10a1 1 0 0 1 1.41 0l5 5a1 1 0 0 1 0 1.41l-9.2 9.3"></path>
                                                    <path d="M18 13.3l-6.3 -6.3"></path>
                                                </svg> Reset
                                            </button>
                                            <button type="button" class="btn d-w-md btn-sm btn-primary btn-save-hs" onclick="addKomoditas()"><i class="fa fa-save me-2" aria-hidden="true"></i>Save</button>
                                        @endif

                                        <button type="reset" class="btn d-w-md btn-sm btn-danger disabled d-none btn-update-hs" id="reset-form-komoditas" onclick="resetFormKomoditas()">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eraser" width="15" height="15" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M19 20h-10.5l-4.21 -4.3a1 1 0 0 1 0 -1.41l10 -10a1 1 0 0 1 1.41 0l5 5a1 1 0 0 1 0 1.41l-9.2 9.3"></path>
                                                <path d="M18 13.3l-6.3 -6.3"></path>
                                            </svg> Batal
                                        </button>
                                        <button type="button" class="btn d-w-md btn-sm btn-warning disabled d-none btn-update-hs" onclick="addKomoditas()"><i class="fa fa-save me-2" aria-hidden="true"></i>Update</button>
                                    
                                        <input type="hidden" name="idPosTarif" id="idPosTarif">
                                    </div>
                                </div>
                            </form> 
                        </div> 
                    </div>
                @endif

                <div class="table-responsive mt-5">
                    <table id="table-komoditas" class="table text-md-nowrap table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">Seri</th>
                                <th>Pos Tarif / Jumlah</th>
                                <th>Uraian / Spesifikasi</th>
                                <th>NTPN / IUP</th>
                                <th>Berat / Negara / Harga</th>
                                <th class="text-center">AKSI</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>  
            </div> 
        </div>

        <div class="col-md-12 mt-3"> 
            <!-- <div class="card"> 
                <div class="card-body"> -->
                    <ul role="menu" aria-label="Pagination">
                        <li class="d-none" aria-disabled="false"> 
                        </li> 
                    </ul>
                <!-- </div>
            </div> -->
        </div> 
    </div>
</div>

<!-- modal package -->
<div id="modalPackage" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">   
                <div class="col-md-12"> 
                    <div class="card"> 
                        <div class="card-body"> 
                            <h5 class="card-title fw-semibold mb-4">Tambah Package</h5>

                            <form id="form-package" name="form-package" action="javascript:void(0);">  
                                <div class="card-body"> 
                                    <div class="row">
                                        <div class="col">
                                            <div class="row mb-3">
                                                <label class="col-md-4 form-label">Jumlah Package</label>
                                                <div class="col-md-8"> 
                                                    <input type="text" class="form-control numberaja" id="p_jml" name="p_jml" placeholder="Jumlah Package">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label class="col-md-4 form-label">Unit</label>
                                                <div class="col-md-8">  
                                                    <select id="p_unit" name="p_unit" class="form-control select2-show-search form-select" data-placeholder="-- Pilih --" style="width: 100%;">
														<option label="-- Pilih --"></option>
													</select>                                                
                                                </div>
                                            </div> 
                                        </div>
                                        
                                        <div class="col">
                                            <div class="row mb-3">
                                                <label class="col-md-4 form-label">Package Information</label>
                                                <div class="col-md-8"> 
                                                    <textarea class="form-control" id="p_packageInfo" name="p_packageInfo" rows="3"></textarea> 
                                                </div>
                                            </div>  
                                        </div>
                                    </div>  
                                </div> 
                                <div class="row">
                                    <div class="btn-list text-center">
										<input type="hidden" id="idPackage" name="idPackage" readonly> 
                                        <button type="button" class="btn d-w-md btn-dark btn-sm" data-bs-dismiss="modal" > 
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="15" height="15" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M18 6l-12 12"></path>
                                                <path d="M6 6l12 12"></path>
                                            </svg>
                                            Tutup
                                        </button>
                                        &nbsp;
                                        <button type="reset" class="btn d-w-md btn-danger btn-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eraser" width="15" height="15" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M19 20h-10.5l-4.21 -4.3a1 1 0 0 1 0 -1.41l10 -10a1 1 0 0 1 1.41 0l5 5a1 1 0 0 1 0 1.41l-9.2 9.3"></path>
                                                <path d="M18 13.3l-6.3 -6.3"></path>
                                            </svg>
                                            Reset
                                        </button>
                                        &nbsp;
                                        <button type="button" class="btn d-w-md btn-primary btn-sm" onclick="add_package()"><i class="fa fa-save me-2" aria-hidden="true"></i>Simpan</button>  
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div> 
            </div><!-- modal-body -->
        </div><!-- modal-content -->
    </div><!-- modal-dialog -->
</div> 
 
<!-- modal container -->
<div id="modalContainer" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">   
                <div class="col-md-12"> 
                    <div class="card"> 
                        <div class="card-body"> 
                            <h5 class="card-title fw-semibold mb-4">Tambah Container</h5> 
                            <form id="form-container" name="form-container" action="javascript:void(0);">  
                                <div class="card-body"> 
                                    <div class="row">
                                        <div class="col">
                                            <div class="row mb-3">
                                                <label class="col-md-4 form-label">Jenis</label>
                                                <div class="col-md-8"> 
													<select id="cnt_jenis" name="cnt_jenis" class="form-control select2-show-search form-select" data-placeholder="-- Pilih --" style="width: 100%;">
														<option label="-- Pilih --"></option>
													</select>
													<small><a href="https://api-pdsi.gitbook.io/api-laporan-surveyor/reference/api-reference/kode-kontainer" target="_blank">Referensi klik disini</a></small>
                                                </div>
                                            </div> 
                                        </div>
                                        
                                        <div class="col">
                                            <div class="row mb-3">
                                                <label class="col-md-4 form-label">Nomor</label>
                                                <div class="col-md-8"> 
													<input type="text" class="form-control" id="cnt_nomor" name="cnt_nomor" placeholder="Nomor container">
                                                </div>
                                            </div>  
                                        </div>
                                    </div>  
                                </div> 
                                <div class="row">
                                    <div class="btn-list text-center"> 
										<input type="hidden" id="idContainer" name="idContainer" readonly> 
                                        <button type="button" class="btn d-w-md btn-dark btn-sm" data-bs-dismiss="modal" > 
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="15" height="15" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M18 6l-12 12"></path>
                                                <path d="M6 6l12 12"></path>
                                            </svg>
                                            Tutup
                                        </button>
                                        &nbsp;
                                        <button type="reset" class="btn d-w-md btn-danger btn-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eraser" width="15" height="15" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M19 20h-10.5l-4.21 -4.3a1 1 0 0 1 0 -1.41l10 -10a1 1 0 0 1 1.41 0l5 5a1 1 0 0 1 0 1.41l-9.2 9.3"></path>
                                                <path d="M18 13.3l-6.3 -6.3"></path>
                                            </svg>
                                            Reset
                                        </button>
                                        &nbsp;
                                        <button type="button" class="btn d-w-md btn-primary btn-sm" onclick="add_container()"><i class="fa fa-save me-2" aria-hidden="true"></i>Simpan</button>  
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div> 
            </div><!-- modal-body -->
        </div><!-- modal-content -->
    </div><!-- modal-dialog -->
</div> 
  
<!-- modal upload dokumen -->
<div id="modalNewDok" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-body">   
                <div class="col-md-12"> 
                    <div class="card"> 
                        <div class="card-body"> 
                            <h5 class="card-title fw-semibold mb-4">Upload Dokumen</h5> 
                            <form id="form-dokumen" name="form-dokumen" enctype="multipart/form-data" action="javascript:void(0);">
                                <div class="card-body"> 
                                    <div class="row">
                                        <div class="col">
                                            <div class="row mb-3">
                                                <label class="col-md-4 form-label">Jenis Dokumen</label>
                                                <div class="col-md-8"> 
													<select id="t_idJenisDok" name="t_idJenisDok" class="form-control select2-show-search form-select" data-placeholder="-- Pilih --" style="width: 100%;">
                                                        <option label="-- Pilih --"></option>
                                                    </select>
													<small><a href="https://api-pdsi.gitbook.io/api-laporan-surveyor/reference/api-reference/kode-kontainer" target="_blank">Referensi klik disini</a></small>
                                                </div>
                                            </div> 
                                            <div class="row mb-3">
                                                <label class="col-md-4 form-label">Nomor Dokumen</label>
                                                <div class="col-md-8"> 
                                                    <input type="text" class="form-control" id="t_noDokumen" name="t_noDokumen" placeholder="Nomor Dokumen">
                                                </div>
                                            </div> 
                                            <div class="row mb-3">
                                                <label class="col-md-4 form-label">Tanggal Dokumen</label>
                                                <div class="col-md-8"> 
                                                    <input type="text" class="form-control bs-datepicker" id="t_tglDokumen" name="t_tglDokumen" placeholder="Tanggal Dokumen">
                                                </div>
                                            </div> 
                                        </div>
                                        
                                        <div class="col">
                                            <div class="row mb-3">
                                                <label class="col-md-4 form-label">Negara Penerbit</label>
                                                <div class="col-md-8"> 
													<select id="t_negaraPenerbit" name="t_negaraPenerbit" class="form-control select2-show-search form-select" data-placeholder="-- Pilih --" style="width: 100%;">
                                                        <option label="-- Pilih --"></option>
                                                    </select>
                                                </div>
                                            </div>  
                                            <div class="row mb-3" id="uploadFile">
                                                <label class="col-md-4 form-label">Pilih File</label>
                                                <div class="col-md-8"> 
                                                    <input class="form-control file-input" type="file" id="t_fileDok" name="t_fileDok" placeholder="File">
                                                </div>
                                            </div>
                                            <div class="row mb-3" id="viewFile">
                                                <label class="col-md-4 form-label">Pilih File</label>
                                                <div class="col-md-5">
                                                        <input class="form-control file-input" type="file" id="t_fileDokUpdate" name="t_fileDokUpdate" placeholder="File">
                                                </div> 
                                                <div class="col-md-3" id="lingFile">  
                                                </div>
                                            </div>  
                                            <div class="row mb-3">
                                                <label class="col-md-4 form-label">Tanggal Akhir</label>
                                                <div class="col-md-8"> 
                                                    <input type="text" class="form-control bs-datepicker" id="t_tglAkhirDokumen" name="t_tglAkhirDokumen" placeholder="Tanggal Akhir Dokumen">
                                                </div>
                                            </div>  
                                        </div>
                                    </div>  
                                    <div class="row"> 
                                        <div class="col">
                                            <div class="row mb-3">
                                                <div class="col-md-1"> 
                                                    <input type="checkbox" id="cbx-pilih">    
                                                </div>
                                                Simpan dan pilih sebagai referensi LS ini
                                            </div>  
                                        </div> 
                                    </div>
                                </div> 
                                <div class="row">
                                    <div class="btn-list text-center"> 
                                        <button type="button" class="btn d-w-md btn-dark btn-sm" data-bs-dismiss="modal" id="close-form-upload"> 
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="15" height="15" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M18 6l-12 12"></path>
                                                <path d="M6 6l12 12"></path>
                                            </svg>
                                            Tutup
                                        </button>
                                        &nbsp;
                                        <button type="reset" class="btn d-w-md btn-danger btn-sm" id="reset-form-upload">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eraser" width="15" height="15" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M19 20h-10.5l-4.21 -4.3a1 1 0 0 1 0 -1.41l10 -10a1 1 0 0 1 1.41 0l5 5a1 1 0 0 1 0 1.41l-9.2 9.3"></path>
                                                <path d="M18 13.3l-6.3 -6.3"></path>
                                            </svg>
                                            Reset
                                        </button>
                                        &nbsp;
                                        <button type="button" class="btn d-w-md btn-primary btn-sm" onclick="upload_dok()">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-upload" width="15" height="15" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2"></path>
                                                <path d="M7 9l5 -5l5 5"></path>
                                                <path d="M12 4l0 12"></path>
                                             </svg>
                                            Simpan
                                        </button>  
                                        <input type="hidden" id="idDok" name="idDok">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div> 
            </div><!-- modal-body -->
        </div><!-- modal-content -->
    </div><!-- modal-dialog -->
</div> 
  
<!-- modal pilih dokumen -->
<div id="modalPilihDok" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false"> 
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-body">   
                <div class="col-md-12"> 
                    <div class="card"> 
                        <div class="card-body"> 
                            <h5 class="card-title fw-semibold mb-4">Pilih Dokumen Pendukung</h5> 
                            <form id="form-dokumen" name="form-dokumen" enctype="multipart/form-data" action="javascript:void(0);">
                                <div class="card-body"> 
                                    <div class="row"> 
                                        <table id="table-pilih-dok" class="table table-bordered border-bottom w-100">
                                            <thead>
                                                <tr> 
                                                    <th width="5%">No</th>
                                                    <th width="5%">#</th>
                                                    <th width="20%">Jenis Dokumen</th>
                                                    <th width="10%">Negara Penerbit</th>
                                                    <th width="15%">No Dokumen</th>
                                                    <th width="15%">Tanggal Dokumen</th>
                                                    <th width="15%">Tanggal Akhir</th>
                                                    <th width="15%" class="text-center">Aksi</th>
                                                </tr>
                                            </thead> 
                                        </table>
                                    </div>   
                                </div> 
                                <div class="row">
                                    <div class="btn-list text-center">  
                                        <button type="button" class="btn d-w-md btn-dark btn-sm" data-bs-dismiss="modal" > 
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="15" height="15" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M18 6l-12 12"></path>
                                                <path d="M6 6l12 12"></path>
                                            </svg>
                                            Tutup
                                        </button>
                                        &nbsp;
                                        <button type="reset" class="btn d-w-md btn-danger btn-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eraser" width="15" height="15" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M19 20h-10.5l-4.21 -4.3a1 1 0 0 1 0 -1.41l10 -10a1 1 0 0 1 1.41 0l5 5a1 1 0 0 1 0 1.41l-9.2 9.3"></path>
                                                <path d="M18 13.3l-6.3 -6.3"></path>
                                            </svg>
                                            Reset
                                        </button>
                                        &nbsp;
                                        <button type="button" class="btn d-w-md btn-primary btn-sm" onclick="pilihDokAct()">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-upload" width="15" height="15" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2"></path>
                                                <path d="M7 9l5 -5l5 5"></path>
                                                <path d="M12 4l0 12"></path>
                                             </svg>
                                            Simpan
                                        </button>  
										<input type="hidden" id="iddokumen" name="iddokumen" readonly> 
                                        <input  id="idChecked" name="idChecked" type="hidden" readonly> 
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div> 
            </div><!-- modal-body -->
        </div><!-- modal-content -->
    </div><!-- modal-dialog -->
</div> 

<!-- modal dokuemen NSW--> 
<div id="modalDokNsw" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-body">   
                <div class="col-md-12"> 
                    <div class="card"> 
                        <div class="card-body"> 
                            <h5 class="card-title fw-semibold mb-4">Upload Dokumen LNSW</h5>
                            <form id="form-dokumen-insw" name="form-dokumen-insw" enctype="multipart/form-data" action="javascript:void(0);">
                                <div class="card-body"> 
                                    <div class="row">
                                        <div class="col">
                                            <div class="row mb-3">
                                                <label class="col-md-4 form-label">Jenis Dokumen</label>
                                                <div class="col-md-8"> 
                                                    <label class="form-label" id="lbl-jenisdok-nsw"> </label>
                                                </div>
                                            </div> 
                                            <div class="row mb-3">
                                                <label class="col-md-4 form-label">Nomor Dokumen</label>
                                                <div class="col-md-8"> 
                                                    <label class="form-label" id="lbl-nodok-nsw"> </label>
                                                </div>
                                            </div> 
                                            <div class="row mb-3">
                                                <label class="col-md-4 form-label">Tanggal Dokumen</label>
                                                <div class="col-md-8"> 
                                                    <label class="form-label" id="lbl-tgldok-nsw"> </label>
                                                </div>
                                            </div> 
                                        </div>
                                        
                                        <div class="col">
                                            <div class="row mb-3">
                                                <label class="col-md-4 form-label">Negara Penerbit</label>
                                                <div class="col-md-8"> 
                                                    <select id="t_negaraPenerbitNsw" name="t_negaraPenerbitNsw" class="form-control select2-show-search form-select" data-placeholder="-- Pilih --" style="width: 100%;">
                                                        <option label="-- Pilih --"></option>
                                                    </select>
                                                </div>
                                            </div>   
                                            <div class="row mb-3">
                                                <label class="col-md-4 form-label">Pilih File</label> 
                                                <div class="col-md-8">  
                                                    <label class="form-label" id="lbl-file-nsw"> </label>
                                                </div>
                                            </div>  
                                            <div class="row mb-3">
                                                <label class="col-md-4 form-label">Tanggal Akhir</label>
                                                <div class="col-md-8"> 
                                                    <input type="text" class="form-control bs-datepicker" id="t_tglAkhirDokumenNsw" name="t_tglAkhirDokumenNsw" placeholder="Tanggal Akhir Dokumen">
                                                </div>
                                            </div>  
                                        </div>
                                    </div>   
                                </div> 
                                <div class="row">
                                    <div class="btn-list text-center"> 
                                        <button type="button" class="btn d-w-md btn-dark btn-sm" data-bs-dismiss="modal" id="close-form-upload"> 
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="15" height="15" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M18 6l-12 12"></path>
                                                <path d="M6 6l12 12"></path>
                                            </svg>
                                            Tutup
                                        </button>
                                        &nbsp;
                                        <button type="reset" class="btn d-w-md btn-danger btn-sm" id="reset-form-upload">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eraser" width="15" height="15" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M19 20h-10.5l-4.21 -4.3a1 1 0 0 1 0 -1.41l10 -10a1 1 0 0 1 1.41 0l5 5a1 1 0 0 1 0 1.41l-9.2 9.3"></path>
                                                <path d="M18 13.3l-6.3 -6.3"></path>
                                            </svg>
                                            Reset
                                        </button>
                                        &nbsp;
                                        <button type="button" class="btn d-w-md btn-primary btn-sm" onclick="save_dok_nsw()">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-upload" width="15" height="15" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2"></path>
                                                <path d="M7 9l5 -5l5 5"></path>
                                                <path d="M12 4l0 12"></path>
                                             </svg>
                                            Save
                                        </button>   
                                        <input type="hidden" id="idDokNSW" name="idDokNSW" >
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div> 
            </div><!-- modal-body -->
        </div><!-- modal-content -->
    </div><!-- modal-dialog -->
</div> 