<!-- Row -->
<div class="row row-sm">
    <div class="col-lg-12 col-md-12">
        <!-- Form Pencarian --> 
            <div class="card mb-5 mt-2">
            <input type="hidden" id="dataFilter" name="dataFilter" value="{{ $dataFilter }}"> 
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Form Pencarian</h5>
                <form id="frm-tracking-npwp" method="POST" return>
                    <div class="form-horizontal">
                        <div class="row">
                            <div class="col">
                                <div class="row mb-3">
                                    <label class="col-md-4 form-label">Nama Perusahaan</label>
                                    <div class="col-md-8">
                                        <input class="form-control" id="s_nama" name="s_nama" type="text">
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <div class="row mb-3">
                                    <label class="col-md-4 form-label">NPWP 15 DIGIT</label>
                                    <div class="col-md-8">
                                        <input class="form-control mask-npwp" id="s_npwp15" name="s_npwp15" type="text">
                                    </div>
                                </div> 
                            </div> 
                            <div class="col"> 
                                <div class="row mb-3">
                                    <label class="col-md-4 form-label">NPWP 16 DIGIT</label>
                                    <div class="col-md-8">
                                        <input class="form-control mask-npwp-16" id="s_npwp16" name="s_npwp16" type="text">
                                    </div>
                                </div>
                            </div> 
                        </div>
                        <div class="row">
                            <div class="mt-3 text-center">                                            
                                <button type="reset" id="btn-reset-npwp" class="btn btn-danger">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eraser" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M19 20h-10.5l-4.21 -4.3a1 1 0 0 1 0 -1.41l10 -10a1 1 0 0 1 1.41 0l5 5a1 1 0 0 1 0 1.41l-9.2 9.3"></path>
                                        <path d="M18 13.3l-6.3 -6.3"></path>
                                    </svg>
                                    Reset
                                </button>
                                <button type="button" id="btn-tracking-npwp" class="btn btn-info"><i class="fa fa-search me-2" aria-hidden="true"></i>Cari</button>
                                <!-- <button type="button" id="btn-export-cabang" class="btn btn-primary me-1 mb-1">Export Excel</button>  -->
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
                        <h5 class="card-title fw-semibold mb-4">Data Npwp</h5>
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
                            <table id="table-npwp" class="table table-striped table-hover w-100">
                                <thead>
                                    <tr>
                                        <th class="border-bottom-0 text-center">NO</th>
                                        <th class="border-bottom-0">NAMA PERUSAHAAN</th>
                                        <th class="border-bottom-0">NPWP 15 DIGIT</th>
                                        <th class="border-bottom-0">NPWP 16 DIGIT</th>
                                        <!-- <th class="border-bottom-0"></th>  -->
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
<div id="modalAddNpwp" class="modal fade" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-body">   
                <div class="col-md-12"> 
                    <div class="card">
                        <!-- <div class="card-header">
                            <h5 class="card-title fw-semibold mb-4">Tambah Cabang</h5>
                        </div> -->
                        <div class="card-body"> 
                            <h5 class="card-title fw-semibold mb-4">Tambah NPWP</h5>

                            <form id="form-npwp" name="form-npwp" enctype="multipart/form-data" action="javascript:void(0);">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col">
                                            <div class="row mb-4">
                                                <label class="col-md-4 form-label">Nama Perusahaan</label>
                                                <div class="col-md-8">
                                                    <input class="form-control" id="i_nama" name="i_nama" >
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col">
                                            <div class="row mb-4">
                                                <label class="col-md-4 form-label">NPWP 15 Digit</label>
                                                <div class="col-md-8">
                                                    <input class="form-control mask-npwp" id="i_npwp15" name="i_npwp15" >
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col">  
                                            <div class="row mb-4">
                                                <label class="col-md-4 form-label">NPWP 16 Digit</label>
                                                <div class="col-md-8">
                                                    <input class="form-control mask-npwp-16" id="i_npwp16" name="i_npwp16" >
                                                </div>
                                            </div>
                                        </div>
                                    </div> 
                                </div> 
                                <div class="row">
                                    <div class="btn-list text-center">
                                        <input type="text" class="d-none" id="idNpwp" name="idNpwp" readonly>
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
                                        <button type="button" class="btn d-w-md btn-info btn-sm" id="btn-simpan-npwp"><i class="fa fa-save me-2" aria-hidden="true"></i>Simpan</button>
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