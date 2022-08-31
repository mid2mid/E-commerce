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
							<li class="active">Keranjang</a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- End Breadcrumbs -->

	<!-- Shopping Cart -->
	<div class="shopping-cart section">
		<div class="container">
			<div class="justify-content-end align-items-center" style="width: 100%; height: 100px; display: flex">
				<button class="btn-refresh btn-custom" style="max-width: 100px;">refresh</button>
			</div>
			<div class="justify-content-center align-items-center content-spinner" style="width: 100%; height: 100px; display: flex">
				<i class="fas fa-spinner fa-pulse" style="font-size: 30px"></i>
			</div>
			<div class="justify-content-center align-items-center content-error" style="width: 100%; height: 100px; display: none; flex-direction: column">
				<p style="font-weight: bold">Error !</p>
				<button class="btn-refresh btn-custom" style="max-width: 100px;">refresh</button>
			</div>
			<div class="row content-item" style="display: none">
				<div class="col-12">
					<table class="table shopping-summery">
						<thead>
							<tr class="main-hading">
								<th></th>
								<th>PRODUK</th>
								<th class="text-center">UNIT PRICE</th>
								<th class="text-center">QUANTITY</th>
								<th class="text-center">BERAT(GRAM)</th>
								<th class="text-center">TOTAL</th>
								<th class="text-center"><i class="ti-trash remove-icon"></i></th>
							</tr>
						</thead>
						<tbody></tbody>
					</table>
				</div>
				<div class="col-12">
					<div class="total-amount">
						<div class="row justify-content-end">
							<div class="col-6">
								<div class="right">
									<ul>
										<li>Total<span class="bill-total"></span></li>
										<li>Berat( gram )<span class="bill-berat"></span></li>
									</ul>
									<p class="text-danger">* belum termasuk ongkir</p>
									<div class="button5">
										<button class="btn btn-checkout">Checkout</button>
										<a href="/produk" class="btn">Continue shopping</a>
									</div>
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
	function keranjangHide() {
		$("tbody").html("");
		$(".content-item").hide();
		$(".content-error").hide();
		$(".content-spinner").css('display', 'flex');
	}
	function keranjangShow() {
		$(".content-error").hide();
		$(".content-spinner").hide();
		$(".content-item").show();
	}
	function keranjangError() {
		$(".content-item").hide();
		$(".content-spinner").hide();
		// $(".content-error").show();
		$(".content-error").css('display', 'flex');
	}
	function formatUang(number) {
		return new Intl.NumberFormat("id-ID", {
			style: "currency",
			currency: "IDR",
			maximumSignificantDigits: (number + "").replace(".", "").length,
		}).format(number);
	}
	function formatRibuan(number) {
    var parts = n.toString().split(",");
    const numberPart = parts[0];
    const decimalPart = parts[1];
    const thousands = /\B(?=(\d{3})+(?!\d))/g;
    return numberPart.replace(thousands, ".") + (decimalPart ? "," + decimalPart : "");
	}
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
	function total() {
		let total = 0;
		$(`tbody .total`).each(function (i, v) {
			total = parseInt($(this).data("total")) + total;
		});
		$(".bill-total").text(formatUang(total));
	}
	function berat() {
		let berat = 0;
		$(`tbody .berat`).each(function (i, v) {
			berat = parseInt($(this).text()) + berat;
		});
		$(".bill-berat").text(berat);
	}
	function appendKeranjang(res){
		return `
                <tr class="dt dt-${res.id_produk}" data-id="${res.id_produk}">
                  <td class="gambar" data-title="No"><img src="{{ asset('storage') }}/image/produk/${res.id_produk}/${res.cover}" /></td>
                  <td class="product-des" data-title="Description">
                    <p class="product-name nama"><a href="#">${res.produk}</a></p>
                  </td>
                  <td class="harga" data-title="harga"><span>${formatUang(res.harga)}</span></td>
                  <td class="qty" data-title="jumlah">${res.jumlah}</td>
                  <td class="berat" data-title="berat" data-berat="${res.berat}"><span>${res.berat}</span></td>
                  <td class="total" data-title="Total" data-total="${res.total_harga}"><span>${formatUang(res.total_harga)}</span></td>
                  <td class="action" data-title="Remove">
										<div class="row">
											<div class="col-12">
												<input type="number" name="jumlah" value="${res.jumlah}" style="width:100px;" class="input-jumlah" data-produk="${res.id_produk}">
											</div>
											<div class="col-12 mt-2">
												<button type="button" class="btn-custom p-2 btn-tambah" data-produk="${res.id_produk}" style="width:fit-content;"><i class="ti-plus remove-icon"></i></button>
												<button type="button" class="btn-custom p-2 btn-kurang" data-produk="${res.id_produk}" style="width:fit-content;"><i class="ti-minus remove-icon"></i></button>
												<button type="button" class="btn-custom p-2 btn-hapus " data-produk="${res.id_produk}" style="width:fit-content;"><i class="ti-trash remove-icon"></i></button>
											</div>
										</div>
										</td>
                </tr>`
	}

	function getKeranjang() {
		const user = $('meta[name="USER-ID"]').attr('content')
		$.ajax({
			method: "get",
			url: `{{ route('v1.user.keranjang.index') }}?user=${user}`,
			headers: {
				'X-Requested-With': 'XMLHttpRequest',
				'USER-KEY' : $('meta[name="USER-KEY"]').attr('content')
			},
			beforeSend: function () {
				keranjangHide()
			},
			complete: function () {
				// $(".btn-submit").prop("disabled", false);
			},
		})
		.done(function (response) {
			$.each(response.data.produk, function (i, v) {
				$('tbody').append(appendKeranjang(v))
			});
			$(".bill-total").text(formatUang(response.data.details.keranjang_harga));
			$(".bill-berat").text(response.data.details.keranjang_berat);
			keranjangShow()
		})
		.fail(function (jqXHR, status) {
			keranjangError()
		});
	}

	getKeranjang();
	$(document).on("click", ".btn-refresh", function (e) {
		getKeranjang();
	});

	$(document).on("click", ".btn-tambah", function (e) {
		$.ajax({
			method: "put",
			url: `{{ route('v1.user.keranjang.tambah') }}`,
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
				$(".btn-tambah").prop("disabled", true);
			},
			complete: function () {
				overlayHide()
				$(".btn-tambah").prop("disabled", false);
			},
		})
		.done(function (response) {
			getKeranjang()
			notif('berhasil menambahkan ke keranjang')
		})
		.fail(function (jqXHR, status) {
			getKeranjang()
			notif('gagal menambahkan ke keranjang', 'error')
		});
	});
	$(document).on("click", ".btn-kurang", function (e) {
		$.ajax({
			method: "put",
			url: `{{ route('v1.user.keranjang.kurang') }}`,
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
				$(".btn-kurang").prop("disabled", true);
			},
			complete: function () {
				overlayHide()
				$(".btn-kurang").prop("disabled", false);
			},
		})
		.done(function (response) {
			getKeranjang()
			notif('berhasil mengurangi ke keranjang')
		})
		.fail(function (jqXHR, status) {
			getKeranjang()
			notif('gagal mengurangi ke keranjang', 'error')
		});
	});

	// J U M L A H =====================================================================
	$(document).on("change", ".input-jumlah", function (e) {
		$.ajax({
			method: "put",
			url: `{{ route('v1.user.keranjang.set') }}`,
			headers: {
				'X-Requested-With': 'XMLHttpRequest',
				'USER-KEY' : $('meta[name="USER-KEY"]').attr('content')
			},
			data: {
				produk: $(this).data('produk'),
				user: $('meta[name="USER-ID"]').attr('content'),
				jumlah: $(this).val()
			},
			beforeSend: function () {
				overlayShow()
			},
			complete: function () {
				overlayHide()
			},
		})
		.done(function (response) {
			getKeranjang()
			notif('berhasil mengubah jumlah ke keranjang')
		})
		.fail(function (jqXHR, status) {
			getKeranjang()
			if(jqXHR.responseJSON){
				notif(jqXHR.responseJSON.message != 'failed' ? jqXHR.responseJSON.message  : 'gagal menambahkan kategori', 'error')
			}else{
				notif('gagal menambahkan kategori', 'error')
			}
		});
	});
	$(document).on("click", ".btn-hapus", function (e) {
		$.ajax({
			method: "delete",
			url: `{{ route('v1.user.keranjang.destroy') }}`,
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
				$(".btn-hapus").prop("disabled", true);
			},
			complete: function () {
				overlayHide()
				$(".btn-hapus").prop("disabled", false);
			},
		})
		.done(function (response) {
			getKeranjang()
			notif('berhasil menghapus ke keranjang')
		})
		.fail(function (jqXHR, status) {
			getKeranjang()
			notif('gagal menghapus ke keranjang', 'error')
		});
	});

	$(document).on("click", ".btn-checkout", function (e) {
		const user = $('meta[name="USER-ID"]').attr('content')
		$.ajax({
			method: "post",
			url: `{{ route('v1.user.checkout.tunggu') }}`,
			headers: {
				'X-Requested-With': 'XMLHttpRequest',
				'USER-KEY' : $('meta[name="USER-KEY"]').attr('content')
			},
			data: {
				user: user
			},
			beforeSend: function () {
				overlayShow()
				$(".btn-checkout").prop("disabled", true);
			},
			complete: function () {
				overlayHide()
				$(".btn-checkout").prop("disabled", false);
			},
		})
		.done(function (response) {
			if(response.status){
				window.location = response.data.link
			}
		})
		.fail(function (jqXHR, status) {
			notif('gagal checkout', 'error')
		});
	});
</script>
@endsection