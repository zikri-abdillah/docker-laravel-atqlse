<!-- Row -->
<div class="row row-sm">
    <div class="col-lg-12 col-md-12">
        <!-- Form Pencarian --> 
            <div class="card mb-5 mt-2">
            <input type="hidden" id="dataFilter" name="dataFilter" value="{{ $dataFilter }}"> 
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Form Pencarian</h5>
                <form id="frm-tracking-kota" method="POST" return>
                    <div class="form-horizontal">
                        <div class="row">
                            <div class="col">
                                <div class="row mb-3">
                                    <label class="col-md-4 form-label">Negara</label>
                                    <div class="col-md-8">
                                        <select class="form-control select2-show-search form-select select-negara" id="s_negara" name="s_negara" data-placeholder="Negara" data-allow-clear="true" style="width: 100%;">
                                            <option label="Negara"></option> 
                                            @if (!empty($data->kodeNegara))
                                                <option value="{{ $data->kodeNegara }}" selected>{{ $data->nama }}</option>
                                            @endif
                                        </select> 
                                    </div>
                                </div> 
                                <div class="row mb-3">
                                    <label class="col-md-4 form-label">Kode UNLOCODE</label>
                                    <div class="col-md-8">
                                        <input class="form-control" id="s_unlocode" name="s_unlocode" type="text" placeholder="Kode UNLOCODE">
                                    </div>
                                </div>  
                            </div> 
                            <div class="col">  
                                <div class="row mb-3">
                                    <label class="col-md-4 form-label">Nama Kota</label>
                                    <div class="col-md-8">
                                        <input class="form-control" id="s_nama" name="s_nama" type="text" placeholder="Nama Kota">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-md-4 form-label">Status</label>
                                    <div class="col-md-8">
                                        <select class="form-control" id="s_status" name="s_status" data-placeholder="Status" data-allow-clear="true" style="width: 100%;">
                                            <option label="Status"></option> 
                                            <option Value="Y">Active</option> 
                                            <option value="N">Non-Active</option> 
                                        </select> 
                                    </div>
                                </div> 
                            </div> 
                        </div>
                        <div class="row">
                            <div class="mt-3 text-center">                                            
                                <button type="reset" id="btn-reset-kota" class="btn btn-danger">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eraser" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M19 20h-10.5l-4.21 -4.3a1 1 0 0 1 0 -1.41l10 -10a1 1 0 0 1 1.41 0l5 5a1 1 0 0 1 0 1.41l-9.2 9.3"></path>
                                        <path d="M18 13.3l-6.3 -6.3"></path>
                                    </svg>
                                    Reset
                                </button>
                                <button type="button" id="btn-tracking-kota" class="btn btn-info"><i class="fa fa-search me-2" aria-hidden="true"></i>Cari</button>  
                                <!-- <button type="button" id="btn-export-kota" class="btn btn-primary me-1 mb-1">Export Excel</button>  -->
                            </div>
                        </div>
                    </div>
                </form> 
            </div>
        </div>  

        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <h5 class="card-title fw-semibold mb-4">Data Kota</h5> 
                    </div>
                    <div class="col-md-6 mb-4" align="right">
                        <a class="btn btn-indigo m-1" onclick="open_modal()">
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
                            <table id="table-kota" class="table table-striped table-hover w-100">
                                <thead>
                                    <tr>  
                                        <th class="border-bottom-0 text-center">NO</th>
                                        <th class="border-bottom-0">Negara</th> 
                                        <th class="border-bottom-0">KODE UNLOCODE</th>
                                        <th class="border-bottom-0">KODE INATRADE</th>
                                        <th class="border-bottom-0">KABUPATEN/KOTA</th>
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
<!--/Row --> 


<!-- modal -->
<div id="modalAddKota" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body">   
                <div class="col-md-12"> 
                    <div class="card">
                        <!-- <div class="card-header">
                            <h5 class="card-title fw-semibold mb-4">Tambah Kota</h5>
                        </div> -->
                        <div class="card-body"> 
                            <h5 class="card-title fw-semibold mb-4">Tambah Kota</h5>

                            <form id="form-kota" name="form-kota" enctype="multipart/form-data" action="javascript:void(0);">  
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <div class="row mb-3">
                                                <label class="col-md-4 form-label">Negara</label>
                                                <div class="col-md-8">
                                                    <select class="form-control select2-show-search form-select select-negara" id="i_negara" name="i_negara" data-placeholder="Negara" data-allow-clear="true" style="width: 100%;">
                                                        <option label="Negara"></option>  
                                                    </select> 
                                                </div>
                                            </div> 
                                            <div class="row mb-3">
                                                <label class="col-md-4 form-label">Kode UNLOCODE</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" id="i_unlocode" name="i_unlocode" type="text" placeholder="Kode UNLOCODE" value="{{ $data->kodeUNLOCODE }}" maxlength="5">
                                                </div>
                                            </div> 
                                            <div class="row mb-3">
                                                <label class="col-md-4 form-label">Kode INATRADE</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" id="i_inatrade" name="i_inatrade" type="text" placeholder="Kode INATRADE" value="{{ $data->kodeInatrade }}">
                                                </div>
                                            </div> 
                                            <div class="row mb-3">
                                                <label class="col-md-15 form-label">    
                                                    <small class="text-danger">Note: tidak boleh ada spasi pada Kode UNLOCODE dan Kode INATRADE</small>                                                         
                                                </label> 
                                            </div> 
                                        </div>

                                        <div class="col">  
                                            <div class="row mb-3">
                                                <label class="col-md-4 form-label">Nama Lengkap</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" id="i_lengkap" name="i_lengkap" type="text" placeholder="Nama Lengkap" value="{{ $data->namaLengkap }}">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label class="col-md-4 form-label">Nama Kota</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" id="i_nama" name="i_nama" type="text" placeholder="Nama Kota" value="{{ $data->namaKota }}">
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <label class="col-md-4 form-label">Status</label>
                                                <div class="col-md-8">
                                                    <select class="form-control select2-show-search form-select" id="i_status" name="i_status" data-placeholder="Status" data-allow-clear="true" style="width: 100%;">
                                                        <option label="Status"></option> 
                                                        <option Value="Y">Active</option> 
                                                        <option value="N">Non-Active</option>  
                                                    </select> 
                                                </div>
                                            </div> 
                                        </div>
                                    </div> 
                                </div> 
                                <div class="row">
                                    <div class="btn-list text-center">
                                        <input type="hidden" id="idKota" name="idKota" readonly> 
                                        <button type="button" class="btn d-w-md btn-dark btn-sm" data-bs-dismiss="modal" > 
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-x" width="15" height="15" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M18 6l-12 12"></path>
                                                <path d="M6 6l12 12"></path>
                                            </svg>
                                            Batal
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
                                        <button type="button" class="btn d-w-md btn-info btn-sm" id="btn-simpan-kota"><i class="fa fa-save me-2" aria-hidden="true"></i>Simpan</button>  
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