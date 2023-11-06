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
							<label for="title" class="form-label mb-1">{{ __('judul') }}</label>
							<input type="text" name="title" id="title" class="form-control form-control-lg counting-input" placeholder="{{ __('isi disini') }}" value="{{ $template->title ?? old('title') }}" maxlength="110">
							<ul class="list-unstyled small">
								<li><span class="counting fw-bold">{{ strlen($template->title ?? null) }}</span>/110</li>
							</ul>
						</div>
					</div>
					<div class="col-12 col-lg-8">
						<label class="form-label mb-1">{{ __('preset') }}</label>
						<div class="border rounded">
							<div class="p-3 border-bottom">
								<div class="row g-3">
									<div class="col-6">
										<div class="form-group mb-2">
											<label for="title_color" class="d-block form-label mb-1">Warna judul</label>
											<input class="border-0" type="color" id="title_color" name="title_color" value="{{ $template->preset->design->title->color }}" placeholder="isi disini" required="">
										</div>
										<div class="form-group">
											<label for="content_color" class="d-block form-label mb-1">Warna konten</label>
											<input class="border-0" type="color" id="content_color" name="content_color" value="{{ $template->preset->design->content->color }}" placeholder="isi disini" required="">
										</div>
									</div>
									<div class="col-6">
										<div class="form-group mb-2">
											<label for="button_color" class="d-block form-label mb-1">Warna tombol</label>
											<input class="border-0" type="color" id="button_color" name="button_color" value="{{ $template->preset->design->button->color }}" placeholder="isi disini" required="">
											<input class="border-0" type="color" id="button_background" name="button_background" value="{{ $template->preset->design->button->background }}" placeholder="isi disini" required="">
										</div>
										<div class="form-group">
											<label for="background" class="d-block form-label mb-1">Warna latar</label>
											<input class="border-0" type="color" id="background" name="background" value="{{ $template->preset->design->background }}" placeholder="isi disini" required="">
										</div>
									</div>
									<div class="col-12 col-lg-6">
										<div class="form-group mb-2">
											<label for="title_font" class="d-block form-label mb-1">Font judul</label>
											<select class="form-select" id="title_font" name="title_font">
												@foreach ($data['font'] as $item)
												<option value="{{ $item->content }}" style="font-family: '{{ $item->content }}'" @selected(isitsame($item->content, $template->preset->design->title->font))>{{ $item->title }}</option>
												@endforeach
											</select>
										</div>
									</div>
									<div class="col-12 col-lg-6">
										<div class="form-group mb-2">
											<label for="content_font" class="d-block form-label mb-1">Font konten</label>
											<select class="form-select" id="content_font" name="content_font">
												@foreach ($data['font'] as $item)
												<option value="{{ $item->content }}" style="font-family: '{{ $item->content }}'" @selected(isitsame($item->content, $template->preset->design->content->font))>{{ $item->title }}</option>
												@endforeach
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="p-3">
								<div class="row g-3">
									<div class="col-12 col-lg-6">
										<div class="form-group mb-2">
											<label for="photo_male" class="d-block form-label mb-1">Foto pria</label>
											<select class="form-select preview-image-option" id="photo_male" name="photo_male">
												@foreach ($data['avatar-male'] as $item)
												<option value="{{ $item->content }}" data-url="{{ url('storage/avatar/'.$item->content) }}" @selected(isitsame($item->content, $template->preset->profile->photo->male->image))>{{ $item->title }}</option>
												@endforeach
											</select>
											<img src="{{ url('storage/avatar/'.$template->preset->profile->photo->male->image) }}" class="img-fluid" alt="male">
										</div>
									</div>
									<div class="col-12 col-lg-6">
										<div class="form-group mb-2">
											<label for="photo_female" class="d-block form-label mb-1">Foto wanita</label>
											<select class="form-select preview-image-option" id="photo_female" name="photo_female">
												@foreach ($data['avatar-female'] as $item)
												<option value="{{ $item->content }}" data-url="{{ url('storage/avatar/'.$item->content) }}" @selected(isitsame($item->content, $template->preset->profile->photo->female->image))>{{ $item->title }}</option>
												@endforeach
											</select>
											<img src="{{ url('storage/avatar/'.$template->preset->profile->photo->female->image) }}" class="img-fluid" alt="male">
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-12 col-lg-4">
						<div class="card border mb-3">
							<div class="card-body">
								@if ($data['form']['class']=='form-update' && $template->url=='no-file')
								<div class="alert alert-danger mb-0" role="alert">
									<i class="bx bx-error"></i>
									<span>Template tidak ditemukan</span>
								</div>
								@else
								<div class="form-check form-switch">
									<input class="form-check-input" type="checkbox" name="publish" id="publish" value="publish" {{ ($data['form']['class']=='form-update' && $template->publish=='publish') ? 'checked' : null }} @disabled(($data['form']['class']=='form-update') ? false : true)>
									<label class="form-check-label" for="publish">{{ Str::title('publish') }}</label>
								</div>
								@endif
							</div>
						</div>
						@if ($data['form']['class']=='form-update' && $template->url!='no-file')
						<div class="mb-3">
							<a href="{{ route('preview-template.index', $template->slug) }}" target="_BLANK" class="btn text-primary w-100 border shadow-sm">
								<i class="bx bx-link-external"></i>
								<span>{{ Str::title('preview') }}</span>
							</a>
						</div>
						@endif
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
								<input type="hidden" name="file_type" id="input-file-type" value="{{ $template->file_type ?? old('file_type') }}" readonly>
								<div id="thumbail-preview">
									@if ($data['form']['class']=='form-update')
									<div>
										<div class="item-image">
											@if ($template->file_type=='image')
											{!! image(src:url('storage/'.$template->file), alt:$template->file) !!}
											@elseif ($service->file_type=='video')
											{!! image(src:url('https://img.youtube.com/vi/'.$template->file.'/hqdefault.jpg'), alt:$template->file_type) !!}
											@endif
											<div class="overlay">
												<button title="button" class="remove unchoose-image">&times;</button>
												<h4>{{ Str::title($template->file_type) }}</h4>
												<input type="hidden" name="file" value="{{ $template->file }}">
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
									<label for="grade" class="form-label mb-1">{{ __('katalog') }}</label>
									<select name="grade" id="grade" class="form-control select2">
										@forelse (['basic', 'premium', 'exclusive'] as $item)
										<option value="{{ $item }}" {{ ($data['form']['class']=='form-update' && $template->grade==$item) ? 'selected' : null }}>{{ Str::title($item) }}</option>
										@empty
										<option>{{ __('empty') }}</option>
										@endforelse
									</select>
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
<link href="https://fonts.googleapis.com/css2?family=Caveat&family=Dancing+Script&family=Great+Vibes&family=Kaushan+Script&family=Nova+Cut&family=Raleway&family=Righteous&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('modules/datatable/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('modules/select2/dist/css/select2.min.css') }}">
@endpush

@push('script')
@include('panel.layouts.storage-modal', ['mode'=>'single'])
<script src="{{ asset('modules/datatable/datatables.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('modules/select2/dist/js/select2.min.js') }}" type="text/javascript"></script>
<script src="https://cdn.tiny.cloud/1/9itgriy90vlqq8utp58vwdgdp06frez49d36w3lv684grblh/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script>
	$(".preview-image-option").on('change', function(e) {
		let image = $(`select[name=${e.target.name}] :selected`).data('url');
		$(this).siblings('img').attr('src', image);
	});
</script>
@endpush