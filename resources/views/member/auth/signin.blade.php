@extends('member.layouts.auth')
@section('title', Str::title('masuk'))
@section('content')
<div class="auth__title text-center py-3">
	<h1>{{ Str::upper('masuk') }}</h1>
	<p>Masuk dan kustomisasi undangan pernikahanmu, sesuai dengan apa yang kamu mau.</p>
</div>
<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="sign-form bg-white p-3">
            <form action="{{ route('signin-store') }}" method="post" class="login">
                @csrf
                <div class="mb-3">
                    <label for="email" class="form-label">{{ Str::title('email') }}</label>
                    <input type="email" name="email" id="email" class="form-control form-control-sm" value="{{ old('email') ?? null }}" placeholder="email">
                    @error('email')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">{{ Str::title('kata sandi') }}</label>
                    <input type="password" name="password" id="password" class="form-control form-control-sm" placeholder="password">
                    @error('password')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="text-center py-2">
                    <button type="submit" name="submit" id="submit" class="btn btn-creasik-primary text-uppercase w-100">
                        <i class="bx bx-log-in"></i>
                        <span>{{ Str::title('masuk') }}</span>
                    </button>
                    <hr class="spliter" data-text="atau">
                    <a href="{{ '/auth/redirect' }}" class="login-with-google-btn">
                        <span>Google</span>
                    </a>
                </div>
            </form>
        </div>
        <div class="text-center py-3">Belum punya akun? <a href="{{ route('signup') }}" class="text-creasik-primary">{{ Str::title('buat akun baru') }}</a></div>
    </div>
</div>
@endsection

@push('style')
@endpush

@push('script')
@endpush