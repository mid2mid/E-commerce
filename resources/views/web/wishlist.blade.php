@extends('components.web-layout')

@section('content')
<!-- Breadcrumbs -->
<div class="breadcrumbs">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<div class="bread-inner">
					<ul class="bread-list">
						<li>
							<a href="/">Home<i class="ti-arrow-right"></i></a>
						</li>
						<li class="active"><a href="#">Whishlist</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End Breadcrumbs -->
{{-- main content --}}
<div class="container">
	<div class="justify-content-center align-items-center wishlist-spinner" style="width: 100%; height: 100px; display: flex">
		<i class="fas fa-spinner fa-pulse" style="font-size: 30px"></i>
	</div>
	<div class="justify-content-center align-items-center wishlist-error" style="width: 100%; height: 100px; display: flex; flex-direction: column">
		<p style="font-weight: bold">Error !</p>
		<button class="btn-refresh btn-custom" style="max-width: 100px;">refresh</button>
	</div>
	<div class="row my-3 wishlist-item"  style="display: none">
	</div>
</div>
{{-- end main content --}}
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
	function wishlistHide() {
		$(".wishlist-item").html("");
		$(".wishlist-item").hide();
		$(".wishlist-error").hide();
		$(".wishlist-spinner").show();
	}
	function wishlistShow() {
		$(".wishlist-error").hide();
		$(".wishlist-spinner").hide();
		$(".wishlist-item").show();
	}
	function wishlistError() {
		$(".wishlist-item").hide();
		$(".wishlist-spinner").hide();
		$(".wishlist-error").show();
	}
	function notif(title, icon = 'success'){
		Swal.fire({
			icon: icon,
			title: title,
			timer: 2000,
		})
	}
	function overlayShow(){
		$('#overlay').css('display', 'flex')
	}
	function overlayHide(){
		$('#overlay').css('display', 'none')
	}

	function wishlistCard(res) {
		return `
			<div class="col-12 my-2 position-relative">
				<a href="#">
					<div class="card">
						<div class="row align-items-center">
							<div class="col-auto ml-2">
								<img src="{{ asset('storage') }}/image/produk/${res.id_produk}/${res.cover}" class="d-block" style="width: 70px; height: 70px; object-fit: cover" />
							</div>
							<div class="col">
								<div class="card-body">
									<h5 class="card-title">${res.produk}</h5>
								</div>
							</div>
						</div>
					</div>
				</a>
				<div class="position-absolute" style="top: -10px; right: 0">
					<button type="button" class="bg-danger rounded-circle p-2 btn-hapus" data-produk="${res.id_produk}"><i class="ti-trash text-white"></i></button>
				</div>
			</div>`
	}

	function getWishlist() {
		const user = $('meta[name="USER-ID"]').attr('content')
		$.ajax({
			method: "get",
			url: `{{ route('v1.user.wishlist.index') }}?user=${user}`,
			headers: {
				'X-Requested-With': 'XMLHttpRequest',
				'USER-KEY' : $('meta[name="USER-KEY"]').attr('content')
			},
			beforeSend: function () {
				wishlistHide()
			},
			complete: function () {
				// $(".btn-submit").prop("disabled", false);
			},
		})
		.done(function (response) {
			$.each(response.data, function (i, v) {
				$('.wishlist-item').append(wishlistCard(v))
			});
			wishlistShow();
		})
		.fail(function (jqXHR, status) {
			wishlistError()
		});
	}

	$(document).ready(function () {
		getWishlist()
	});
	$(document).on("click", ".btn-refresh", function (e) {
		getWishlist()
	});
	$(document).on("click", ".btn-hapus", function (e) {
		e.preventDefault();
		const produk = $(this).parent().parent().find('.card-title').text()
		Swal.fire({
				title: `hapus wishlist`,
				html: `Apakah yakin ingin wishlist ini ?<span class="badge badge-danger d-block">${produk}</span>`,
				showDenyButton: true,
				confirmButtonText: "Ya",
				denyButtonText: `Tidak`,
			}).then((result) => {
				if (result.isConfirmed) {
					$.ajax({
						method: "delete",
						url: `{{ route('v1.user.wishlist.index') }}`,
						headers: {
							'X-Requested-With': 'XMLHttpRequest',
							'USER-KEY' : $('meta[name="USER-KEY"]').attr('content')
						},
						data: {
							produk: $(this).data('produk'),
							user: $('meta[name="USER-ID"]').attr('content')
						},
						beforeSend: function () {
							overlayShow()
						},
						complete: function () {
							overlayHide()
						},
					})
					.done(function (response) {
						getWishlist()
						notif("berhasil menghapus wishlist");
					})
					.fail(function (jqXHR, status) {
						notif("gagal menghapus wishlist");
					});
				}
			});
	});
</script>
@endsection