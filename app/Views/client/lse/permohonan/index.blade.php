<div class="app-content main-content mt-0">
    <div class="side-app">
        <!-- CONTAINER -->
        <div class="main-container container-fluid">

            <!-- PAGE-HEADER -->
            <div class="page-header">
                <div class="ms-auto pageheader-btn">
                    <ol class="breadcrumb"> 
                        <li class="breadcrumb-item"><a href="javascript:void(0);">LS Ekspor</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $breadcrumb_active }}</li>
                    </ol>
                </div>
            </div>
            <!-- PAGE-HEADER END -->

            <!-- Row -->
            <div class="row row-sm">
            	<div class="col-lg-12 col-md-12">
            		<!-- Form Pencarian -->
					<div aria-multiselectable="true" class="accordion-info mb-2" id="accordion3" role="tablist">
						<div class="card mb-5 mt-2">
							<div class="card-header border-bottom-0" id="heading3" role="tab">
								<a class="accor-style2 collapsed" aria-controls="collapse3" aria-expanded="false" data-bs-toggle="collapse" href="#collapse3"><i class="fe fe-plus-circle me-2"></i>Form Pencarian</a>
							</div>
							<div aria-labelledby="heading3" class="collapse" data-bs-parent="#accordion3" id="collapse3" role="tabpanel">
								<div class="card-body">
									<div class="form-horizontal">
                                        <form id="frm-tracking-pengajuan" method="POST" return>
                                            <div class="row">
                                                <div class="col">
                                                    <div class="row">
                                                        <label class="col-md-4 form-label">Jenis Pengajuan</label>
                                                        <div class="form-group col-md-8">
                                                            <select class="form-control select2-show-search form-select" id="i_idJenisTerbit" name="i_idJenisTerbit" data-placeholder="-- Pilih --" style="width: 100%;">
                                                                <option value="">Semua Jenis</option>
                                                                <option value="1">Baru</option>
                                                                <option value="2">Perubahan</option>
                                                                <option value="4">Perpanjangan</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label class="col-md-4 form-label">Nomor Aju</label>
                                                        <div class="col-md-8">
                                                            <input class="form-control" id="i_nomorAju" name="i_nomorAju" type="text" placeholder="Nomor Aju">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label class="col-md-4 form-label">Nomor Permohonan</label>
                                                        <div class="col-md-8">
                                                            <input class="form-control" id="i_nomorPermohonan" name="i_nomorPermohonan" type="text" placeholder="Nomor Permohonan">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col"> 
                                                    <div class="row mb-3">
                                                        <label class="col-md-4 form-label">Nomor ET</label>
                                                        <div class="col">
                                                            <input class="form-control" id="i_nomorEt" name="i_nomorEt" type="text" placeholder="Nomor ET">
                                                        </div>
                                                    </div>
                                                    <div class="row mb-3">
                                                        <label class="col-md-4 form-label">Nama Alat Transport</label>
                                                        <div class="form-group col-md-8 mb-0">
                                                            <input class="form-control" id="i_namaAlatPengirim" name="i_namaAlatPengirim" type="text" placeholder="Nama Alat Transport">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="mt-3 text-center">           
                                                    <button type="reset" id="btn-reset-pengajuan" class="btn btn-danger">
                                                        <i class="fa fa-eraser me-2" aria-hidden="true"></i>Reset
                                                    </button>
                                                    <button type="button" id="btn-tracking-pengajuan" class="btn btn-info">
                                                        <i class="fa fa-search me-2" aria-hidden="true"></i>Cari
                                                    </button>  
                                                    <button type="button" id="btn-export-pengajuan" class="btn btn-success">
                                                        <i class="fa fa-file-excel-o me-2" aria-hidden="true"></i>Export Excel
                                                    </button> 
                                                </div>
                                            </div>
                                        </form>
                                    </div>
								</div>
							</div><!-- collapse -->
						</div>
					</div>
					<!-- End Form Pencarian -->

					<div class="card">
						<div class="card-body">
							<h4>{{ $table_title }}</h4>  
							<div class="table-responsive mt-4">
                                <table id="table-pengajuan" class="table table-bordered border-bottom w-100">
                                    <thead>
                                        <tr>
                                            <th class="border-bottom-0 text-center">NO</th>
                                            <th class="border-bottom-0">PENGAJUAN</th>
                                            <th class="border-bottom-0">PERUSAHAAN</th>
                                            <th class="border-bottom-0">PERIZINAN</th>
                                            <th class="border-bottom-0">SHIPMENT</th>
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
            <!--/Row -->

        </div>

    </div>
    <!-- CONTAINER CLOSED -->
</div>
