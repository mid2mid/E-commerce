<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Register | {{ strtoupper(env('APP_NAME', "laravel")) }}</title>
    <link rel="icon" href="/icon.ico">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="/plugins/fontawesome-free/css/all.min.css" />
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="/plugins/icheck-bootstrap/icheck-bootstrap.min.css" />
    <!-- Theme style -->
    <link rel="stylesheet" href="/plugins/adminlte/css/adminlte.min.css" />
		<!-- Sweetalert 2 -->
		<link rel="stylesheet" href="/plugins/sweetalert2/sweetalert2.min.css" />
  </head>
  <body class="hold-transition login-page">
    <div class="login-box">
      <div class="login-logo">
        <a href="#"><b>susuKNTL</b>LTE</a>
      </div>
      <!-- /.login-logo -->
      <div class="card">
				<div class="card-body login-card-body">
					<p class="login-box-msg">Register New Account</p>
					<form action="" method="post">
						<div class="form-group mb-3">
							<label>User</label>
							<input type="text" class="form-control" placeholder="User" name="user" id="f-user" >
							<small class="form-text e e-user text-danger"></small>
						</div>
						<div class="form-group mb-3">
							<label>Email</label>
							<input type="email" class="form-control" placeholder="Email" name="email" id="f-email">
							<small class="form-text e e-email text-danger"></small>
						</div>
						<div class="form-group mb-3">
							<label>Password</label>
							<input type="password" class="form-control" placeholder="Password" name="password" id="f-password">
							<small class="form-text e e-password text-danger"></small>
						</div>
						<div class="form-group mb-3">
							<label>Repeat Password</label>
							<input type="password" class="form-control" placeholder="Re-type Password" name="password_confirmation" id="f-password-confirmation">
							<small class="form-text e e-password_confirmation text-danger"></small>
						</div>
						<div class="row">
							<div class="col-8">
							</div>
							<div class="col-4">
								<button type="submit" class="btn btn-primary btn-block btn-submit">Register</button>
							</div>
						</div>
					</form>
					<div class="social-auth-links text-center mb-3">
					</div>
					<p class="mb-0">
						Have already acoount. <a href="{{ route('view.web.login') }}" class="text-center">Login</a>
					</p>
				</div>
      </div>
    </div>
    <div class="modal fade" id="modal">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-body"></div>
        </div>
      </div>
    </div>
		<div id="overlay" class="position-fixed justify-content-center align-items-center" style="top: 0; right: 0; left:0; bottom:0; background-color: rgba(128, 128, 128, 0.298); z-index:999999999; display: none">
			<i class="fas fa-spinner fa-pulse" style="font-size: 30px"></i>
		</div>

    <!-- jQuery -->
    <script src="/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="/plugins/adminlte/js/adminlte.min.js"></script>
		<!-- Sweetalert 2 -->
		<script src="/plugins/sweetalert2/sweetalert2.min.js"></script>
		{{-- OVERLAY --}}
    <script>
      console.clear();
			function overlayShow(){
				$('#overlay').css('display', 'flex')
			}
			function overlayHide(){
				$('#overlay').css('display', 'none')
			}
			function notif(title, icon = 'success'){
				Swal.fire({
					icon: icon,
					title: title,
					timer: 2000,
				})
			}
			function errorShow(response){
				$.each(response, function(i,v){
					$('.e-'+i).html(v)
				})
			}
			function errorHide(response){
				$('.e').html('')
			}

      $(document).on("submit", "form", function (e) {
        e.preventDefault();
        $.ajax({
          method: "post",
          url: "{{ route('v1.web.register') }}",
          data: $(this).serialize(),
          beforeSend: function () {
						overlayShow()
						errorHide()
            $(".btn-submit").prop("disabled", true);
          },
          complete: function () {
						overlayHide()
            $(".btn-submit").prop("disabled", false);
          },
        })
				.done(function (response) {
					alert("Silakan Cek Email Anda Untuk Verifikasi");
					if(response.status){
						window.location = response.data.link
					}
				})
				.fail(function (jqXHR, status) {
					if(jqXHR.responseJSON){
						notif(jqXHR.responseJSON.message != 'failed' ? jqXHR.responseJSON.message  : 'gagal daftar', 'error')
						errorShow(jqXHR.responseJSON.field)
					}else{
						notif('gagal daftar', 'error')
					}
				});
      });
    </script>
  </body>
</html>
