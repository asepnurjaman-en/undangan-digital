@extends('panel.layouts.auth')
@section('title', 'Login')
@section('content')
<form id="formAuthentication" class="mb-3" action="{{ route('login') }}" method="POST">
	@csrf
	<div class="mb-3">
		<label for="email" class="form-label">Email</label>
		@error('email')
		<span class="text-danger small" role="alert">
			<strong>{{ $message }}</strong>
		</span>
		@enderror
		<input
			type="text"
			class="form-control"
			id="email"
			name="email"
			placeholder="Enter your email"
			autofocus/>
	</div>
	
	<div class="mb-3 form-password-toggle">
		<label class="form-label" for="password">Password</label>
		@error('password')
		<span class="text-danger small" role="alert">
			<strong>{{ $message }}</strong>
		</span>
		@enderror
		<div class="input-group input-group-merge">
			<input
				type="password"
				id="password"
				class="form-control"
				name="password"
				placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
				aria-describedby="password"/>
			<span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
		</div>
	</div>
	<div class="mb-3">
	  	<button class="btn btn-primary d-grid w-100" type="submit">{{ __('Login') }}</button>
	</div>
</form>
@endsection