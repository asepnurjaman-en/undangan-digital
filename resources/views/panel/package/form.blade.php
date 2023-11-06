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
					<div class="col-12">
						<div class="form-group mb-3">
							<label for="title" class="form-label mb-1">{{ __('judul') }}</label>
							<input type="text" name="title" id="title" class="form-control form-control-lg counting-input" placeholder="{{ __('isi disini') }}" value="{{ $package->title ?? old('title') }}" maxlength="110">
							<ul class="list-unstyled small">
								<li><span class="counting fw-bold">{{ strlen($package->title ?? null) }}</span>/110</li>
							</ul>
						</div>
					</div>
                    <div class="col-12 col-md-8">
						<div class="border rounded p-3">
							@foreach (json_decode($data['form_content']->content, true) as $key => $item)
							<div class="row mb-3">
								<label class="col-sm-4 col-form-label" for="content_{{ $key }}">{{ $item }}</label>
								<div class="col-sm-8">
									{{-- @if (in_array($key, ['smart-wa', 'manual-wa'])) --}}
									@if (in_array($key, ['active']))
									<div class="input-group">
										<input type="number" class="form-control" name="content[{{ $key }}]" id="content_{{ $key }}" placeholder="{{ $item }}" value="{{ $content[$key] ?? null }}" min="0">
										<span class="input-group-text">Hari</span>
									</div>
									@elseif (in_array($key, ['gift', 'e-invitation', 'filter-ig', 'story', 'event', 'live-stream', 'private-invitation', 'free-text', 'smart-wa', 'manual-wa']))
									<div class="form-check form-switch mb-2">
										<input class="form-check-input" type="checkbox" name="content[{{ $key }}]" id="content_{{ $key }}" @checked($content[$key] ?? false)>
										<label class="form-check-label" for="content_{{ $key }}">Aktifkan</label>
									  </div>
									@elseif (in_array($key, ['gallery-video', 'gallery-photo', 'guest', 'event-count', 'story-count']))
									<div class="input-group">
										<input type="number" class="form-control" name="content[{{ $key }}]" id="content_{{ $key }}" placeholder="{{ $item }}" value="{{ $content[$key] ?? null }}" min="0" @disabled(isitsame($content[$key] ?? false, 'unlimited'))>
										<div class="input-group-text">
											<label class="d-flex align-items-center">
												<input class="form-check-input mt-0 me-2" type="checkbox" name="content_{{ $key }}_unlimited" aria-label="Unlimited" value="unlimited" @checked(isitsame($content[$key] ?? false, 'unlimited'))>
												Unlimited
											</label>
										</div>
									</div>
									@elseif (in_array($key, ['template']))
									@foreach (['basic', 'premium', 'exclusive'] as $item)
									<div class="form-check form-check-inline mt-1">
										<input class="form-check-input" type="checkbox" name="content[{{ $key }}][]" id="{{ $key.$item }}" value="{{ $item }}" {{ in_array($item, $content[$key] ?? []) ? 'checked' : null }}>
										<label class="form-check-label" for="{{ $key.$item }}">{{ Str::title($item) }}</label>
									</div>
									@endforeach
									@elseif (in_array($key, ['music']))
									<select name="content[{{ $key }}]" id="content_{{ $key }}" class="form-select">
										@foreach (['custom', 'template'] as $item)
										<option value="{{ $item }}" @selected(isitsame($item, $content[$key] ?? null))>{{ Str::title($item) }}</option>
										@endforeach
									</select>
									@else
									<input type="text" class="form-control" name="content[{{ $key }}]" id="content_{{ $key }}" placeholder="{{ $item }}" value="{{ $content[$key] ?? null }}">
									@endif
								</div>
							</div>
							@endforeach
						</div>
					</div>
					<div class="col-12 col-md-4">
						<div class="card border mb-3">
							<div class="card-body">
								<div class="form-check form-switch">
									<input class="form-check-input" type="checkbox" name="publish" id="publish" value="publish" {{ ($data['form']['class']=='form-update' && $package->publish!='publish') ? null : 'checked' }}>
									<label class="form-check-label" for="publish">{{ Str::title('publish') }}</label>
								</div>
							</div>
						</div>
						<div class="card border mb-3">
							<div class="card-body p-3">
								<label class="form-label" for="upload-file">{{ Str::title('thumbnail') }}</label>
								<div class="btn-group d-flex justify-content-between">
									<label for="upload-file" class="btn btn-outline-primary change-file-type" data-file-type="upload-file">
										<i class="bx bx-upload" data-bs-toggle="tooltip" data-bs-original-title="Unggah Foto" data-bs-placement="bottom"></i>
										<input type="file" name="upload_file" id="upload-file" class="choose-image" hidden="" accept="image/png, image/jpeg">
									</label>
									<button type="button" class="btn btn-outline-info border-start-0 change-file-type" data-bs-toggle="modal" data-bs-target="#single-storage-modal" data-file-type="image">
										<i class="bx bx-image" data-bs-toggle="tooltip" data-bs-original-title="Buka Penyimpanan" data-bs-placement="bottom"></i>
									</button>
								</div>
								<input type="hidden" name="file_type" id="input-file-type" value="image" readonly>
								<div id="thumbail-preview">
									@if ($data['form']['class']=='form-update')
									<div>
										<div class="item-image">
											{!! image(src:url('storage/sm/'.$package->file), alt:$package->file) !!}
											<div class="overlay">
												<button title="button" class="remove unchoose-image">&times;</button>
												<h4>{{ Str::title($package->file) }}</h4>
												<input type="hidden" name="file" value="{{ $package->file }}">
											</div>
										</div>
									</div>
									@endif
								</div>
							</div>
						</div>
						<div class="card border mb-3">
							<div class="card-body">
								<div class="form-group">
									<label for="price" class="form-label mb-1">{{ __('harga') }}</label>
									<div class="input-group">
										<span class="input-group-text">Rp.</span>
										<input type="number" name="price" id="price" class="form-control" placeholder="{{ __('isi disini') }}" value="{{ $package->price ?? old('price') }}" min="0">
									</div>
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