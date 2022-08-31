<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta name="MASTER-KEY" content="{{ $key }}">
    <title>Master | {{ strtoupper(env('APP_NAME', "laravel")) }}</title>
    <link rel="icon" href="/icon.ico">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/plugins/fontawesome-free/css/all.min.css" />
    <!-- Theme style -->
    <link rel="stylesheet" href="/plugins/adminlte/css/adminlte.min.css" />
		<!-- Sweetalert 2 -->
		<link rel="stylesheet" href="/plugins/sweetalert2/sweetalert2.min.css" />
		<!-- DataTables -->
		<link rel="stylesheet" href="/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
		<link rel="stylesheet" href="/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
		<link rel="stylesheet" href="/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
		<style>
			
      table .col-fit {
        white-space: nowrap;
        width: 1%;
      }
		</style>
		@yield('header')
  </head>
  <body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
      <!-- Navbar -->
      <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
          </li>
        </ul>
        <ul class="navbar-nav ml-auto">
					<a href="{{ route('view.master.logout') }}" class="btn btn-danger btn-sm">Logout</a>
				</ul>
      </nav>
      <!-- /.navbar -->

      <!-- Main Sidebar Container -->
      <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="#" class="brand-link">
          {{-- <img src="/plugins/adminlte/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: 0.8" /> --}}
          <span class="brand-text font-weight-light">MASTER</span>
        </a>
        <div class="sidebar">
					@include('components.master-navbar')
        </div>
      </aside>

      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <div class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1 class="m-0">Dashboard</h1>
              </div>
            </div>
          </div>
        </div>

				@yield('content')
      </div>

    </div>
		
		<div id="overlay" class="position-fixed justify-content-center align-items-center" style="top: 0; right: 0; left:0; bottom:0; background-color: rgba(71, 70, 70, 0.437); z-index:999999999; display: none">
			<i class="fas fa-spinner fa-pulse" style="font-size: 30px; color: white"></i>
		</div>

		@yield('modal')
		<div class="modal fade" id="modal">
			<div class="modal-dialog modal-xl">
				<div class="modal-content">
					<div class="modal-body"></div>
				</div>
			</div>
		</div>

    <!-- jQuery -->
    <script src="/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="/plugins/adminlte/js/adminlte.js"></script>
		<!-- Sweetalert 2 -->
		<script src="/plugins/sweetalert2/sweetalert2.min.js"></script>
		<!-- DataTables  & Plugins -->
		<script src="/plugins/datatables/jquery.dataTables.min.js"></script>
		<script src="/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
		<script src="/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
		<script src="/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
		@yield('footer')
		@yield('script')
  </body>
</html>
