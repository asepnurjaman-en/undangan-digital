<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title'){{ " - Wedding of ".implode(' & ', json_decode(Auth::user()->inv->title, true))." | Creasik Digital" }}</title>
    <meta name="theme-color" content="">
    <meta name="keywords" content="">
    <link href="https://fonts.googleapis.com/css2?family=Caveat&family=Dancing+Script&family=Great+Vibes&family=Kaushan+Script&family=Nova+Cut&family=Raleway&family=Righteous&display=swap" rel="stylesheet">
	@vite(['resources/css/member-style.css', 'resources/sass/member-style-s.scss', 'resources/js/member-script.js'])
    {{-- <link rel="stylesheet" href="{{ asset('build/assets/member-style-03d7fa95.css') }}"> --}}
    {{-- <link rel="stylesheet" href="{{ asset('build/assets/member-style-s-46c277b6.css') }}"> --}}
    {{-- <script src="{{ asset('build/assets/member-script-72440df0.js') }}" type="module"></script> --}}
    @stack('style')
</head>
<body>
    @include('guestbook.layouts.nav')
    <div class="container">
        @yield('content')
    </div>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
		@csrf
	</form>
	<script src="{{ asset('modules/jquery/jquery.min.js') }}"></script>
    @stack('script')
    <script>
        $(".logout-form").on('click', function(e) {
            e.preventDefault();
            $("#logout-form").submit();
        });
    </script>
</body>
</html>