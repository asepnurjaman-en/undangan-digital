<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title') | {{ $global['setting'][0]->content }}</title>
    <meta name="keywords" content="{{ $global['setting'][5]->content }}">
    <link rel="shortcut icon" href="{{ url('storage/'.$global['setting'][1]->content) }}" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.5.5/css/simple-line-icons.min.css">
    @stack('meta')
    <script src="{{ asset('modules/jquery/jquery.min.js') }}"></script>
    @vite(['resources/sass/landing-style-s.scss', 'resources/js/landing-script.js'])
    {{-- <link rel="stylesheet" href="{{ asset('build/assets/landing-style-s-6da5d2b9.css') }}"> --}}
    {{-- <script src="{{ asset('build/assets/landing-script-09c2e587.js') }}" type="module"></script> --}}
    @stack('style')
    <style>
        @font-face {
            font-family: "Grotesque";
            src: url('{{ asset('font/body-grotesque/Body Grotesque by Zetafonts/3.Body-Grotesque-Fit-Bold-trial.ttf') }}') format('texttype');
        }
    </style>
</head>
<body>
    @include('layouts.nav')
    @yield('content')
    @stack('script')
</body>
</html>
