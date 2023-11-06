@extends('panel.layouts.app')
@section('title', Str::title($data['title']))
@section('content')
<div class="container">
	<form action="{{ $data['form']['action'] }}" method="post" class="{{ $data['form']['class'] }} my-3">
		@csrf
		@method('PATCH')
		<div class="card border-0 my-3">
			<div class="card-header p-3">
				<div class="form-group d-flex justify-content-between">
					<button type="button" class="btn btn-outline-secondary">
						<i class="bx bx-chevron-left"></i>
						<span>{{ Str::title('kembali') }}</span>
					</button>
					<button type="submit" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-original-title="CTRL + S" data-bs-placement="bottom">
						<i class="bx bx-save"></i>
						<span>{{ Str::title('simpan') }}</span>
					</button>
				</div>
			</div>
			<div class="card-body p-3">
				<div class="row g-3">
					@forelse ($ecommerce as $item)
					<div class="col-12 col-md-6">
						<div class="link-external_form shadow rounded {{ $item->type }} p-1">
							<div class="d-flex justify-content-between py-2 px-3">
								<span>
									<img src="{{ url('img/icons/'.$item->icon) }}">
									{{ Str::title($item->brand) }}
								</span>
								<div class="form-check form-switch">
									<input class="form-check-input change-input-status change-input-edited" data-edit="{{ $item->brand.$item->id }}" type="checkbox" name="active_{{ $item->brand.$item->id }}" data-brand="{{ $item->brand.$item->id }}" value="true" {{ ($item->actived=='1') ? 'checked' : null }}>
								</div>
							</div>
							<div class="input-group input-group-merge input-group-{{ $item->brand.$item->id }} {{ ($item->actived=='0') ? 'disabled' : null }}">
								<div class="input-group-text">
									<i class="bx bx-{{ ($item->actived=='0') ? 'unlink' : 'link-alt' }}"></i>
								</div>
								<input type="url" name="url_{{ $item->brand.$item->id }}" id="url-{{ $item->brand.$item->id }}" class="form-control change-input-edited input-{{ $item->brand.$item->id }}" value="{{ $item->url }}" data-edit="{{ $item->brand.$item->id }}" placeholder="isi url disini" {{ ($item->actived=='0') ? 'readonly' : null }}>
							</div>
							<input type="radio" name="edit_{{ $item->brand.$item->id }}" id="edit-{{ $item->brand.$item->id }}" value="true">
						</div>
					</div>
					@empty
					<div class="empty"></div>
					@endforelse
				</div>
			</div>
		</div>
	</form>
</div>
@endsection

@push('style')
	<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@push('script')
@endpush