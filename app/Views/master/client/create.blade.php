<div class="card">   
    <div class="card-body">
    <h5 class="card-title fw-semibold mb-3">{{ $page_title }}</h5>
        <ul class="nav tabs-menu-form">
            <li>
                <a href="#tabProfile" data-bs-toggle="tab" class="active" id="buttonProfile">
					<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-dollar" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h3" /><path d="M21 15h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5" /><path d="M19 21v1m0 -8v1" /></svg>
					Profile Cient
                </a>
            </li>
            <li>
                <a href="#tabReferensi" data-bs-toggle="tab" id="buttonReferensi">
					<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-invoice" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 7l1 0" /><path d="M9 13l6 0" /><path d="M13 17l2 0" /></svg>
                    Dokumen Referensi
                </a>
            </li>   
        </ul> 
 
        <div class="tab-content">
            <div class="tab-pane active" id="tabProfile">  
                <div class="row">
					<form id="form-main" name="form-main"> 
						<div class="card-body"> 
							<input type="hidden" id="idData" name="idData" value="{{ isset($arrPerusahaan->id)?encrypt_id($arrPerusahaan->id):'' }}">
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
											<input class="form-control" id="i_nama" name="i_nama" type="text" placeholder="Nama Perusahaan" value="{{ $arrPerusahaan->nama }}"> 
										</div>
									</div>
									<div class="row mb-3">
										<label class="col-md-4 form-label">NPWP 15 Digit</label>
										<div class="col-md-8">
											<input class="form-control mask-npwp" id="i_npwp" name="i_npwp" type="text" placeholder="NPWP 15 Digit" value="{{ FormatNPWP($arrPerusahaan->npwp??'') }}">
										</div>
									</div>
									<div class="row mb-3">
										<label class="col-md-4 form-label">NPWP 16 Digit</label>
										<div class="col-md-8">
											<input class="form-control mask-npwp-16" id="i_npwp16" name="i_npwp16" type="text" placeholder="NPWP 16 Digit" value="{{ FormatNPWP($arrPerusahaan->npwp16??'') }}">
										</div>
									</div>
									<div class="row mb-3">
										<label class="col-md-4 form-label">NIB</label>
										<div class="col-md-8">
											<input class="form-control" id="i_nib" name="i_nib" type="text" placeholder="NIB" value="{{ $arrPerusahaan->nib }}">
										</div>
									</div>
									<div class="row mb-3">
										<label class="col-md-4 form-label">NITKU</label>
										<div class="col-md-8">
											<input class="form-control" id="i_nitku" name="i_nitku" type="text" placeholder="NITKU" value="{{ $arrPerusahaan->nitku }}">
										</div>
									</div>
								</div>
								
								<div class="col">
									<div class="row mb-3">
										<label class="col-md-4 form-label">Alamat Perusahaan</label>
										<div class="col-md-8"> 
											<textarea class="form-control" id="i_alamat" name="i_alamat" rows="3" placeholder="Alamat Client">{{ $arrPerusahaan->alamat }}</textarea>
										</div>
									</div>
									<div class="row mb-3">
										<label class="col-md-4 form-label">Kab/Kota</label>
										<div class="col-md-8"> 
											<select class="form-control select2-show-search form-select select-kota" id="i_kodeKab" name="i_kodeKab" data-placeholder="-- Pilih --" data-allow-clear="true" style="width: 100%;">
												<option label="-- Pilih --"></option>
												@if (!empty($arrPerusahaan->idKab))
													<option value="{{ $arrPerusahaan->idKab }}" data-kode="{{ $arrPerusahaan->idKab }}" data-value="{{ $arrPerusahaan->namaKab }}" selected>{{ $arrPerusahaan->namaKab }}</option>
												@endif
											</select>
										</div>
									</div>
									<div class="row mb-3">
										<label class="col-md-4 form-label">Provinsi</label>
										<div class="col-md-8">
											<select class="form-control select2-show-search form-select select-propinsi" id="i_kodeProp" name="i_kodeProp" data-placeholder="-- Pilih --" data-allow-clear="true" style="width: 100%;">
												<option label="-- Pilih --"></option>
												@if (!empty($arrPerusahaan->kodeProp))
													<option value="{{ $arrPerusahaan->kodeProp }}" selected>{{ $arrPerusahaan->namaProp }}</option>
												@endif
											</select>
										</div>
									</div>
									<div class="row mb-3">
										<label class="col-md-4 form-label">Kode Pos</label>
										<div class="col-md-8">
											<input class="form-control" id="i_kodePos" name="i_kodePos" type="text" placeholder="Kode Pos" value="{{ $arrPerusahaan->kodePos }}">
										</div>
									</div>
									<div class="row mb-3">
										<label class="col-md-4 form-label">Jenis IUP</label>
										<div class="col-md-8">
											<select class="form-control select2-show-search form-select select-jenis-iup" id="i_idJenisIup" name="i_idJenisIup" data-placeholder="-- Pilih --" data-allow-clear="true" style="width: 100%;">
												<option label="-- Pilih --"></option>
												@if (!empty($arrPerusahaan->idJenisIup))
													<option value="{{ $arrPerusahaan->idJenisIup }}" selected>{{ $arrPerusahaan->jenisIUP }}</option>
												@endif
											</select>
										</div>
									</div>
									<div class="row mb-3">
										<label class="col-md-4 form-label">No & Tgl IUP</label>
										<div class="col-md-4">
											<input class="form-control" id="i_noIUP" name="i_noIUP" type="text" placeholder="Nomor IUP" value="{{ $arrPerusahaan->noIUP }}">
										</div>
										<div class="col-md-4">
											<input class="form-control bs-datepicker" id="i_tglIUP" name="i_tglIUP" type="text" placeholder="Tanggal IUP" value="{{ reverseDate($arrPerusahaan->tglIUP??'') }}">
										</div>
									</div>
								</div>
							</div> 
						</div>

						<div class="card-header border-bottom">
							<h5 class="mb-0">Kontak</h5>
						</div>   
						<div class="card-body">
							<div class="row">
								<div class="col">
									<div class="row mb-3">
										<label class="col-md-4 form-label">e-mail</label>
										<div class="col-md-8">
											<input class="form-control" id="i_email" name="i_email" type="text" placeholder="e-mail" value="{{ $arrPerusahaan->email }}">
										</div>
									</div>
									
									<div class="row mb-3">
										<label class="col-md-4 form-label">Telephone</label>
										<div class="col-md-8">
											<input class="form-control" id="i_telp" name="i_telp" type="text" placeholder="Telephone" value="{{ $arrPerusahaan->telp }}">
										</div>
									</div>
								</div>
								<div class="col"> 
									<div class="row mb-3">
										<label class="col-md-4 form-label">Fax</label>
										<div class="col-md-8">
											<input class="form-control" id="i_fax" name="i_fax" type="text" placeholder="Fax" value="{{ $arrPerusahaan->fax }}">
										</div>
									</div>
								</div>
							</div> 
						</div>

						<div class="card-header border-bottom">
							<h5 class="mb-0">PIC</h5>
						</div>   
						<div class="card-body">
							<div class="row">
								<div class="col">
									<div class="row mb-3">
										<label class="col-md-4 form-label">Nama PIC</label>
										<div class="col-md-8">
											<input class="form-control" id="i_picNama" name="i_picNama" type="text" placeholder="Nama PIC" value="{{ $arrPerusahaan->picNama }}">
										</div>
									</div>
									
									<div class="row mb-3">
										<label class="col-md-4 form-label">Jabatan PIC</label>
										<div class="col-md-8">
											<input class="form-control" id="i_picJabatan" name="i_picJabatan" type="text" placeholder="Jabatan PIC" value="{{ $arrPerusahaan->picJabatan }}">
										</div>
									</div>
								</div>
								<div class="col"> 
									<div class="row mb-3">
										<label class="col-md-4 form-label">Telephone PIC</label>
										<div class="col-md-8">
											<input class="form-control" id="i_picTelp" name="i_picTelp" type="text" placeholder="Telephone PIC" value="{{ $arrPerusahaan->picTelp }}">
										</div>
									</div>
									<div class="row mb-3">
										<label class="col-md-4 form-label">e-mail PIC</label>
										<div class="col-md-8">
											<input class="form-control" id="i_picEmail" name="i_picEmail" type="text" placeholder="e-mail PIC" value="{{ $arrPerusahaan->picEmail }}">
										</div>
									</div>
								</div>
							</div> 
						</div>
					</form>
                </div>
					
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
						<button type="button" class="btn d-w-md btn-info btn-sm" id="btn-simpan-profile"><i class="fa fa-save me-2" aria-hidden="true"></i>Simpan</button> 
					</div>
				</div> 
            </div>
            
            <div class="tab-pane" id="tabReferensi">      
                <div class="row mt-5">
					<form id="form-dokumen" name="form-dokumen" enctype="multipart/form-data" action="javascript:void(0);">
						<div class="form-horizontal">
							<div class="row">
								<div class="col"> 
									<div class="row mb-3">
										<label class="col-md-4 form-label">Jenis Dokumen</label>
										<div class="col-md-8">
											<select id="t_idJenisDok" name="t_idJenisDok" class="form-control select2-show-search form-select" data-placeholder="-- Pilih --" style="width: 100%;">
												<option label="-- Pilih --"></option>
											</select>
										</div> 
									</div>
									<div class="row mb-3">
										<label class="col-md-4 form-label">Nomor Dokumen</label>
										<div class="col-md-8">
											<input type="text" class="form-control" id="t_noDokumen" name="t_noDokumen" placeholder="Nomor Dokumen">
										</div>
									</div>
									<div class="row mb-3">
										<label class="col-md-4 form-label">Tanggal Dokumen</label>
										<div class="col-md-8">
											<input type="text" class="form-control bs-datepicker" id="t_tglDokumen" name="t_tglDokumen" placeholder="Tanggal Dokumen">
										</div>
									</div> 
								</div>
								
								<div class="col"> 
									<div class="row mb-3">
										<label class="col-md-4 form-label">Negara Penerbit</label>
										<div class="col-md-8">
											<select id="t_negaraPenerbit" name="t_negaraPenerbit" class="form-control select2-show-search form-select" data-placeholder="-- Pilih --" style="width: 100%;">
												<option label="-- Pilih --"></option>
											</select>
										</div> 
									</div>
									<div class="row mb-3" id="uploadFile">
										<label class="col-md-4 form-label">Pilih File</label>
										<div class="col-md-8"> 
											<input class="form-control file-input" type="file" id="t_fileDok" name="t_fileDok" placeholder="File">
										</div>
									</div>
									<div class="row mb-3" id="viewFile">
										<label class="col-md-4 form-label">Pilih File</label>
										<div class="col-md-5">
												<input class="form-control file-input" type="file" id="t_fileDokUpdate" name="t_fileDokUpdate" placeholder="File">
										</div> 
										<div class="col-md-3" id="lingFile">  
										</div>
									</div>
									<div class="row mb-3">
										<label class="col-md-4 form-label">Tanggal Akhir</label>
										<div class="col-md-8">
											<input type="text" class="form-control bs-datepicker" id="t_tglAkhirDokumen" name="t_tglAkhirDokumen" placeholder="Tanggal Akhir Dokumen">
										</div>
									</div> 
								</div>
							</div>
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
									<button type="reset" class="btn d-w-md btn-danger btn-sm" id="reset-form-upload">
										<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eraser" width="15" height="15" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
											<path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
											<path d="M19 20h-10.5l-4.21 -4.3a1 1 0 0 1 0 -1.41l10 -10a1 1 0 0 1 1.41 0l5 5a1 1 0 0 1 0 1.41l-9.2 9.3"></path>
											<path d="M18 13.3l-6.3 -6.3"></path>
										</svg>
										Reset
									</button>
									&nbsp;
									<button type="button" class="btn d-w-md btn-info btn-sm" id="btn-save-dokumen"><i class="fa fa-save me-2" aria-hidden="true"></i>Simpan</button> 
									<input type="hidden" id="idDok" name="idDok">
								</div>
							</div>
						</div>
					</form>
				</div>

				<div class="row table-responsive mt-5">
					<table id="table-dok-ref" class="table text-md-nowrap table-bordered">
						<thead>
							<tr>
								<th class="text-center">#</th>
								<th>Jenis Dokumen</th>
								<th>Nomor Dokumen</th>
								<th>Tanggal Dokumen</th>
								<th>Tanggal Akhir</th>
								<th>Negara Penerbit</th>
								<th class="text-center">Aksi</th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
            </div> 
        </div>

        <div class="col-md-12 mt-3"> 
            <!-- <div class="card"> 
                <div class="card-body"> -->
                    <ul role="menu" aria-label="Pagination">
                        <li class="d-none" aria-disabled="false"> 
                        </li> 
                    </ul>
                <!-- </div>
            </div> -->
        </div> 
    </div>
</div>
  
   