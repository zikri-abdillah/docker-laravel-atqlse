<!-- Row -->
<div class="row row-sm">
    <div class="col-lg-12 col-md-12">
        <div aria-multiselectable="true" class="accordion-search mb-2" id="accordion3" role="tablist">
            <div class="accordion" id="accordionExample">
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button text-white" style="background-color: #416589;" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            <i class="fe fe-search me-2"></i>Form Pencarian
                        </button>
                    </h2>
                    <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <form id="frm-tracking-cow" method="POST" return>
                                <div class="row">
                                    <div class="col">
                                        <div class="row mb-3">
                                            <label class="col-md-4 form-label">Nama Perusahaan</label>
                                            <div class="form-group col-md-8">
                                                <input type="text" class="form-control" id="s_namaPerusahaan" name="s_namaPerusahaan" placeholder="Nama Perusahaan">
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-md-4 form-label">Nomor LS</label>
                                            <div class="col-md-8">
                                                <input class="form-control" id="s_noLs" name="s_noLs" type="text" placeholder="Nomor LS">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="row mb-3">
                                            <label class="col-md-4 form-label">Nomor COW</label>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control" id="s_nomorCow" name="s_nomorCow" placeholder="Nomor COW">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mt-3 text-center">
                                        <button type="reset" id="btn-reset-cow" class="btn btn-danger btn-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eraser" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M19 20h-10.5l-4.21 -4.3a1 1 0 0 1 0 -1.41l10 -10a1 1 0 0 1 1.41 0l5 5a1 1 0 0 1 0 1.41l-9.2 9.3"></path>
                                                <path d="M18 13.3l-6.3 -6.3"></path>
                                            </svg> Reset
                                        </button>
                                        <button type="button" id="btn-tracking-cow" class="btn btn-primary btn-sm">
                                            <i class="fa fa-search" aria-hidden="true"></i> Search
                                        </button>
                                        <input type="hidden" id="dataFilter" name="dataFilter" value="{{ $dataFilter }}">
                                        <input type="hidden" id="jenis" name="jenis" value="1">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card card-data">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <h5 class="card-title fw-semibold mb-4">{{ $table_title }}</h5>
                    </div>
                    <div class="col-md-6 mb-4" align="right">
                        @if (session()->get('sess_role') == 6)
                            <a class="btn btn-indigo m-1" href="<?= site_url() ?>/ekspor/cow/input">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-pencil-plus" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                    <path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4"></path>
                                    <path d="M13.5 6.5l4 4"></path>
                                    <path d="M16 19h6"></path>
                                    <path d="M19 16v6"></path>
                                </svg>
                                Tambah Data
                            </a>
                        @endif
                    </div>

                    <div class="mb-12">
                        <div class="table-responsive" style="font-size: 12px;">
                            <table class="table table-bordered w-100" id="cow-list" style="font-size: 12px;">
                                <thead>
                                    <tr>
                                        <th class="bg-data-table border-bottom-0 text-center">No</th>
                                        <th class="bg-data-table border-bottom-0">COW</th>
                                        <th class="bg-data-table border-bottom-0">Perusahaan</th>
                                        <th class="bg-data-table border-bottom-0">Komoditas</th>
                                        <th class="bg-data-table border-bottom-0">Info</th>
                                        <th class="bg-data-table border-bottom-0">Status</th>
                                        <th class="bg-data-table border-bottom-0 text-center">Aksi</th>
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
<div id="modalLog" class="modal fade bs-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content"> 
            <div class="modal-body">
                <div class="card"> 
                    <div class="card-body">

                        <div class="row border-bottom mb-3">
                            <div class="col"> 
                                <div class="row mb-3">
                                    <label class="col-md-2 form-label"><b>Jenis LS</b></label>
                                    <label class="col-md-8 form-label"id="text-jenis-ls"></label> 
                                </div>
                                <div class="row mb-3">
                                    <label class="col-md-2 form-label"><b>Nomor LS</b></label> 
                                    <label class="col-md-8 form-label"id="text-no-ls"></label> 
                                </div> 
                            </div>
                            <div class="col">
                                <div class="row mb-3">
                                    <label class="col-md-3 form-label"><b>Nomor Draft</b></label> 
                                    <label class="col-md-8 form-label"id="text-no-draft"></label> 
                                </div> 
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="table-log-lse" class="table table-striped table-hover w-100">
                                <thead>
                                    <tr>
                                        <th class="border-bottom-0 text-center">NO</th> 
                                        <th class="border-bottom-0 text-center">STATUS PROSES</th>
                                        <th class="border-bottom-0 text-center">STATUS LS</th>
                                        <th class="border-bottom-0 text-center">AKSI</th>
                                        <th class="border-bottom-0 text-center">USER</th>
                                        <th class="border-bottom-0 text-center">WAKTU</th> 
                                        <th class="border-bottom-0 text-center">KETERANGAN</th>
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
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<!-- modal -->
<div id="modalDistribusi" class="modal fade bs-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="modalDistribusiLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">

                        <div class="row border-bottom mb-3">
                            <div class="col">
                                <div class="row mb-1">
                                    <label class="col-md-4 form-label"><b>Nomor Draft</b></label>
                                    <label class="col-md-8 form-label" id="distribusi-nodraft"></label>
                                </div>
                                <div class="row mb-1">
                                    <label class="col-md-4 form-label"><b>Nama Perusahaan</b></label>
                                    <label class="col-md-8 form-label" id="distribusi-nmpersh"></label>
                                </div>
                            </div>
                            <div class="col">
                                <div class="row mb-1">
                                    <label class="col-md-4 form-label"><b>Nomor SI</b></label>
                                    <label class="col-md-8 form-label" id="distribusi-nosi"></label>
                                </div>
                                <div class="row mb-1">
                                    <label class="col-md-4 form-label"><b>Nomor SI</b></label>
                                    <label class="col-md-8 form-label" id="distribusi-tglsi"></label>
                                </div>
                            </div>
                        </div>
                        <div class="row border-bottom mb-3">
                            <div class="row mb-3">
                                <label class="col-md-2 form-label">Cabang Penerbit</label>
                                <div class="col-md-4">
                                    <select class="form-control select2-show-search form-select select-cabang" id="d_idCabang" name="d_idCabang" data-placeholder="-- Silahkan Pilih --" data-allow-clear="true" style="width: 100%;">
                                        <option label="-- Silahkan Pilih --"></option>
                                    </select>
                                    <input type="text" class="d-none" id="idls" name="idls">
                                </div>
                                <label class="col-md-4 form-label">
                                    <button type="button" class="btn d-w-md btn-sm btn-secondary mt-1" onclick="save_distribusi_sabang()">
                                        <i class="mdi mdi-content-save-all-outline" aria-hidden="true"></i> Simpan
                                    </button>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
