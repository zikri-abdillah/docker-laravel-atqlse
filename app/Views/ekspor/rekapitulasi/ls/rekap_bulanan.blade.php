<!-- Row -->
<div class="row row-sm">
    <div class="col-lg-12 col-md-12">
        <!-- Form Pencarian -->
        <div aria-multiselectable="true" class="accordion-search mb-2" id="accordion3" role="tablist">
            <div class="card mb-5 mt-2">
                <div class="card-header border-bottom-0" id="heading3" role="tab" aria-controls="collapse3" aria-expanded="false" data-bs-toggle="collapse" href="#collapse3" style="cursor: pointer;">
                    <a class="accor-style2 collapsed text-white" aria-controls="collapse3" aria-expanded="false" data-bs-toggle="collapse" href="#collapse3"><i class="fe fe-plus-circle me-2"></i>Form Pencarian</a>
                </div>
                <div aria-labelledby="heading3" class="collapse" data-bs-parent="#accordion3" id="collapse3" role="tabpanel">
                    <div class="card-body">
                        <form id="frm-search" method="POST" return>
                            <div class="row">
                                <div class="col">
                                    <div class="row mb-3">
                                        <label class="col-md-4 form-label">Periode Laporan</label>
                                        <div class="col-md-4">
                                            <input class="form-control form-control-sm" id="s_noSi" name="s_noSi" type="text" placeholder="Bulan">
                                        </div>
                                        <div class="col-md-4">
                                            <input class="form-control form-control-sm" id="s_noSi" name="s_noSi" type="text" placeholder="Tahun">
                                        </div>
                                    </div>
                                </div>
                                <div class="col">

                                    <div class="row mb-3">
                                        <label class="col-md-4 form-label">Nomor Laporan</label>
                                        <div class="col">
                                            <input class="form-control form-control-sm" id="s_noLs" name="s_noLs" type="text" placeholder="Nomor Laporan">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="mt-3 text-center">
                                    <button type="reset" id="btn-reset-coal" class="btn btn-danger btn-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eraser" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M19 20h-10.5l-4.21 -4.3a1 1 0 0 1 0 -1.41l10 -10a1 1 0 0 1 1.41 0l5 5a1 1 0 0 1 0 1.41l-9.2 9.3"></path>
                                            <path d="M18 13.3l-6.3 -6.3"></path>
                                        </svg> Reset
                                    </button>  
                                    <button type="button" id="btn-tracking-coal" class="btn btn-primary btn-sm">
                                        <i class="fa fa-search" aria-hidden="true"></i> Search 
                                    </button>
                                </div>
                            </div> 
                        </form>
                    </div>
                </div><!-- collapse -->
            </div>
        </div>
        <!-- End Form Pencarian -->

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <h5 class="card-title fw-semibold mb-4">{{ $table_title }}</h5> 
                    </div>
                    <div class="col-md-6 mb-4" align="right">
                        {{-- @if (session()->get('sess_role') == 1) --}}
                            <a class="btn btn-indigo m-1" onclick="showModalAdd()">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-pencil-plus" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4"></path>
                                    <path d="M13.5 6.5l4 4"></path>
                                    <path d="M16 19h6"></path>
                                    <path d="M19 16v6"></path>
                                </svg> 
                                Buat Laporan
                            </a>
                        {{-- @endif --}}
                    </div> 
                    
                    <div class="mb-12"> 
                        <div class="table-responsive">
                            <table class="table table-striped table-hover w-100" id="table-data">
                                <thead>
                                    <tr>
                                        <th class="border-bottom-0 text-center">#</th>
                                        <th class="border-bottom-0">NOMOR<br>LAPORAN</th>
                                        <th class="border-bottom-0">TANGGAL<br>LAPORAN</th>
                                        <th class="border-bottom-0">PERIODE<br>LAPORAN</th>
                                        <th class="border-bottom-0">SUMMARY</th>
                                        <th class="border-bottom-0">STATUS</th>
                                        <th class="border-bottom-0 text-center">AKSI</th>
                                    </tr>
                                </thead>
                            </table>
                        </div> 
                    </div> 
                </div> 
            </div> 
        </div> 
    </div> 
