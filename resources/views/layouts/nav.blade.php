@php
    $menu = [
        [
            'id' => 'guide',
            'title' => 'panduan',
            'url' => '#'
        ],
        [
            'id' => 'contact',
            'title' => 'hubungi kami',
            'url' => '#'
        ],
    ];
@endphp
<nav class="navbar navbar-expand-lg navbar-light fixed-top bg-white shadow p-4">
    <a class="navbar-brand" href="#">Creasik Digital</a>
    <button class="navbar-toggler p-0 border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbar" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbar">
        <ul class="creasik-nav navbar-nav me-auto mb-2 mb-lg-0">
            @foreach ($menu as $item)
            <li class="nav-item">
                <a class="nav-link" aria-current="{{ $item['id'] }}" href="{{ $item['url'] }}">{{ Str::title($item['title']) }}</a>
            </li>
            @endforeach
        </ul>
        @if (Auth::check())
        <a href="{{ route('member.main') }}" class="creasik-login-nav authed rounded py-2 px-3" role="button">
            <i class="icon-user me-1"></i>
            <span>Dashboard</span>
        </a>
        @else
        <a href="{{ route('signin') }}" class="creasik-login-nav py-2 px-3" role="button">
            <i class="icon-login me-1"></i>
            <span>Masuk</span>
        </a>
        @endif
    </div>
</nav>