<!-- Row -->
<div class="row row-sm">
    <div class="col-lg-12 col-md-12">
        <!-- Form Pencarian --> 
        <div class="card mb-5 mt-2">  
            <input type="hidden" id="dataFilter" name="dataFilter" value="{{ $dataFilter }}"> 
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Form Pencarian</h5>
                <form id="frm-tracking-user" method="POST" return>
                    <div class="form-horizontal">
                        <div class="row">
                            <div class="col">
                                <div class="row mb-3">
                                    <label class="col-md-4 form-label">User Role</label>
                                    <div class="col-md-8"> 
                                        <select class="form-control select2-show-search form-select select-role" id="s_role" name="s_role" data-placeholder="-- Pilih --" data-allow-clear="true" style="width: 100%;">
                                            <option label="-- Pilih --"></option> 
                                        </select>
                                    </div>
                                </div> 
                            </div> 
                            <div class="col"> 
                                <div class="row mb-3">
                                    <label class="col-md-4 form-label">User Type</label>
                                    <div class="col-md-8">
                                        <select class="form-control select2-show-search form-select select-type" id="s_type" name="s_type" data-placeholder="-- Pilih --" data-allow-clear="true" style="width: 100%;">
                                            <option label="-- Pilih --"></option> 
                                        </select>
                                    </div>
                                </div>
                            </div> 
                        </div>
                        <div class="row">
                            <div class="mt-3 text-center">                      
                                <button type="reset" id="btn-reset-user" class="btn btn-danger">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eraser" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M19 20h-10.5l-4.21 -4.3a1 1 0 0 1 0 -1.41l10 -10a1 1 0 0 1 1.41 0l5 5a1 1 0 0 1 0 1.41l-9.2 9.3"></path>
                                        <path d="M18 13.3l-6.3 -6.3"></path>
                                    </svg>
                                    Reset
                                </button>
                                <button type="button" id="btn-tracking-user" class="btn btn-info"><i class="fa fa-search me-2" aria-hidden="true"></i>Cari</button>                                  
                                <!-- <button type="button" id="btn-export-user" class="btn btn-primary me-1 mb-1">Export Excel</button>  -->
                            </div>
                        </div>
                    </div>
                </form> 
            </div>
        </div> 
        <!-- End Form Pencarian -->

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <h5 class="card-title fw-semibold mb-4">Data User</h5> 
                    </div>
                    <div class="col-md-6 mb-4" align="right">
                        <a class="btn  btn-indigo m-1" href="<?= base_url() ?>/management/user/add">
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
                    
                    <div class="mb-12 mb-4">
                    
                        <div class="table-responsive">
                            <table id="table-user" class="table table-striped table-hover w-100">
                                <thead>
                                    <tr>
                                        <th class="border-bottom-0 text-center">NO</th>
                                        <th class="border-bottom-0">NAMA LENGKAP</th>  
                                        <th class="border-bottom-0">USERNAME</th>  
                                        <th class="border-bottom-0">USER ROLE</th>
                                        <th class="border-bottom-0">USER TYPE</th> 
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