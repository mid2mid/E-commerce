@extends('components.admin-layout')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-12 mb-3">
			<div class="card">
				<div class="card-body">
					<div class="d-flex justify-content-end mb-3">
						<button class="btn btn-sm btn-outline-primary btn-refresh">Refresh</button>
					</div>
					<div class="justify-content-center align-items-center spinner-table" style="display: flex">
						<i class="fas fa-spinner fa-pulse" style="font-size: 30px"></i>
					</div>
					<div class="justify-content-center align-items-center error-table" style="display: none">
						<p>Not Found</p>
					</div>
					<div id="table-wrapper" style="display: none">
					</div>
				</div>
				<!-- /.card-body -->
			</div>
			<!-- /.card -->
		</div>
	</div>
</div>
@endsection

@section('modal')
<!-- Modal Update-->
<div class="modal fade" id="update-modal" data-backdrop="static"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Update Kupon</h5>
			</div>
			<div class="modal-body">
				<form action="#" method="post" id="update-form">
					<input type="hidden" id="u-id">
					<div class="form-group mb-3">
						<label for="resi">Resi</label>
						<input type="text" class="form-control" id="u-resi" name="resi" required/>
						<small class="form-text eu eu-resi text-danger"></small>
					</div>
					<div class="d-flex">
						<button type="submit" class="btn btn-primary px-5 update-submit">Update</button>
						<button type="button" class="btn btn-secondary ml-auto update-close">Close</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- Modal Info-->
<div class="modal fade" id="info-modal"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">info Pesanan</h5>
			</div>
			<div class="modal-body">
				<div class="form-group row">
					<label class="col-sm-2 col-form-label">Pesanan</label>
					<div class="col-sm-10">
						<input type="text" readonly class="form-control-plaintext" id="i-pesanan">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-2 col-form-label">User</label>
					<div class="col-sm-10">
						<input type="text" readonly class="form-control-plaintext i-input" id="i-user">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-2 col-form-label">Penerima</label>
					<div class="col-sm-10">
						<input type="text" readonly class="form-control-plaintext i-input" id="i-penerima">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-2 col-form-label">Alamat</label>
					<div class="col-sm-10">
						<input type="text" readonly class="form-control-plaintext i-input" id="i-alamat">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-2 col-form-label">Hp</label>
					<div class="col-sm-10">
						<input type="text" readonly class="form-control-plaintext i-input" id="i-hp">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-2 col-form-label">Midtrans ID</label>
					<div class="col-sm-10">
						<input type="text" readonly class="form-control-plaintext i-input" id="i-midtrans">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-2 col-form-label">Status</label>
					<div class="col-sm-10">
						<input type="text" readonly class="form-control-plaintext i-input" id="i-status">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-2 col-form-label">Paket</label>
					<div class="col-sm-10">
						<input type="text" readonly class="form-control-plaintext i-input" id="i-paket">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-2 col-form-label">Ongkir</label>
					<div class="col-sm-10">
						<input type="text" readonly class="form-control-plaintext i-input" id="i-ongkir">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-2 col-form-label">Resi</label>
					<div class="col-sm-10">
						<input type="text" readonly class="form-control-plaintext i-input" id="i-resi">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-2 col-form-label">Payment Code</label>
					<div class="col-sm-10">
						<input type="text" readonly class="form-control-plaintext i-input" id="i-payment-code">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-2 col-form-label">Promo</label>
					<div class="col-sm-10">
						<input type="text" readonly class="form-control-plaintext i-input" id="i-promo">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-sm-2 col-form-label">Payment Metode</label>
					<div class="col-sm-10">
						<input type="text" readonly class="form-control-plaintext i-input" id="i-payment-metode">
					</div>
				</div>
				<div class="d-flex">
					<button type="button" class="btn btn-secondary ml-auto info-close">Close</button>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Modal Produk-->
<div class="modal fade" id="produk-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">info Pesanan</h5>
			</div>
			<div class="modal-body">
				<ul class="list-group list-group-flush" id="produk-item">
					<li class="list-group-item">An item</li>
					<li class="list-group-item">A second item</li>
					<li class="list-group-item">A third item</li>
					<li class="list-group-item">A fourth item</li>
					<li class="list-group-item">And a fifth one</li>
				</ul>
				<div class="d-flex">
					<button type="button" class="btn btn-secondary ml-auto produk-close" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('header')
