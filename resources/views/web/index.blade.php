@extends('components.web-layout')

@section('content')
<!-- Start Most Popular -->
<div class="product-area most-popular section">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="section-title btn-refresh">
					<h2>Best Product</h2>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-12">
				<div class="justify-content-center align-items-center rekomen-spinner" style="width: 100%; height: 100px; display: flex">
					<i class="fas fa-spinner fa-pulse" style="font-size: 30px"></i>
				</div>
				<div class="rekomen-item" style="display: none">
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End Most Popular Area -->

<!-- Start Shop Services Area -->
<section class="shop-services section home">
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-lg-3 col-md-6 col-12">
				<!-- Start Single Service -->
				<div class="single-service">
					<i class="ti-lock"></i>
					<h4>Sucure Payment</h4>
					<p>100% secure payment</p>
				</div>
				<!-- End Single Service -->
			</div>
			<div class="col-lg-3 col-md-6 col-12">
				<!-- Start Single Service -->
				<div class="single-service">
					<i class="ti-tag"></i>
					<h4>Best Peice</h4>
					<p>Guaranteed price</p>
				</div>
				<!-- End Single Service -->
			</div>
		</div>
	</div>
</section>
<!-- End Shop Services Area -->

<!-- Start Product Area -->
<div class="product-area section">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="section-title">
					<h2>Latest Product</h2>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-12">
				<div class="product-info">
					<div class="justify-content-center align-items-center content-spinner" style="width: 100%; height: 100px; display: flex">
						<i class="fas fa-spinner fa-pulse" style="font-size: 30px"></i>
					</div>
					<div class="tab-content" id="myTabContent">
						<!-- Start Single Tab -->
						<div class="tab-pane fade show active" role="tabpanel">
							<div class="tab-single">
								<div class="row content-item"></div>
							</div>
						</div>
						<!--/ End Single Tab -->
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End Product Area -->
@endsection

@section('header')
@endsection

@section('footer')
@endsection

@section('modal')
@endsection

@section('script')
<script>
	console.clear()
	function produkHide() {
		$(".content-item").html("");
		$(".content-item").hide();
		$(".content-spinner").show();
	}
	function produkShow() {
		$(".content-item").show();
		$(".content-spinner").hide();
	}
	function rekomenHide() {
		$(".rekomen-item").html("");
		$(".rekomen-item").hide();
		$(".rekomen-spinner").show();
	}
	function rekomenShow() {
		$(".rekomen-item").show();
		$(".rekomen-spinner").hide();
	}
	function formatUang(number) {
		return new Intl.NumberFormat("id-ID", {
			style: "currency",
			currency: "IDR",
			maximumSignificantDigits: (number + "").replace(".", "").length,
		}).format(number);
	}
	function notifikasi(title, icon) {
		Swal.fire({
			title: title,
			icon: icon,
			timer: 1000,
		});
	}
	function overlayShow(){
		$('#overlay').css('display', 'flex')
	}
	function overlayHide(){
		$('#overlay').css('display', 'none')
	}
	function produkCard(res) {
		return `
				<div class="card p-2" style="width: 100%; border:1px solid orange" data-id="${res.id_produk}">
					<img src="{{ asset('storage') }}/image/produk/${res.id_produk}/${res.cover}" class="card-img-top" style="height:300px" />
					<div class="card-body">
						<h6 class="card-text">${res.produk}</h6>
						<p class="card-number text-center">${formatUang(res.harga)}</p>
						<a href="/produk/${res.id_produk}" class="btn-custom mt-1" style="display:block;text-align:center">details</a>
						<div class="d-flex justify-content-around mt-2">
							<button type="button" class="btn-custom btn-wishlist" data-produk="${res.id_produk}" title="Masukkan ke whishlist" style="width:75px"><i class="ti-heart"></i></button>
							<button type="button" class="btn-custom btn-keranjang" data-produk="${res.id_produk}" title="Masukkan ke keranjang" style="width:75px"><i class="ti-bag"></i></button>
						</div>
					</div>
				</div>`;
	}
	function getProduk() {
		$.ajax({
			method: "get",
			url: "{{ route('v1.web.produk.index') }}",
			headers: {
				'X-Requested-With': 'XMLHttpRequest',
			},
			beforeSend: function () {
				produkHide();
			},
			complete: function () {
				// $(".btn-submit").prop("disabled", false);
			},
		})
		.done(function (res) {
			$.each(res.data, function (i, v) {
				$(".content-item").append(`<div class="col-xl-3 col-lg-4 col-md-4 col-12 p-3">${produkCard(v)}</div>`)
			});
			$(".content-item").append(`<div class="col-12 justify-content-center align-items-center content-page" style="height: 100px; display: flex"><a href="/produk" class="btn-custom text-center" style="max-width: 100px;">More Produk</a></div>`)
			produkShow()
		})
		.fail(function (jqXHR, status) {
			notif('produk tidak ada', 'error')	
		});
	}
	function getRekomen() {
		rekomenHide();
		$.ajax({
			url: "{{ route('v1.web.produk.rekomendasi') }}",
			type: "get",
			headers: {
				"X-Requested-With": "XMLHttpRequest",
			},
			beforeSend: function () {
				rekomenHide()
			},
		})
		.done(function (res) {
			$.each(res.data, function (i, v) {
				$(".rekomen-item").append(produkCard(v))
			});
			$(".rekomen-item").owlCarousel({
				items: 1,
				autoplay: true,
				autoplayTimeout: 5000,
				smartSpeed: 400,
				animateIn: "fadeIn",
				animateOut: "fadeOut",
				autoplayHoverPause: true,
				loop: true,
				nav: true,
				merge: true,
				dots: false,
				navText: ['<i class="ti-angle-left"></i>', '<i class="ti-angle-right"></i>'],
				responsive: {
					0: {
						items: 1,
					},
					300: {
						items: 1,
					},
					480: {
						items: 2,
					},
					768: {
						items: 3,
					},
					1170: {
						items: 4,
					},
				},
			});
			rekomenShow()
		})
		.fail(function (jqXHR, status) {
			notif('produk tidak ada', 'error')	
		});
	}

	getRekomen();
	getProduk();
	$(document).on('cilck','.btn-refesh',function (e) {
		getRekomen();
	});
