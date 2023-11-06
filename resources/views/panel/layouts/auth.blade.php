<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"/>
	<title>@yield('title')</title>
	<link rel="shortcut icon" href="{{ url('sneat/img/favicon.png') }}" type="image/x-icon">
	@vite(['resources/css/sneat.css', 'resources/js/sneat.js'])
	{{-- <link rel="stylesheet" href="{{ asset('build/assets/sneat-0648bf1d.css') }}"> --}}
    {{-- <script src="{{ asset('build/assets/sneat-a80572b3.js') }}" type="module"></script> --}}
	@stack('style')
</head>
<body>
	<div class="container-xxl">
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <div class="card">
                    <div class="card-body">
                        @yield('content')
                    </div>
                </div>
			</div>
		</div>
	</div>
	{{-- Scripts --}}
	<script src="{{ asset('modules/jquery/jquery.min.js') }}"></script>
	{{-- <script src="{{ asset('node_modules/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script> --}}
	{{-- <script src="{{ asset('node_modules/@popperjs/core/dist/umd/popper.min.js') }}"></script> --}}
	{{-- <script src="{{ asset('node_modules/perfect-scrollbar/dist/perfect-scrollbar.min.js') }}"></script> --}}
	{{-- <script src="{{ asset('node_modules/sweetalert2/dist/sweetalert2.all.min.js') }}"></script> --}}
	@stack('script')
	{{-- <script src="{{ asset('sneat/js/menu.js') }}"></script> --}}
	{{-- <script src="{{ asset('sneat/js/main.js') }}"></script> --}}
	{{-- <script src="{{ asset('sneat/js/mine.js') }}"></script> --}}
</body>
</html>