<!-- Sweetalert 2 -->
<link rel="stylesheet" href="/plugins/sweetalert2/sweetalert2.min.css" />
@endsection

@section('footer')
<!-- Sweetalert 2 -->
<script src="/plugins/sweetalert2/sweetalert2.min.js"></script>
@endsection

@section('script')
	<script>
		console.clear();

		// ===============================================================================================
		function tableShow(){
			$('.spinner-table').hide()
			$('.error-table').hide()
			$('#table-wrapper').show()
		}
		function tableHide(){
			$('.error-table').hide()
			$('#table-wrapper').hide()
			$('.spinner-table').show()
		}
		function tableError(){
			$('.spinner-table').hide()
			$('#table-wrapper').hide()
			$('.error-table').css('display', 'flex')
		}
		function setDatatables(){
			$('.datatables').DataTable({
				"paging": true,
				"lengthChange": false,
				"info": true,
				"autoWidth": false,
				"responsive": true,
				'columnDefs': [{
					'searchable': false,
					'targets': [0]
				}
				]
			});
		}
		function resetFormUpdate(){
			$('#u-id').val('')
			$('#update-form').trigger('reset');
			errorUpdateHide()
		}
		function updateShow(){
			resetFormUpdate();
			$('#update-modal').modal('show')
		}
		function updateHide(){
			resetFormUpdate();
			$('#update-modal').modal('hide')
		}
		function infoShow(){
			$('.i-input').val('')
			$('#info-modal').modal('show')
		}
		function infoHide(){
			$('.i-input').val('')
			$('#info-modal').modal('hide')
		}
		function overlayShow(){
			$('#overlay').css('display', 'flex')
		}
		function overlayHide(){
			$('#overlay').css('display', 'none')
		}
		function errorUpdateShow(response){
			$.each(response, function(i,v){
				$('.eu-'+i).html(v)
			})
		}
		function errorUpdateHide(response){
			$('.eu').html('')
		}
		function notif(title, icon = 'success'){
			Swal.fire({
				icon: icon,
				title: title,
				timer: 2000,
			})
		}
		function appendTable(data, i){
			return `
							<tr class="${data.id_pesanan}" data-id="${data.id_pesanan}">
								<td class="fit">${i}</td>
								<td class="tbl-pesanan">${data.id_pesanan}</td>
								<td class="tbl-resi">${data.resi ? data.resi : '-'}</td>
								<td class="tbl-status">${data.status}</td>
								<td class="d-flex">
									<button type="button" class="btn-info btn btn-sm btn-warning mr-2" data-id="${data.id_pesanan}"><i class="fa fa-info" aria-hidden="true"></i></button>
									<button type="button" class="btn-produk btn btn-sm btn-warning mr-2" data-id="${data.id_pesanan}"><i class="fa fa-times" aria-hidden="true"></i></button>
									${
										data.status == 'settlement' || data.status == 'capture' ? '<button type="button" class="btn-update btn btn-sm btn-primary mr-2" data-id="'+data.id_pesanan+'" data-resi="'+data.resi+'"><i class="fa fa-edit" aria-hidden="true"></i></button>':''
									}
								</td>
							</tr>`
		}
		// ===============================================================================================



		// ===============================================================================================
		function getPesanan(){
			$.ajax({
				method: "get",
				url: "{{ route('v1.admin.pesanan.index') }}",
				headers: {
					'X-Requested-With': 'XMLHttpRequest',
					'ADMIN-KEY' : $('meta[name="ADMIN-KEY"]').attr('content')
				},
				beforeSend: function () {
					tableHide()
				},
			})
			.done(function (response) {
				tableShow()
				$('#table-wrapper').html(`
					<table class="table table-hover datatables">
						<thead class="thead-dark">
							<tr>
								<th class="col-fit">#</th>
								<th>Pesanan</th>
								<th>Resi</th>
								<th class="col-fit">Status</th>
								<th class="col-fit"></th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>`)
				$.each(response.data, function(i,v){
					$('tbody').append(appendTable(v,i+1))
				})
				setDatatables()
			})
			.fail(function (jqXHR, status) {
				tableError()
			});
		}
		// ===============================================================================================

		getPesanan()
		$(document).ready(function(e){
		})
		$(document).on('click', '.btn-refresh', function(e){
			e.preventDefault()
			$(this).prop("disabled", true);
			getPesanan()
			$(this).prop("disabled", false);
		})

		// U P D A T E ===============================================================================================
		$(document).on('click', '.btn-update', function(e){
			e.preventDefault()
			updateShow()
			$('#u-id').val($(this).data('id'))
			$('#u-resi').val($(this).data('resi'))
		})
		$(document).on('submit', '#update-form', function(e){
			e.preventDefault()
			const pesanan = $(this).find('#u-id').val()
			$.ajax({
				method: "put",
				url: `{{ route('v1.admin.pesanan.index') }}/${pesanan}`,
				data: $(this).serialize(),
				headers: {
					'X-Requested-With': 'XMLHttpRequest',
					'ADMIN-KEY' : $('meta[name="ADMIN-KEY"]').attr('content')
				},
				beforeSend: function () {
					overlayShow()
					$(".update-submit").prop("disabled", true);
				},
				complete: function () {
					overlayHide()
					$(".update-submit").prop("disabled", false);
				},
			})
			.done(function (response) {
				getPesanan()
				updateHide();
				notif('berhasil mengubah resi')
			})
			.fail(function (jqXHR, status) {
				if(jqXHR.responseJSON){
					notif(jqXHR.responseJSON.message != 'failed' ? jqXHR.responseJSON.message  : 'gagal mengubah resi', 'error')
					errorUpdateShow(jqXHR.responseJSON.field)
				}else{
					notif('gagal mengubah resi', 'error')
				}
			});
		})
		$(document).on('click', '.update-close', function(e){
			e.preventDefault()
			updateHide()
		})

		// I N F O ===============================================================================================
		$(document).on("click", ".btn-info", function (e) {
			const pesanan = $(this).data('id')
			$.ajax({
				method: "get",
				url: `{{ route('v1.admin.pesanan.index') }}/${pesanan}`,
				headers: {
					'X-Requested-With': 'XMLHttpRequest',
					'ADMIN-KEY' : $('meta[name="ADMIN-KEY"]').attr('content')
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
				$('#i-user').val(response.id_user)
				$('#i-penerima').val(response.penerima.penerima)
				$('#i-alamat').val(response.penerima.alamat)
				$('#i-hp').val(response.penerima.hp)
				$('#i-paket').val(response.ongkir.paket)
				$('#i-midtrans').val(response.midtrans)
				$('#i-ongkir').val(response.ongkir.harga)
				$('#i-status').val(response.status)
				$('#i-resi').val(response.resi)
				$('#i-payment-code').val(response.payment_code)
				$('#i-payment-metode').val(response.metode)
			})
			.fail(function (jqXHR, status) {
				notif(jqXHR.responseJSON ? jqXHR.responseJSON.message : 'error', 'error')
			});
		});
		$('#info-modal').on('hide.bs.modal', function (event) {
			$('.i-input').val('')
		})

		// P R O D U K ===============================================================================================
		$(document).on("click", ".btn-produk", function (e) {
			const pesanan = $(this).data('id')
			$.ajax({
				method: "get",
				url: `{{ route('v1.admin.pesanan.index') }}/${pesanan}`,
				headers: {
					'X-Requested-With': 'XMLHttpRequest',
					'ADMIN-KEY' : $('meta[name="ADMIN-KEY"]').attr('content')
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
				$('#produk-modal .modal-body').html('')
				$.each(response.data.produk, function(i,v){
					$('#produk-modal .modal-body').append(`<li class="list-group-item">( ${v.jumlah} ) ${v.produk}</li>`)
				})
				$('#produk-modal').modal('show')
			})
			.fail(function (jqXHR, status) {
				notif(jqXHR.responseJSON ? jqXHR.responseJSON.message : 'error', 'error')
			});
		});
		$('#produk-modal').on('hide.bs.modal', function (event) {
				$('#produk-modal .modal-body').html('')
		})


	</script>
@endsection