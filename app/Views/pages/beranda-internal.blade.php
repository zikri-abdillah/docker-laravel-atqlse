<div class="row">
  <div class="col-lg-3 d-flex align-items-stretch">
    <div class="card w-100">
      <div class="card-body p-4">
        <div class="mb-4">
          <h5 class="card-title fw-semibold">LSE Batubara</h5>
        </div>
        <div class="card-body">
          <div class="row alig n-items-start">
            <div class="col-8">
              <h4 class="fw-semibold mb-3">{{ isset($batu_aju) ? $batu_aju : 0 }}</h4>
              <div class="d-flex align-items-center pb-1">
                <span class="me-2 rounded-circle bg-light-info round-20 d-flex align-items-center justify-content-center">
                  <i class="ti ti-moneybag text-info"></i>
                </span>
                <p class="text-dark me-1 fs-3 mb-0">Pengajuan</p>
              </div>
            </div>
            <div class="col-4">
              <div class="d-flex justify-content-end">
                <div class="text-white bg-info rounded-circle p-6 d-flex align-items-center justify-content-center">
                  <i class="ti ti-file-alert fs-6"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="row alig n-items-start">
            <div class="col-8">
              <h4 class="fw-semibold mb-3">{{ isset($batu_tolak) ? $batu_tolak : 0 }}</h4>
              <div class="d-flex align-items-center pb-1">
                <span class="me-2 rounded-circle bg-light-warning round-20 d-flex align-items-center justify-content-center">
                  <i class="ti ti-moneybag text-warning"></i>
                </span>
                <p class="text-dark me-1 fs-3 mb-0">Penolakan</p>
              </div>
            </div>
            <div class="col-4">
              <div class="d-flex justify-content-end">
                <div class="text-white bg-warning rounded-circle p-6 d-flex align-items-center justify-content-center">
                  <i class="ti ti-file-dislike fs-6"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="row alig n-items-start">
            <div class="col-8">
              <h4 class="fw-semibold mb-3">{{ isset($batu_terbit) ? $batu_terbit : 0 }}</h4>
              <div class="d-flex align-items-center pb-1">
                <span class="me-2 rounded-circle bg-light-success round-20 d-flex align-items-center justify-content-center">
                  <i class="ti ti-moneybag text-success"></i>
                </span>
                <p class="text-dark me-1 fs-3 mb-0">Penerbitan</p>
              </div>
            </div>
            <div class="col-4">
              <div class="d-flex justify-content-end">
                <div class="text-white bg-success rounded-circle p-6 d-flex align-items-center justify-content-center">
                  <i class="ti ti-certificate-2 fs-6"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="row alig n-items-start">
            <div class="col-8">
              <h4 class="fw-semibold mb-3">{{ isset($batu_cabut) ? $batu_cabut : 0 }}</h4>
              <div class="d-flex align-items-center pb-1">
                <span class="me-2 rounded-circle bg-light-danger round-20 d-flex align-items-center justify-content-center">
                  <i class="ti ti-moneybag text-danger"></i>
                </span>
                <p class="text-dark me-1 fs-3 mb-0">Pencabutan</p>
              </div>
            </div>
            <div class="col-4">
              <div class="d-flex justify-content-end">
                <div class="text-white bg-danger rounded-circle p-6 d-flex align-items-center justify-content-center">
                  <i class="ti ti-certificate-2-off fs-6"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-9 d-flex align-items-stretch">
    <div class="card w-100">
      <div class="card-body p-4">
        <h5 class="card-title fw-semibold mb-4">5 Pengajuan Terakhir</h5>
        <table class="table text-nowrap mb-0 align-middle" id="tabel-pengajuan">
          <thead class="text-dark fs-4">
            <tr>
              <th class="border-bottom-0">
                <h6 class="fw-semibold mb-0">No</h6>
              </th>
              <th class="border-bottom-0">
                <h6 class="fw-semibold mb-0">Nomor Draft</h6>
              </th>
              <th class="border-bottom-0">
                <h6 class="fw-semibold mb-0">Tanggal Draft</h6>
              </th>
              <th class="border-bottom-0">
                <h6 class="fw-semibold mb-0">Perusahaan</h6>
              </th>
              <th class="border-bottom-0">
                <h6 class="fw-semibold mb-0">Status Proses</h6>
              </th>
            </tr>
          </thead>
        </table> 
      </div>
      
      <div class="card-body p-4">
        <h5 class="card-title fw-semibold mb-4">5 Penerbitan Terakhir</h5>
        <table class="table text-nowrap mb-0 align-middle" id="tabel-penerbitan">
          <thead class="text-dark fs-4">
            <tr>
              <th class="border-bottom-0">
                <h6 class="fw-semibold mb-0">No</h6>
              </th>
              <th class="border-bottom-0">
                <h6 class="fw-semibold mb-0">Nomor Draft</h6>
              </th>
              <th class="border-bottom-0">
                <h6 class="fw-semibold mb-0">Tanggal Draft</h6>
              </th>
              <th class="border-bottom-0">
                <h6 class="fw-semibold mb-0">Perusahaan</h6>
              </th>
              <th class="border-bottom-0">
                <h6 class="fw-semibold mb-0">Status Proses</h6>
              </th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div> 