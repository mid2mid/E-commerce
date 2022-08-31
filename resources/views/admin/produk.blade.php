@extends('components.admin-layout')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-12 mb-3">
			<div class="card">
				<div class="card-body">
					<button type="button" class="btn btn-secondary btn-sm tambah-show"><i class="fa fa-plus mr-2" aria-hidden="true"></i>Tambah Produk</button>
					<button type="button" class="btn btn-warning btn-sm restore-show"><i class="fa fa-plus mr-2" aria-hidden="true"></i>Restore Produk</button>
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
						<button type="button" class="btn btn-sm btn-primary btn-refresh">reload</button>
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
<div class="modal fade" data-backdrop="static" id="tambah-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Tambah Produk</h5>
			</div>
			<div class="modal-body">
				<form action="#" method="post" id="tambah-form" enctype="multipart/form-data">
					<div class="form-group mb-3">
						<label for="t-produk">Produk</label>
						<input type="text" class="form-control" id="t-produk" name="produk" required/>
						<small class="form-text et et-produk text-danger"></small>
					</div>
					<div class="form-group mb-3">
						<label for="t-deskripsi">Deskripsi</label>
						<textarea class="form-control summernote" id="t-deskripsi" name="deskripsi" style="min-height: 50vh" required> </textarea>
						<small class="form-text et et-deskripsi text-danger"></small>
					</div>
					<div class="form-group mb-3">
						<label>Kategori</label>
						<div class="select2-purple">
							<select class="select2 t-kategori" id="t-kategori" multiple="multiple" data-placeholder="Silakan Pilih Kategori" data-dropdown-css-class="select2-purple" style="width: 100%; height: fit-content" name="kategori[]" required>
								@foreach ($kategori as $v)
									<option value="{{ $v->id_kategori }}">{{$v->kategori}}</option>
								@endforeach
							</select>
							<small class="form-text et et-kategori text-danger"></small>
						</div>
					</div>
					<div class="form-group mb-3">
						<label for="t-harga">Harga ( Rp )</label>
						<input type="number" class="form-control" style="width: fit-content" id="t-harga" name="harga" required/>
						<small class="form-text et et-harga text-danger"></small>
					</div>
					<div class="form-group mb-3">
						<label for="t-berat">Berat ( gram )</label>
						<input type="number" class="form-control" style="width: fit-content" id="t-berat" name="berat" required/>
						<small class="form-text et et-berat text-danger"></small>
					</div>
					<div class="form-group mb-3">
						<label for="t-jumlah">Jumlah</label>
						<input type="number" class="form-control" style="width: fit-content" id="t-jumlah" name="jumlah" required/>
						<small class="form-text et et-jumlah text-danger"></small>
					</div>
					<div class="form-group mb-3">
						<label for="t-gambar">Cover</label>
						<div class="custom-file mb-3">
							<input type="file" class="custom-file-input" id="t-cover" name="cover" required/>
							<label class="custom-file-label" for="t-gambar">Choose file</label>
						</div>
						<small class="form-text et et-cover text-danger"></small>
					</div>
					<div class="form-group mb-3">
						<label for="t-publish">Publish</label>
						<input type="datetime-local" class="form-control" style="width: fit-content" id="t-publish" name="publish" required/>
						<small class="form-text et et-publish text-danger"></small>
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
<div class="modal fade" id="update-modal" data-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title">Update Produk</h5>
			</div>
			<div class="modal-body">
				<form action="#" method="post" id="update-form" enctype="multipart/form-data">
					<input type="hidden" id="u-id">
					@method('put')
					<div class="form-group mb-3">
						<label for="u-produk">Produk</label>
						<input type="text" class="form-control" id="u-produk" name="produk" required/>
						<small class="form-text eu eu-produk text-danger"></small>
					</div>
					<div class="form-group mb-3">
						<label for="u-deskripsi">Deskripsi</label>
						<textarea class="form-control summernote" id="u-deskripsi" name="deskripsi" style="min-height: 50vh" required> </textarea>
						<small class="form-text eu eu-deskripsi text-danger"></small>
					</div>
					<div class="form-group mb-3">
						<label>Kategori</label>
						<div class="select2-purple">
							<select class="select2 u-kategori" id="u-kategori" name="kategori[]" multiple="multiple" data-placeholder="Silakan Pilih Kategori" data-dropdown-css-class="select2-purple" style="width: 100%; height: fit-content" required>
								@foreach ($kategori as $v)
									<option value="{{ $v->id_kategori }}">{{$v->kategori}}</option>
								@endforeach
							</select>
							<small class="form-text eu eu-kategori text-danger"></small>
						</div>
					</div>
					<div class="form-group mb-3">
						<label for="u-harga">harga ( Rp )</label>
						<input type="number" class="form-control" style="width: fit-content" id="u-harga" name="harga" required/>
						<small class="form-text eu eu-harga text-danger"></small>
					</div>
					<div class="form-group mb-3">
						<label for="u-berat">Berat ( gram )</label>
						<input type="number" class="form-control" style="width: fit-content" id="u-berat" name="berat" required/>
						<small class="form-text eu eu-berat text-danger"></small>
					</div>
					<div class="form-group mb-3">
						<label for="u-jumlah">Jumlah</label>
						<input type="number" class="form-control" style="width: fit-content" id="u-jumlah" name="jumlah" required/>
						<small class="form-text eu eu-jumlah text-danger"></small>
					</div>
					<div class="form-group mb-3">
						<label>Cover</label>
						<div class="custom-file">
							<input type="file" class="custom-file-input" id="u-cover" name="cover"/>
							<label class="custom-file-label" for="u-cover">Choose file</label>
						</div>
						<small class="form-text eu eu-cover text-danger"></small>
						<div class="d-flex flex-wrap" id="u-cover-old">
						</div>
					</div>
					<div class="d-flex">
						<button type="submit" class="btn btn-primary update-submit px-5">Update</button>
						<button type="button" class="btn btn-secondary  update-close ml-auto">Close</button>
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
<div class="modal fade" id="modal">
	<div class="modal-dialog modal-xl">
		<div class="modal-content">
			<div class="modal-body"></div>
		</div>
	</div>
