  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <div
      class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100"> 
          <div class="col-md-8 col-lg-6 col-xxl-8">
            <div class="card mb-0">
                <a href="index.html" class="text-nowrap text-center d-block py-3 w-100">
                  <img src="<?= base_url() ?>assets/images/logos/atq-logo.PNG" width="180" alt=""> 
                </a> 

                <form action="javascript:void(0)" id="form-registrasi" class="form-registrasi">  
					<div class="card-header border-bottom">
						<h4 class="mb-0">INFORMASI PERUSAHAAN</h4>
					</div>  
              		<div class="card-body">
						<div class="row" class="col-xl-8">  
							<div class="col-lg-5 m-6">
								<div class="mb-3">
									<label for="uname" class="form-label">Bentuk Perusahaan</label> 
									<input class="form-control" id="i_bentukPersh" name="i_bentukPersh" type="text" placeholder="Contoh: PT / CV / FIRMA dll"required autocomplete="off"> 		
								</div> 
								<div class="mb-3">
									<label for="upass" class="form-label">Nama Perusahaan</label>								
									<input class="form-control" id="i_nama" name="i_nama" type="text" placeholder="Nama Perusahaan" required autocomplete="off"> 
								</div> 
								<div class="mb-3">
									<label for="uname" class="form-label">NPWP</label>								
									<input class="form-control mask-npwp" id="i_npwp" name="i_npwp" type="text" placeholder="NPWP" required autocomplete="off"> 
								</div> 
								<div class="mb-3">
									<label for="upass" class="form-label">NIB</label>								
									<input class="form-control" id="i_nib" name="i_nib" type="text" placeholder="NIB" required autocomplete="off"> 
								</div> 
								<div class="mb-3">
									<label for="uname" class="form-label">Jenis IUP</label> 
									<select class="form-control select2-show-search form-select select-jenis-iup" id="i_idJenisIup" name="i_idJenisIup" data-placeholder="Jenis IUP" data-allow-clear="true" style="width: 100%;">
										<option label="Jenis IUP"></option>
									</select>							
								</div> 
								<div class="mb-3">
									<label for="upass" class="form-label">Nomor IUP</label>								
									<input class="form-control" id="i_noIUP" name="i_noIUP" type="text" placeholder="Nomor IUP" required autocomplete="off"> 
								</div> 
								<div class="mb-3">
									<label for="upass" class="form-label">Tanngal IUP</label>									
									<input class="form-control bs-datepicker" id="i_tglIUP" name="i_tglIUP" type="text" placeholder="Nomor IUP" required autocomplete="off"> 
								</div> 
								<div>
									<label for="upass" class="form-label">NITKU</label>									
									<input class="form-control" id="i_nitku" name="i_nitku" type="text" placeholder="NITKU" required autocomplete="off"> 
								</div> 
							</div>
		
							<div class="col-lg-5 m-6">
								<div class="mb-3">
									<label for="uname" class="form-label">Alamat</label>
									<textarea class="form-control" id="i_alamat" name="i_alamat" rows="3" placeholder="Alamat Perusahaan"required autocomplete="off"></textarea>
								</div> 
								<div class="mb-3">
									<label for="upass" class="form-label">Propinsi</label> 
									<select class="form-control select2-show-search form-select select-propinsi" id="i_kodeProp" name="i_kodeProp" data-placeholder="Propinsi" data-allow-clear="true" style="width: 100%;">
										<option label="Propinsi"></option> 
									</select>							
								</div> 
								<div class="mb-3">
									<label for="upass" class="form-label">Kab/Kota</label>									
									<select class="form-control select2-show-search form-select select-kota" id="i_kodeKab" name="i_kodeKab" data-placeholder="Kab/Kota" data-allow-clear="true" style="width: 100%;">
										<option label="Kab/Kota"></option> 
									</select>
								</div> 
								<div class="mb-3">
									<label for="upass" class="form-label">Kode Pos</label>										
									<input class="form-control" id="i_kodePos" name="i_kodePos" type="text" placeholder="Kode Pos" required autocomplete="off"> 
								</div> 
								<div class="mb-3">
									<label for="upass" class="form-label">e-mail Perusahaan</label>									
									<input class="form-control" id="i_email" name="i_email" type="text" placeholder="e-mail" required autocomplete="off"> 
								</div>  
								<div class="mb-3">
									<label for="upass" class="form-label">Telephone</label>										
									<input class="form-control" id="i_telp" name="i_telp" type="text" placeholder="Telephone" required autocomplete="off"> 
								</div> 
								<div>
									<label for="upass" class="form-label">Fax</label>											
									<input class="form-control" id="i_fax" name="i_fax" type="text" placeholder="Fax" required autocomplete="off"> 
								</div> 
							</div>
						</div> 
            		</div> 

              		<div class="card-body">
						<div class="row" class="col-xl-8">  
							<div class="col-lg-5 m-6">
								<div class="card-header border-bottom">
									<h4 class="mb-0">INFORMASI PIC</h4>
								</div>   
								<div class="mb-3 mt-3">
									<label for="upass" class="form-label">Nama PIC</label>				
									<input class="form-control" id="i_picNama" name="i_picNama" type="text" placeholder="Nama PIC" required autocomplete="off"> 
								</div> 
								<div class="mb-3">
									<label for="uname" class="form-label">Jabatan PIC</label>										
									<input class="form-control" id="i_picJabatan" name="i_picJabatan" type="text" placeholder="Jabatan PIC" required autocomplete="off"> 
								</div> 
								<div class="mb-3">
									<label for="upass" class="form-label">Telephone PIC</label>									
									<input class="form-control" id="i_picTelp" name="i_picTelp" type="text" placeholder="Telephone PIC" required autocomplete="off"> 
								</div>  
								<div>
									<label for="upass" class="form-label">e-mail PIC</label>										
									<input class="form-control" id="i_picEmail" name="i_picEmail" type="text" placeholder="e-mail PIC" required autocomplete="off"> 
								</div>  
							</div>
		
							<div class="col-lg-5 m-6">
								<div class="card-header border-bottom">
									<h4 class="mb-0">USERNAME / PASSWORD</h4>
								</div>  
								<div class="mb-3 mt-3">
									<label for="uname" class="form-label">Username</label>								
									<input class="form-control" id="i_username" name="i_username" placeholder="Username" required autocomplete="off"> 
								</div>

								<div class="mb-3">
									<label for="upass" class="form-label">Password</label>								
									<input class="form-control" type="password" id="i_password" name="i_password" placeholder="Password" required autocomplete="off"> 
								</div>

								<div class="row form-group text-center mb-3">
									<div class="col-sm-8" id="captcha-image">
									</div>
									<div class="col-sm-1">
										<button type="button" class="btn btn-sm btn-success mt-1" onclick="getCaptcha()" title="Ganti Captcha"><i class="fa fa-refresh"></i></button>
									</div>
								</div>  
								<div>
									<label for="ucaptcha" class="form-label">Captcha</label>
									<input id="ucaptcha" name="ucaptcha" type="text" class="form-control" placeholder="Captcha" required autocomplete="off">
								</div> 
							</div>
						</div>  
					</div>  
 
					<div class="card-body text-center">				         						 
						<button type="button" id="btn-cancel" class="btn btn-dark py-8 fs-4 mb-3 rounded-2 ">Batal</button>
						<button type="button" id="btn-save-register" class="btn btn-primary py-8 fs-4 mb-3 rounded-2 ">Registrasi</button>
 					</div>  
                </form> 
              </div>
          </div>
        </div>
      </div>
    </div>
  </div>  