</div>  

<!-- modal -->
<div id="modalDetailLs" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-body">   
                <div class="col-md-12"> 
                    <div class="card"> 
                        <div class="card-body">  
                            <div class="row border-bottom mb-3">
                                <div class="col">
                                    <div class="row mb-1">
                                        <label class="col-md-4 form-label"><b>Nomor Laporan</b></label>
                                        <label class="col-md-8 form-label" id="text-nomor"></label>
                                    </div>
                                    <div class="row mb-1">
                                        <label class="col-md-4 form-label"><b>Tanggal Laporan</b></label>
                                        <label class="col-md-8 form-label" id="text-tanggal"></label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="row mb-1">
                                        <label class="col-md-4 form-label"><b>Periode Laporan</b></label>
                                        <label class="col-md-8 form-label" id="text-periode"></label>
                                    </div>
                                    <div class="row mb-1">
                                        <label class="col-md-4 form-label"><b>File Laporan</b></label>
                                        <label class="col-md-3 form-label"><button type="button" class="btn btn-sm btn-primary btn-file-laporan"><i class="fa fa-file me-1" aria-hidden="true"></i>Lihat</button></label>

                                    </div>
                                </div>
                                <div class="col-md-1" align="right">
                                    <button type="button" class="btn btn-dark btn-sm" data-bs-dismiss="modal" >
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                            </div>

                            <form id="frm-search-detail" name="frm-search-detail" method="POST" return>
                                <input type="text" class="d-none" name="idLaporanModal" id="idLaporanModal">
                                <div class="row">
                                    <div class="col-md-6">
                                        {{-- <div class="row mb-2">
                                            <label class="col-md-4 form-label">Jenis LS</label>
                                            <div class="col-md-8">
                                                <select class="form-control select2-show-search form-select select2" id="t_jenisls" name="t_jenisls" data-placeholder="-- SEMUA JENIS --" data-allow-clear="true" style="width: 100%;">
                                                    <option value="">-- SEMUA JENIS --</option>
                                                    <option value="COAL">BATUBARA</option>
                                                    <option value="MINERAL">MINERAL</option>
                                                </select>
                                            </div>
                                        </div> --}}
                                        <div class="row mb-2">
                                            <label class="col-md-4 form-label">Jenis Penerbitan</label>
                                            <div class="col-md-8">
                                                <select class="form-control select2-show-search form-select select2" id="t_jeniterbit" name="t_jeniterbit" data-placeholder="-- SEMUA STATUS --" data-allow-clear="true" style="width: 100%;">
                                                    <option value="">-- SEMUA STATUS --</option>
                                                    <option value="BARU">BARU</option>
                                                    <option value="PERUBAHAN">PERUBAHAN</option>
                                                    <option value="PEMBATALAN">PEMBATALAN</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="row mb-2">
                                            <label class="col-md-4 form-label">Nomor LS</label>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control" id="t_nols" name="t_nols" placeholder="Nomor Laporan Surveyor">
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <label class="col-md-4 form-label">Eksportir</label>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control" id="t_namapersh" name="t_namapersh" placeholder="Nomor Eksportir">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="mt-3 text-center">
                                        <button type="button" id="btn-search-detail" class="btn btn-sm btn-info">
                                            <i class="ion ion-md-search me-1" aria-hidden="true"></i>Search
                                        </button>
                                    </div>
                                </div>
                            </form>

                            <div class="row table-responsive">
                                <table id="table-detail" class="table table-sm table-bordered border-bottom w-100 small ">
                                    <thead>
                                        <tr>
                                            <th class="border-bottom-0 text-center">NO</th>
                                            <th class="border-bottom-0 text-center">JENIS LS</th>
                                            <th class="border-bottom-0 text-center">EKSPORTIR</th>
                                            <th class="border-bottom-0 text-center">NOMOR LS</th>
                                            <th class="border-bottom-0 text-center">TANGGAL LS</th>
                                            <th class="border-bottom-0 text-center">JENIS PENERBITAN</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div> 
                        </div>
                    </div>
                </div> 
            </div><!-- modal-body -->
        </div><!-- modal-content -->
    </div><!-- modal-dialog -->
