<div class="card">   
    <div class="card-body">
    <h5 class="card-title fw-semibold mb-3">{{ $page_title }}</h5>
        <ul class="nav tabs-menu-form">
            <li>
                <a href="#tabProfile" data-bs-toggle="tab" class="active">
					<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-user-dollar" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h3" /><path d="M21 15h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5" /><path d="M19 21v1m0 -8v1" /></svg>
					Profile Cient
                </a>
            </li>
            <li>
                <a href="#tabReferensi" data-bs-toggle="tab">
					<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-invoice" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M14 3v4a1 1 0 0 0 1 1h4" /><path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /><path d="M9 7l1 0" /><path d="M9 13l6 0" /><path d="M13 17l2 0" /></svg>
                    Dokumen Referensi
                </a>
            </li>   
        </ul> 
 
        <div class="tab-content">
            <div class="tab-pane active" id="tabProfile">  
                <div class="row">
					<div class="card-header border-bottom">
						<h4 class="mb-0">Profile Client</h4>
					</div>   
					<div class="card-body"> 
						<input type="hidden" id="idData" name="idData" value="{{ isset($arrPerusahaan->id)?encrypt_id($arrPerusahaan->id):'' }}" readonly>
						<div class="row">
							<div class="col"> 
								<div class="row mb-3">
									<label class="col-md-4 form-label">Nama Perusahaan</label>
									<div class="col-md-8"> 
										:&nbsp;&nbsp;{{ $arrPerusahaan->bentukPersh }}. {{ $arrPerusahaan->nama }}
									</div>
								</div>
								<div class="row mb-3">
									<label class="col-md-4 form-label"> 15 Digit</label>
									<div class="col-md-8">
										:&nbsp;&nbsp;{{ FormatNPWP($arrPerusahaan->npwp??'') }}
									</div>
								</div>
								<div class="row mb-3">
									<label class="col-md-4 form-label">NPWP 16 Digit</label>
									<div class="col-md-8">
										:&nbsp;&nbsp;{{ FormatNPWP($arrPerusahaan->npwp16??'') }}
									</div>
								</div>
								<div class="row mb-3">
									<label class="col-md-4 form-label">NIB</label>
									<div class="col-md-8">
										:&nbsp;&nbsp;{{ $arrPerusahaan->nib }}
									</div>
								</div>
							</div>
							<div class="col">
								<div class="row mb-3">
									<label class="col-md-4 form-label">NITKU</label>
									<div class="col-md-8">
										:&nbsp;&nbsp;{{ $arrPerusahaan->nitku }}
									</div>
								</div>
								<div class="row">
									<label class="col-md-4 form-label">Jenis IUP</label>
									<div class="form-group col-md-8">
										:&nbsp;&nbsp;{{ $arrPerusahaan->jenisIUP }}
									</div>
								</div>
								<div class="row mb-3">
									<label class="col-md-4 form-label">Alamat Eksportir</label>
									<div class="col-md-8">
										:&nbsp;&nbsp;{{ $arrPerusahaan->alamat }}, {{ $arrPerusahaan->namaKab }} - {{ $arrPerusahaan->namaProp }}, {{ $arrPerusahaan->kodePos }}
									</div>
								</div> 
							</div>
						</div> 
					</div>

					<div class="card-header border-bottom">
						<h4 class="mb-0">Kontak</h4>
					</div>   
					<div class="card-body">
						<div class="row">
							<div class="col">
								<div class="row mb-3">
									<label class="col-md-4 form-label">e-mail</label>
									<div class="col-md-8">
										:&nbsp;&nbsp;{{ $arrPerusahaan->email }}
									</div>
								</div>
								
								<div class="row mb-3">
									<label class="col-md-4 form-label">Telephone</label>
									<div class="col-md-8">
										:&nbsp;&nbsp;{{ $arrPerusahaan->telp }}
									</div>
								</div>
							</div>
							<div class="col"> 
								<div class="row mb-3">
									<label class="col-md-4 form-label">Fax</label>
									<div class="col-md-8">
										:&nbsp;&nbsp;{{ $arrPerusahaan->fax }}
									</div>
								</div>
							</div>
						</div> 
					</div>

					<div class="card-header border-bottom">
						<h4 class="mb-0">PIC</h4>
					</div>   
					<div class="card-body">
						<div class="row">
							<div class="col">
								<div class="row mb-3">
									<label class="col-md-4 form-label">Nama PIC</label>
									<div class="col-md-8">
										:&nbsp;&nbsp;{{ $arrPerusahaan->picNama }}
									</div>
								</div>
								
								<div class="row mb-3">
									<label class="col-md-4 form-label">Jabatan PIC</label>
									<div class="col-md-8">
										:&nbsp;&nbsp;{{ $arrPerusahaan->picJabatan }}
									</div>
								</div>
							</div>
							<div class="col"> 
								<div class="row mb-3">
									<label class="col-md-4 form-label">Telephone PIC</label>
									<div class="col-md-8">
										:&nbsp;&nbsp;{{ $arrPerusahaan->picTelp }}
									</div>
								</div>
								<div class="row mb-3">
									<label class="col-md-4 form-label">e-mail PIC</label>
									<div class="col-md-8">
										:&nbsp;&nbsp;{{ $arrPerusahaan->picEmail }}
									</div>
								</div>
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
					</div>
				</div> 
            </div>
            
            <div class="tab-pane" id="tabReferensi">  
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
  
   