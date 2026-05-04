  <!--  Body Wrapper -->
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
    data-sidebar-position="fixed" data-header-position="fixed">
    <div
      class="position-relative overflow-hidden radial-gradient min-vh-100 d-flex align-items-center justify-content-center">
      <div class="d-flex align-items-center justify-content-center w-100">
        <div class="row justify-content-center w-100">
          <div class="col-md-8 col-lg-6 col-xxl-3">
            <div class="card mb-0">
              <div class="card-body">
                <a href="index.html" class="text-nowrap text-center d-block py-3 w-100">
                  <img src="<?= base_url() ?>assets/images/logos/atq-logo.png" width="180" alt="">
                </a> 

                <form action="javascript:void(0)" id="signin-form" class="signin-form">
		      	      <input type="hidden" id="login_token" name="login_token" value="{{ session('sess_login_token') }}">
                  
                  <div class="mb-3">
                    <label for="uname" class="form-label">Username TEST</label>
		      		      <input type="text" class="form-control" id="uname" name="uname" placeholder="Username" required autocomplete="off"> 
                  </div>

                  <div class="mb-4">
                    <label for="upass" class="form-label">Password</label>
                    <input id="upass" name="upass" type="password" class="form-control" placeholder="Password" required autocomplete="off"> 
                  </div>

                  <div class="mb-4 row form-group justify-content-center"> 
                    <div class="col-sm-8" id="captcha-image"> 
                    </div>
                    <div class="col-sm-1"> 
                      <button type="button" class="btn btn-sm btn-danger mt-1" onclick="getCaptcha()" title="Ganti Captcha"><i class="fa fa-refresh"></i></button>
                    </div>
                  </div>
                  
                  <div class="mb-4">
                    <label for="ucaptcha" class="form-label">Captcha</label>
                    <input id="ucaptcha" name="ucaptcha" type="text" class="form-control" placeholder="Captcha" required autocomplete="off">
                  </div>
 
                  <div class="mb-4">
                    <div class="w-100 text-md-center" style="background-color: #00000024;">
                      <small class="login-msg text-danger"></small>
                    </div>
                  </div>

				          <button type="button" id="btn-login" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2 submit">Sign In</button>

                </form> 
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>  