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
							<label for="name" class="form-label">{{ __('nama') }}</label>
							<input class="form-control" type="text" id="name" name="name" value="{{ Auth::user()->name }}" placeholder="isi disini" required autofocus>
						</div>
						<div class="mb-3 col-md-6">
							<label for="email" class="form-label">{{ __('e-mail') }}</label>
							<input class="form-control" type="email" id="email" name="email" value="{{ Auth::user()->email }}" placeholder="isi disini" required>
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