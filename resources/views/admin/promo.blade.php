@extends('components.admin-layout')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-12 mb-3">
			<div class="card">
				<div class="card-body">
					<button type="button" class="btn btn-secondary btn-sm tambah-show"><i class="fa fa-plus mr-2" aria-hidden="true"></i>Tambah Promo</button>
				</div>
			</div>
		</div>
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
<!-- Modal Tambah-->
<div class="modal fade open" id="tambah-modal" data-backdrop="static"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Tambah Kupon</h5>
			</div>
			<div class="modal-body">
				<form action="#" method="post" id="tambah-form">
					<div class="form-group mb-3">
						<label>Promo</label>
						<input type="text" class="form-control" id="t-promo" name="promo" required/>
						<small class="form-text et et-promo text-danger"></small>
					</div>
					<div class="form-group mb-3">
						<label for="kode">Kode</label>
						<input type="text" class="form-control" id="t-kode" name="kode" required/>
						<small class="form-text et et-kode text-danger"></small>
					</div>
					<div class="form-group mb-3">
						<label for="deskripsi">Deskripsi</label>
						<input type="text" class="form-control" id="t-deskripsi" name="deskripsi" required/>
						<small class="form-text et et-deskripsi text-danger"></small>
					</div>
					<div class="form-group mb-3">
						<label for="persen">Persen Potongan</label>
						<input type="number" class="form-control" id="t-persen" name="persen" required max="100" min="0"/>
						<small class="form-text et et-persen text-danger"></small>
					</div>
					<div class="form-group mb-3">
						<label for="max">Maksimal Potongan</label>
						<input type="number" class="form-control" id="t-max" name="max" required/>
						<small class="form-text et et-max text-danger"></small>
					</div>
					<div class="form-group mb-3">
						<label for="publish_start">Publish Start</label>
						<input type="date" class="form-control" style="width: fit-content" id="t-publish-start" name="publish_start" required/>
						<small class="form-text et et-publish_start text-danger"></small>
					</div>
					<div class="form-group mb-3">
						<label for="publish_end">Publish End</label>
						<input type="date" class="form-control" style="width: fit-content" id="t-publish-end" name="publish_end" required/>
						<small class="form-text et et-publish_end text-danger"></small>
					</div>
					<div class="d-flex">
						<button type="submit" class="btn btn-primary px-5 tambah-submit">tambah</button>
						<button type="button" class="btn btn-secondary ml-auto tambah-close">Close</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
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
						<label>Promo</label>
						<input type="text" class="form-control" id="u-promo" name="promo" required/>
						<small class="form-text eu eu-promo text-danger"></small>
					</div>
					<div class="form-group mb-3">
						<label for="deskripsi">Deskripsi</label>
						<input type="text" class="form-control" id="u-deskripsi" name="deskripsi" required/>
						<small class="form-text eu eu-deskripsi text-danger"></small>
					</div>
					<div id="u-coming"></div>
					<div class="d-flex">
						<button type="submit" class="btn btn-primary px-5 update-submit">Update</button>
						<button type="button" class="btn btn-secondary ml-auto update-close">Close</button>
					</div>
				</form>
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
		function appendTable(data, i){
			return `
							<tr class="${data.kode}" data-id="${data.kode}">
								<td class="fit">${i}</td>
								<td class="tbl-kupon">${data.promo}</td>
								<td class="tbl-kode">${data.kode}</td>
								<td class="tbl-status">${data.status}</td>
								<td class="d-flex">
									<button type="button" class="btn-update btn btn-sm btn-primary mr-2"><i class="fa fa-edit" aria-hidden="true"></i></button>
									<button type="button" class="btn-hapus btn btn-sm btn-danger"><i class="fa fa-trash" aria-hidden="true"></i></button>
								</td>
							</tr>`
		}
		function htmlAppendUpdate(kode, persen, max,start, end){
			const a = new Date(start)
			const b = new Date(end)
			return `
					<div class="form-group mb-3">
						<label for="kode">Kode</label>
						<input type="text" class="form-control" id="u-kode" name="kode" value="${kode}" required/>
						<small class="form-text eu eu-kode text-danger"></small>
					</div>
					<div class="form-group mb-3">
						<label for="persen">Persen Potongan</label>
						<input type="number" class="form-control" id="u-persen" name="persen" value="${persen}" required/>
						<small class="form-text eu eu-persen text-danger"></small>
					</div>
					<div class="form-group mb-3">
						<label for="max">Maksimal Potongan</label>
						<input type="number" class="form-control" id="u-max" name="max" value="${max}" required/>
						<small class="form-text eu eu-max text-danger"></small>
					</div>
					<div class="form-group mb-3">
						<label for="publish_start">Publish Start</label>
						<input type="date" class="form-control" style="width: fit-content" id="u-publish-start" name="publish_start" value="${a.getFullYear() + '-'+(a.getMonth()+1).toString().padStart(2,'0') + '-'+ a.getDate().toString().padStart(2,'0') }" required/>
						<small class="form-text eu eu-publish_start text-danger"></small>
					</div>
					<div class="form-group mb-3">
						<label for="publish_end">Publish End</label>
						<input type="date" class="form-control" style="width: fit-content" id="u-publish-end" name="publish_end" value="${b.getFullYear() + '-'+(b.getMonth()+1).toString().padStart(2,'0') + '-'+ b.getDate().toString().padStart(2,'0') }" required/>
						<small class="form-text eu eu-publish_end text-danger"></small>
					</div>`
		}
		function resetFormUpdate(){
			$('#u-id').val('')
			$('#update-form').trigger('reset');
			$('#u-coming').html('')
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
		function resetFormTambah(){
			$('#tambah-form').trigger('reset');
			errorUpdateHide()
		}
		function tambahShow(){
			resetFormTambah();
			$('#tambah-modal').modal('show')
		}
		function tambahHide(){
			resetFormTambah();
			$('#tambah-modal').modal('hide')
		}
		function overlayShow(){
			$('#overlay').css('display', 'flex')
		}
		function overlayHide(){
			$('#overlay').css('display', 'none')
		}
		function errorTambahShow(response){
			$.each(response, function(i,v){
				$('.et-'+i).html(v)
			})
		}
		function errorTambahHide(response){
			$('.et').html('')
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
		// ===============================================================================================

		// ===============================================================================================
		function getKupon(){
			$.ajax({
				method: "get",
				url: "{{ route('v1.admin.promo.index') }}",
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
								<th>Promo</th>
								<th>Kode</th>
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

		$(document).ready(function(e){
			getKupon()
		})
		$(document).on('click', '.btn-refresh', function(e){
			e.preventDefault()
			$(this).prop("disabled", true);
			getKupon()
			$(this).prop("disabled", false);
		})

		// T A M B A H ===============================================================================================
		$(document).on("click", ".tambah-show", function (e) {
      e.preventDefault()
			tambahShow()
		});
		$(document).on('click', '.tambah-close', function(e){
			e.preventDefault()
			tambahHide()
		})
		$(document).on("submit", "#tambah-form", function (e) {
      e.preventDefault()
			$.ajax({
				method: "post",
				url: "{{ route('v1.admin.promo.store') }}",
				data: $(this).serialize(),
				headers: {
					'X-Requested-With': 'XMLHttpRequest',
					'ADMIN-KEY' : $('meta[name="ADMIN-KEY"]').attr('content')
				},
				beforeSend: function () {
					errorTambahHide()
					overlayShow()
					$(".tambah-submit").prop("disabled", true);
				},
				complete: function () {
					overlayHide()
					$(".tambah-submit").prop("disabled", false);
				},
			})
			.done(function (response) {
				getKupon()
				tambahHide();
				notif('berhasil menambahkan Promo')
			})
			.fail(function (jqXHR, status) {
				if(jqXHR.responseJSON){
					notif(jqXHR.responseJSON.message != 'failed' ? jqXHR.responseJSON.message  : 'gagal menambahkan promo', 'error')
					errorTambahShow(jqXHR.responseJSON.field)
				}else{
					notif('gagal menambahkan promo', 'error')
				}
			});
		});

		// U P D A T E ===============================================================================================
		$(document).on('click', '.btn-update', function(e){
			e.preventDefault()
			const kode = $(this).parent().parent().find('.tbl-kode').text();
			$.ajax({
				method: "get",
				url: `{{ route('v1.admin.promo.index') }}/${kode}`,
				headers: {
					'X-Requested-With': 'XMLHttpRequest',
					'ADMIN-KEY' : $('meta[name="ADMIN-KEY"]').attr('content')
				},
				beforeSend: function () {
					overlayShow()
					$(".btn-update").prop("disabled", true);
				},
				complete: function () {
					overlayHide()
					$(".btn-update").prop("disabled", false);
				},
			})
			.done(function (response) {
				updateShow()
				response = response.data
				$('#u-id').val(response.kode)
				$('#u-promo').val(response.promo)
				$('#u-deskripsi').val(response.deskripsi)
				if(response.status === 'coming soon'){
					$('#u-coming').html('')
					$('#u-coming').append(htmlAppendUpdate(response.kode,response.persen,response.max, response.publish_start * 1000, response.publish_end * 1000))
				}
			})
			.fail(function (jqXHR, status) {
				notif('error', 'error')
			});
		})
		$(document).on('click', '.update-close', function(e){
			e.preventDefault()
			updateHide()
		})
		$(document).on('submit', '#update-form', function(e){
			e.preventDefault()
			const kode = $(this).find('#u-id').val()
			$.ajax({
				method: "put",
				url: `{{ route('v1.admin.promo.index') }}/${kode}`,
				data: $(this).serialize(),
				headers: {
					'X-Requested-With': 'XMLHttpRequest',
					'ADMIN-KEY' : $('meta[name="ADMIN-KEY"]').attr('content')
				},
				beforeSend: function () {
					$(".update-submit").prop("disabled", true);
					errorUpdateHide()
					overlayShow()
				},
				complete: function () {
					$(".update-submit").prop("disabled", false);
					overlayHide()
				},
			})
			.done(function (response) {
				getKupon()
				updateHide();
				notif('berhasil mengubah promo')
			})
			.fail(function (jqXHR, status) {
				if(jqXHR.responseJSON){
					notif(jqXHR.responseJSON.message != 'failed' ? jqXHR.responseJSON.message  : 'gagal mengubah promo', 'error')
					errorUpdateShow(jqXHR.responseJSON.field)
				}else{
					notif('gagal mengubah promo', 'error')
				}
			});
		})

		// H A P U S ===============================================================================================
		$(document).on("click", ".btn-hapus", function (e) {
			const kode = $(this).parent().parent().data('id')
			Swal.fire({
				title: `Hapus Promo`,
				html: `Apakah yakin ingin menghapus promo ini ?<span class="badge badge-danger d-block">${kode}</span>`,
				showDenyButton: true,
				confirmButtonText: "Ya",
				denyButtonText: `Tidak`,
			}).then((result) => {
				if (result.isConfirmed) {
					$.ajax({
						method: "delete",
						url: `{{ route('v1.admin.promo.index') }}/${kode}`,
						headers: {
							'X-Requested-With': 'XMLHttpRequest',
							'ADMIN-KEY' : $('meta[name="ADMIN-KEY"]').attr('content')
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
						getKupon()
						notif('berhasil menghapus promo')
					})
					.fail(function (jqXHR, status) {
						notif('gagal menghapus promo', 'error')
					});
				}
			});
		});


	</script>
@endsection