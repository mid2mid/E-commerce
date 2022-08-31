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
						<li class="active">Promo</a></li>
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
	function contentHide() {
		$(".content-item").html("");
		$(".content-item").hide();
		$(".content-error").hide();
		$(".content-spinner").show();
	}
	function contentShow() {
		$(".content-error").hide();
		$(".content-spinner").hide();
		$(".content-item").show();
	}
	function contentError() {
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
	function appendPromo(response){
		return `
		<div class="col-12 my-2">
			<div class="card" style="border: 1px solid orange">
				<div class="card-body">
					<h5 class="card-title">${response.promo}</h5>
					<p class="card-text">Kode		 		: ${response.kode}</p>
					<p class="card-text">Max 				: ${formatUang(response.max)}</p>
					<p class="card-text">Potongan		: ${response.persen} %</p>
					<p class="card-text">Status 		: ${response.status}</p>
					<p class="card-text">Periode 		: ${new Date(response.publish_start * 1000).toISOString().slice(0, 10)} - ${new Date(response.publish_end * 1000).toISOString().slice(0, 10)}</p>
					<p class="card-text">Deskripsi	: ${response.deskripsi}</p>
				</div>
			</div>
		</div>`
	}
	function getPromo(){
		$.ajax({
			method: "get",
			url: `{{ route('v1.web.promo.index') }}`,
			headers: {
				'X-Requested-With': 'XMLHttpRequest',
			},
			beforeSend: function () {
				contentHide()
			},
			complete: function () {
			},
		})
		.done(function (response) {
			$('.content-item').html('')
			$.each(response.data, function (i, v) {
				$('.content-item').append(appendPromo(v))
			});
			contentShow()
		})
		.fail(function (jqXHR, status) {
			contentError()
		});
	}
	$(document).ready(function () {
		getPromo()
	});
	$(document).on('click', '.btn-refresh', function(){
		getPromo()
	})

</script>
@endsection