<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login | {{ strtoupper(env('APP_NAME', "laravel")) }}</title>
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
      <div class="card">
				<div class="card-body login-card-body">
					<p class="login-box-msg">Sign in to start your session</p>
					<form action="" method="post">
						<div class="input-group mb-3">
							<input type="email" class="form-control" placeholder="Email" id="f-email" name="email" required>
							<div class="input-group-append">
								<div class="input-group-text">
									<span class="fas fa-envelope"></span>
								</div>
							</div>
						</div>
						<div class="input-group mb-3">
							<input type="password" class="form-control" placeholder="Password" id="f-password" name="password" required>
							<div class="input-group-append">
								<div class="input-group-text">
									<span class="fas fa-lock"></span>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-8">
							</div>
							<div class="col-4">
								<button type="submit" class="btn btn-primary btn-block">Sign In</button>
							</div>
						</div>
					</form>
					<div class="social-auth-links text-center mb-3">
					</div>
					<p class="mb-1">
						<a href="{{ route('view.web.password') }}">I forgot my password</a>
					</p>
					<p class="mb-0">
						<a href="{{ route('view.web.register') }}" class="text-center">Register a new membership</a>
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
	
		{{-- OVERLAY --}}
		<div id="overlay" class="position-fixed justify-content-center align-items-center" style="top: 0; right: 0; left:0; bottom:0; background-color: rgba(71, 70, 70, 0.437); z-index:999999999; display: none">
			<i class="fas fa-spinner fa-pulse" style="font-size: 30px; color: white"></i>
		</div>
		
    <!-- jQuery -->
    <script src="/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="/plugins/adminlte/js/adminlte.min.js"></script>
		<!-- Sweetalert 2 -->
		<script src="/plugins/sweetalert2/sweetalert2.min.js"></script>
    <script>
      console.clear();
			function overlayShow(){
				$('#overlay').css('display', 'flex')
			}
			function overlayHide(){
				$('#overlay').css('display', 'none')
			}
      $(document).on("submit", "form", function (e) {
        e.preventDefault();
        $.ajax({
          method: "post",
          url: "{{ route('v1.web.login') }}",
          data: $(this).serialize(),
          beforeSend: function () {
						overlayShow()
            $(".btn-submit").prop("disabled", true);
          },
          complete: function () {
						overlayHide()
            $(".btn-submit").prop("disabled", false);
          },
        })
				.done(function (response) {
					if(response.status){
						window.location = '{{ route("view.web.home") }}'
					}
				})
				.fail(function (jqXHR, status) {
					Swal.fire({
						icon: 'error',
						title: 'gagal login',
						timer: 2000,
					})
				});
      });
    </script>
  </body>
</html>
