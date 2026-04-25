<div class="row row-sm">
    <div class="col-lg-12 col-md-12">
        <!-- Form Pencarian --> 
        <div class="card mb-5 mt-2"> 
            <div class="card-body"> 
                <h5 class="card-title fw-semibold mb-4">Form Validasi Sertifikat Asuransi</h5>
                <form return>
					<div class="row">
						<div class="col-lg-1"> 
							<label class="form-label">NTPN</label>
						</div>
						<div class="col-lg-8">   
							<textarea 
								class="form-control" 
								id="nosertifikat" 
								name="nosertifikat"
								placeholder="Nomor Sertifikat Asuransi" 
								required="" 
								rows="3"></textarea>  
						</div>

						<div class="col-lg-2 justify-content-center"> 
							<div class="row justify-content-center mb-2 pl-4"> 
								<div class="col-md-9">   
									<button type="button" class="btn btn-info" onclick="find()">
										<i class="fa fa-search me-1" aria-hidden="true"></i>Cek
									</button>    
								</div>
							</div>
							<div class="row justify-content-center"> 
								<div class="col-md-9"> 
									<button type="reset" id="btn-reset-izin" class="btn btn-danger">
										<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eraser" width="20" height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
											<path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
											<path d="M19 20h-10.5l-4.21 -4.3a1 1 0 0 1 0 -1.41l10 -10a1 1 0 0 1 1.41 0l5 5a1 1 0 0 1 0 1.41l-9.2 9.3"></path>
											<path d="M18 13.3l-6.3 -6.3"></path>
										</svg>
										Reset
									</button>
								</div>
							</div>
						</div>
					</div>	  
					<div class="row">
						<div class="mt-3 text-center">
						</div>
					</div> 
                </form> 
            </div>
        </div> 
        <!-- End Form Pencarian -->
		
        <div class="card"> 
            <div class="card-body"> 
				<div class="table-responsive">  
					<table id="table-asuransi" class="table table-hover table-bordered border-bottom" style="width:100%"> 
						<thead>
							<tr>
								<th class="border-bottom-0 text-center" style="width: 20.4px;">NO</th>
								<th class="border-bottom-0">NOMOR SERTIFIKAT</th> 
								<th class="border-bottom-0">MODA TRANSPORTASI</th>
								<th class="border-bottom-0">NAMA TERTANGGUNG</th>
								<th class="border-bottom-0">KOMODITI</th>
								<th class="border-bottom-0">TANGGAL</th>
								<th class="border-bottom-0">NILAI PREMI</th>
								<th class="border-bottom-0 text-center" align="center">HASIL SURVEY</th>
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