</script>
@if ($user && $key)
	<script>
		$(document).on("click", ".btn-wishlist", function (e) {
			e.preventDefault();
			const produk = $(this).data('produk')
			$.ajax({
				url: "{{ route('v1.user.wishlist.store') }}",
				type: "post",
				headers: {
					"X-Requested-With": "XMLHttpRequest",
					'USER-KEY' : $('meta[name="USER-KEY"]').attr('content')
				},
				data: {
					produk: produk,
					user: $('meta[name="USER-ID"]').attr('content')
				},
				beforeSend: function(){
					overlayShow()
					$(".btn-wishlist").prop("disabled", true);
				},
				complete: function(){
					overlayHide()
					$(".btn-wishlist").prop("disabled", false);
				},
			})
			.done(function (res) {
				notifikasi("berhasil menambahkan ke wishlist", "success");
			})
			.fail(function (jqXHR, status) {
				notifikasi("gagal menambahkan ke wishlist", "error");
			});
		});
		$(document).on("click", ".btn-keranjang", function (e) {
			e.preventDefault();
			const produk = $(this).data('produk')
			$.ajax({
				url: "{{ route('v1.user.keranjang.store') }}",
				type: "post",
				headers: {
					"X-Requested-With": "XMLHttpRequest",
					'USER-KEY' : $('meta[name="USER-KEY"]').attr('content')
				},
				data: {
					produk: produk,
					user: $('meta[name="USER-ID"]').attr('content')
				},
				beforeSend: function(){
					overlayShow()
					$(".btn-keranjang").prop("disabled", true);
				},
				complete: function(){
					overlayHide()
					$(".btn-keranjang").prop("disabled", false);
				},
			})
			.done(function (res) {
				notifikasi("berhasil menambahkan ke keranjang", "success");
			})
			.fail(function (jqXHR, status) {
				notifikasi("gagal menambahkan ke keranjang", "error");
			});
		});
	</script>
@else
	<script>
		$(document).on("click", ".btn-wishlist, .btn-keranjang", function (e) {
			e.preventDefault();
			notifikasi("Silakan Login Dulu", "error");
			// alert($(this).data("id"));
		});
	</script>
@endif
@endsection