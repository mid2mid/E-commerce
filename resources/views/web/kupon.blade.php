@extends('components.web-layout')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-12 mb-3">
			<div class="card">
				<div class="card-body">
					<button type="button" class="btn btn-secondary btn-sm tambah-show"><i class="fa fa-plus mr-2" aria-hidden="true"></i>Tambah Kupon</button>
				</div>
			</div>
		</div>
		<div class="col-12 mb-3">
			<div class="card">
				<div class="card-body">
					<button class="btn btn-sm btn-outline-primary btn-refresh mb-3 mr-auto"><i class="fa fa-times"></i></button>
					<div class="justify-content-center align-items-center spinner-table" style="display: flex">
						<i class="fas fa-spinner fa-pulse" style="font-size: 30px"></i>
					</div>
					<div class="justify-content-center align-items-center error-table" style="display: none">
						<button type="button" class="btn btn-sm btn-primary btn-refresh">reload</button>
					</div>
					<table class="table table-hover" style="display: none">
						<thead class="thead-dark">
							<tr>
								<th class="col-fit">#</th>
								<th>Kupon</th>
								<th>Kode</th>
								<th class="col-fit">Status</th>
								<th class="col-fit"></th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
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
<div class="modal fade open" id="tambah-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Tambah Kupon</h5>
			</div>
			<div class="modal-body">
				<form action="#" method="post" id="tambah-form">
					<div class="form-group mb-3">
						<label for="kupon">Kupon</label>
						<input type="text" class="form-control" id="t-kupon" name="kupon" />
					</div>
					<div class="form-group mb-3">
						<label for="kode">Kode</label>
						<input type="text" class="form-control" id="t-kode" name="kode" />
					</div>
					<div class="form-group mb-3">
						<label for="deskripsi">Deskripsi</label>
						<input type="text" class="form-control" id="t-deskripsi" name="deskripsi" />
					</div>
					<div class="form-group mb-3">
						<label for="persen">Persen Potongan</label>
						<input type="number" class="form-control" id="t-persen" name="persen" />
					</div>
					<div class="form-group mb-3">
						<label for="max">Maksimal Potongan</label>
						<input type="number" class="form-control" id="t-max" name="max" />
					</div>
					<div class="form-group mb-3">
						<label for="publish_start">Publish Start</label>
						<input type="date" class="form-control" style="width: fit-content" id="t-publish-start" name="publish_start" />
					</div>
					<div class="form-group mb-3">
						<label for="publish_end">Publish End</label>
						<input type="date" class="form-control" style="width: fit-content" id="t-publish-end" name="publish_end" />
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
<div class="modal fade" id="update-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Update Kupon</h5>
			</div>
			<div class="modal-body">
				<form action="#" method="post" id="update-form">
					<input type="hidden" id="u-id">
					<div class="form-group mb-3">
						<label for="kupon">Kupon</label>
						<input type="text" class="form-control" id="u-kupon" name="kupon" />
					</div>
					<div class="form-group mb-3">
						<label for="kode">Kode</label>
						<input type="text" class="form-control" id="u-kode" name="kode" />
					</div>
					<div class="form-group mb-3">
						<label for="deskripsi">Deskripsi</label>
						<input type="text" class="form-control" id="u-deskripsi" name="deskripsi" />
					</div>
					<div class="form-group mb-3">
						<label for="persen">Persen Potongan</label>
						<input type="number" class="form-control" id="u-persen" name="persen" />
					</div>
					<div class="form-group mb-3">
						<label for="max">Maksimal Potongan</label>
						<input type="number" class="form-control" id="u-max" name="max" />
					</div>
					<div id="u-date"></div>
					<div class="d-flex">
						<button type="submit" class="btn btn-primary px-5 update-submit">Update</button>
						<button type="button" class="btn btn-secondary ml-auto update-close">Close</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="modal">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-body"></div>
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

