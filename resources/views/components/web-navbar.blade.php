@php
		$navbar = [
			'home' => [
				'url' => '/',
				'text' => 'Home',
				'active' => '',
			],
			'produk' => [
				'url' => '/produk',
				'text' => 'Produk',
				'active' => '',
			],
			'promo' => [
				'url' => '/promo',
				'text' => 'Promo',
				'active' => '',
			],
		];
		if(isset($navbar[$page]['active'])){
			$navbar[$page]['active'] = 'active';
		}
@endphp

<nav class="navbar navbar-expand-lg">
	<div class="navbar-collapse">
		<div class="nav-inner">
			<ul class="nav main-menu menu navbar-nav">
				@foreach ($navbar as $v)
					<li class="{{ $v['active'] }}"><a href="{{ $v['url'] }}">{{ $v['text'] }}</a></li>
				@endforeach
			</ul>
		</div>
	</div>
</nav>

