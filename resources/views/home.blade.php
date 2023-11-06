@extends('layouts.app')
@section('title', Str::title($data['title']))
@section('content')
<main class="home">
    <section class="home__intro d-flex align-items-center min-vh-100">
        <div class="container">
            <h1>Creasik Digital</h1>
            <h3>Solusi undangan digital di era modern.</h3>
            <div class="home__intro--button">
                <a href="#" class="bg-dark text-white rounded py-2 px-4">Let`s Go</a>
                <a href="#" class="creasik-button-primary rounded py-2 px-4">
                    <i class="icon-plus me-1"></i>
                    <span>Buat undangan</span>
                </a>
            </div>
        </div>
    </section>
    <section class="home__prologue py-5">
        <div class="container">
            <div class="row g-3 align-items-center justify-content-center">
                <div class="col-lg-4">
                    <div class="home__prologue--image">
                        <img src="{{ url('storage/smartphone-screen-with-post.png') }}" alt="" class="img-fluid">
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="home__prologue--text">
                        <h6>Dengan undangan digital undang orang-orang terdekat jadi lebih hemat, praktis dan cepat.</h6>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection

@push('style')
@endpush

@push('meta')
<meta name="theme-color" content="{{ $global['setting'][3]->content }}">
<meta name="description" content="{{ strip_tags($global['setting'][4]->content) }}">
<meta property="og:type" content="article">
<meta property="og:url" content="{{ request()->fullUrl() }}">
<meta property="og:title" content="{{ $global['setting'][0]->content }}">
<meta property="og:description" content="{{ strip_tags($global['setting'][4]->content) }}">
<meta property="og:image" content="{{ url('storage/sm/'.$global['setting'][2]->content) }}">
<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="{{ request()->fullUrl() }}">
<meta property="twitter:title" content="{{ $global['setting'][0]->content }}">
<meta property="twitter:description" content="{{ strip_tags($global['setting'][4]->content) }}">
<meta property="twitter:image" content="{{ url('storage/sm/'.$global['setting'][2]->content) }}">
@endpush

@push('script')
@endpush