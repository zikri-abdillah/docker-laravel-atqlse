  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
    <div class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center">
        <div class="row justify-content-center">
          <div class="col-md-12">
            <div class="card mb-0">
              <div class="card-body">
                <a href="index.html" class="text-nowrap text-center d-block py-3">
                  <img src="<?= base_url() ?>assets/images/logos/atq-logo.png" width="180" alt="">
                </a> 

                <form action="javascript:void(0)" id="registrastion-form" class="registrastion-form">
					<div class="card-header border-bottom">
						<h5 class="mb-0">Informasi Perusahaan</h5>
					</div>  
		      	    <div class="card-body"> 
 						<div class="row">
							<div class="col">
								<div class="row mb-3">
									<label class="col-md-4 form-label">Bentuk Perusahaan</label>
									<div class="col-md-8">  
										<select class="form-control select2-show-search form-select" id="i_bentukPersh" name="i_bentukPersh" data-placeholder="Bentuk Perusahaan" style="width: 100%;">
											@php
												$CV = $PT = '';
												if(isset($arrPerusahaan)){
													if($arrPerusahaan->bentukPersh == 'CV')
														$CV = 'selected';
													else if($arrPerusahaan->bentukPersh == 'PT')
														$PT = 'selected'; 
												}
											@endphp
											<option value="">Bentuk Perusahaan</option>
											<option value="CV" {{ $CV }}>CV</option>
											<option value="PT" {{ $PT }}>PT</option> 
										</select>
									</div>
								</div>
								<div class="row mb-3">
									<label class="col-md-4 form-label">Nama Perusahaan</label>
									<div class="col-md-8"> 
										<input class="form-control" id="i_nama" name="i_nama" type="text" placeholder="Nama Perusahaan"> 
									</div>
								</div>
								<div class="row mb-3">
									<label class="col-md-4 form-label">NPWP 15 Digit</label>
									<div class="col-md-8">
										<input class="form-control mask-npwp" id="i_npwp" name="i_npwp" type="text" placeholder="NPWP 15 Digit">
									</div>
								</div>
								<div class="row mb-3">
									<label class="col-md-4 form-label">File NPWP</label>
									<div class="col-md-8">
 										<input class="form-control file-input" type="file" id="i_fileNPWP" name="i_fileNPWP"> 
									</div>
								</div> 
								<div class="row mb-3">
									<label class="col-md-4 form-label">NPWP 16 Digit</label>
									<div class="col-md-8">
										<input class="form-control mask-npwp-16" id="i_npwp16" name="i_npwp16" type="text" placeholder="NPWP 16 Digit">
									</div>
								</div>
								<div class="row mb-3">
									<label class="col-md-4 form-label">NIB</label>
									<div class="col-md-8">
										<input class="form-control" id="i_nib" name="i_nib" type="text" placeholder="NIB">
									</div>
								</div>
								<div class="row mb-3">
									<label class="col-md-4 form-label">File NPWP</label>
									<div class="col-md-8">
 										<input class="form-control file-input" type="file" id="i_fileNIB" name="i_fileNIB"> 
									</div>
								</div> 
								<div class="row mb-3">
									<label class="col-md-4 form-label">No & Tgl IUP</label>
									<div class="col-md-4">
										<input class="form-control" id="i_noIUP" name="i_noIUP" type="text" placeholder="Nomor IUP">
									</div>
									<div class="col-md-4">
										<input class="form-control bs-datepicker" id="i_tglIUP" name="i_tglIUP" type="text" placeholder="Tanggal IUP">
									</div>
								</div>
								<div class="row mb-3">
									<label class="col-md-4 form-label">NITKU</label>
									<div class="col-md-8">
										<input class="form-control" id="i_nitku" name="i_nitku" type="text" placeholder="NITKU">
									</div>
								</div>
							</div>
							
							<div class="col">
								<div class="row mb-3">
									<label class="col-md-4 form-label">Alamat Perusahaan</label>
									<div class="col-md-8"> 
										<textarea class="form-control" id="i_alamat" name="i_alamat" rows="3" placeholder="Alamat Client"></textarea>
									</div>
								</div>
								<div class="row mb-3">
									<label class="col-md-4 form-label">Kab/Kota</label>
									<div class="col-md-8"> 
										<select class="form-control select2-show-search form-select select-kota" id="i_kodeKab" name="i_kodeKab" data-placeholder="-- Pilih --" data-allow-clear="true" style="width: 100%;">
											<option label="-- Pilih --"></option> 
										</select>
									</div>
								</div>
								<div class="row mb-3">
									<label class="col-md-4 form-label">Provinsi</label>
									<div class="col-md-8">
										<select class="form-control select2-show-search form-select select-propinsi" id="i_kodeProp" name="i_kodeProp" data-placeholder="-- Pilih --" data-allow-clear="true" style="width: 100%;">
											<option label="-- Pilih --"></option> 
										</select>
									</div>
								</div>
								<div class="row mb-3">
									<label class="col-md-4 form-label">Kode Pos</label>
									<div class="col-md-8">
										<input class="form-control" id="i_kodePos" name="i_kodePos" type="text" placeholder="Kode Pos">
									</div>
								</div>
								<div class="row mb-3">
									<label class="col-md-4 form-label">Jenis IUP</label>
									<div class="col-md-8">
										<select class="form-control select2-show-search form-select select-jenis-iup" id="i_idJenisIup" name="i_idJenisIup" data-placeholder="-- Pilih --" data-allow-clear="true" style="width: 100%;">
											<option label="-- Pilih --"></option> 
										</select>
									</div>
								</div>
								<div class="col">
									<div class="row mb-3">
										<label class="col-md-4 form-label">e-mail Perusahaan</label>
										<div class="col-md-8">
											<input class="form-control" id="i_email" name="i_email" type="text" placeholder="e-mail Perusahaan">
										</div>
									</div>
									
									<div class="row mb-3">
										<label class="col-md-4 form-label">Telephone Perusahaan</label>
										<div class="col-md-8">
											<input class="form-control" id="i_telp" name="i_telp" type="text" placeholder="Telephone Perusahaan">
										</div>
									</div>
								</div>
								<div class="col"> 
									<div class="row mb-3">
										<label class="col-md-4 form-label">Fax Perusahaan</label>
										<div class="col-md-8">
											<input class="form-control" id="i_fax" name="i_fax" type="text" placeholder="Fax Perusahaan">
										</div>
									</div>
								</div>
							</div>
						</div> 
					</div>
  
					<div class="card-header border-bottom">
						<h5 class="mb-0">Informasi PIC</h5>
					</div>   
					<div class="card-body">
						<div class="row">
							<div class="col">
								<div class="row mb-3">
									<label class="col-md-4 form-label">Nama PIC</label>
									<div class="col-md-8">
										<input class="form-control" id="i_picNama" name="i_picNama" type="text" placeholder="Nama PIC">
									</div>
								</div> 
								<div class="row mb-3">
									<label class="col-md-4 form-label">Nomor KTP</label>
									<div class="col-md-8"> 
 										<input class="form-control" id="i_picNoKtp" name="i_picNoKtp" type="text" placeholder="Nomor KTP" required="">
									</div>
								</div> 
								<div class="row mb-3">
									<label class="col-md-4 form-label">File KTP</label>
									<div class="col-md-8"> 
										<input class="form-control file-input" type="file" id="i_fileKTP" name="i_fileKTP">
										<span id="span-file-ls"> 
											<small>*KTP atas nama kuasa perusahaan yang bertanggung jawab pengurusan Verifikasi / Penelusuran Teknis Ekspor Produk Pertambangan</small>
										</span>
									</div>
								</div>
							</div>
							<div class="col">  
								<div class="row mb-3">
									<label class="col-md-4 form-label">Jabatan PIC</label>
									<div class="col-md-8">
										<input class="form-control" id="i_picJabatan" name="i_picJabatan" type="text" placeholder="Jabatan PIC">
									</div>
								</div>
								<div class="row mb-3">
									<label class="col-md-4 form-label">Telephone PIC</label>
									<div class="col-md-8">
										<input class="form-control" id="i_picTelp" name="i_picTelp" type="text" placeholder="Telephone PIC">
									</div>
								</div>
								<div class="row mb-3">
									<label class="col-md-4 form-label">e-mail PIC</label>
									<div class="col-md-8">
										<input class="form-control" id="i_picEmail" name="i_picEmail" type="text" placeholder="e-mail PIC">
									</div>
								</div>
							</div>
						</div> 
					</div>
					 
					<div class="card-body">
						<div class="row">
							<div class="col-md-4" align="right"> 
								<button type="button" class="btn btn-sm btn-danger mt-1" onclick="getCaptcha()" title="Ganti Captcha"><i class="fa fa-refresh"></i></button>
							</div> 
							<div class="col-md-2" id="captcha-image">  
							</div>  
							<div class="col-md-3 ml-2"> 
								<input id="ucaptcha" name="ucaptcha" type="text" class="form-control" placeholder="Captcha" required autocomplete="off">
							</div>   
							<div class="col-md-3">  
							</div>  
						</div> 
						<div class="row">
							<div class="mb-4">
								<div class="w-100 text-md-center" style="background-color: #00000024;">
								<small class="login-msg text-danger"></small>
								</div>
							</div>
						</div> 
					</div>
                </form> 
					
				<div class="row"> 
					<div class="btn-list text-center">
						<button type="button" class="btn d-w-md btn-dark btn-sm" onclick="back_client()">
							<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-back-up-double" width="15" height="15" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
								<path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
								<path d="M13 14l-4 -4l4 -4"></path>
								<path d="M8 14l-4 -4l4 -4"></path>
								<path d="M9 10h7a4 4 0 1 1 0 8h-1"></path>
							</svg>
							Kembali
						</button>
						&nbsp;
						<button type="reset" class="btn d-w-md btn-danger btn-sm" id="btn-reset-profile">
							<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eraser" width="15" height="15" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
								<path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
								<path d="M19 20h-10.5l-4.21 -4.3a1 1 0 0 1 0 -1.41l10 -10a1 1 0 0 1 1.41 0l5 5a1 1 0 0 1 0 1.41l-9.2 9.3"></path>
								<path d="M18 13.3l-6.3 -6.3"></path>
							</svg>
							Reset
						</button>
						&nbsp;
						<button type="button" class="btn d-w-md btn-info btn-sm" id="btn-registrasi"><i class="fa fa-save me-2" aria-hidden="true"></i>Registrasi</button> 
					</div>
				</div> 
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>  