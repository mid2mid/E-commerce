@extends('admin.components.layout')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-12 mb-3">
			<div class="card">
				<div class="card-header">
					Change Username
				</div>
				<div class="card-body">
					<form action="" id="profile-form">
						<input type="hidden" class="profile-id" value="{{ $profile->id_admin }}">
						<div class="form-group mb-3">
							<label for="username">Username</label>
							<input type="text" class="form-control fc-keyup" id="username" name="username" value="{{ $profile->id_admin }}"/>
						</div>
						<div class="form-group mb-3">
							<label for="admin">Admin</label>
							<input type="text" class="form-control fc-keyup" id="admin" name="admin" value="{{ $profile->admin }}"/>
						</div>
						<button type="submit" class="profile-submit btn btn-success px-5">Update</button>
					</form>
				</div>
			</div>
		</div>
		<div class="col-12 mb-3">
			<div class="card">
				<div class="card-header">
					Change Password
				</div>
				<div class="card-body">
					<form action="" id="password-form">
						<input type="hidden" class="profile-id" value="{{ $profile->id_admin }}">
						<div class="form-group mb-3">
							<label for="old">old Password</label>
							<input type="password" class="form-control" id="p-old" name="old" />
						</div>
						<div class="form-group mb-3">
							<label for="password">New Password</label>
							<input type="password" class="form-control" id="p-password" name="password" />
						</div>
						<div class="form-group mb-3">
							<label for="password_confirmation">Re-type New Password</label>
							<input type="password" class="form-control" id="p-password-confirmation" name="password_confirmation" />
						</div>
						<button type="submit" class="password-submit btn btn-success px-5">Update</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('modal')
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
		function notif(title, icon = 'success'){
			Swal.fire({
				icon: icon,
				title: title,
				timer: 2000,
			})
		}

		$(document).ready(function(e){
			// 
		})

		$(document).on("keyup change", ".fc-keyup", function (e) {
			$(".profile-submit").show();
		});
		// CHANGE PROFILE
		$(document).on("submit", "#profile-form", function (e) {
      e.preventDefault()
			const id = $(this).find('.profile-id').val()
			$.ajax({
				method: "put",
				url: `{{ $route }}/${id}/`,
				data: $(this).serialize(),
				headers: {
					'X-Requested-With': 'XMLHttpRequest',
					'ADMIN-KEY' : $('meta[name="ADMIN-KEY"]').attr('content')
				},
				beforeSend: function () {
					$(".profile-submit").prop("disabled", true);
				},
				complete: function () {
					$(".profile-submit").prop("disabled", false);
				},
			})
			.done(function (response) {
				console.log(response);
				$('.profile-id').val(id)
				notif('berhasil mengubah profile')
			})
			.fail(function (jqXHR, status) {
				// Swal.fire("failed", "error");
				if (jqXHR.responseJSON !== undefined) {
					notif(jqXHR.responseJSON.message, 'error')
					// $("#modal .modal-body").html(jqXHR.responseJSON);
					// $("#modal").modal("show");
					// console.log(jqXHR.responseJSON);
				} else {
					$("#modal .modal-body").html(jqXHR.responseText);
					$("#modal").modal("show");
					// console.log(jqXHR);
				}
			});
		});

		// CHANGE PASSWORD
		$(document).on("submit", "#password-form", function (e) {
			e.preventDefault()
			const id = $(this).find('.profile-id').val()
			$.ajax({
				method: "put",
				url: `{{ $route }}/${id}/password`,
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
				console.log(response);
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

	</script>
@endsection