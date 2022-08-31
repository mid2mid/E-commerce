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
						<li class="active"><a href="blog-single.html">Profil</a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End Breadcrumbs -->

<div class="container">
	<div class="justify-content-center align-items-center content-spinner mb-3" style="display: flex; height: 200px;">
		<i class="fas fa-spinner fa-pulse" style="font-size: 30px"></i>
	</div>
	<div class="justify-content-center align-items-center content-error" style="display: none;height: 200px;">
		<p style="font-weight: bold; color: red">User Not Found</p>
	</div>
	<div class="content-item">
		<div class="col-12 m-0 p-0 mt-3">
			<button type="button" class="btn btn-password">Ubah Password</button>
		</div>
		<form id="profil-form">
			<div class="row my-3" id="user-item">
			</div>
		</form>
	</div>
</div>
@endsection

@section('modal')
<!-- Modal Update-->
<div class="modal fade" id="password-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				{{-- <h5 class="modal-title">Password Kategori</h5> --}}
			</div>
			<div class="modal-body p-3" style="height: fit-content">
				<form action="#" method="post" id="password-form">
					<input type="hidden" name="user" value="{{ $user['id_user'] }}">
					<div class="form-group mb-3">
						<label>Old Password</label>
						<input type="password" class="form-control" name="old" required/>
					</div>
					<div class="form-group mb-3">
						<label>New Password</label>
						<input type="password" class="form-control" name="password" required/>
					</div>
					<div class="form-group mb-3">
						<label>Repeat Password</label>
						<input type="password" class="form-control" name="password_confirmation" required/>
					</div>
					<div class="d-flex">
						<button type="submit" class="btn btn-primary px-5 password-submit">Ubah</button>
						<button type="button" class="btn btn-secondary ml-auto password-close" data-dismiss="modal">Close</button>
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
	console.clear()
	function overlayShow(){
		$('#overlay').css('display', 'flex')
	}
	function overlayHide(){
		$('#overlay').css('display', 'none')
	}
	function passwordShow(){
		$('#password-form').trigger('reset');
		$('#password-modal').modal('show')
	}
	function passwordHide(){
		$('#password-form').trigger('reset');
		$('#password-modal').modal('hide')
	}
	function contentShow(){
		$('.content-spinner').hide()
		$('.content-error').hide()
		$('.content-item').show()
	}
	function contentHide(){
		$('.content-error').hide()
		$('.content-item').hide()
		$('.content-spinner').show()
	}
	function contentError(){
		$('.content-spinner').hide()
		$('.content-item').hide()
		$('.content-error').css('display', 'flex')
	}
	function notif(title, icon = 'success'){
		Swal.fire({
			icon: icon,
			title: title,
			timer: 2000,
		})
	}
	function getUser(){
		$.ajax({
			method: "get",
			url: `{{ route('v1.user.profil.show', ['user' => $user['id_user']]) }}`,
			data: $(this).serialize(),
			headers: {
				'X-Requested-With': 'XMLHttpRequest',
				'USER-KEY' : $('meta[name="USER-KEY"]').attr('content')
			},
			beforeSend: function () {
				contentHide()
			},
		})
		.done(function (response) {
			response = response.data
			$('#user-item').html('')
			let lahir = '';
			if(response.lahir){
				lahir = new Date(response.lahir)
				lahir = lahir.getFullYear() + '-'+(lahir.getMonth()+1).toString().padStart(2,'0') + '-'+ lahir.getDate().toString().padStart(2,'0')
			}
			$('#user-item').append(`
			<div class="col-12">
				<div class="form-group mb-3">
					<label for="email">Email</label>
					<input type="email" class="form-control" id="f-email" value="${response.email}" disabled />
				</div>
			</div>
			<div class="col-12">
				<div class="form-group mb-3">
					<label>User</label>
					<input type="text" class="form-control" id="f-user" value="${response.user}" name="user"/>
				</div>
			</div>
			<div class="col-12">
				<div class="form-group mb-3">
					<label>Jenis Kelamin</label>
					<select id="f-jk" class="custom-select" name="jk">
						<option value="pria" ${response.jk === 'pria'? 'selected':''}>Pria</option>
						<option value="wanita" ${response.jk === 'wanita'? 'selected':''}>Wanita</option>
					</select>
				</div>
			</div>
			<div class="col-12">
				<div class="form-group mb-3">
					<label for="lahir">Tanggal Lahir</label>
					<input type="date" class="form-control" id="lahir" value="${lahir}" name="lahir" style="width:fit-content"/>
				</div>
			</div>
			<div class="col-12">
				<button type="submit" class="btn btn-save">Perbarui</button>
			</div>`)
			contentShow()
		})
		.fail(function (jqXHR, status) {
			contentError()
		});
	}
	$('#profil-form').on('submit', function(e){
		e.preventDefault()
		$.ajax({
			method: "put",
			url: `{{ route('v1.user.profil.update', ['user' => $user['id_user']]) }}`,
			data: $(this).serialize(),
			headers: {
				'X-Requested-With': 'XMLHttpRequest',
				'USER-KEY' : $('meta[name="USER-KEY"]').attr('content')
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
			getUser()
			notif('berhasil mengubah profil')
		})
		.fail(function (jqXHR, status) {
			notif('gagal update profil', 'error')
		});
	})
	getUser()
	$('.btn-password').on('click', function(e){
		passwordShow()
	})
	$('.password-close').on('click', function(e){
		passwordShow()
	})
	$(document).on('submit','#password-form', function(e){
		e.preventDefault()
		$.ajax({
			method: "put",
			url: `{{ route('v1.user.profil.password', ['user' => $user['id_user']]) }}`,
			data: $(this).serialize(),
			headers: {
				'X-Requested-With': 'XMLHttpRequest',
				'USER-KEY' : $('meta[name="USER-KEY"]').attr('content')
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
			getUser()
			passwordHide()
			notif('berhasil mengubah profil')
		})
		.fail(function (jqXHR, status) {
			notif('gagal update profil', 'error')
		});
	})

</script>
@endsection