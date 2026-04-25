<div class="row row-sm">
    <div class="col-lg-12 col-md-12">
        <!-- Form Pencarian --> 
        <div class="card mb-5 mt-2"> 
            <div class="card-body"> 
                <h5 class="card-title fw-semibold mb-4">Form Validasi Survey Permohonan Asuransi</h5>
                <form>  
					<div class="row">
						<div class="col-lg-6 col-md-6">
							<div class="row mb-4">
								<label for="firstName" class="col-md-3 form-label">Nomor Sertifikat</label>
								<div class="col-md-9"> 
									<input class="form-control" id="nosertifikat" name="nosertifikat" placeholder="Nomor Sertifikat" type="text"> 
								</div>
							</div>
							<div class="row mb-4">
								<label for="lastName" class="col-md-3 form-label">Tanggal Survey</label>
								<div class="col-md-9"> 
									<input class="form-control bs-datepicker" id="tglsurvey" name="tglsurvey" placeholder="Tanggal Survey" type="text"> 
								</div>
							</div> 
						</div>

						<div class="col-lg-6 col-md-6">
							<div class="row mb-4">
								<label for="email" class="col-md-3 form-label">Hasil Survey</label>
								<div class="col-md-9">
									<select class="form-control select2-show-search form-select" id="hasilsurvey" name="hasilsurvey" data-placeholder="-- Pilih --" style="width: 100%;">
										<option value="">-- Pilih --</option>
										<option value="0">Kosong</option>
										<option value="1">Yes</option>
										<option value="2">No</option>
									</select>
								</div>
							</div>
							<div class="row mb-4">
								<label for="firstName" class="col-md-3 form-label">Keterangan</label>
								<div class="col-md-9">
									<textarea 
										class="form-control"  
										id="keterangan" 
										name="keterangan"
										placeholder="Keterangan" 
										required="" 
										rows="2"
									></textarea>
								</div>
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
							<button type="button" class="btn btn-info" onclick="survey()">
								<i class="fa fa-search me-1" aria-hidden="true"></i>Cek
							</button>    
						</div>
					</div> 
                </form> 
            </div>
        </div> 
        <!-- End Form Pencarian --> 
    </div>
</div>
 
<div class="modal fade" id="modalRespon" aria-modal="true" role="dialog">
  	<div class="modal-dialog modal-dialog-centered text-center " role="document">
    	<div class="modal-content tx-size-sm">
      		<div class="modal-body text-center p-4">  
				<h4 class="text-success tx-semibold">Success!</h4>
				<p class="text-muted" id="ketRespon"></p>
				<button aria-label="Close" class="btn btn-success pd-x-25" data-bs-dismiss="modal">Oke</button>
      		</div>
    	</div>
  	</div>
</div> 