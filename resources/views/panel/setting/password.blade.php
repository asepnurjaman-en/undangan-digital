@extends('panel.layouts.app')
@section('title', Str::title($data['title']))
@section('content')
<div class="content-wrapper">
	<div class="container-fluid flex-grow-1 container-p-y position-relative">
		<ul class="nav nav-pills flex-column flex-md-row mb-3">
            @foreach ($tablist as $tab)
            <li class="nav-item">
				<a class="nav-link {{ (request()->fullUrl()==$tab['url']) ? 'active' : null }}" href="{{ $tab['url'] }}"><i class="{{ $tab['icon'] }} me-1"></i> {{ Str::title($tab['title']) }}</a>
			</li>    
            @endforeach
		</ul>
		<div class="card mb-4">
			<div class="card-body p-3">
				<form action="{{ $data['form']['action'] }}" method="POST" class="{{ $data['form']['class'] }}">
					@csrf
					@method('PATCH')
                    <div class="row">
						<div class="mb-3 col-md-6">
							<label for="password" class="form-label">{{ __('kata sandi baru') }}</label>
							<input class="form-control" type="password" id="password" name="password" placeholder="isi disini" required>
						</div>
						<div class="mb-3 col-md-6">
							<label for="password-confirmation" class="form-label">{{ __('konfirmasi kata sandi baru') }}</label>
							<input class="form-control" type="password" id="password-confirmation" name="password_confirmation" placeholder="isi disini" required>
						</div>
						<div class="mb-3 col-md-6">
							<label for="password-old" class="form-label">{{ __('kata sandi sebelumnya') }}</label>
							<input class="form-control" type="password" id="password-old" name="password_old" placeholder="isi disini" required>
						</div>
					</div>
					<div class="mt-2">
						<button type="submit" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-original-title="CTRL + S" data-bs-placement="bottom">
							<i class="bx bx-save"></i>
							<span>{{ Str::title('simpan') }}</span>
						</button>
						<button type="reset" class="btn btn-outline-secondary">
							<i class="bx bx-reset"></i>
							<span>{{ Str::title('batal') }}</span>
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endsection

@push('style')
@endpush

@push('script')
@endpush