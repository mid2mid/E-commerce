@extends('components.admin-layout')

@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-12 mb-3">
			<div class="card">
				<div class="card-body">
					<div class="form-group">
						<label>Date range:</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text">
									<i class="far fa-calendar-alt"></i>
								</span>
							</div>
							<input type="text" class="form-control float-right" id="reservation">
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12 mb-3">
			<div class="card">
				<div class="card-body">
					<div class="d-flex justify-content-end mb-3">
						<button class="btn btn-sm btn-outline-primary btn-refresh">All</button>
					</div>
					<div class="justify-content-center align-items-center spinner-table" style="display: flex">
						<i class="fas fa-spinner fa-pulse" style="font-size: 30px"></i>
					</div>
					<div class="justify-content-center align-items-center error-table" style="display: none">
						<h1>Result Not Found!</h1>
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
<!-- Tempusdominus Bootstrap 4 -->
<link rel="stylesheet" href="/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
<!-- daterange picker -->
<link rel="stylesheet" href="/plugins/daterangepicker/daterangepicker.css">
@endsection

@section('footer')
<!-- InputMask -->
<script src="/plugins/moment/moment.min.js"></script>
<script src="/plugins/inputmask/jquery.inputmask.min.js"></script>
<!-- date-range-picker -->
<script src="/plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
@endsection

@section('script')
	<script>
		
		console.clear();

		//Date range picker
		$('#reservation').daterangepicker({
			minYear: 1901,
			maxYear: parseInt(moment().format('YYYY'),10)
		}, function(start, end, label) {
			const a = Math.floor(new Date(start.format('YYYY-MM-DD')).getTime() / 1000);
			const s = Math.floor(new Date(end.format('YYYY-MM-DD')).getTime() / 1000);
			getLaporan(`?start=${a}&end=${s}`)
		});


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
			$('#datatables').DataTable({
				"paging": true,
				"lengthChange": false,
				"info": true,
				"autoWidth": false,
				"responsive": true,
				'columnDefs': [{
					'searchable': false,
					'targets': [0]
				}],		
				"buttons": [{
					"extend": "print",
					"text": "Cetak Laporan",
					"className": 'btn-primary mr-1'
				}],
			}).buttons().container().appendTo('#datatables_wrapper .col-md-6:eq(0)');
			
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
							<tr id="tr-${data.id_produk}" data-id="${data.produk}">
								<td class="fit">${i}</td>
								<td class="tbl-kategori">${data.produk}</td>
								<td class="tbl-jumlah">${data.jumlah}</td>
							</tr>`
		}
		// =========================================================================================

		// =========================================================================================
		function getLaporan(q = ''){
			$.ajax({
				method: "get",
				url: `{{ route('v1.admin.laporan.index') }}${q}`,
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
					<table class="table table-hover datatables" id="datatables">
						<thead class="thead-dark">
							<tr>
								<th class="col-fit">#</th>
								<th>produk</th>
								<th class="col-fit">Jumlah</th>
							</tr>
						</thead>
						<tbody>
						</tbody>
					</table>`)
				let count = 0;
				$.each(response.data, function(i,v){
					$('tbody').append(appendTable(v,count+1))
					count++
				})
				setDatatables()
			})
			.fail(function (jqXHR, status) {
				tableError()
			});
		}
		// ======================================================================


		getLaporan();
		$(document).on('click', '.btn-refresh', function(e){
			e.preventDefault()
			$(this).prop("disabled", true);
			getLaporan()
			$(this).prop("disabled", false);
		})

	</script>
@endsection