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
						<li class="active">Checkout</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End Breadcrumbs -->

<!-- Start Checkout -->
<section class="shop checkout section">
	<div class="container">
		<form class="form" id="checkout-form">
			<div class="row">
				<div class="col-lg-8 col-12">
					<div class="checkout-form">
						<div class="row">
							<input type="hidden" name="checkout" value="{{ $checkout }}">
							<input type="hidden" name="email" value="{{ $user['email'] }}">
							<div class="col-12">
								<div class="form-group">
									<label>Penerima<span>*</span></label>
									<input type="text" name="penerima" id="c-penerima" placeholder="" required="required" value="{{ $user['user'] }}"/>
								</div>
							</div>
							<div class="col-12">
								<div class="form-group">
									<label>No Hp<span>*</span></label>
									<input type="number" name="hp" id="c-hp" placeholder="" required="required"/>
								</div>
							</div>
							<div class="col-12">
								<div class="form-group">
									<label>Alamat<span>*</span></label>
									<input type="text" name="alamat" id="c-alamat" placeholder="" required="required"/>
								</div>
							</div>
							<div class="col-12">
								<div class="form-group">
									<label>Kurir<span>*</span></label>
									<select name="kurir" id="c-kurir" class="custom-select">
										<option value="jne" selected>JNE</option>
										<option value="pos">POS</option>
										<option value="tiki">Tiki</option>
									</select>
								</div>
							</div>
							<div class="col-12">
								<div class="form-group">
									<label>Provinsi<span>*</span></label>
									<select name="provinsi" id="c-provinsi" class="custom-select">
									</select>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-12">
								<div class="form-group">
									<label>Kota<span>*</span></label>
									<select name="kota" id="c-kota" class="custom-select" disabled>
									</select>
								</div>
							</div>
							<div class="col-lg-6 col-md-6 col-12">
								<div class="form-group">
									<label>Kode Pos<span>*</span></label>
									<input type="text" required="required" id="c-pos" disabled/>
								</div>
							</div>
							<div class="col-12">
								<div class="form-group">
									<label>Paket<span>*</span></label>
									<select name="paket" id="c-paket" class="custom-select">
									</select>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-4 col-12">
					<div class="coupon form">
						<div class="form-group" style="display: flex; margin-bottom: 0">
							<input placeholder="Enter Your Coupon" class="mr-3" name="promo" id="c-promo" style="text-transform: uppercase;border: 1px solid black" />
							<button type="button" class="btn" id="btn-promo">Apply</button>
						</div>
						<small class="form-text text-danger"><a href="/promo" target="_blank">lihat promo</a></small>
					</div>
					<div class="order-details">
						<div class="single-widget">
							<h2>CART TOTALS</h2>
							<div class="content">
								<ul>
									<li>Sub Total<span id="b-harga" data-val="">Rp 0</span></li>
									<li>Ongkir<span id="b-ongkir"data-val="">Rp 0</span></li>
								</ul>
								<ul>
									<li class="last subtotal">Subtotal<span id="b-sub-total" data-val="">Rp 0</span></li>
									<li class="diskon">Diskon<span id="b-promo" data-val="">Rp 0</span></li>
									<li class="last total">Total<span id="b-total" data-val="">Rp 0</span></li>
								</ul>
							</div>
							<div class="single-widget get-button">
								<div class="content">
									<div class="button">
										<button type="submit" class="btn">proceed to checkout</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</section>
<!--/ End Checkout -->
@endsection

@section('header')
<script src="{{ $midtrans['snap'] }}" data-client-key="{{ $midtrans['key'] }}"></script>
@endsection

@section('footer')
@endsection

