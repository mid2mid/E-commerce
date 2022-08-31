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
						<li class="active">Pesanan</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End Breadcrumbs -->

<!-- Start Checkout -->
<div class="content-spinner">
	<div class="justify-content-center align-items-center" style="width: 100%; height: 100px; display: flex">
		<i class="fas fa-spinner fa-pulse" style="font-size: 30px"></i>
	</div>
</div>
<div class="content-error" style="display: none">
	<div class="justify-content-center align-items-center" style="width: 100%; height: 100px; display: flex; flex-direction: column">
		<p style="font-weight: bold">Pesanan Masih Kosong</p>
		<button class="btn-refresh btn-custom" style="max-width: 100px;">refresh</button>
	</div>
</div>
<div class="container">
	<div class="row my-3 content-item" style="display: none">
	</div>
</div>
<!--/ End Checkout -->
@endsection

@section('header')
@endsection

@section('footer')
@endsection

@section('modal')
<!-- Modal Info-->
<div class="modal fade" id="info-modal"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-body p-2" style="max-height: none;height: fit-content; overflow: hidden;">
				<div class="form-group row">
					<label class="col-sm-2 col-form-label">Pesanan</label>
					<div class="col-sm-10">
						<input type="text" readonly class="form-control-plaintext border-0" id="i-pesanan">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-2 col-form-label">Penerima</label>
					<div class="col-sm-10">
						<input type="text" readonly class="form-control-plaintext border-0 i-input" id="i-penerima">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-2 col-form-label">Alamat</label>
					<div class="col-sm-10">
						<input type="text" readonly class="form-control-plaintext border-0 i-input" id="i-alamat">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-2 col-form-label">Hp</label>
					<div class="col-sm-10">
						<input type="text" readonly class="form-control-plaintext border-0 i-input" id="i-hp">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-2 col-form-label">Status</label>
					<div class="col-sm-10">
						<input type="text" readonly class="form-control-plaintext border-0 i-input" id="i-status">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-2 col-form-label">Paket</label>
					<div class="col-sm-10">
						<input type="text" readonly class="form-control-plaintext border-0 i-input" id="i-paket">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-2 col-form-label">Nominal</label>
					<div class="col-sm-10">
						<input type="text" readonly class="form-control-plaintext border-0 i-input" id="i-nominal">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-2 col-form-label">Resi</label>
					<div class="col-sm-10">
						<input type="text" readonly class="form-control-plaintext border-0 i-input" id="i-resi">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-2 col-form-label">Payment Code</label>
					<div class="col-sm-10">
						<input type="text" readonly class="form-control-plaintext border-0 i-input" id="i-payment-code">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-2 col-form-label">Promo</label>
					<div class="col-sm-10">
						<input type="text" readonly class="form-control-plaintext border-0 i-input" id="i-promo">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-2 col-form-label">Payment Metode</label>
					<div class="col-sm-10">
						<input type="text" readonly class="form-control-plaintext border-0 i-input" id="i-payment-metode">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-2 col-form-label">Tgl Bayar</label>
					<div class="col-sm-10">
						<input type="text" readonly class="form-control-plaintext border-0 i-input" id="i-tgl-bayar">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Modal Produk-->
<div class="modal fade" id="produk-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-body" style="max-height: none;height: fit-content; overflow: hidden;">
				<ul class="list-group list-group-flush" id="produk-item">
				</ul>
			</div>
		</div>
	</div>
</div>
@endsection

