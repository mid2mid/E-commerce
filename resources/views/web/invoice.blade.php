<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Invoice | Profitto</title>
    <link rel="icon" href="/profitto.ico">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="/plugins/fontawesome-free/css/all.min.css" />
	<!-- Theme style -->
	<link rel="stylesheet" href="/plugins/adminlte/css/adminlte.min.css" />
	<style>
		body {
  -webkit-print-color-adjust: exact !important;
}
		@media print {
				.print-hide {
					display: none;
				}
		}
		body{
			margin-top:20px;
			background:#eee;
		}

		.invoice {
			background: #fff;
			padding: 20px
		}

		.invoice-company {
			font-size: 20px
		}

		.invoice-header {
			margin: 0 -20px;
			background: #f0f3f4;
			padding: 20px
		}

		.invoice-date,
		.invoice-from,
		.invoice-to {
			display: table-cell;
			width: 1%
		}

		.invoice-from,
		.invoice-to {
			padding-right: 20px
		}

		.invoice-date .date,
		.invoice-from strong,
		.invoice-to strong {
			font-size: 16px;
			font-weight: 600
		}

		.invoice-date {
			text-align: right;
			padding-left: 20px
		}

		.invoice-price {
			background: #f0f3f4;
			display: table;
			width: 100%
		}

		.invoice-price .invoice-price-left,
		.invoice-price .invoice-price-right {
			display: table-cell;
			padding: 20px;
			font-size: 20px;
			font-weight: 600;
			width: 75%;
			position: relative;
			vertical-align: middle
		}

		.invoice-price .invoice-price-left .sub-price {
			display: table-cell;
			vertical-align: middle;
			padding: 0 20px
		}

		.invoice-price small {
			font-size: 12px;
			font-weight: 400;
			display: block
		}

		.invoice-price .invoice-price-row {
			display: table;
			float: left
		}

		.invoice-price .invoice-price-right {
			width: 25%;
			background: #2d353c;
			color: #fff;
			font-size: 28px;
			text-align: right;
			vertical-align: bottom;
			font-weight: 300
		}

		.invoice-price .invoice-price-right small {
			display: block;
			opacity: .6;
			position: absolute;
			top: 10px;
			left: 10px;
			font-size: 12px
		}

		.invoice-footer {
			border-top: 1px solid #ddd;
			padding-top: 10px;
			font-size: 10px
		}

		.invoice-note {
			color: #999;
			margin-top: 80px;
			font-size: 85%
		}

		.invoice>div:not(.invoice-footer) {
			margin-bottom: 20px
		}

		.btn.btn-white, .btn.btn-white.disabled, .btn.btn-white.disabled:focus, .btn.btn-white.disabled:hover, .btn.btn-white[disabled], .btn.btn-white[disabled]:focus, .btn.btn-white[disabled]:hover {
			color: #2d353c;
			background: #fff;
			border-color: #d9dfe3;
		}
	</style>
</head>
<body>
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
<div class="container">
   <div class="col-md-12">
      <div class="invoice"style="border: 2px solid black">
         <!-- begin invoice-company -->
         <div class="invoice-company text-inverse f-w-600">
					<span class="pull-right hidden-print">
						<a href="javascript:;" onclick="window.print()" class="btn btn-sm btn-white m-b-10 p-l-5 print-hide"><i class="fa fa-print t-plus-1 fa-fw fa-lg"></i> Print</a>
					</span>
					PT Profitto Inovasi Kreatif
         </div>
         <!-- end invoice-company -->
         <!-- begin invoice-header -->
         <div class="invoice-header">
            <div class="invoice-from">
               <small>from</small>
               <address class="m-t-5 m-b-5">
                  <strong class="text-inverse">PT Profitto Inovasi Kreatif</strong><br>
                  Jl. Kapten Soebijanto Djojohadikusumo<br>
                  Kota Tangerang Selatan, Banten<br>
               </address>
            </div>
            <div class="invoice-to">
               <small>to</small>
               <address class="m-t-5 m-b-5">
                  <strong class="text-inverse">{{ $invoice->penerima['penerima'] }}</strong><br>
                  {{ $invoice->penerima['alamat'] }}<br>
                  {{ $invoice->penerima['hp'] }}<br>
               </address>
            </div>
            <div class="invoice-date">
               <small>Invoice</small>
               <div class="date text-inverse m-t-5">{{ date('d-m-Y',strtotime($invoice->created_at)) }}</div>
               <div class="invoice-detail">
                  #{{ $invoice->id_pesanan }}<br>
               </div>
            </div>
         </div>
         <!-- end invoice-header -->
         <!-- begin invoice-content -->
         <div class="invoice-content">
            <!-- begin table-responsive -->
            <div class="table-responsive">
               <table class="table table-invoice">
                  <thead>
                     <tr>
                        <th>PRODUK</th>
                        <th class="text-center" width="10%">JUMLAH</th>
                        <th class="text-center" width="10%">HARGA</th>
                        <th class="text-right" width="20%">TOTAL</th>
                     </tr>
                  </thead>
                  <tbody>
										@foreach ($invoice->produk as $v)
										<tr>
											 <td>
													<span class="text-inverse">{{ $v['produk'] }}</span><br>
											 </td>
											 <td class="text-center">{{ $v['jumlah'] }}</td>
											 <td class="text-center">Rp {{ number_format($v['harga'],0,',','.') }}</td>
											 <td class="text-right">Rp {{ number_format($v['jumlah'] * $v['harga'],0,',','.') }}</td>
										</tr>
										@endforeach
										<tr>
											 <td>
													<span class="text-inverse">Ongkos Kirim</span><br>
											 </td>
											 <td class="text-center"></td>
											 <td class="text-center"></td>
											 <td class="text-right">Rp {{ number_format($invoice->ongkir['harga'],0,',','.') }}</td>
										</tr>
										@if (!empty($invoice->promo))
										<tr>
											 <td>
													<span class="text-inverse">Promo</span><br>
											 </td>
											 <td class="text-center"></td>
											 <td class="text-center"></td>
											 <td class="text-right">Rp -{{ number_format($invoice->promo,0,',','.') }}</td>
										</tr>
										@endif
                  </tbody>
               </table>
            </div>
            <!-- end table-responsive -->
            <!-- begin invoice-price -->
            <div class="invoice-price">
               <div class="invoice-price-left">
               </div>
               <div class="invoice-price-right">
                  <small>TOTAL</small> <span class="f-w-600">Rp {{ number_format($total,0,',','.') }}</span>
               </div>
            </div>
            <!-- end invoice-price -->
         </div>
         <!-- end invoice-content -->
         <!-- begin invoice-footer -->
         <div class="invoice-footer">
            <p class="text-center m-b-5 f-w-600">
              TERIMA KASIH TELAH BELANJA DI KAMI
            </p>
         </div>
         <!-- end invoice-footer -->
      </div>
   </div>
</div>

<!-- Bootstrap 4 -->
<script src="/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="/plugins/adminlte/js/adminlte.js"></script>
</body>
</html>