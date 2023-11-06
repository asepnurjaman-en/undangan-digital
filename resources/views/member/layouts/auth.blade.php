<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <meta name="theme-color" content="">
    <meta name="keywords" content="">
    @stack('style')
	@vite(['resources/css/member-style.css', 'resources/sass/member-style-s.scss', 'resources/js/member-script.js'])
    {{-- <link rel="stylesheet" href="{{ asset('build/assets/member-style-03d7fa95.css') }}"> --}}
    {{-- <link rel="stylesheet" href="{{ asset('build/assets/member-style-s-46c277b6.css') }}"> --}}
    {{-- <script src="{{ asset('build/assets/member-script-72440df0.js') }}" type="module"></script> --}}
</head>
<body class="auth">
    <div class="container">
        @yield('content')
    </div>
	<script src="{{ asset('modules/jquery/jquery.min.js') }}"></script>
    @stack('script')
</body>
</html>