@section('script')
<script>
	console.clear()
	function formatUang(number) {
		return new Intl.NumberFormat("id-ID", {
			style: "currency",
			currency: "IDR",
			maximumSignificantDigits: (number + "").replace(".", "").length,
		}).format(number);
	}
	function formatRibuan(number) {
		// return new Intl.NumberFormat("id-ID", {
		// 	style: "currency",
		// 	currency: "IDR",
		// 	maximumSignificantDigits: (number + "").replace(".", "").length,
		// }).format(number);
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
	function pesananHide() {
		$(".content-item").html("");
		$(".content-item").hide();
		$(".content-error").hide();
		$(".content-spinner").show();
	}
	function pesananShow() {
		$(".content-error").hide();
		$(".content-spinner").hide();
		$(".content-item").show();
	}
	function pesananError() {
		$(".content-item").hide();
		$(".content-spinner").hide();
		$(".content-error").show();
	}
	function infoShow(){
		$('.i-input').val('')
		$('#info-modal').modal('show')
	}
	function infoHide(){
		$('.i-input').val('')
		$('#info-modal').modal('hide')
	}
	function localTime(time){
		let a = Intl.DateTimeFormat().resolvedOptions()
		let b = new Date(time * 1000)
		return b.toLocaleString(a, { timeZone: a.timeZone })
	}
	function appendPesanan(response){
		return `
		<div class="col-12 my-2">
			<div class="card" style="border: 1px solid orange">
				<div class="card-body">
					<h5 class="card-title">${response.id_pesanan}</h5>
					<p class="card-text">Status 					: ${response.status}</p>
					<p class="card-text">Pembayaran				: ${response.status == 'settlement' ? 'Sudah Bayar' : 'Silakan Selesaikan Pemabayaran'}</p>
					<p class="card-text">Total						: ${formatUang(response.total)}</p>
					<p class="card-text">Resi							: ${response.resi ? response.resi : '-'}</p>
					<p class="card-text">Kode Transaksi		: ${response.payment_code ? response.payment_code :'-'}</p>
					<p class="card-text">Tgl Bayar		: ${response.settlement_time ? localTime(response.settlement_time) :'-'}</p>
					<div class="mt-2" style="display:flex">
						<button type="button" class="btn-custom mx-2 btn-info" style="width:fit-content; padding:5px 20px;" data-id="${response.id_pesanan}">details</button>
						<button type="button" class="btn-custom mx-2 btn-produk" style="width:fit-content; padding:5px 20px;" data-id="${response.id_pesanan}">lihat produk</button>
						${response.status === 'settlement' ? '<a href="/invoice?id='+response.id_pesanan+'" target="_blank" class="btn-custom mx-2" style="width:fit-content; padding:5px 20px;" data-id="'+response.id_pesanan+'">cetak invoice</a>' : ''}
					</div>
				</div>
			</div>
		</div>`
	}
	function getPesanan(){
		const user = $('meta[name="USER-ID"]').attr('content')
		$.ajax({
			method: "get",
			url: `{{ route('v1.user.pesanan.index') }}?user=${user}`,
			headers: {
				'X-Requested-With': 'XMLHttpRequest',
				'USER-KEY' : $('meta[name="USER-KEY"]').attr('content')
			},
			beforeSend: function () {
				pesananHide()
			},
			complete: function () {
			},
		})
		.done(function (response) {
			$('.content-item').html('')
			console.log(response)
			$.each(response.data, function (i, v) {
				$('.content-item').append(appendPesanan(v))
			});
			pesananShow()
		})
		.fail(function (jqXHR, status) {
			pesananError()
		});
	}
	$(document).ready(function () {
		getPesanan()
	});
	$(document).on('click', '.btn-refresh', function(){
		getPesanan()
	})

	$(document).on("click", ".btn-info", function (e) {
		const pesanan = $(this).data('id')
		$.ajax({
			method: "get",
			url: `{{ route('v1.user.pesanan.index') }}/${pesanan}`,
			headers: {
				'X-Requested-With': 'XMLHttpRequest',
				'USER-KEY' : $('meta[name="USER-KEY"]').attr('content')
			},
			beforeSend: function () {
				overlayShow()
				$(".btn-info").prop("disabled", true);
			},
			complete: function () {
				overlayHide()
				$(".btn-info").prop("disabled", false);
			},
		})
		.done(function (response) {
			response = response.data
			infoShow()
			$('#i-pesanan').val(response.id_pesanan)
			$('#i-penerima').val(response.penerima.penerima)
			$('#i-alamat').val(response.penerima.alamat)
			$('#i-hp').val(response.penerima.hp)
			$('#i-paket').val(response.ongkir.paket)
			$('#i-nominal').val(formatUang(response.gross_amount))
			$('#i-status').val(response.status)
			$('#i-resi').val(response.resi)
			$('#i-payment-code').val(response.payment_code)
			$('#i-payment-metode').val(response.metode)
			$('#i-tgl-bayar').val(response.settlement_time ? localTime(response.settlement_time) :'-')
		})
		.fail(function (jqXHR, status) {
			notif('error', 'error')
		});
	});

	// P R O D U K ===============================================================================================
	$(document).on("click", ".btn-produk", function (e) {
		const pesanan = $(this).data('id')
		$.ajax({
			method: "get",
			url: `{{ route('v1.user.pesanan.index') }}/${pesanan}/produk`,
			headers: {
				'X-Requested-With': 'XMLHttpRequest',
				'USER-KEY' : $('meta[name="USER-KEY"]').attr('content')
			},
			beforeSend: function () {
				overlayShow()
				$(".btn-produk").prop("disabled", true);
			},
			complete: function () {
				overlayHide()
				$(".btn-produk").prop("disabled", false);
			},
		})
		.done(function (response) {
			$('#produk-modal #produk-item').html('')
			$.each(response.data, function(i,v){
				$('#produk-modal #produk-item').append(`<li class="list-group-item">( ${v.jumlah} ) ${v.produk}</li>`)
			})
			$('#produk-modal').modal('show')
		})
		.fail(function (jqXHR, status) {
			notif('error', 'error')
		});
	});
	$('#produk-modal').on('hide.bs.modal', function (event) {
			$('#produk-modal #produk-item').html('')
	})
</script>
@endsection