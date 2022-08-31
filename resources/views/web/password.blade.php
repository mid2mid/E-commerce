<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Password | {{ strtoupper(env('APP_NAME', "laravel")) }}</title>
    <link rel="icon" href="/icon.ico">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/plugins/fontawesome-free/css/all.min.css" />
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="/plugins/icheck-bootstrap/icheck-bootstrap.min.css" />
    <!-- Theme style -->
    <link rel="stylesheet" href="/plugins/adminlte/css/adminlte.min.css" />
    <!-- jQuery -->
    <script src="/plugins/jquery/jquery.min.js"></script>

  </head>
  <body class="hold-transition login-page">
    <div class="login-box">
      <div class="login-logo">
        <a href="#"><b>susuKNTL</b>LTE</a>
      </div>
      <!-- /.login-logo -->
      <div class="card">
				<div class="card-body login-card-body">
					<p class="login-box-msg">Ubah Password</p>
					<form action="" method="post">
						<div class="input-group mb-3">
							<input type="email" class="form-control" name="email" placeholder="Email">
							<div class="input-group-append">
								<div class="input-group-text">
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-8">
							</div>
							<div class="col-4">
								<button type="submit" class="btn btn-primary btn-block">Password</button>
							</div>
						</div>
					</form>
					<p class="mb-0">
						<a href="{{ route('view.web.login') }}" class="text-center">Back to Login</a>
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

		<!-- Bootstrap 4 -->
    <script src="/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="/plugins/adminlte/js/adminlte.min.js"></script>
		{{-- OVERLAY --}}
		<div id="overlay" class="position-fixed justify-content-center align-items-center" style="top: 0; right: 0; left:0; bottom:0; background-color: rgba(128, 128, 128, 0.298); z-index:999999999; display: none">
			<i class="fas fa-spinner fa-pulse" style="font-size: 30px"></i>
		</div>
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
          url: "{{ route('v1.web.password.store') }}",
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
					alert('Silakan Cek Email Anda Untuk Ubah Password')
				// 	window.location = response.data.link
				})
				.fail(function (jqXHR, status) {
					alert('gagal')
				});
			});
    </script>
  </body>
</html>