</div>
@endsection

@section('header')
<!-- Select 2 -->
<link rel="stylesheet" href="/plugins/select2/css/select2.min.css" />
<!-- Summernote -->
<link rel="stylesheet" href="/plugins/summernote/summernote-bs4.css" />
@endsection

@section('footer')
<!-- Select 2 -->
<script src="/plugins/select2/js/select2.full.min.js"></script>
<!-- Summernote -->
<script src="/plugins/summernote/summernote-bs4.min.js"></script>
@endsection

@section('script')

<script>
		console.clear();
		$(".select2").select2();
		$(".summernote").summernote({
			toolbar: [
				["misc", ["undo", "redo"]],
				["style", ["bold", "italic", "underline", "clear"]],
				["font", ["fontname", "fontsize"]],
				["color", ["forecolor", "backcolor"]],
				["para", ["ul", "ol"]],
				["insert", ["link"]],
			],
		});

		// ===============================================================================================
		function formatUang(number) {
			return new Intl.NumberFormat("id-ID", {
				style: "currency",
				currency: "IDR",
				maximumSignificantDigits: (number + "").replace(".", "").length,
			}).format(number);
		}
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
					'targets': [0,2,3,4]
				},
				{
					targets: 2,
					render: $.fn.dataTable.render.number( '.', ',', 0, 'Rp ' )
				},
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
			$('#update-form').trigger('reset');
			$('#u-kategori').val(null).trigger('change')
			$('#u-deskripsi').summernote('code', '')
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
			$('#t-kategori').val(null).trigger('change')
			$('#t-deskripsi').summernote('code', '')
			errorTambahHide()
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
		function gambarOld(id,data){
			return `
							<div class="d-block position-relative mr-2 my-2">
								<img src="{{ asset('storage/image/produk/${id}/${data}') }}" class="d-block img-thumbnail" style="object-fit: contain; width: 200px; height: 200px" />
							</div>`
		}
		function appendTable(data, i){
			return `
							<tr class="${data.id_produk}" data-id="${data.id_produk}">
								<td class="fit">${i}</td>
								<td class="tbl-produk">${data.produk}</td>
								<td class="tbl-harga">${data.harga}</td>
								<td class="tbl-jumlah">${data.jumlah}</td>
								<td class="d-flex">
									<a href="/produk/${data.id_produk}" target="_blank" class="btn-info btn btn-sm btn-warning mr-2"><i class="fa fa-info" aria-hidden="true"></i></a>
									<button type="button" class="btn-update btn btn-sm btn-primary mr-2"><i class="fa fa-edit" aria-hidden="true"></i></button>
									<button type="button" class="btn-hapus btn btn-sm btn-danger"><i class="fa fa-trash" aria-hidden="true"></i></button>
								</td>
							</tr>`
		}
		function appendRestore(data){
			return `
				<div class="w-100 mb-3 rounded d-flex justify-content-between align-items-center p-2 bg-secondary">
					<div class="res-produk">
						<h6 class="m-0 p-0">${data.produk}</h6>
					</div>
					<div class="res-aksi">
						<button type="button" class="btn btn-warning btn-sm restore-submit" data-id="${data.id_produk}" data-produk="${data.produk}"><i class="fas fa-plus"></i></button>
					</div>
				</div>`
		}
		// ===============================================================================================

		// ===============================================================================================
		function getProduk(){
			$.ajax({
				method: "get",
				url: `{{ route('v1.admin.produk.index') }}?orderBy=produk&sort=asc`,
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
									<th>Nama</th>
									<th class="col-fit">Harga</th>
									<th class="col-fit">Stok</th>
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
		function getRestore(){
			$.ajax({
				method: "get",
				url: `{{ route('v1.admin.produk.index') }}?only=restore`,
				headers: {
					'X-Requested-With': 'XMLHttpRequest',
					'ADMIN-KEY' : $('meta[name="ADMIN-KEY"]').attr('content')
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
		}

		// ===============================================================================================
		getProduk()
		$(document).ready(function(e){
		})
		$(document).on('click', '.btn-refresh', function(e){
			e.preventDefault()
			$(this).prop("disabled", true);
			getProduk()
			$(this).prop("disabled", false);
		})

		// T A M B A H ===============================================================================================	
		$(document).on("click", ".tambah-show", function (e) {
      e.preventDefault()
			tambahShow()
		});
		$(document).on("submit", "#tambah-form", function (e) {
      e.preventDefault()
			$.ajax({
				method: "post",
				url: "{{ route('v1.admin.produk.store') }}",
				data: new FormData(this), 
				contentType: false, 
				cache: false, 
				processData: false,
				headers: {
					'X-Requested-With': 'XMLHttpRequest',
					'ADMIN-KEY' : $('meta[name="ADMIN-KEY"]').attr('content')
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
				getProduk()
				tambahHide();
				notif('berhasil menambahkan produk')
			})
			.fail(function (jqXHR, status) {
				if(jqXHR.responseJSON){
					notif(jqXHR.responseJSON.message != 'failed' ? jqXHR.responseJSON.message  : 'gagal menambahkan produk', 'error')
					errorTambahShow(jqXHR.responseJSON.field)
				}else{
					notif('gagal menambahkan produk', 'error')
				}
			});
		});
		$(document).on("click", ".tambah-close", function (e) {
			tambahHide()
		});

		// U P D A T E ===============================================================================================
		$(document).on('click', '.btn-update', function(e){
			e.preventDefault()
			const id = $(this).parent().parent().data('id');
			$.ajax({
				method: "get",
				url: `{{ route('v1.admin.produk.index') }}/${id}`,
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
				resetFormUpdate()
				updateShow()
				response = response.data
				$('#u-produk').val(response.produk)
				$('#u-id').val(response.id_produk)
				$('#u-deskripsi').summernote('code', response.deskripsi)
				$('#u-kategori').val(response.kategori.map(d => d.id_kategori)).trigger('change')
				$('#u-jumlah').val(response.jumlah)
				$('#u-harga').val(response.harga)
				$('#u-berat').val(response.berat)
				$('#u-cover-old').html('')
				$('#u-cover-old').append(gambarOld(response.id_produk,response.cover))
			})
			.fail(function (jqXHR, status) {
				notif('Produk Tidak ada', 'error')
			});
		})
		$(document).on('submit', '#update-form', function(e){
			e.preventDefault()
			const id = $(this).find('#u-id').val()
			$.ajax({
				method: "post",
				url: `{{ route('v1.admin.produk.index') }}/${id}`,
				data: new FormData(this), 
  			contentType: false,
				cache: false, 
				processData: false,
				headers: {
					'X-Requested-With': 'XMLHttpRequest',
					'ADMIN-KEY' : $('meta[name="ADMIN-KEY"]').attr('content')
				},
				beforeSend: function () {
					overlayShow()
					errorUpdateHide()
					$(".update-submit").prop("disabled", true);
				},
				complete: function () {
					overlayHide()
					$(".update-submit").prop("disabled", false);
				},
			})
			.done(function (response) {
				getProduk()
				updateHide();
				notif('berhasil mengubah produk')
			})
			.fail(function (jqXHR, status) {
				if(jqXHR.responseJSON){
					notif(jqXHR.responseJSON.message != 'failed' ? jqXHR.responseJSON.message  : 'gagal mengubah produk', 'error')
					errorUpdateShow(jqXHR.responseJSON.field)
				}else{
					notif('gagal mengubah produk', 'error')
				}
			});
		})
		$(document).on("click", ".update-close", function (e) {
			updateHide()
		});

		// H A P U S ===============================================================================================
		$(document).on("click", ".btn-hapus", function (e) {
			const nama = $(this).parent().parent().find(".tbl-produk").text();
			const id = $(this).parent().parent().data('id')
			Swal.fire({
				title: `Hapus Produk`,
				html: `Apakah yakin ingin menghapus produk ini ?<span class="badge badge-danger d-block">${nama}</span>`,
				showDenyButton: true,
				confirmButtonText: "Ya",
				denyButtonText: `Tidak`,
			}).then((result) => {
				if (result.isConfirmed) {
					$.ajax({
						method: "delete",
						url: `{{ route('v1.admin.produk.index') }}/${id}`,
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
						getProduk()
						notif('berhasil menghapus produk')
					})
					.fail(function (jqXHR, status) {
						notif('Gagal Mengpahus Produk', 'error')
					});
				}
			});
		});

		// R E S T O R E ===============================================================================================
		$(document).on("click", ".restore-show", function (e) {
			getRestore()
		});
		$(document).on("click", ".restore-close", function (e) {
			restoreHide()
		});
		$(document).on("click", ".restore-submit", function (e) {
			const nama = $(this).data('produk')
			const id = $(this).data('id')
			Swal.fire({
				title: `Restore Produk`,
				html: `Apakah yakin ingin merestore produk ini ?<span class="badge badge-danger d-block">${nama}</span>`,
				showDenyButton: true,
				confirmButtonText: "Ya",
				denyButtonText: `Tidak`,
			}).then((result) => {
				if (result.isConfirmed) {
					$.ajax({
						method: "put",
						url: `{{ route('v1.admin.produk.index') }}/${id}/restore`,
						headers: {
							'X-Requested-With': 'XMLHttpRequest',
							'ADMIN-KEY' : $('meta[name="ADMIN-KEY"]').attr('content')
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
						getProduk()
						restoreHide()
						notif('berhasil merestore data')
					})
					.fail(function (jqXHR, status) {
						notif('gagal merestore produk', 'error')
					});
				}
			});
		});


	</script>
@endsection