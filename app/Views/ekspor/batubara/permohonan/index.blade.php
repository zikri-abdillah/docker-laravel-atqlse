<!-- Row -->
<div class="row row-sm">
    <div class="col-lg-12 col-md-12">
        <!-- Form Pencarian -->
        <div aria-multiselectable="true" class="accordion-search mb-2" id="accordion3" role="tablist">

            <div class="accordion mb-5" id="accordionExample">
              <div class="accordion-item">
                <h2 class="accordion-header">
                  <button class="accordion-button text-white" style="background-color: #416589;" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                    <i class="fe fe-search me-2"></i>Form Pencarian
                  </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                  <div class="accordion-body">
                    <form id="frm-tracking-pengajuan" method="POST" return>
                        <div class="row">
                            <div class="col">
                                <div class="row mb-3">
                                    <label class="col-md-4 form-label">Jenis Pengajuan</label>
                                    <div class="form-group col-md-8">
                                        <select class="form-control select2-show-search form-select" id="s_jnspengajuan" name="s_jnspengajuan" data-placeholder="-- Pilih --" style="width: 100%;">
                                            <option value="">Semua Jenis</option>
                                            <option value="1">Baru</option>
                                            <option value="2">Perubahan</option>
                                            <option value="4">Perpanjangan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-md-4 form-label">Nomor Pengajuan</label>
                                    <div class="col-md-8">
                                        <input class="form-control" id="s_nomorAju" name="s_nomorAju" type="text" placeholder="Nomor Pengajuan">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-md-4 form-label">Nomor Permohonan</label>
                                    <div class="col-md-8">
                                        <input class="form-control" id="s_nomorPermohonan" name="s_nomorPermohonan" type="text" placeholder="Nomor Permohonan">
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="row mb-3">
                                    <label class="col-md-4 form-label">Nama Perusahaan</label>
                                    <div class="col">
                                        <input class="form-control" id="s_namaPerusahaan" name="s_namaPerusahaan" type="text" placeholder="Nama Perusahaan">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-md-4 form-label">Nomor ET</label>
                                    <div class="col">
                                        <input class="form-control" id="s_nomorEt" name="s_nomorEt" type="text" placeholder="Nomor ET">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-md-4 form-label">Alat Transport</label>
                                    <div class="form-group col-md-8 mb-0">
                                        <input class="form-control" id="s_namaAlatPengirim" name="s_namaAlatPengirim" type="text" placeholder="Alat Transport">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mt-3 text-center">
                                <button type="reset" id="btn-reset-pengajuan" class="btn btn-danger btn-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eraser" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M19 20h-10.5l-4.21 -4.3a1 1 0 0 1 0 -1.41l10 -10a1 1 0 0 1 1.41 0l5 5a1 1 0 0 1 0 1.41l-9.2 9.3"></path>
                                        <path d="M18 13.3l-6.3 -6.3"></path>
                                    </svg> Reset
                                </button>
                                <button type="button" id="btn-tracking-pengajuan" class="btn btn-primary btn-sm">
                                    <i class="fa fa-search" aria-hidden="true"></i> Search
                                </button>
                                <button type="button" id="btn-export-pengajuan" class="btn btn-success btn-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-download" width="18" height="18" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M4 17v2a2 2 0 0 0 2 2h12a2 2 0 0 0 2 -2v-2"></path>
                                        <path d="M7 11l5 5l5 -5"></path>
                                        <path d="M12 4l0 12"></path>
                                    </svg> Export
                                </button>
                            </div>
                        </div>
                    </form>
                  </div>
                </div>
              </div>
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
                    </div> 
                    
                    <div class="mb-12"> 
                        <table class="table table-striped table-hover w-100 fs-2" id="table-pengajuan">
                            <thead>
                                <tr>
                                    <th class="border-bottom-0 text-center">No</th>
                                    <th class="border-bottom-0">Pengajuan</th>
                                    <th class="border-bottom-0">Perusahaan</th>
                                    <th class="border-bottom-0">Perizinan</th>
                                    <th class="border-bottom-0">Shipment</th>
                                    <th class="border-bottom-0">Status</th>
                                    <th class="border-bottom-0 text-center">Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div> 
                </div> 
            </div> 
        </div> 
    </div> 
</div>  