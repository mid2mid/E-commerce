@extends('components.web-layout')

@section('content')
<!-- Open Content -->
<section class="bg-light">
	<div class="container pb-4">
		<div class="row">
			<div class="col-lg-5 mt-2">
				<div class="card mb-3">
					<img class="card-img img-fluid" src="{{ asset('storage') }}/image/produk/{{ $produk['id_produk'] }}/{{ $produk['cover'] }}" style="max-height: 450px; object-fit: contain;">
				</div>
			</div>
			<!-- col end -->
			<div class="col-lg-7 mt-2">
				<div class="card">
					<div class="card-body">
						<h1 class="h2">{{ $produk['produk'] }}</h1>
						<p class="h3 py-2">Rp {{ number_format($produk['harga'],0,",",".") }}</p>
						<h6 class="mb-2">Berat		: {{ $produk['berat'] }} gr</h6>
						<h6>Description:</h6>
						<p>{{ $produk['deskripsi'] }}</p>
						<button type="button" class="btn mt-5 btn-keranjang" data-produk="{{ $produk['id_produk'] }}">Tambah Keranjang</button>
						<button type="button" class="btn mt-5 btn-wishlist" data-produk="{{ $produk['id_produk'] }}">Tambah Wishlist</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
<!-- Close Content -->
@endsection

@section('header')
@endsection

@section('footer')
@endsection

@section('script')
<script>
	console.clear()
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
				beforeSend: function () {
					overlayShow()
					$(".btn-wishlist").prop("disabled", true);
				},
				complete: function () {
					overlayHide()
					$(".btn-wishlist").prop("disabled", false);
				},
			})
			.done(function (res) {
				notif("berhasil menambahkan ke wishlist", "success");
			})
			.fail(function (jqXHR, status) {
				notif('gagal menambahkan ke wishlist', 'error')
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
				beforeSend: function () {
					overlayShow()
					$(".btn-keranjang").prop("disabled", true);
				},
				complete: function () {
					overlayHide()
					$(".btn-keranjang").prop("disabled", false);
				},
			})
			.done(function (res) {
				notif("berhasil menambahkan ke keranjang", "success");
			})
			.fail(function (jqXHR, status) {
				notif('gagak menambahkan ke keranjang', 'error')
			});
		});
	</script>
@else
	<script>
		$(document).on("click", ".btn-wishlist, .btn-keranjang", function (e) {
			e.preventDefault();
			notif("Silakan Login Dulu", "error");
		});
	</script>
@endif
@endsection