@section('script')
<script>
	console.clear()
	let bill = {
		harga: 0,
		ongkir:0,
		promo:0
	}
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

	function getCheckout() {
		const user = $('meta[name="USER-ID"]').attr('content')
		const checkout = "{{ $checkout }}"
		$.ajax({
			method: "get",
			url: `{{ route('v1.user.checkout.index') }}?user=${user}&checkout=${checkout}`,
			headers: {
				'X-Requested-With': 'XMLHttpRequest',
				'USER-KEY' : $('meta[name="USER-KEY"]').attr('content')
			},
			beforeSend: function () {
				// keranjangHide()
				// overlayShow()
			},
			complete: function () {
				// overlayHide()
				// $(".btn-submit").prop("disabled", false);
			},
		})
		.done(function (response) {
			response = response.data
			$('#b-harga').text(formatUang(response.bill.harga))
			$('#b-ongkir').text(formatUang(response.bill.ongkir))
			$('#b-sub-total').text(formatUang(response.bill.subtotal))
			$('#b-promo').text(formatUang(response.bill.promo))
			$('#b-total').text(formatUang(response.bill.total))
			bill.harga = response.bill.harga
		})
		.fail(function (jqXHR, status) {
			notif('Error', 'error')
		});
	}
	function getProvinsi(id = ''){
		$.ajax({
			method: "get",
			url: `{{ route('v1.user.rajaongkir.provinsi') }}`,
			headers: {
				'X-Requested-With': 'XMLHttpRequest',
				'USER-KEY' : $('meta[name="USER-KEY"]').attr('content')
			},
			beforeSend: function () {
				$('#c-provinsi').html('')
				$('#c-provinsi').prop("disabled", true)
			},
			complete: function () {
				// $(".btn-submit").prop("disabled", false);
			},
		})
		.done(function (response) {
			$('#c-provinsi').prop("disabled", false)
			$('#c-provinsi').append(`<option selected>Pilih Provinsi ...</option>`)
			$.each(response.data, function (i, v) {
				$('#c-provinsi').append(`<option value="${v.province_id}">${v.province}</option>`)
			});
		})
		.fail(function (jqXHR, status) {
			notif('Error', 'error')
		});
	}
	
	getProvinsi();
	getCheckout();

	$(document).on("change", "#c-provinsi", function (e) {
		const provinsi =  $(this).val()
		$.ajax({
			method: "get",
			url: `{{ route('v1.user.rajaongkir.kota') }}`,
			headers: {
				'X-Requested-With': 'XMLHttpRequest',
				'USER-KEY' : $('meta[name="USER-KEY"]').attr('content')
			},
			data: {
				province_id: provinsi
			},
			beforeSend: function () {
				overlayShow()
				$('#c-kota').html('')
				$('#c-kota').prop("disabled", true)
				$("#c-pos").prop("disabled", false).val('').prop("disabled", true);
				$("#c-paket").html('')
			},
			complete: function(){
				overlayHide()
			},
		})
		.done(function (response) {
			$('#c-kota').prop("disabled", false)
			$('#c-kota').append(`<option selected>Pilih Kota ...</option>`)
			$.each(response.data, function (i, v) {
				$('#c-kota').append(`<option value="${v.city_id}" data-pos="${v.postal_code}">${v.type} ${v.city_name}</option>`)
			});
			bill.ongkir = 0
			billRefersh()
		})
		.fail(function (jqXHR, status) {
			notif('error', 'error')
		});
	});
	$(document).on("change", "#c-kota", function (e) {
		$("#c-pos").prop("readonly", false).val($( "#c-kota option:selected" ).data('pos')).prop("readonly", true);
		$.ajax({
			method: "get",
			url: `{{ route('v1.user.rajaongkir.cost') }}`,
			headers: {
				'X-Requested-With': 'XMLHttpRequest',
				'USER-KEY' : $('meta[name="USER-KEY"]').attr('content')
			},
			data: {
				city_id: $(this).val(),
				courier: $('#c-kurir').val(),
				checkout: "{{ $checkout }}"
			},
			beforeSend: function () {
				overlayShow()
			},
			complete: function(){
				overlayHide()
			},
		})
		.done(function (response) {
			$('#c-paket').html('')
			$('#c-paket').append(`<option value="" data-pos="">Pilih Paket ...</option>`)
			$.each(response.data, function (i, v) {
				$('#c-paket').append(`<option value="${v.paket}" data-harga="${v.harga}">${v.paket}</option>`)
			});
			bill.ongkir = 0
			billRefersh()
		})
		.fail(function (jqXHR, status) {
			notif('Error', 'error')
		});
	});
	function billRefersh(){
		$('#b-harga').text(formatUang(bill.harga))
		$('#b-ongkir').text(formatUang(bill.ongkir))
		const sub = bill.harga + bill.ongkir
		$('#b-sub-total').text(formatUang(sub))
		$('#b-promo').text(formatUang(bill.promo))
		const total = sub - bill.promo
		$('#b-total').text(formatUang(total))
	}
	$(document).on("change", "#c-paket", function (e) {
		bill.ongkir = parseInt($("#c-paket option:selected").data('harga'))
		billRefersh()
	});
	$(document).on("change", "#c-kurir", function (e) {
		// const harga = $("#c-paket option:selected").data('harga')
		// $('#b-ongkir').text(formatUang(harga)).data('val', harga)
	});
	$(document).on("click", "#btn-promo", function (e) {
		$.ajax({
			method: "get",
			url: `{{ route('v1.user.checkout.promo') }}`,
			headers: {
				'X-Requested-With': 'XMLHttpRequest',
				'USER-KEY' : $('meta[name="USER-KEY"]').attr('content')
			},
			data: {
				promo: $('#c-promo').val(),
			},
			beforeSend: function () {
				overlayShow()
				$("#btn-promo").prop("disabled", true);
			},
			complete: function () {
				overlayHide()
				$("#btn-promo").prop("disabled", false);
			},
		})
		.done(function (response) {
			notif('promo dapat dipakai')
			let	sub = bill.harga + bill.ongkir 
			let a = parseInt((sub * response.data.persen)/100 )
			if(a > response.data.max){
				bill.promo = response.data.max
			}else{
				bill.promo = a
			}
			billRefersh()
			$('#btn-promo').css('display', 'none')
			$('#c-promo').attr('name', 'promo')
		})
		.fail(function (jqXHR, status) {
			notif('promo tidak tersedia', 'error')
		});
	});
	$('#c-promo').on('change', function(e){
		e.preventDefault()
		$(this).removeAttr('name')
		$('#btn-promo').css('display', 'block')
		bill.promo = 0
		billRefersh()
	})
	$(document).on("submit", "#checkout-form", function (e) {
		e.preventDefault();
		$.ajax({
			method: "post",
			url: `{{ route('v1.user.checkout.store') }}`,
			headers: {
				'X-Requested-With': 'XMLHttpRequest',
				'USER-KEY' : $('meta[name="USER-KEY"]').attr('content')
			},
			data: $(this).serialize(),
			beforeSend: function () {
				overlayShow()
			},
			complete: function () {
				overlayHide()
			},
		})
		.done(function (response) {
			window.snap.pay(response.data.token, {
					// Optional
					onSuccess: function(result) {
						window.location = `{{ route("view.web.pesanan", ['user' => $user['id_user']]) }}`
					},
					// Optional
					onPending: function(result) {
    					window.location = `{{ route("view.web.pesanan", ['user' => $user['id_user']]) }}`
					},
					// Optional
					onError: function(result) {
					}
			});
		})
		.fail(function (jqXHR, status) {
			notif(jqXHR.responseJSON ? jqXHR.responseJSON.message : 'error' , 'error')
		});
	});
</script>
@endsection