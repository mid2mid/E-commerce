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
			</div>
		</div>
	</div>
</div>
@endsection

@section('modal')
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
		function overlayShow(){
			$('#overlay').css('display', 'flex')
		}
		function overlayHide(){
			$('#overlay').css('display', 'none')
		}
		function notif(title, icon = 'success'){
			Swal.fire({
				icon: icon,
				title: title,
				timer: 2000,
			})
		}
		function appendTable(data, i){
			let html = `
							<tr id="tr-${data.id_user}" data-id="${data.id_user}">
								<td class="fit">${i}</td>
								<td class="tbl-email">${data.email}</td>
								<td class="tbl-user">${data.user}</td>
								<td class="d-flex">`
			if(data.status){
				html += `<button type="button" class="btn-unban btn btn-sm btn-danger" data-id="${data.id_user}">unban</button>`
			}else{
				html += `<button type="button" class="btn-ban btn btn-sm btn-success" data-id="${data.id_user}">ban</button>`
			}
			html += `</td></tr>`
			return html
		}
		// ===============================================================================================


		// ===============================================================================================
		function getPelanggan(){
			$.ajax({
				method: "get",
				url: "{{ route('v1.admin.pelanggan.index') }}",
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
								<th>Email</th>
								<th>Pelanggan</th>
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

		getPelanggan()
		$(document).on('click', '.btn-refresh', function(e){
			e.preventDefault()
			$(this).prop("disabled", true);
			getPelanggan()
			$(this).prop("disabled", false);
		})

		// B A N N E D ===============================================================================================
		$(document).on("click", ".btn-ban", function (e) {
			const id = $(this).data('id')
			const email = $(this).parent().parent().find('.tbl-email').text()
			Swal.fire({
				title: `Banned Pelanggan`,
				html: `Apakah yakin ingin membanned ini ?<span class="badge badge-danger d-block">${email}</span>`,
				showDenyButton: true,
				confirmButtonText: "Ya",
				denyButtonText: `Tidak`,
			}).then((result) => {
				if (result.isConfirmed) {
					$.ajax({
						method: "put",
						url: `{{ route('v1.admin.pelanggan.index') }}/${id}/ban`,
						headers: {
							'X-Requested-With': 'XMLHttpRequest',
							'ADMIN-KEY' : $('meta[name="ADMIN-KEY"]').attr('content')
						},
						beforeSend: function () {
							overlayShow()
							$(".btn-ban").prop("disabled", true);
						},
						complete: function () {
							overlayHide()
							$(".btn-ban").prop("disabled", false);
						},
					})
					.done(function (response) {
						getPelanggan()
						notif('berhasil membanned pelanggan')
					})
					.fail(function (jqXHR, status) {
						notif('gagal membanned pelanggan', 'error')
					});
				}
			});
		});

		// U N B A N N E D ===============================================================================================
		$(document).on("click", ".btn-unban", function (e) {
			const id = $(this).data('id')
			const email = $(this).parent().parent().find('.tbl-email').text()
			Swal.fire({
				title: `Unbanned Pelanggan`,
				html: `Apakah yakin ingin unbanned ini ?<span class="badge badge-danger d-block">${email}</span>`,
				showDenyButton: true,
				confirmButtonText: "Ya",
				denyButtonText: `Tidak`,
			}).then((result) => {
				if (result.isConfirmed) {
					$.ajax({
						method: "put",
						url: `{{ route('v1.admin.pelanggan.index') }}/${id}/unban`,
						headers: {
							'X-Requested-With': 'XMLHttpRequest',
							'ADMIN-KEY' : $('meta[name="ADMIN-KEY"]').attr('content')
						},
						beforeSend: function () {
							overlayShow()
							$(".btn-unban").prop("disabled", true);
						},
						complete: function () {
							overlayHide()
							$(".btn-unban").prop("disabled", false);
						},
					})
					.done(function (response) {
						getPelanggan()
						notif('berhasil unbanned pelanggan')
					})
					.fail(function (jqXHR, status) {
						notif('gagagl unbanned pelanggan', 'error')
					});
				}
			});
		});


	</script>
@endsection