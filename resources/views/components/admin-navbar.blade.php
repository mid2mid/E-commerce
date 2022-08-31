@php
		$navbar = [
			'home' => [
				'url' => route('view.admin.home'),
				'text' => 'Home',
				'active' => '',
			],
			'kategori' => [
				'url' => route('view.admin.kategori'),
				'text' => 'Kategori',
				'active' => '',
			],
			'produk' => [
				'url' => route('view.admin.produk'),
				'text' => 'Produk',
				'active' => '',
			],
			'promo' => [
				'url' => route('view.admin.promo'),
				'text' => 'Promo',
				'active' => '',
			],
			'pelanggan' => [
				'url' => route('view.admin.pelanggan'),
				'text' => 'Pelanggan',
				'active' => '',
			],
			'pesanan' => [
				'url' => route('view.admin.pesanan'),
				'text' => 'Pesanan',
				'active' => '',
			],
			'laporan' => [
				'url' => route('view.admin.laporan'),
				'text' => 'Laporan',
				'active' => '',
			],
		];
		$navbar[$page]['active'] = 'active';
@endphp
<nav class="mt-2">
	<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
		@foreach ($navbar as $v)
		<li class="nav-item">
			<a href="{{ $v['url'] }}" class="nav-link {{ $v['active'] }} ">
				<i class="nav-icon fas fa-th"></i>
				<p>{{ $v['text'] }}</p>
			</a>
		</li>
		@endforeach
	</ul>
</nav>

