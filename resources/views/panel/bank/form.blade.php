@extends('panel.layouts.app')
@section('title', Str::title($data['title']))
@php
    $content = json_decode($package->content ?? "[]", true);
@endphp
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
                    <div class="col-12 col-md-8">
						<div class="border rounded p-3">
							<div class="form-group mb-2">
								<label for="name" class="form-label mb-1">{{ __('nama akun') }}</label>
								<input type="text" name="name" id="name" class="form-control" placeholder="{{ __('isi disini') }}" value="{{ $bank->name ?? old('name') }}">
							</div>
							<div class="form-group">
								<label for="content_code" class="form-label mb-1">{{ __('nomor rekening') }}</label>
								<div class="input-group">
									<span class="input-group-text">
										<i class="bx bx-health"></i>
									</span>
									<input type="text" name="content_code" id="content_code" class="form-control" placeholder="{{ __('isi disini') }}" value="{{ $bank->content->code ?? old('content_code') }}">
								</div>
							</div>
							<div class="form-group">
								<label for="file" class="form-label mb-1">{{ __('bank') }}</label>
								<div class="input-group">
									<span class="input-group-text">
										<i class="bx bx-credit-card"></i>
									</span>
									<select name="file" id="file" class="form-select">
										@foreach ($data['bank_list'] as $code => $name)
										<option value="{{ $code }}" {{ ($data['form']['class']=='form-update' && $bank->file==$code) ? 'selected' : null }}>{{ $name }}</option>
										@endforeach
									</select>
								</div>
							</div>
						</div>
					</div>
					<div class="col-12 col-md-4">
						<div class="card border mb-3">
							<div class="card-body">
								<div class="form-check form-switch">
									<input class="form-check-input" type="checkbox" name="publish" id="publish" value="publish" {{ ($data['form']['class']=='form-update' && $bank->publish!='publish') ? null : 'checked' }}>
									<label class="form-check-label" for="publish">{{ Str::title('publish') }}</label>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
@endsection

@push('style')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{ asset('modules/datatable/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('modules/select2/dist/css/select2.min.css') }}">
@endpush

@push('script')
<script src="{{ asset('modules/datatable/datatables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('modules/select2/dist/js/select2.min.js') }}" type="text/javascript"></script>
<script src="https://cdn.tiny.cloud/1/9itgriy90vlqq8utp58vwdgdp06frez49d36w3lv684grblh/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
@include('panel.layouts.storage-modal', ['mode'=>'single'])
@include('panel.layouts.input-youtube-modal')
@endpush