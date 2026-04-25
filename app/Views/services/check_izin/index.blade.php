<div class="row row-sm">
    <div class="col-lg-12 col-md-12">
        <!-- Form Pencarian --> 
        <div class="card mb-5 mt-2"> 
            <div class="card-body"> 
                <h5 class="card-title fw-semibold mb-4">Form Validasi Izin</h5>
                <form return>
					<div class="row">
						<div class="col-lg-6 col-md-6">
							<div class="row mb-4">
								<label for="firstName" class="col-md-3 form-label">NIB</label>
								<div class="col-md-9"> <input class="form-control" id="nib" name="nib" placeholder="Nomor Induk Berusaha" type="text"> </div>
							</div>
							<div class="row mb-4">
								<label for="lastName" class="col-md-3 form-label">NPWP 15 Digit</label>
								<div class="col-md-9"> <input class="form-control mask-npwp" id="npwp" name="npwp" placeholder="Nomor Pokok Wajib Pajak 15 Digit" type="text"> </div>
							</div>
							<div class="row mb-4">
								<label for="lastName" class="col-md-3 form-label">NPWP 16 Digit</label>
								<div class="col-md-9"> <input class="form-control mask-npwp-16" id="npwp16" name="npwp16" placeholder="Nomor Pokok Wajib Pajak 16 Digit" type="text"> </div>
							</div>
							<div class="row mb-4">
								<label for="email" class="col-md-3 form-label">IDTKU</label>
								<div class="col-md-9"> <input class="form-control" id="idtku" name="idtku" placeholder="IDTKU" type="text"> </div>
							</div>
						</div>

						<div class="col-lg-6 col-md-6">
							<div class="row mb-4">
								<label for="email" class="col-md-3 form-label">Jenis Perizinan</label>
								<div class="col-md-9">
									<select class="form-control select2-show-search form-select" id="probis" name="probis" data-placeholder="-- Pilih --" style="width: 100%;">
										<option value="">-- Pilih --</option>
										<option value="E">Ekspor</option>
										<option value="I">Impor</option>
									</select>
								</div>
							</div>
							<div class="row mb-4">
								<label for="firstName" class="col-md-3 form-label">Nomor Izin</label>
								<div class="col-md-9">
									<input class="form-control" id="noIzin" name="noIzin" placeholder="Nomor Izin Dari Inatrade" type="text">
								</div>
							</div>
							<div class="row mb-5">
								<label for="lastName" class="col-md-3 form-label">Tanggal Izin</label>
								<div class="col-md-9"> <input class="form-control bs-datepicker" id="tglIzin" placeholder="Tanggal Izin" type="text"> </div>
							</div>
						</div>
					</div>	  
					<div class="row">
						<div class="mt-3 text-center">
							<button type="reset" id="btn-reset-izin" class="btn btn-danger">
								<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eraser" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
									<path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
									<path d="M19 20h-10.5l-4.21 -4.3a1 1 0 0 1 0 -1.41l10 -10a1 1 0 0 1 1.41 0l5 5a1 1 0 0 1 0 1.41l-9.2 9.3"></path>
									<path d="M18 13.3l-6.3 -6.3"></path>
								</svg>
								Reset
							</button>
							<button type="button" class="btn btn-info" onclick="act()">
								<i class="fa fa-search me-1" aria-hidden="true"></i>Cek
							</button>    
						</div>
					</div> 
                </form> 
            </div>
        </div> 
        <!-- End Form Pencarian -->
		
        <div class="card"> 
            <div class="card-body"> 
				<ul class="nav tabs-menu-form" role="tablist">
                    <li class="nav-item waves-effect waves-light">
                        <a class="nav-link active" data-bs-toggle="tab" href="#tabHeader" role="tab">Header</a>
                    </li>
                    <li class="nav-item waves-effect waves-light">
                        <a class="nav-link" data-bs-toggle="tab" href="#tabKepatuhan" role="tab">Kepatuhan</a>
                    </li>
                    <li class="nav-item waves-effect waves-light">
                        <a class="nav-link" data-bs-toggle="tab" href="#tabKomoditas" role="tab">Komoditas</a>
                    </li> 
                </ul>

				<div class="tab-content">
					<div class="tab-pane p-1 active" id="tabHeader" role="tabpanel">  
						<table id="table-header-izin" class="table table-striped table-hover w-100" style="width:100%"> 
							<thead>
								<tr> 
									<th class="border-bottom-0">Nib</th>
									<th class="border-bottom-0">NPWP</th>
									<th class="border-bottom-0">NITKU</th>
									<th class="border-bottom-0">Nama Perusahaan</th>
									<th class="border-bottom-0">Nomor Izin</th>
									<th class="border-bottom-0">Tanggal Awal Izin</th> 
									<th class="border-bottom-0">Tanggal Akhir Izin</th> 
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table> 
					</div>
					<div class="tab-pane p-1" id="tabKepatuhan" role="tabpanel">  
						<table id="table-kepatuhan-izin" class="table table-striped table-hover w-100" style="width:100%"> 
							<thead>
								<tr> 
									<th class="border-bottom-0">Kode</th>
									<th class="border-bottom-0">Keterangan</th> 
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table> 
					</div>
					<div class="tab-pane p-1" id="tabKomoditas" role="tabpanel">  
						<table id="table-komoditas-izin" class="table table-striped table-hover w-100" style="width:100%"> 
							<thead>
								<tr> 
									<th class="border-bottom-0">Seri</th>
									<th class="border-bottom-0" width="25%">HS/Pos Tarif</th> 
									<th class="border-bottom-0" width="25%">Spesifikasi</th>
									<th class="border-bottom-0">Kuota</th>  
									<!-- <th class="border-bottom-0">Satuan</th>  -->
									<th class="border-bottom-0">Pelabuhan</th>   
									<th class="border-bottom-0">Negara</th> 
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
