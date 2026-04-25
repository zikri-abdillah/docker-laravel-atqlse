<div class="row" id="user-profile">
	<div class="col-lg-12"> 
		<div class="row row-sm">
			<div class="col-lg-12 col-md-12">
				<div class="card"> 
				<form id="form-user" name="form-user"> 
					<div class="card-header border-bottom">
						<h5 class="mb-0">Profile Pegawai</h5>
					</div>  
					<div class="card-body"> 
						<div class="row">
							<div class="col">
								<div class="row mb-3">
									<label class="col-md-4 form-label">NIK</label>
									<div class="col-md-8"> 
										:&nbsp;&nbsp;&nbsp;{{ $arrUser->nik }}
									</div>
								</div>
								<div class="row">
									<label class="col-md-4 form-label">Cabang</label>
									<div class="form-group col-md-8">
										:&nbsp;&nbsp;&nbsp;{{ $arrUser->cabang }}
									</div>
								</div>
								<div class="row mb-4">
									<label class="col-md-4 form-label">NIP</label>
									<div class="col-md-8">
										:&nbsp;&nbsp;&nbsp;{{ $arrUser->nip }}
									</div>
								</div>
								<div class="row mb-4">
									<label class="col-md-4 form-label">Jabatan</label>
									<div class="col-md-8">
										:&nbsp;&nbsp;&nbsp;{{ $arrUser->jabatan }}
									</div>
								</div> 
							</div>
							<div class="col">
								<div class="row mb-3">
									<label class="col-md-4 form-label">Nama Lengkap</label>
									<div class="col-md-8"> 
										:&nbsp;&nbsp;&nbsp;{{ $arrUser->nama }} 
									</div>
								</div>
								<div class="row mb-3">
									<label class="col-md-4 form-label">Alamat</label>
									<div class="col-md-8">
										:&nbsp;&nbsp;&nbsp;{{ $arrUser->alamat }}
									</div>
								</div> 
								<div class="row mb-3">
									<label class="col-md-4 form-label">Nomor Telephone</label>
									<div class="col-md-8"> 
										:&nbsp;&nbsp;&nbsp;{{ $arrUser->telp }} 
									</div>
								</div>
								<div class="row mb-4">
									<label class="col-md-4 form-label">e-mail</label>
									<div class="col-md-8">
										:&nbsp;&nbsp;&nbsp;{{ $arrUser->email }}
									</div>
								</div>  
							</div>
						</div> 
					</div> 
					
					<div class="card-header border-bottom">
						<h5 class="mb-0">Hak Akses</h5>
					</div>   
					<div class="card-body">
						<div class="row">
							<div class="col">
								<div class="row mb-4">
									<label class="col-md-4 form-label">User Role</label>
									<div class="col-md-8"> 
										:&nbsp;&nbsp;&nbsp;{{ $arrUser->role }}
									</div>
								</div>
								
								<div class="row mb-4">
									<label class="col-md-4 form-label">User Type</label>
									<div class="col-md-8"> 
											:&nbsp;&nbsp;&nbsp;{{ $arrUser->type }}
									</div>
								</div>
							</div>
							<div class="col"> 
								<div class="row mb-4">
									<label class="col-md-4 form-label">Username</label>
									<div class="col-md-8">
										:&nbsp;&nbsp;&nbsp;{{ $arrUser->username }}
									</div>
								</div> 
							</div>
						</div> 
					</div>

				</form>

				<div class="row p-5"> 
					<div class="btn-list text-center"> 
						<button type="button" class="btn d-w-md btn-dark btn-sm" id="btn-back-user">
							<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-back-up-double" width="15" height="15" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
								<path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
								<path d="M13 14l-4 -4l4 -4"></path>
								<path d="M8 14l-4 -4l4 -4"></path>
								<path d="M9 10h7a4 4 0 1 1 0 8h-1"></path>
							</svg>
							Kembali 
						</button> 
					</div>
				</div> 
				</div>
			</div>
		</div>  
	</div> 
</div> 