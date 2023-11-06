@php
	$menu = [
		[
			'title' => 'Dasbor',
			'menu'	=> [
				[
					'id'	=> ['home.dashboard'],
					'title'	=> 'Dasbor',
					'url'	=> route('home.dashboard'),
					'icon'	=> 'bx bxs-dashboard',
					'sub'	=> []
				]
			]
		],
		[
			'title'	=> 'Undangan',
			'menu'	=> [
				[
					'id'	=> ['member.index', 'member.show'],
					'title'	=> 'Akun Undangan',
					'url'	=> route('member.index'),
					'icon'	=> 'bx bx-box',
					'sub'	=> []
                ],
                [
					'id'	=> ['invoice-transaction.index', 'invoice-transaction.create', 'invoice-transaction.edit'],
					'title'	=> '<span title="Pending" class="'.(($global['admin']['payment_waiting'] <= 0) ? 'd-none' : null).' small badge badge-center rounded bg-primary">'.$global['admin']['payment_waiting'].'</span> Transaksi',
					'url'	=> route('invoice-transaction.index'),
					'icon'	=> 'bx bx-box',
					'sub'	=> []
                ]
            ]
        ],
		[
			'title'	=> 'Tema',
			'menu'	=> [
				[
					'id'	=> ['template.index', 'template.create', 'template.edit'],
					'title'	=> 'Template Undangan',
					'url'	=> route('template.index'),
					'icon'	=> 'bx bx-layer',
					'sub'	=> []
				],
				[
					'id'	=> ['template.component'],
					'title'	=> 'Komponen',
					'url'	=> route('template.component', 'avatar'),
					'icon'	=> 'bx bx-box',
					'sub'	=> [
						[
							'id'	=> [],
							'title'	=> 'Avatar',
							'url'	=> route('template.component', 'avatar')
						],
                        [
							'id'	=> [],
							'title'	=> 'Dekorasi',
							'url'	=> route('template.component', 'decoration')
						],
                        [
							'id'	=> [],
							'title'	=> 'Frame',
							'url'	=> route('template.component', 'frame')
						],
                        [
							'id'	=> [],
							'title'	=> 'Musik',
							'url'	=> route('template.component', 'music')
						],
                        [
							'id'	=> [],
							'title'	=> 'Quote',
							'url'	=> route('template.component', 'quote')
						],
					]
				],
			],
		],
		[
			'title'	=> 'Lainnya',
			'menu'	=> [
                [
					'id'	=> ['contact.index'],
					'title'	=> 'Kontak',
					'url'	=> route('contact.index', 'address'),
					'icon'	=> 'bx bx-id-card',
					'sub'	=> []
				],
				[
					'id'	=> ['bank.index', 'bank.create', 'bank.edit'],
					'title'	=> 'Bank',
					'url'	=> route('bank.index'),
					'icon'	=> 'bx bx-credit-card',
					'sub'	=> []
				],
				[
					'id'	=> ['social.index', 'ecommerce.index'],
					'title'	=> 'Sosial Media',
					'url'	=> route('social.index'),
					'icon'	=> 'bx bx-message-square',
					'sub'	=> [
						[
							'id'	=> ['social.index'],
							'title'	=> 'Sosial Media',
							'url'	=> route('social.index')
						],
						[
							'id'	=> ['ecommerce.index'],
							'title'	=> 'Toko Online',
							'url'	=> route('ecommerce.index')
						],
					]
				],
            ]
        ],
        [
			'title'	=> 'Paket',
			'menu'	=> [
				[
					'id'	=> ['package.index', 'package.create', 'package.edit'],
					'title'	=> 'Paket',
					'url'	=> route('package.index'),
					'icon'	=> 'bx bx-box',
					'sub'	=> []
                ]
            ]
        ]
    ];
@endphp
<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
	<div class="app-brand py-2">
		<a class="app-brand-link">
			<span class="app-brand-logo">
				<i class="bx bx-customize text-primary fs-1"></i>
			</span>
			<span class="app-brand-text menu-text fw-bolder ms-2">
				@if (Auth::user()->role=='admin')
				{{ Str::title('panel') }}
				@elseif (Auth::user()->role=='developer')
				<span class="badge badge-tag warning" data-tag="dev">{{ Str::title('panel') }}</span>
				@endif
			</span>
		</a>
		<a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
			<i class="bx bx-chevron-left bx-sm align-middle"></i>
		</a>
	</div>
	<div class="menu-inner-shadow"></div>
	<ul class="menu-inner py-1">
		@foreach($menu as $nav)
		<li class="menu-header small text-uppercase">
			<span class="menu-header-text">{{ $nav['title'] }}</span>
		</li>
		@if(count($nav['menu']) > 0)
		@foreach($nav['menu'] as $nav_link)
		@if(count($nav_link['sub']) > 0)
		<li class="menu-item {{ (in_array(Route::currentRouteName(), $nav_link['id'])) ? 'active open' : null }}">
			<a href="javascript:void(0);" class="menu-link menu-toggle text-decoration-none">
				<i class="menu-icon tf-icons {{ $nav_link['icon'] }}"></i>
				<div data-i18n="{{ $nav_link['title'] }}">{!! $nav_link['title'] !!}</div>
			</a>
			<ul class="menu-sub">
				@foreach($nav_link['sub'] as $sub_link)
				<li class="menu-item {{ (in_array(Route::currentRouteName(), $sub_link['id'])) ? 'active open' : null }}">
					<a href="{{ $sub_link['url'] }}" class="menu-link text-decoration-none">
						<div data-i18n="{{ $sub_link['title'] }}">{{ $sub_link['title'] }}</div>
					</a>
				</li>
				@endforeach
			</ul>
		</li>
		@else
		<li class="menu-item {{ (in_array(Route::currentRouteName(), $nav_link['id'])) ? 'active' : null }}">
			<a href="{{ $nav_link['url'] }}" class="menu-link text-decoration-none">
				<i class="menu-icon tf-icons {{ $nav_link['icon'] }}"></i>
				<div data-i18n="{{ $nav_link['title'] }}">{!! $nav_link['title'] !!}</div>
			</a>
		</li>
		@endif
		@endforeach
		@endif
		@endforeach
		<li class="menu-header small text-uppercase">
			<span class="menu-header-text"></span>
		</li>
		<li class="menu-item">
			<a href="{{ route('logout') }}" class="menu-link text-decoration-none text-white bg-danger" 
			onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
				<i class="menu-icon tf-icons bx bx-exit"></i>
				<div data-i18n="Logout">{{ __('Logout') }}</div>
			</a>
		</li>
	</ul>
</aside>