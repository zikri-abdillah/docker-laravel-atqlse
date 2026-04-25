<div class="row" id="user-profile">
	<div class="col-lg-12"> 
		<div class="row row-sm">
			<div class="col-lg-12 col-md-12">
				<div class="card"> 
				<form id="form-user" name="form-user"> 
					<div class="card-header border-bottom">
						<h4 class="mb-0">Profile Pegawai</h4>
					</div>  
					<div class="card-body"> 
						<div class="row">
							<div class="col">
								<div class="row mb-3">
									<label class="col-md-4 form-label">NIK</label>
									<div class="col-md-8"> 
										<input class="form-control" id="i_nik" name="i_nik" type="text" placeholder="NIK" value="{{ $arrUser->nik }}"> 
									</div>
								</div>
								<div class="row mb-3">
									<label class="col-md-4 form-label">Cabang</label>
									<div class="col-md-8"> 
										<select class="form-control select2-show-search form-select select-cabang" id="i_cabang" name="i_cabang" data-placeholder="-- Pilih --" data-allow-clear="true" style="width: 100%;">
											<option label="-- Pilih --"></option>
											@if (!empty($arrUser->idcabang))
												<option value="{{ $arrUser->idcabang }}" selected>{{ $arrUser->cabang }}</option>
											@endif
										</select>
									</div>
								</div> 
								<div class="row mb-4">
									<label class="col-md-4 form-label">NIP</label>
									<div class="col-md-8">
										<input class="form-control" id="i_nip" name="i_nip" type="text" placeholder="NIP" value="{{ $arrUser->nip }}">
									</div>
								</div>
								<div class="row mb-4">
									<label class="col-md-4 form-label">Jabatan</label>
									<div class="col-md-8">
										<input class="form-control" id="i_jabatan" name="i_jabatan" type="text" placeholder="Jabatan" value="{{ $arrUser->jabatan }}">
									</div>
								</div> 
							</div>
							<div class="col">
								<div class="row mb-3">
									<label class="col-md-4 form-label">Nama Lengkap</label>
									<div class="col-md-8"> 
										<input class="form-control" id="i_nama" name="i_nama" type="text" placeholder="Nama Lengkap" value="{{ $arrUser->nama }}"> 
									</div>
								</div>
								<div class="row mb-3">
									<label class="col-md-4 form-label">Alamat</label>
									<div class="col-md-8">
										<textarea class="form-control" id="i_alamat" name="i_alamat" rows="2">{{ $arrUser->alamat }}</textarea>
									</div>
								</div> 
								<div class="row mb-3">
									<label class="col-md-4 form-label">Nomor Telephone</label>
									<div class="col-md-8"> 
										<input class="form-control" id="i_telp" name="i_telp" type="text" placeholder="Nomor Telephone" value="{{ $arrUser->telp }}"> 
									</div>
								</div>
								<div class="row mb-4">
									<label class="col-md-4 form-label">e-mail</label>
									<div class="col-md-8">
										<input class="form-control" id="i_email" name="i_email" type="text" placeholder="e-mail" value="{{ $arrUser->email }}">
									</div>
								</div>  
							</div>
						</div> 
					</div> 
					
					<div class="card-header border-bottom">
						<h4 class="mb-0">Hak Akses</h4>
					</div>   
					<div class="card-body">
						<div class="row">
							<div class="col">
								<div class="row mb-4">
									<label class="col-md-4 form-label">User Role</label>
									<div class="col-md-8"> 
										<select class="form-control select2-show-search form-select select-role" id="i_role" name="i_role" data-placeholder="-- Pilih --" data-allow-clear="true" style="width: 100%;">
											<option label="-- Pilih --"></option> 
											@if (!empty($arrUser->idrole))
												<option value="{{ $arrUser->idrole }}" selected>{{ $arrUser->role }}</option>
											@endif
										</select>
									</div>
								</div> 
								<div class="row mb-4">
									<label class="col-md-4 form-label">User Type</label>
									<div class="col-md-8"> 
										<select class="form-control select2-show-search form-select select-type" id="i_type" name="i_type" data-placeholder="-- Pilih --" data-allow-clear="true" style="width: 100%;">
											<option label="-- Pilih --"></option>
											@if (!empty($arrUser->usertype))
												<option value="{{ $arrUser->usertype }}" selected>{{ $arrUser->type }}</option>
											@endif
										</select>
									</div>
								</div>
								<div class="row mb-4">
									<label class="col-md-4 form-label">Status</label>
									<div class="col-md-8">
										<select class="form-control select2-show-search form-select select2" id="i_isActive" name="i_isActive" data-placeholder="-- Pilih --" data-allow-clear="true" style="width: 100%;">
											@php
												if (!empty($arrUser->usertype)){
													if($arrUser->isActive == 'Y'){
														$active = 'selected'; $inActive = '';
													}
													else{
														$active = ''; $inActive = 'selected';
													}
												}
											@endphp
											<option value="Y" {{ $active }}>Aktif</option>
											<option value="N" {{ $inActive }}>Tidak Aktif</option>
										</select>
									</div>
								</div>
							</div>
							<div class="col"> 
								<div class="row mb-4">
									<label class="col-md-4 form-label">Username</label>
									<div class="col-md-8">
										<input class="form-control" id="i_username" name="i_username" placeholder="Username" type="text" value="{{ $arrUser->username }}">
									</div>
								</div>
								<div class="row mb-4">
									<label class="col-md-4 form-label">Password</label>
									<div class="col-md-8">
										<input class="form-control" type="password" id="i_password" name="i_password" placeholder="Password" type="text">
									</div>
								</div>
							</div>
						</div> 
					</div>

				</form>

				<div class="row p-5"> 
					<div class="btn-list text-center">
						<input type="hidden" id="idPegawai" name="idPegawai" value="{{ isset($arrUser->idPegawai)?encrypt_id($arrUser->idPegawai):'' }}" readonly>
						<input type="hidden" id="idUser" name="idUser" value="{{ isset($arrUser->idUser)?encrypt_id($arrUser->idUser):'' }}" readonly>
						 
						<button type="button" class="btn d-w-md btn-dark btn-sm" id="btn-back-user">
							<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-arrow-back-up-double" width="15" height="15" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
								<path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
								<path d="M13 14l-4 -4l4 -4"></path>
								<path d="M8 14l-4 -4l4 -4"></path>
								<path d="M9 10h7a4 4 0 1 1 0 8h-1"></path>
							</svg>
							Kembali
						</button>
						&nbsp;
						<button type="reset" class="btn d-w-md btn-danger btn-sm" id="btn-reset-pegawai">
							<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eraser" width="15" height="15" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
								<path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
								<path d="M19 20h-10.5l-4.21 -4.3a1 1 0 0 1 0 -1.41l10 -10a1 1 0 0 1 1.41 0l5 5a1 1 0 0 1 0 1.41l-9.2 9.3"></path>
								<path d="M18 13.3l-6.3 -6.3"></path>
							</svg>
							Reset
						</button>
						&nbsp;
						<button type="button" class="btn d-w-md btn-info btn-sm" id="btn-simpan-user">
							<i class="fa fa-save me-2" aria-hidden="true"></i>Simpan
						</button> 
					</div>
				</div> 
				</div>
			</div>
		</div>  
	</div> 
</div>
 