@extends('components.admin-layout')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-12 mb-3">
			<div class="card">
				<div class="card-body">
					<button type="button" class="btn btn-secondary btn-sm tambah-show"><i class="fa fa-plus mr-2" aria-hidden="true"></i>Tambah Kategori</button>
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
						<h1>Error!</h1>
					</div>
					<div id="table-wrapper" style="display: none">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('modal')
<!-- Modal Tambah-->
<div class="modal fade" id="tambah-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Tambah Kategori</h5>
			</div>
			<div class="modal-body">
				<form action="#" method="post" id="tambah-form">
					<div class="form-group mb-3">
						<label for="kategori">Kategori</label>
						<input type="text" class="form-control" id="t-kategori" name="kategori" required/>
						<small class="form-text et et-kategori text-danger"></small>
					</div>
					<div class="form-group mb-3">
						<label for="deskripsi">Deskripsi</label>
						<input type="text" class="form-control" id="t-deskripsi" name="deskripsi"/>
						<small class="form-text et et-deskripsi text-danger"></small>
					</div>
					<div class="d-flex">
						<button type="submit" class="btn btn-primary px-5 tambah-submit">tambah</button>
						<button type="button" class="btn btn-secondary ml-auto tambah-close" data-dismiss="modal">Close</button>
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
				<h5 class="modal-title">update Kategori</h5>
			</div>
			<div class="modal-body">
				<form action="#" method="post" id="update-form">
					<input type="hidden" id="u-id">
					<div class="form-group mb-3">
						<label for="kategori">Kategori</label>
						<input type="text" class="form-control" id="u-kategori" name="kategori" required/>
						<small class="form-text eu eu-kategori text-danger"></small>
					</div>
					<div class="form-group mb-3">
						<label for="deskripsi">Deskripsi</label>
						<input type="text" class="form-control" id="u-deskripsi" name="deskripsi"/>
						<small class="form-text eu eu-deskripsi text-danger"></small>
					</div>
					<div class="d-flex">
						<button type="submit" class="btn btn-primary px-5 update-submit">update</button>
						<button type="button" class="btn btn-secondary ml-auto update-close" data-dismiss="modal">Close</button>
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

		// ================================================================================
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
		function resetFormUpdate(){
			errorUpdateHide()
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
		function notif(title, icon = 'success'){
			Swal.fire({
				icon: icon,
				title: title,
				timer: 2000,
			})
		}
		function appendTable(data, i){
			return `
							<tr id="tr-${data.id_kategori}" data-id="${data.id_kategori}">
								<td class="fit">${i}</td>
								<td class="tbl-kategori">${data.kategori}</td>
								<td class="d-flex">
									<button type="button" class="btn-update btn btn-sm btn-primary mr-2"><i class="fa fa-edit" aria-hidden="true"></i></button>
									<button type="button" class="btn-hapus btn btn-sm btn-danger"><i class="fa fa-trash" aria-hidden="true"></i></button>
								</td>
							</tr>`
		}
		// =========================================================================================

		// =========================================================================================
		function getKategori(){
			$.ajax({
				method: "get",
				url: "{{ route('v1.admin.kategori.index') }}",
				headers: {
					'X-Requested-With': 'XMLHttpRequest',
					'ADMIN-KEY' : $('meta[name="ADMIN-KEY"]').attr('content')
				},
				beforeSend: function () {
					tableHide()
				},
				complete: function () {
				},
			})
			.done(function (response) {
				tableShow()
				$('#table-wrapper').html(`
					<table class="table table-hover datatables">
						<thead class="thead-dark">
							<tr>
								<th class="col-fit">#</th>
								<th>Kategori</th>
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
		// ======================================================================


		getKategori();
		$(document).on('click', '.btn-refresh', function(e){
			e.preventDefault()
			$(this).prop("disabled", true);
			getKategori()
			$(this).prop("disabled", false);
		})

		// T A M B A H ==========================================================
		$(document).on("click", ".tambah-show", function (e) {
      e.preventDefault()
			$('#tambah-modal').modal('show')
			$('#tambah-form').trigger('reset')
			errorTambahHide()
		});
		$(document).on("submit", "#tambah-form", function (e) {
			e.preventDefault()
			$.ajax({
				method: "post",
				url: "{{ route('v1.admin.kategori.store') }}",
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
				getKategori()
				$('#tambah-modal').modal('hide')
				$('#tambah-modal').trigger('reset')
				notif('berhasil menambahkan kategori');
			})
			.fail(function (jqXHR, status) {
				if(jqXHR.responseJSON){
					notif(jqXHR.responseJSON.message != 'failed' ? jqXHR.responseJSON.message  : 'gagal menambahkan kategori', 'error')
					errorTambahShow(jqXHR.responseJSON.field)
				}else{
					notif('gagal menambahkan kategori', 'error')
				}
			});
		})

		// U P D A T E =========================================================
		$(document).on('click', '.btn-update', function(e){
			e.preventDefault()
			const id = $(this).parent().parent().data('id')
			$.ajax({
				method: "get",
				url: `{{ route('v1.admin.kategori.index') }}/${id}`,
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
				response = response.data
				updateShow();
				$('#u-id').val(response.id_kategori);
				$('#u-kategori').val(response.kategori);
				$('#u-deskripsi').val(response.deskripsi);
			})
			.fail(function (jqXHR, status) {
				notif('error', 'error')
			});
		})
		$(document).on('submit', '#update-form', function(e){
			e.preventDefault()
			const id = $(this).find('#u-id').val()
			$.ajax({
				method: "put",
				url: `{{ route('v1.admin.kategori.index') }}/${id}`,
				data: $(this).serialize(),
				headers: {
					'X-Requested-With': 'XMLHttpRequest',
					'ADMIN-KEY' : $('meta[name="ADMIN-KEY"]').attr('content')
				},
				beforeSend: function () {
					errorUpdateHide()
					overlayShow()
					$(".update-submit").prop("disabled", true);
				},
				complete: function () {
					overlayHide()
					$(".update-submit").prop("disabled", false);
				},
			})
			.done(function (response) {
				getKategori();
				updateHide();
				notif('berhasil mengubah kategori');
			})
			.fail(function (jqXHR, status) {
				if(jqXHR.responseJSON){
					notif(jqXHR.responseJSON.message != 'failed' ? jqXHR.responseJSON.message  : 'gagal mengubah kategori', 'error')
					errorUpdateShow(jqXHR.responseJSON.field)
				}else{
					notif('gagal mengubah kategori', 'error')
				}
			});
		});

		// H A P U S  ===============================================================================
		$(document).on("click", ".btn-hapus", function (e) {
			const nama = $(this).parent().parent().find(".tbl-kategori").text();
			const id = $(this).parent().parent().data('id')
			Swal.fire({
				title: `hapus kategori`,
				html: `Apakah yakin ingin menghapus kategori ini ?<span class="badge badge-danger d-block">${nama}</span>`,
				showDenyButton: true,
				confirmButtonText: "Ya",
				denyButtonText: `Tidak`,
			}).then((result) => {
				if (result.isConfirmed) {
					$.ajax({
						method: "delete",
						url: `{{ route('v1.admin.kategori.index') }}/${id}`,
						data: $(this).serialize(),
						headers: {
							'X-Requested-With': 'XMLHttpRequest',
							'ADMIN-KEY' : $('meta[name="ADMIN-KEY"]').attr('content')
						},
						beforeSend: function () {
							overlayShow()
							$(".tambah-submit").prop("disabled", true);
						},
						complete: function () {
							overlayHide()
							$(".tambah-submit").prop("disabled", false);
						},
					})
					.done(function (response) {
						getKategori()
						$('#tambah-modal').modal('hide')
						$('#tambah-modal').trigger('reset')
						notif('berhasil menghapus kategori')
					})
					.fail(function (jqXHR, status) {
						notif('gagal menghapus kategori', 'error')
					});
				}
			});
		});
	</script>
@endsection