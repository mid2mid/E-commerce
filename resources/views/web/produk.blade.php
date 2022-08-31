@extends('components.web-layout')

@section('content')
<div class="product-area section">
	<div class="container">
		<div class="row">
			@if ($search)
				<div class="col-12 mb-3 top rounded" id="info" style="max-width: 1200px">
					<h5><i class="bi bi-info-square"></i> Hasil pencarian untuk '<span class="text-danger">{{$search}}</span>'</h5>
				</div>
			@endif
			<div class="col-12 mb-3 top rounded p-2" id="filter" style="max-width: 1200px; background-color: rgb(211, 207, 207)">
				{{-- <button type="button" class="btn-custom" style="max-width: 100px"><i class="bi bi-funnel"></i> Filter</button> --}}
			</div>
			<div class="col-12">
				<div class="product-info">
					<div class="justify-content-center align-items-center content-spinner" style="width: 100%; height: 100px; display: flex">
						<i class="fas fa-spinner fa-pulse" style="font-size: 30px"></i>
					</div>
					<div class="justify-content-center align-items-center content-error" style="width: 100%; height: 100px; display: flex; flex-direction: column">
						<p style="font-weight: bold" class='error-text'>Error !</p>
						<button class="btn-refresh btn-custom" style="max-width: 100px;">refresh</button>
					</div>
					<div class="tab-content" id="myTabContent">
						<div class="tab-pane fade show active" role="tabpanel">
							<div class="tab-single">
								<div class="row content-item"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('header')
@endsection

@section('footer')
@endsection

@section('script')
<script>
	console.clear()
	function produkHide() {
		$(".content-item").html("");
		$(".content-item").hide();
		$(".content-error").hide();
		$(".content-spinner").show();
	}
	function produkShow() {
		$(".content-error").hide();
		$(".content-spinner").hide();
		$(".content-item").show();
	}
	function produkError() {
		$(".content-item").hide();
		$(".content-spinner").hide();
		$(".content-error").show();
	}
	function formatUang(number) {
		return new Intl.NumberFormat("id-ID", {
			style: "currency",
			currency: "IDR",
			maximumSignificantDigits: (number + "").replace(".", "").length,
		}).format(number);
	}
	function notif(title, icon = 'success'){
		Swal.fire({
			icon: icon,
			title: title,
			timer: 2000,
		})
	}
	function produkCard(res) {
		return `
				<div class="card p-2" style="width: 100%; border:1px solid orange" data-id="${res.id_produk}">
					<img src="{{ asset('storage') }}/image/produk/${res.id_produk}/${res.cover}" class="card-img-top" style="height:300px" />
					<div class="card-body">
						<h6 class="card-text">${res.produk}</h6>
						<p class="card-number text-center">${formatUang(res.harga)}</p>
						<a href="/produk/${res.id_produk}" class="btn-custom mt-1"  style="display:block;text-align:center">details</a>
						<div class="d-flex justify-content-around mt-2">
							<button type="button" class="btn-custom btn-wishlist" data-produk="${res.id_produk}" title="Masukkan ke whishlist" style="width:75px"><i class="ti-heart"></i></button>
							<button type="button" class="btn-custom btn-keranjang" data-produk="${res.id_produk}" title="Masukkan ke keranjang" style="width:75px"><i class="ti-bag"></i></button>
						</div>
					</div>
				</div>`;
	}
	function produkPage(next, prev){
		let html = `<div class="col-12 justify-content-center align-items-center content-page" style="height: 100px; display: flex">`
		if(prev != null){
			html = html + `<a href="${prev}" class="btn-custom mx-2 text-center" style="max-width: 100px;">Prev</a>`
		}
		if(next != null){
			html = html + `<a href="${next}" class="btn-custom mx-2 text-center" style="max-width: 100px;">Next</a>`
		}
		html += `</div>`
		return html
	}
	function overlayShow(){
		$('#overlay').css('display', 'flex')
	}
	function overlayHide(){
		$('#overlay').css('display', 'none')
	}

	function getProduk() {
		$.ajax({
			method: "get",
			url: "{{ route('v1.web.produk.index') }}?{!! $param !!}&orderBy=produk&sort=asc",
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
			if(res.data){
				$.each(res.data, function (i, v) {
					$(".content-item").append(`<div class="col-xl-3 col-lg-4 col-md-4 col-12 p-3">${produkCard(v)}</div>`)
				});
				$(".content-item").append(produkPage(res.next, res.prev)) 
				produkShow()
			}else{
				$('.error-text').text('Result Not Found')
				produkError();
			}
		})
		.fail(function (jqXHR, status) {
			produkError()
		});
	}

	getProduk();
	$(document).ready(function () {
	});
	$(document).on("click", ".btn-refresh", function (e) {
		getProduk();
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
				notif("berhasil menambahkan ke wishlist", "success");
			})
			.fail(function (jqXHR, status) {
				notif("gagal menambahkan ke wishlist", "error");
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
				notif("berhasil menambahkan ke keranjang", "success");
			})
			.fail(function (jqXHR, status) {
				notif("gagal menambahkan ke keranjang", "error");
			});
		});
	</script>
@else
	<script>
		$(document).on("click", ".btn-wishlist, .btn-keranjang", function (e) {
			e.preventDefault();
			notif("Silakan Login Dulu", "error");
			// alert($(this).data("id"));
		});
	</script>
@endif
@endsection