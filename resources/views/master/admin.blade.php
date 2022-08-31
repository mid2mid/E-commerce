@extends('components.master-layout')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-12 mb-3">
			<div class="card">
				<div class="card-body">
					<button type="button" class="tambah-show btn btn-sm btn-secondary"><i class="fa fa-plus mr-2" aria-hidden="true"></i>Tambah Admin</button>
					<button type="button" class="btn btn-warning btn-sm restore-show"><i class="fa fa-plus mr-2" aria-hidden="true"></i>Restore Admin</button>
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
						<h1>Not Found !</h1>
					</div>
					<table class="table table-hover" style="display: none">
						<thead class="thead-dark">
							<tr>
								<th>#</th>
								<th>Email</th>
								<th>Admin</th>
								<th class="col-fit"></th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('modal')
<!-- Modal Tambah-->
<div class="modal fade open" id="tambah-modal" tabindex="-1" aria-labelledby="form tambah" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Tambah Admin</h5>
			</div>
			<div class="modal-body">
				<form action="#" method="post" id="tambah-form">
					<div class="form-group mb-3">
						<label>Email</label>
						<input type="email" class="form-control" id="t-email" name="email" required/>
						<small class="form-text et et-email text-danger"></small>
					</div>
					<div class="form-group mb-3">
						<label>Admin</label>
						<input type="text" class="form-control" id="t-admin" name="admin" required/>
						<small class="form-text et et-admin text-danger"></small>
					</div>
					<div class="form-group mb-3">
						<label for="password">Password</label>
						<input type="password" class="form-control" id="t-password" name="password" required/>
						<small class="form-text et et-password text-danger"></small>
					</div>
					<div class="form-group mb-3">
						<label for="password_confirmation">Password Confirmation</label>
						<input type="password" class="form-control" id="t-password_confirmation" name="password_confirmation" required/>
						<small class="form-text et et-password_confirmation text-danger"></small>
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
<!-- Modal Tambah-->
<div class="modal fade open" id="update-modal" tabindex="-1" aria-labelledby="form tambah" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Update Admin</h5>
			</div>
			<div class="modal-body">
				<form action="#" method="post" id="update-form">
					<input type="hidden" id="u-id">
					<div class="form-group mb-3">
						<label>email</label>
						<input type="text" class="form-control" id="u-email" name="email" disabled />
					</div>
					<div class="form-group mb-3">
						<label for="admin">Admin</label>
						<input type="text" class="form-control" id="u-admin" name="admin" required/>
						<small class="form-text eu eu-admin text-danger"></small>
					</div>
					<div class="form-group mb-3">
						<label for="password">Password</label>
						<input type="password" class="form-control" id="u-password" name="password"/>
						<small class="form-text eu eu-password text-danger"></small>
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
{{-- MODAL RESTORE --}}
<div class="modal fade" id="restore-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Restore Produk</h5>
			</div>
			<div class="modal-body" id="restore-wrapper">
			</div>
			<div class="modal-footer">
				<button type="button" class="restore-close btn btn-secondary btn-sm ml-auto">CLose</button>
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
		function overlayShow(){
			$('#overlay').css('display', 'flex')
		}
		function overlayHide(){
			$('#overlay').css('display', 'none')
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
		function resetFormTambah(){
			errorTambahHide()
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
		function restoreShow(){
			$('#restore-modal').modal('show')
		}
		function restoreHide(){
			$('#restore-modal').modal('hide')
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
							<tr id="tr-${data.id_admin}" data-id="${data.id_admin}">
								<td class="col-fit">${i}</td>
								<td class="tbl-email">${data.email}</td>
								<td class="tbl-admin">${data.admin}</td>
								<td class="col-fit d-flex">
									<button type="button" class="btn-update btn btn-sm btn-primary mr-2" data-id="${data.id_admin}"><i class="fa fa-edit" aria-hidden="true"></i></button>
									<button type="button" class="btn-hapus btn btn-sm btn-danger" data-id="${data.id_admin}" data-email="${data.email}"><i class="fa fa-trash" aria-hidden="true"></i></button>
								</td>
							</tr>`
		}
		function appendRestore(data){
			return `
				<div class="w-100 mb-3 rounded d-flex justify-content-between align-items-center p-2 bg-secondary">
					<div class="res-produk">
						<h6 class="m-0 p-0">${data.email}</h6>
					</div>
					<div class="res-aksi">
						<button type="button" class="btn btn-warning btn-sm restore-submit" data-id="${data.id_admin}" data-email="${data.email}"><i class="fas fa-plus"></i></button>
					</div>
				</div>`
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
		function getAdmin(param = ''){
			$.ajax({
				method: "get",
				url: "{{ route('v1.master.admin.index') }}",
				headers: {
					'X-Requested-With': 'XMLHttpRequest',
					'MASTER-KEY' : $('meta[name="MASTER-KEY"]').attr('content')
				},
				beforeSend: function () {
					tableHide()
				},
				complete: function () {
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
			});
		}


		getAdmin();
		$(document).on('click', '.btn-refresh', function(e){
			e.preventDefault()
			$(this).prop("disabled", true);
			getAdmin()
			$(this).prop("disabled", false);
		})

		// T A M B AH ======================================================================================================
		$(document).on('click', '.tambah-show', function(e){
			e.preventDefault()
			tambahShow()
		})
		$(document).on('click', '.tambah-close', function(e){
			e.preventDefault()
			tambahHide()
		})
		$(document).on("submit", "#tambah-form", function (e) {
			e.preventDefault();
			$.ajax({
				method: "post",
				url: "{{ route('v1.master.admin.store') }}",
				data: $(this).serialize(),
				headers: {
					'X-Requested-With': 'XMLHttpRequest',
					'MASTER-KEY' : $('meta[name="MASTER-KEY"]').attr('content')
				},
				beforeSend: function () {
					overlayShow()
					errorTambahHide()
					$(".tambah-submit").prop("disabled", true);
				},
				complete: function () {
					overlayHide()
					$(".tambah-submit").prop("disabled", false);
				},
			})
			.done(function (response) {
				getAdmin()
				tambahHide()
				notif('berhasil menambahkan admin')
			})
			.fail(function (jqXHR, status) {
				if(jqXHR.responseJSON){
					notif(jqXHR.responseJSON.message != 'failed' ? jqXHR.responseJSON.message  : 'gagal menambahkan admin', 'error')
					errorTambahShow(jqXHR.responseJSON.field)
				}else{
					notif('gagal menambahkan admin', 'error')
				}
			});
		});

		// U P D A T E ======================================================================================================
		$(document).on('click', '.btn-update' , function(e){
			const id = $(this).data('id')
			$.ajax({
				method: "get",
				url: `{{ route('v1.master.admin.index') }}/${id}`,
				headers: {
					'X-Requested-With': 'XMLHttpRequest',
					'MASTER-KEY' : $('meta[name="MASTER-KEY"]').attr('content')
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
				const data = response.data
				$('#u-id').val(data.id_admin)
				$('#u-admin').val(data.admin)
				$('#u-email').val(data.email)
			})
			.fail(function (jqXHR, status) {
				notif('Error !', 'error')
			});
		})
		$(document).on('submit', '#update-form' , function(e){
			e.preventDefault();
			const id = $('#u-id').val()
			$.ajax({
				method: "put",
				url: `{{ route('v1.master.admin.index') }}/${id}`,
				data: $(this).serialize(),
				headers: {
					'X-Requested-With': 'XMLHttpRequest',
					'MASTER-KEY' : $('meta[name="MASTER-KEY"]').attr('content')
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
				getAdmin()
				updateHide()
				notif('berhasil mengubah admin')
			})
			.fail(function (jqXHR, status) {
				if(jqXHR.responseJSON){
					notif(jqXHR.responseJSON.message != 'failed' ? jqXHR.responseJSON.message  : 'gagal mengubah admin', 'error')
					errorUpdateShow(jqXHR.responseJSON.field)
				}else{
					notif('gagal mengubah admin', 'error')
				}
			});
		})

		// H A P U S ======================================================================================================
		$(document).on('click', '.btn-hapus', function(e){
			e.preventDefault()
			const id = $(this).data('id')
			const email = $(this).data('email')
			Swal.fire({
				title: `Apakah yakin ingin menghapus admin ini ?<span class="badge badge-danger d-block">${email}</span>`,
				showCancelButton: true,
				confirmButtonColor: '#3085d6',
				cancelButtonColor: '#d33',
				confirmButtonText: 'Hapus'
			}).then((result) => {
				if (result.isConfirmed) {
					$.ajax({
						method: "delete",
						url: `{{ route('v1.master.admin.index') }}/${id}`,
						headers: {
							'X-Requested-With': 'XMLHttpRequest',
							'MASTER-KEY' : $('meta[name="MASTER-KEY"]').attr('content')
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
						getAdmin()
						notif('berhasil menghapus admin')
					})
					.fail(function (jqXHR, status) {
						notif('gagal menghapus admin', 'error')
					});
				}
			})
		})

		// R E S T O R E ======================================================================================================
		$(document).on("click", ".restore-show", function (e) {
			$.ajax({
				method: "get",
				url: `{{ route('v1.master.admin.index') }}?only=trashed`,
				headers: {
					'X-Requested-With': 'XMLHttpRequest',
					'MASTER-KEY' : $('meta[name="MASTER-KEY"]').attr('content')
				},
				beforeSend: function () {
					overlayShow()
				},
				complete: function(){
					overlayHide()
				}
			})
			.done(function (response) {
				restoreShow()
				$('#restore-wrapper').html('')
				$.each(response.data, function(i,v){
					$('#restore-wrapper').append(appendRestore(v))
				})
			})
			.fail(function (jqXHR, status) {
				notif('error', 'error')
			});
		});
		$(document).on("click", ".restore-close", function (e) {
			restoreHide()
		});
		$(document).on("click", ".restore-submit", function (e) {
			const email = $(this).data('email')
			const id = $(this).data('id')
			Swal.fire({
				title: `Apakah yakin ingin merestore admin ini ?<span class="badge badge-danger d-block">${email}</span>`,
				showDenyButton: true,
				confirmButtonText: "Ya",
				denyButtonText: `Tidak`,
			}).then((result) => {
				if (result.isConfirmed) {
					$.ajax({
						method: "put",
						url: `{{ route('v1.master.admin.index') }}/${id}/restore`,
						headers: {
							'X-Requested-With': 'XMLHttpRequest',
							'MASTER-KEY' : $('meta[name="MASTER-KEY"]').attr('content')
						},
						beforeSend: function () {
							overlayShow()
							$(".restore-submit").prop("disabled", true);
						},
						complete: function () {
							overlayHide()
							$(".restore-submit").prop("disabled", false);
						},
					})
					.done(function (response) {
						getAdmin()
						restoreHide()
						notif('berhasil merestore admin')
					})
					.fail(function (jqXHR, status) {
						notif('gagal merestore admin', 'error')
					});
				}
			});
		});
	</script>
@endsection