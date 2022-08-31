<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Meta Tag -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="copyright" content="" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    {{-- <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" /> --}}
		<meta name="viewport" content="width=device-width, initial-scale=1">
		@if ($key && $user)
			<meta name="USER-KEY" content="{{ $key }}">
			<meta name="USER-ID" content="{{ $user['id_user'] }}">
		@endif
    <!-- Title Tag  -->
    <title>susuKNTL | E-Commerce</title>
    <link rel="icon" href="/icon.ico">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="/web/plugins/bootstrap4/css/bootstrap.css" />
    {{-- <link rel="stylesheet" href="/web/css/bootstrap.css" /> --}}
    <!-- Magnific Popup -->
    <link rel="stylesheet" href="/web/css/magnific-popup.min.css" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/web/plugins/fontawesome-free-5/css/all.css" />
    {{-- <link rel="stylesheet" href="/web/css/font-awesome.css" /> --}}
    <!-- Fancybox -->
    <link rel="stylesheet" href="/web/css/jquery.fancybox.min.css" />
    <!-- Themify Icons -->
    <link rel="stylesheet" href="/web/css/themify-icons.css" />
    <!-- Nice Select CSS -->
    {{-- <link rel="stylesheet" href="/web/css/niceselect.css" /> --}}
    <!-- Animate CSS -->
    <link rel="stylesheet" href="/web/css/animate.css" />
    <!-- Flex Slider CSS -->
    <link rel="stylesheet" href="/web/css/flex-slider.min.css" />
    <!-- Owl Carousel -->
    <link rel="stylesheet" href="/web/css/owl-carousel.css" />
    <!-- Slicknav -->
    <link rel="stylesheet" href="/web/css/slicknav.min.css" />
		<!-- Sweetalert 2 -->
		<link rel="stylesheet" href="/web/plugins/sweetalert2/sweetalert2.min.css" />

    <!-- Eshop StyleSheet -->
    <link rel="stylesheet" href="/web/css/reset.css" />
    <link rel="stylesheet" href="/web/css/style.css" />
    <link rel="stylesheet" href="/web/css/responsive.css" />

		@yield('header')
		<style>
			.btn-custom{
				background-color: white;
				color: orange;
				border: 2px solid orange;
				width: 100%;
				padding: 5px 0;
			}
			.btn-custom:hover{
				background-color: orange;
				color: white;
			}
		</style>
  </head>
  <body class="js">
    <!-- Preloader -->
    <div class="preloader">
      <div class="preloader-inner">
        <div class="preloader-icon">
          <span></span>
          <span></span>
        </div>
      </div>
    </div>
    <!-- End Preloader -->

    <!-- Header -->
    <header class="header shop">
      <!-- Topbar -->
      <div class="topbar">
        <div class="container">
          <div class="row">
            <div class="col-lg-4 col-md-12 col-12"></div>
            <div class="col-lg-8 col-md-12 col-12">
              <!-- Top Right -->
              <div class="right-content">
                <ul class="list-main">
									@if ($user && $key)
										<li><i class="ti-user"></i> <a href="/user/{{ $user['id_user'] }}/profil">{{ $user['user'] }}</a></li>
										<li><i class="ti-power-off"></i><a href="/logout">Logout</a></li>
									@else
										<li><i class="ti-power-off"></i><a href="/login">Login</a></li>
									@endif
                </ul>
              </div>
              <!-- End Top Right -->
            </div>
          </div>
        </div>
      </div>
      <!-- End Topbar -->
      <div class="middle-inner">
        <div class="container">
          <div class="row">
            <div class="col-lg-2 col-md-2 col-12">
              <!-- Logo -->
              <div class="logo">
                <a href="{{ route('view.web.home') }}">susuKNTL Mart</a>
              </div>
              <!--/ End Logo -->
              <!-- Search Form -->
              <div class="search-top">
                <div class="top-search">
                  <a href="#0"><i class="ti-search"></i></a>
                </div>
                <!-- Search Form -->
                <div class="search-top">
                  <form class="search-form" action="/produk">
                    <input type="text" placeholder="Search here..." name="query" />
                    <button value="search" type="submit"><i class="ti-search"></i></button>
                  </form>
                </div>
                <!--/ End Search Form -->
              </div>
              <!--/ End Search Form -->
              <div class="mobile-nav"></div>
            </div>
            <div class="col-lg-8 col-md-7 col-12">
              <div class="search-bar-top">
                <div class="search-bar">
                  <form action="/produk">
                    <input name="query" placeholder="Search Products Here....." type="text" />
                    <button class="btnn"><i class="ti-search"></i></button>
                  </form>
                </div>
              </div>
            </div>
            <div class="col-lg-2 col-md-3 col-12">
              <div class="right-bar">
								@if ($user && $key)
									<div class="sinlge-bar shopping p-1">
										<a href="/user/{{ $user['id_user'] }}/pesanan" class="single-icon" style="color: orange"><i class="ti-archive"></i></a>
									</div>
									<div class="sinlge-bar">
										<a href="/user/{{ $user['id_user'] }}/wishlist" class="single-icon" style="color: orange"><i class="ti-heart" aria-hidden="true"></i></a>
										{{-- <a href="#" class="single-icon" style="color: orange"><i class="ti-bell" aria-hidden="true"></i></a> --}}
									</div>
									<div class="sinlge-bar shopping p-1">
										<a href="/user/{{ $user['id_user'] }}/keranjang" class="single-icon" style="color: orange"><i class="ti-bag"></i></a>
									</div>
								@endif
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Header Inner -->
      <div class="header-inner">
        <div class="container">
          <div class="cat-nav-head">
            <div class="row">
              <div class="col-12">
                <div class="menu-area">
									@include('components.web-navbar')
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!--/ End Header Inner -->
    </header>
    <!--/ End Header -->

		<div class="" style="min-height: 75vh">
			@yield('content')
		</div>

		
		@yield('modal')
		<div class="modal fade" id="modal">
			<div class="modal-dialog modal-xl">
				<div class="modal-content">
					<div class="modal-body"></div>
				</div>
			</div>
		</div>

    <footer class="footer">
			<div class="copyright">
        <div class="container">
          <div class="inner">
            <div class="row">
              <div class="col-12">
                <div class="" style="text-align: center">
                  <p>Copyright © 2022 S4mid - All Rights Reserved.</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </footer>

		{{-- OVERLAY --}}
		<div id="overlay" class="position-fixed justify-content-center align-items-center" style="top: 0; right: 0; left:0; bottom:0; background-color: rgba(71, 70, 70, 0.437); z-index:999999999; display: none">
			<i class="fas fa-spinner fa-pulse" style="font-size: 30px; color: white"></i>
		</div>

    <!-- Jquery -->
    <script src="/web/js/jquery.min.js"></script>
    <script src="/web/js/jquery-migrate-3.0.0.js"></script>
    <script src="/web/js/jquery-ui.min.js"></script>
    <!-- Popper JS -->
    <script src="/web/js/popper.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="/web/plugins/bootstrap4/js/bootstrap.js"></script>
    {{-- <script src="/web/js/bootstrap.min.js"></script> --}}
    <!-- Color JS -->
    <script src="/web/js/colors.js"></script>
    <!-- Slicknav JS -->
    <script src="/web/js/slicknav.min.js"></script>
    <!-- Owl Carousel JS -->
    <script src="/web/js/owl-carousel.js"></script>
    <!-- Magnific Popup JS -->
    <script src="/web/js/magnific-popup.js"></script>
    <!-- Waypoints JS -->
    <script src="/web/js/waypoints.min.js"></script>
    <!-- Countdown JS -->
    <script src="/web/js/finalcountdown.min.js"></script>
    <!-- Nice Select JS -->
    {{-- <script src="/web/js/nicesellect.js"></script> --}}
    <!-- Flex Slider JS -->
    <script src="/web/js/flex-slider.js"></script>
    <!-- ScrollUp JS -->
    <script src="/web/js/scrollup.js"></script>
    <!-- Onepage Nav JS -->
    <script src="/web/js/onepage-nav.min.js"></script>
    <!-- Easing JS -->
    <script src="/web/js/easing.js"></script>
    <!-- Active JS -->
    <script src="/web/js/active.js"></script>
		<!-- Sweetalert 2 -->
		<script src="/web/plugins/sweetalert2/sweetalert2.min.js"></script>
		@yield('footer')
		@yield('script')
  </body>
</html>
