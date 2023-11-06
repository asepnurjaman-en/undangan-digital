@extends('panel.layouts.app')
@section('title', Str::title($data['title']))
@section('content')
<div class="container">
	<form action="{{ $data['form']['action'] }}" method="post" enctype="multipart/form-data" class="{{ $data['form']['class'] }} my-3">
		@csrf
		@if ($data['form']['class']=='form-update')
			@method('PATCH')
		@endif
		<div class="card border-0 my-3">
			<div class="card-header p-3">
				<div class="form-group d-flex justify-content-between">
					<button type="button" class="btn-back btn btn-outline-secondary">
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
				<div class="row">
					<div class="col-12">
						<div class="form-group mb-3">
							<label for="title" class="form-label mb-1">{{ __('nama') }}</label>
							<input type="text" name="title" id="title" class="form-control form-control-lg counting-input" placeholder="{{ __('isi disini') }}" value="{{ $strbox->title ?? old('title') }}" maxlength="110">
							<ul class="list-unstyled small">
								<li><span class="counting fw-bold">{{ strlen($strbox->title ?? null) }}</span>/110</li>
							</ul>
						</div>
					</div>
					<div class="col-12 col-md-5">
						<div class="card mb-3">
							<div class="card-body p-0">
								@if ($strbox->file_type=='image')
								{!! image(src:url('storage/sm/'.$strbox->file), alt:$strbox->file, class:['img-fluid', 'rounded']) !!}									
								@elseif ($strbox->file_type=='video')
								{!! video(src:url('storage/'.$strbox->file)) !!}
								@endif
							</div>
						</div>
					</div>
					<div class="col-12 col-md-6"></div>
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