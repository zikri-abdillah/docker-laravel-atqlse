<!-- Row -->
<div class="row row-sm">
    <div class="col-lg-12 col-md-12">
        <!-- Form Pencarian -->
        <div aria-multiselectable="true" class="accordion-search mb-2" id="accordion3" role="tablist">
            <div class="card mb-5 mt-2">
                <div class="card-header border-bottom-0" id="heading3" role="tab">
                    <a class="accor-style2 collapsed" aria-controls="collapse3" aria-expanded="false" data-bs-toggle="collapse" href="#collapse3"><i class="fe fe-plus-circle me-2"></i>Form Pencarian</a>
                </div>
                <div aria-labelledby="heading3" class="collapse" data-bs-parent="#accordion3" id="collapse3" role="tabpanel">
                    <div class="card-body">
                        <form id="frm-tracking-client" method="POST" return>
                            <div class="row">
                                <div class="col">
                                    <div class="row mb-3">
                                        <label class="col-md-4 form-label">NPWP</label>
                                        <div class="col-md-8">
                                            <input class="form-control mask-npwp" id="s_npwp" name="s_npwp" type="text" placeholder="NPWP"> 
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-md-4 form-label">Nama Client</label>
                                        <div class="col-md-8">
                                            <input class="form-control" id="s_nama" name="s_nama" type="text" placeholder="Nama Client">
                                        </div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="row mb-3">
                                        <label class="col-md-4 form-label">NIB</label>
                                        <div class="col">
                                            <input class="form-control" id="i_nib" name="i_nib" type="text" placeholder="NIB">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-md-4 form-label">Jenis IUP</label>
                                        <div class="col">
                                            <select class="form-control select2-show-search form-select select-jenis-iup" id="s_idJenisIup" name="s_idJenisIup" data-placeholder="-- Pilih --" data-allow-clear="true" style="width: 100%;">
                                                <option label="-- Pilih --"></option> 
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="mt-3 text-center">
                                    <button type="reset" id="btn-reset-client" class="btn btn-danger">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eraser" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M19 20h-10.5l-4.21 -4.3a1 1 0 0 1 0 -1.41l10 -10a1 1 0 0 1 1.41 0l5 5a1 1 0 0 1 0 1.41l-9.2 9.3"></path>
                                            <path d="M18 13.3l-6.3 -6.3"></path>
                                        </svg>
                                        Reset
                                    </button>
                                    <button type="button" id="btn-tracking-client" class="btn btn-info"><i class="fa fa-search me-2" aria-hidden="true"></i>Cari</button>    
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
                    <div class="col-md-6 mb-3">
                        <h5 class="card-title fw-semibold mb-3">Data Client</h5> 
                    </div>
                    <div class="col-md-6" align="right">
                        <a class="btn btn-indigo m-1" href="<?= base_url() ?>management/client/add">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-pencil-plus" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4"></path>
                                <path d="M13.5 6.5l4 4"></path>
                                <path d="M16 19h6"></path>
                                <path d="M19 16v6"></path>
                            </svg> 
                            Tambah Data
                        </a> 
                    </div> 
                    
                    <div class="mb-12"> 
                        <div class="table-responsive">
                            <table class="table table-striped table-hover w-100" id="table-client">
                                <thead>
                                    <tr>
                                        <th class="border-bottom-0 text-center">NO</th>
                                        <th class="border-bottom-0">NAMA PERUSAHAAN</th>
                                        <th class="border-bottom-0">PERIZINAN</th>
                                        <th class="border-bottom-0">KONTAK</th>
                                        <th class="border-bottom-0">JENIS IUP</th>
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