@section('js')
	<script>
		console.clear();

		function tableShow(){
			$('.spinner-table').hide()
			$('.error-table').hide()
			$('table').show()
		}
		function tableHide(){
			$('.error-table').hide()
			$('table').hide()
			$('.spinner-table').show()
		}
		function tableError(){
			$('.spinner-table').hide()
			$('table').hide()
			$('.error-table').css('display', 'flex')
		}
		function appendTable(data, i){
			return `
							<tr class="${data.kode}" data-id="${data.kode}">
								<td class="fit">${i}</td>
								<td class="tbl-kupon">${data.kupon}</td>
								<td class="tbl-kode">${data.kode}</td>
								<td class="tbl-status">${data.status}</td>
								<td class="d-flex">
									<button type="button" class="btn-info btn btn-sm btn-warning mr-2"><i class="fa fa-info" aria-hidden="true"></i></button>
									<button type="button" class="btn-update btn btn-sm btn-primary mr-2"><i class="fa fa-edit" aria-hidden="true"></i></button>
									<button type="button" class="btn-hapus btn btn-sm btn-danger"><i class="fa fa-trash" aria-hidden="true"></i></button>
								</td>
							</tr>`
		}
		function htmlUpdatePubilsh(start, end){
			const a = new Date(start)
			const b = new Date(end)
			return `
					<div class="form-group mb-3">
						<label for="publish_start">Publish Start</label>
						<input type="date" class="form-control" style="width: fit-content" id="u-publish-start" name="publish_start" value="${a.getFullYear() + '-'+(a.getMonth()+1).toString().padStart(2,'0') + '-'+ a.getDate().toString().padStart(2,'0') }"/>
					</div>
					<div class="form-group mb-3">
						<label for="publish_end">Publish End</label>
						<input type="date" class="form-control" style="width: fit-content" id="u-publish-end" name="publish_end" value="${b.getFullYear() + '-'+(b.getMonth()+1).toString().padStart(2,'0') + '-'+ b.getDate().toString().padStart(2,'0') }"/>
					</div>`
		}
		function resetFormUpdate(){
			$('#u-id').val('')
			$('#update-form').trigger('reset');
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
		}
		function tambahShow(){
			resetFormTambah();
			$('#tambah-modal').modal('show')
		}
		function tambahHide(){
			resetFormTambah();
			$('#tambah-modal').modal('hide')
		}
		function notif(title, icon = 'success'){
			Swal.fire({
				icon: icon,
				title: title,
				timer: 2000,
			})
		}
		function getKupon(){
			$.ajax({
				method: "get",
				url: "{{ route('v1.admin.kupon.index') }}",
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
				$('tbody').html('')
				$.each(response.data, function(i,v){
					$('tbody').append(appendTable(v,i+1))
				})
			})
			.fail(function (jqXHR, status) {
				tableError()
				if (jqXHR.responseText !== undefined) {
					$("#modal .modal-body").html(jqXHR.responseText);
					$("#modal").modal("show");
				} else {
					$("#modal .modal-body").html(jqXHR);
					$("#modal").modal("show");
				}
			});
		}

		$(document).ready(function(e){
			getKupon()
		})
		$(document).on('click', '.btn-refresh', function(e){
			e.preventDefault()
			$(this).prop("disabled", true);
			getKupon()
			$(this).prop("disabled", false);
		})

		// TAMBAH	
		$(document).on("click", ".tambah-show", function (e) {
      e.preventDefault()
			tambahShow()
		});
		$(document).on("submit", "#tambah-form", function (e) {
      e.preventDefault()
			$.ajax({
				method: "post",
				url: "{{ route('v1.admin.kupon.store') }}",
				data: $(this).serialize(),
				headers: {
					'X-Requested-With': 'XMLHttpRequest',
					'ADMIN-KEY' : $('meta[name="ADMIN-KEY"]').attr('content')
				},
				beforeSend: function () {
					$(".tambah-submit").prop("disabled", true);
				},
				complete: function () {
					$(".tambah-submit").prop("disabled", false);
				},
			})
			.done(function (response) {
				// console.log(response);
				getKupon()
				tambahHide();
				notif('berhasil menambahkan Kupon')
			})
			.fail(function (jqXHR, status) {
				// Swal.fire("failed", "error");
				if (jqXHR.responseJSON !== undefined) {
					notif(jqXHR.responseJSON.message, 'error ')
					// $("#modal .modal-body").html(jqXHR.responseJSON);
					// $("#modal").modal("show");
					// console.log(jqXHR.responseJSON);
				} else {
					// $("#modal .modal-body").html(jqXHR.responseText);
					// $("#modal").modal("show");
					// console.log(jqXHR);
				}
			});
		});

		// UPDATE
		$(document).on('click', '.btn-update', function(e){
			e.preventDefault()
			const kode = $(this).parent().parent().find('.tbl-kode').text();
			$.ajax({
				method: "get",
				url: `{{ route('v1.admin.kupon.index') }}/${kode}`,
				headers: {
					'X-Requested-With': 'XMLHttpRequest',
					'ADMIN-KEY' : $('meta[name="ADMIN-KEY"]').attr('content')
				},
				beforeSend: function () {
					$(".btn-update").prop("disabled", true);
				},
				complete: function () {
					$(".btn-update").prop("disabled", false);
				},
			})
			.done(function (response) {
				updateShow()
				response = response.data
				$('#u-id').val(response.kode)
				$('#u-kupon').val(response.kupon)
				$('#u-kode').val(response.kode)
				$('#u-deskripsi').val(response.deskripsi)
				$('#u-max').val(response.max)
				$('#u-persen').val(response.persen)
				if(response.status !== 'expired'){
					$('#u-date').html('')
					$('#u-date').append(htmlUpdatePubilsh(response.publish_start * 1000, response.publish_end * 1000))
				}
			})
			.fail(function (jqXHR, status) {
				// tableError()
				if (jqXHR.responseText !== undefined) {
					$("#modal .modal-body").html(jqXHR.responseText);
					$("#modal").modal("show");
				} else {
					// $("#modal .modal-body").html(jqXHR);
					// $("#modal").modal("show");
					console.log(jqXHR);
				}
			});
		})
		$(document).on('submit', '#update-form', function(e){
			e.preventDefault()
			const kode = $(this).find('#u-id').val()
			$.ajax({
				method: "put",
				url: `{{ route('v1.admin.kupon.index') }}/${kode}`,
				data: $(this).serialize(),
				headers: {
					'X-Requested-With': 'XMLHttpRequest',
					'ADMIN-KEY' : $('meta[name="ADMIN-KEY"]').attr('content')
				},
				beforeSend: function () {
					$(".update-submit").prop("disabled", true);
				},
				complete: function () {
					$(".update-submit").prop("disabled", false);
				},
			})
			.done(function (response) {
				getKupon()
				updateHide();
				notif('berhasil menambahkan produk')
			})
			.fail(function (jqXHR, status) {
				// Swal.fire("failed", "error");
				if (jqXHR.responseJSON !== undefined) {
					$("#modal .modal-body").html(jqXHR.responseText);
					$("#modal").modal("show");
					// console.log(jqXHR.responseJSON);
				} else {
					$("#modal .modal-body").html(jqXHR.responseText);
					$("#modal").modal("show");
					// console.log(jqXHR);
				}
			});
		})

		// HAPUS
		$(document).on("click", ".btn-hapus", function (e) {
			const kode = $(this).parent().parent().data('id')
			Swal.fire({
				title: `hapus kategori`,
				html: `Apakah yakin ingin menghapus kategori ini ?<span class="badge badge-danger d-block">${kode}</span>`,
				showDenyButton: true,
				confirmButtonText: "Ya",
				denyButtonText: `Tidak`,
			}).then((result) => {
				if (result.isConfirmed) {
					// console.log(id);
					$.ajax({
						method: "delete",
						url: `{{ route('v1.admin.kupon.index') }}/${kode}`,
						headers: {
							'X-Requested-With': 'XMLHttpRequest',
							'ADMIN-KEY' : $('meta[name="ADMIN-KEY"]').attr('content')
						},
						beforeSend: function () {
							$(".btn-hapus").prop("disabled", true);
						},
						complete: function () {
							$(".btn-hapus").prop("disabled", false);
						},
					})
					.done(function (response) {
						notif('berhasil menghapus kategori')
						getKupon()
					})
					.fail(function (jqXHR, status) {
				// Swal.fire("failed", "error");
						if (jqXHR.responseText !== undefined) {
							$("#modal .modal-body").html(jqXHR.responseText);
							$("#modal").modal("show");
						} else {
							$("#modal .modal-body").html(jqXHR);
							$("#modal").modal("show");
							console.log(jqXHR);
						}
					});
				}
			});
		});


	</script>
@endsection