</div> 

<!-- modal laporan -->
<div id="modalAddLaporan" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Buat Laporan Bulanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="card">
                        <div>Laporan bulanan perlu dibuat ulang bila ada penerbitan LS baru / perubahan / pembatalan untuk peride bulan dan tahun yang sudah pernah dibuat<br>
                        Bila status laporan masih konsep, silahkan hapus dan buat ulang laporan. Bila status sudah terkirim, klik tombol buat laporan untuk membuat laporan perubahan</div>
                        <div class="row">
                            <form id="form-laporan" name="form-laporan" action="javascript:void(0);">
                                <input type="text" class="d-none" name="idLaporan" id="idLaporan">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <div class="row mb-3">
                                                <label class="col-md-4 form-label">Nomor Laporan</label>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control" id="i_noLaporan" name="i_noLaporan" placeholder="Nomor Laporan">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label class="col-md-4 form-label">Tanggal Laporan</label>
                                                <div class="col-md-8">
                                                    <input type="text" class="form-control bs-datepicker" id="i_tglLaporan" name="i_tglLaporan" placeholder="Tanggal Laporan">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label class="col-md-4 form-label">Upload File Laporan</label>
                                                <div class="col-md-8 div-upload-file">
                                                    <input class="form-control" id="i_fileLaporan" name="i_fileLaporan" type="file">
                                                </div>
                                                <div class="col-md-8 div-view-file d-none">
                                                    <button type="button" class="btn btn-sm btn-danger btn-hapus-file"><i class="fa fa-trash me-1" aria-hidden="true"></i>Hapus</button>
                                                    <button type="button" class="btn btn-sm btn-primary btn-lihat-file"><i class="fa fa-file me-1" aria-hidden="true"></i>Lihat</button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="row mb-3">
                                                <label class="col-md-4 form-label">Bulan</label>
                                                <div class="col-md-8">
                                                    <select class="form-control select2-show-search form-select select2" id="i_bulan" name="i_bulan" data-placeholder="-- Pilih Bulan Laporan --" data-allow-clear="true" style="width: 100%;">
                                                        <option value="">-- Semua Bulan --</option>
                                                        @foreach ($arrBulan as $key => $bulan)
                                                            <option value="{{ $key }}">{{ $bulan }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label class="col-md-4 form-label">Tahun</label>
                                                <div class="col-md-8">
                                                    <select class="form-control select2-show-search form-select select2" id="i_tahun" name="i_tahun" data-placeholder="-- Pilih Tahun Laporan --" data-allow-clear="true" style="width: 100%;">
                                                        <option value="">-- Semua Tahun --</option>
                                                        @for ($tahun = max(date("Y") - 2, 0); $tahun <= date("Y"); $tahun++)
                                                            <option value="{{ $tahun }}">{{ $tahun }}</option>
                                                        @endfor

                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="btn-list text-center">
                                        <input type="hidden" id="idPackage" name="idPackage" readonly>
                                        <button type="reset" class="btn d-w-md btn-danger btn-sm">
                                            <i class="me-2 fas fa-fw fa-eraser"></i>Reset
                                        </button>
                                        &nbsp;
                                        <button type="button" class="btn d-w-md btn-success btn-sm" onclick="save_draft()">
                                            <i class="fa fa-save me-2" aria-hidden="true"></i>Simpan
                                        </button>
                                        &nbsp;
                                        <button type="button" class="btn d-w-md btn-primary btn-sm" onclick="view_draft_laporan()">
                                            <i class="fa fa-eye me-2" aria-hidden="true"></i>Lihat Data
                                        </button>
                                    </div>
                                </div>
                                <div class="row pt-4" id="div-draft-summary">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div><!-- modal-body -->
        </div><!-- modal-content -->
    </div><!-- modal-dialog -->
</div>