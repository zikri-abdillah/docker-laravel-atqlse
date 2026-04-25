<!-- Row -->
<div class="row row-sm">
    <div class="col-lg-12 col-md-12">
        <!-- Form Pencarian -->
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
                    <form id="frm-tracking-coal" method="POST" return>
                        <div class="row">
                            <div class="col">
                                <div class="row mb-3">
                                    <label class="col-md-4 form-label">Jenis Penerbitan</label>
                                    <div class="form-group col-md-8">
                                        <select class="form-control select2-show-search form-select" id="s_idJenisTerbit" name="s_idJenisTerbit" data-placeholder="-- Pilih --" style="width: 100%;">
                                            <option value="">Semua Jenis</option>
                                            <option value="1">Baru</option>
                                            <option value="2">Perubahan</option>
                                            <option value="4">Perpanjangan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-md-4 form-label">Nomor Aju LNSW</label>
                                    <div class="col-md-8">
                                        <input class="form-control" id="s_noSi" name="s_noSi" type="text" placeholder="Nomor Aju LNSW">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-md-4 form-label">Nomor Draft</label>
                                    <div class="col-md-8">
                                        <input class="form-control" id="s_draftNo" name="s_draftNo" type="text" placeholder="Nomor Draft">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-md-4 form-label">Nama Eksportir</label>
                                    <div class="form-group col-md-8 mb-0">
                                        <input class="form-control" id="s_namaPersh" name="s_namaPersh" type="text" placeholder="Nama Eksportir">
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="row mb-3">
                                    <label class="col-md-4 form-label">Cabang Penerbit</label>
                                    <div class="form-group col-md-8 mb-0">
                                        <select class="form-control select2 select-cabang" id="s_idCabang" name="s_idCabang" data-placeholder="Cabang Penerbit" data-allow-clear="true">
                                            <option label="Cabang Penerbit"></option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-md-4 form-label">Nomor LS</label>
                                    <div class="col">
                                        <input class="form-control" id="s_noLs" name="s_noLs" type="text" placeholder="Nomor LS">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-md-4 form-label">Tanggal LS</label>
                                    <div class="col-md-3">
                                        <input class="form-control bs-datepicker" id="s_tglLs" name="s_tglLs" type="text" placeholder="Tanggal Awal">
                                    </div>
                                    <div class="col-md-1 mt-2">
                                        s.d
                                    </div>
                                    <div class="col-md-3">
                                        <input class="form-control bs-datepicker" id="s_tglAkhirLs" name="s_tglAkhirLs" type="text" placeholder="Tanggal Akhir">
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
                                <button type="button" id="btn-export-coal" class="btn btn-success btn-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-download" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2"></path>
                                        <path d="M7 11l5 5l5 -5"></path>
                                        <path d="M12 4l0 12"></path>
                                    </svg> Export
                                </button>

                                <input type="hidden" id="dataFilter" name="dataFilter" value="{{ $dataFilter }}">
                            </div>
                        </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>

        </div>
        <!-- End Form Pencarian -->

        <div class="card card-data">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <h5 class="card-title fw-semibold mb-4">{{ $table_title }}</h5> 
                    </div>
                    <div class="col-md-6 mb-4" align="right">
                        @if (session()->get('sess_role') == 6) 
                            <a class="btn btn-indigo m-1" href="<?= base_url() ?>/ekspor/coal/ls/input">
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
                            <table class="table table-bordered w-100" id="table-coal">
                                <thead>
                                    <tr>
                                        <th class="bg-data-table border-bottom-0 text-center">No</th>
                                        <th class="bg-data-table border-bottom-0">Pengajuan</th>
                                        <th class="bg-data-table border-bottom-0">Lap. Surveyor</th>
                                        <th class="bg-data-table border-bottom-0">Perusahaan</th>
                                        <th class="bg-data-table border-bottom-0">Shipment</th>
                                        <th class="bg-data-table border-bottom-0">Status</th>
                                        <th class="bg-data-table border-bottom-0 text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div> 
                    </div> 
                </div> 
            </div> 
        </div> 
    </div> 
</div>  

<!-- modal -->
<div id="modalLog" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-body">   
                <div class="col-md-12"> 
                    <div class="card"> 
                        <div class="card-body">  
                            
                            <div class="row border-bottom mb-3">
                                <div class="col"> 
                                    <div class="row mb-3">
                                        <label class="col-md-3 form-label"><b>Jenis LS</b></label>
                                        <label class="col-md-8 form-label"id="text-jenis-ls"></label> 
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-md-3 form-label"><b>Nomor LS</b></label> 
                                        <label class="col-md-8 form-label"id="text-no-ls"></label> 
                                    </div> 
                                </div>
                                <div class="col">
                                    <div class="row mb-3">
                                        <label class="col-md-3 form-label"><b>Nomor Draft</b></label> 
                                        <label class="col-md-8 form-label"id="text-no-draft"></label> 
                                    </div> 
                                </div>
                                <div class="col-md-1" align="right">
                                    <button type="button" class="btn btn-dark btn-sm" data-bs-dismiss="modal" > 
                                        <i class="fa fa-times"></i>
                                    </button> 
                                </div> 
                            </div>

                            <div class="row table-responsive">
                                <table id="table-log-lse" class="table table-bordered border-bottom w-100">
                                    <thead>
                                        <tr>
                                            <th class="border-bottom-0 text-center">NO</th>
                                            <!-- <th class="border-bottom-0">NOMOR DRAFT</th> -->
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
            </div><!-- modal-body -->
        </div><!-- modal-content -->
    </div><!-- modal-dialog -->
</div> 