@extends('member.layouts.app')
@section('title', Str::title($menu['title']))   
@section('content')
<section class="position-relative py-3">
    @include('member.layouts.component', ['content'=>'breadcrumb', 'menu'=>$menu])
    <form action="{{ route('save.setting', 'cover') }}" class="save-menu" method="post">
        @csrf
        @method('put')
        <div class="row g-3">
            <div class="col-lg-6">
                <div class="bg-white shadow-sm rounded p-3">
                    <h4>Nama pasangan</h4>
                    <div class="select-tab">
                        <div>
                            <label for="cover_name_female" class="form-label">
                                <var dir="cover_name_female">Nama wanita</var>
                            </label>
                        </div>
                        <div>
                            <input type="text" name="cover_name_female" id="name_female" class="form-control" value="{{ $data->preset->name->female }}" placeholder="Nama wanita">
                        </div>
                    </div>
                    <div class="select-tab border-bottom">
                        <div>
                            <label for="cover_name_male" class="form-label">
                                <var dir="cover_name_male">Nama pria</var>
                            </label>
                        </div>
                        <div>
                            <input type="text" name="cover_name_male" id="cover_name_male" class="form-control" value="{{ $data->preset->name->male }}" placeholder="Nama pria">
                        </div>
                    </div>
                    <div class="select-tab border-bottom mb-2">
                        <div>
                            <label for="cover_name_size" class="form-label">
                                <var dir="cover_name_size">Ukuran teks:</var>
                                <b class="set_cover_name_size">{{ $data->preset->name->size }}</b>
                            </label>
                        </div>
                        <div>
                            <input type="range" name="cover_name_size" id="cover_name_size" class="form-range change-style" value="{{ $data->preset->name->size }}" min="12" max="82" step="2">
                        </div>
                    </div>
                    <div class="select-tab border-bottom mb-2">
                        <div>
                            <label for="cover_name_style" class="form-label">
                                <var dir="cover_name_style">Style</var>
                            </label>
                        </div>
                        <div class="flex-column align-items-start">
                            @foreach ($data->style as $key => $item)
                            <span class="form-check">
                                <input type="radio" name="cover_name_style" id="style{{ $key }}" class="form-check-input" value="{{ $key }}" @checked(isitsame($key, $data->preset->name->style))>
                                <label for="style{{ $key }}" class="form-check-label me-2">{{ $item }}</label>
                            </span>
                            @endforeach
                        </div>
                    </div>
                    <div class="mb-2">
                        <div>
                            <label for="cover_content" class="form-label">
                                <var dir="cover_content">Teks sampul</var>
                            </label>
                            <textarea name="cover_content" id="cover_content" class="form-control" placeholder="Teks sampul">{{ $data->preset->content }}</textarea>
                        </div>
                    </div>
                    <div class="select-tab border-bottom">
                        <div>
                            <label for="cover_button" class="form-label">
                                <var dir="cover_button">Tombol buka</var>
                            </label>
                        </div>
                        <div>
                            <input type="text" name="cover_button" id="cover_button" class="form-control" value="{{ $data->preset->button }}" placeholder="Tombol buka">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="bg-white shadow-sm rounded p-3 mb-3">
                    <h4>Deskripsi</h4>
                    <div class="border-bottom mb-2">
                        <div>
                            <label for="cover_description_top" class="form-label">
                                <var dir="cover_description_top">Teks bagian atas</var>
                            </label>
                            <textarea name="cover_description_top" id="cover_description_top" class="form-control" placeholder="Teks bagian atas">{{ $data->preset->description->top }}</textarea>
                        </div>
                    </div>
                    <div class="border-bottom mb-2">
                        <div>
                            <label for="cover_description_bottom" class="form-label">
                                <var dir="cover_description_bottom">Teks bagian bawah</var>
                            </label>
                            <textarea name="cover_description_bottom" id="cover_description_bottom" class="form-control" placeholder="Teks bagian bawah">{{ $data->preset->description->bottom }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="bg-white shadow-sm rounded p-3">
                    <h4>
                        <var dir="cover_description_image">Foto</var>
                    </h4>
                    <div class="image-selector">
                        <div>
                            @if ($data->preset->description->image->method=='storage')
                            <img src="{{ url('storage/sm/'.$data->preset->description->image->image) }}" class="set_cover_description_image" alt="">
                            @elseif ($data->preset->description->image->method=='avatar')
                            <img src="{{ url('storage/avatar/'.$data->preset->description->image->image) }}" class="set_cover_description_image" alt="">
                            @endif
                        </div>
                        <div>
                            <div class="border-bottom py-2">
                                <input type="file" name="cover_description_image" id="cover_description_image" class="change-img" accept=".jpg,.png,.jpeg">
                                <input type="hidden" name="cover_description_image__filename" value="{{ $data->preset->description->image->image }}" readonly>
                                <input type="hidden" name="cover_description_image__method" value="{{ $data->preset->description->image->method }}" readonly>
                                <label class="form-label">
                                    <var dir="cover_description_image__method">Ganti</var>
                                </label>
                                <label for="cover_description_image" class="btn btn-creasik-primary d-inline-block mb-1">
                                    <i class="bx bx-upload"></i>
                                    <span>Unggah</span>
                                </label>
                                <button type="button" class="btn btn-creasik-primary mb-1" data-bs-toggle="modal" data-bs-target="#avatar-none">
                                    <i class="bx bx-user-circle"></i>
                                    <span>Avatar</span>
                                </button>
                                <button type="button" class="btn btn-creasik-primary mb-1 change-data" data-image="for-cover" data-bs-toggle="modal" data-bs-target="#storage">
                                    <i class="bx bx-images"></i>
                                    <span>Penyimpanan</span>
                                </button>
                            </div>
                            <div class="py-2">
                                <button type="button" class="btn btn-light mb-1" @disabled(true)>
                                    <i class="bx bx-crop"></i>
                                    <span>Potong</span>
                                </button>
                                <button type="button" class="btn btn-outline-danger remove-image" data-target="cover_description_image">
                                    <i class="bx bx-trash"></i>
                                    <span>Hilangkan</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="save-button">
            <button type="submit">
                <i class="bx bx-save"></i>
                <span>simpan</span>
            </button>
        </div>
    </form>
</section>
@endsection

@push('style')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{ asset('modules/datatable/datatables.min.css') }}">
@endpush

