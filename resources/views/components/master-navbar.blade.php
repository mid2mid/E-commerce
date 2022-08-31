@php
		$navbar = [
			'home' => [
				'url' => route('view.master.home'),
				'text' => 'Home',
				'active' => '',
			],
			'admin' => [
				'url' => route('view.master.admin'),
				'text' => 'Admin',
				'active' => '',
			],
			// 'restore' => [
			// 	'url' => route('view.master.restore'),
			// 	'text' => 'Restore',
			// 	'active' => '',
			// ],
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