@push('script')
@include('member.layouts.component', ['content'=>'modal-avatar', 'gender'=>'none'])
@include('member.layouts.component', ['content'=>'modal-storage', 'mode'=>'single'])
<script src="{{ asset('modules/datatable/datatables.min.js') }}" type="text/javascript"></script>
<script>
    $(".change-asset").on('change', function(e) {
		e.preventDefault();
		if (e.target.name=='cover_avatar_none') {
			let avatar = $(this).siblings('img').attr('src'),
				source = $(this).val();
			$(".set_cover_description_image").attr('src', avatar);
			$("input[name=cover_description_image__filename]").val(source);
			$("input[name=cover_description_image__method]").val('avatar');
			$("input[name=cover_description_image]").parent().siblings().children().find('.remove-image').prop('disabled', false);
            $("input[name=cover_description_image]").parent().parent().find('.remove-image').prop('disabled', false);
		}
	});
    $(".change-data").on('click', function(e) {
		e.preventDefault();
		let data = $(this).data('image');
		$(".use-image").attr('data-image', data);
	});
    $(".change-img").on('change', function(e) {
		if (e.target.name=='cover_description_image') {
			const file = e.target.files[0];
			if (file) {
				let reader = new FileReader();
				reader.onload = function(render) {
					$(".set_cover_description_image").attr('src', render.target.result);
				}
				reader.readAsDataURL(file);
				$("input[name=cover_description_image__method]").val('upload');
			}
            $("input[name=cover_description_image]").parent().parent().find('.remove-image').prop('disabled', false);
        }
    });
    $(".change-style").on('change', function(e) {
        if (e.target.name=='cover_name_size') {
            $(".set_cover_name_size").text(e.target.value);
        }
    });
    $(".remove-image").on('click', function(e) {
		e.preventDefault();
		if ($(this).data('target')=='cover_description_image') {
            $(".set_cover_description_image").attr('src', null);
			$("input[name=cover_description_image]").val(null);
			$("input[name=cover_description_image__filename]").val(null);
			$("input[name=cover_description_image__method]").val(null);
            $("input[name=cover_avatar_none]").prop('checked', false);
		}
		$(this).prop('disabled', true);
	});
    $(".use-image").on('click', function(e) {
		var file = $("input[name='storage_file[]']").map(function() {
			if ($(this).prop('checked')) {
				let key = $(this).attr('id'),
					source = $(this).val(),
					url = $(this).siblings().children('img').attr('src');
				if (e.target.dataset.image=='for-cover') {
                    $(".set_cover_description_image").attr('src', url);
					$("input[name=cover_description_image__filename]").val(source);
					$("input[name=cover_description_image__method]").val('storage');
                    $("input[name=cover_description_image]").parent().parent().find('.remove-image').prop('disabled', false);
				}
			}
		});
		$("#storage").modal('hide');
	});
</script>
@endpush