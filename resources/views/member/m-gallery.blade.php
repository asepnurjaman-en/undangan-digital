@extends('member.layouts.app')
@section('title', Str::title($menu['title']))
@php
    $display = [];
    if ($data->photo!=null) :
        $photo = json_decode($data->photo->content);
        $countPhoto = count($photo->file);
        $display['gallery_show__check'] = ($photo->show===true) ? false : true;       
    endif;
    if ($data->video!=null) :
        $video = json_decode($data->video->content ?? "[]");
        $countVideo = ($video->url!='') ? 1 : 0;
    endif;
@endphp
@section('content')
<section class="position-relative py-3">
    @include('member.layouts.component', ['content'=>'breadcrumb', 'menu'=>$menu])
    <form action="{{ route('menu.gallery-add') }}" class="save-menu" method="post" enctype="multipart/form-data">
        @csrf
        @method('put')
        <div class="row g-3">
            <div class="col-lg-7">
                <div class="bg-white shadow rounded p-3">
                    <h4>Galeri foto</h4>
                    <div class="mb-2">
                        <div>
                            <label for="gallery_title" class="form-label">
                                <var dir="gallery_title">Judul</var>
                            </label>
                            <input type="text" name="gallery_title" id="gallery_title" class="form-control" value="{{ $data->photo->title ?? null }}" placeholder="Judul">
                        </div>
                    </div>
                    <div class="mb-2">
                        <div>
                            <label for="gallery_content" class="form-label">
                                <var dir="gallery_content">Deskripsi</var>
                            </label>
                            <textarea name="gallery_content" id="gallery_content" class="form-control" placeholder="Deskripsi">{{ $photo->content ?? null }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="bg-white shadow rounded p-3 mb-3">
                    <h4>Tampilan galeri</h4>
                    <div class="select-tab border-bottom mb-2">
                        <div>
                            <label for="gallery_style" class="form-label">
                                <var dir="gallery_style">Style</var>
                            </label>
                        </div>
                        <div class="flex-column align-items-start">
                            @foreach ($data->style as $key => $item)
                            <span class="form-check">
                                <input type="radio" name="gallery_style" id="style{{ $key }}" class="form-check-input" value="{{ $key }}" @checked(isitsame($key, $photo->style ?? null))>
                                <label for="style{{ $key }}" class="form-check-label me-2">{{ $item }}</label>
                            </span>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="gallery_show" id="gallery_show" @checked($display['gallery_show__check'] ?? false)>
                        <label class="form-check-label" for="gallery_show">Sembunyikan</label>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="bg-white shadow rounded p-3 mb-1">
                    <div class="d-flex justify-content-between">
                        <h4>
                            <var>Isi galeri</var>
                        </h4>
                        <span class="d-inline-block bg-light rounded px-2 py-1">
                            <b class="set_count_photo" data-limit="{{ ($data->limitPhoto=='∞') ? 500 : $data->limitPhoto }}">{{ $countPhoto ?? 0 }}</b>/<b class="text-creasik-primary">{{ $data->limitPhoto }}</b>
                        </span>
                    </div>
                    <div class="gallery-filler">
                        <div class="item">
                            <button type="button" data-bs-toggle="modal" data-bs-target="#storage">
                                <i class="bx bx-plus-circle"></i>
                            </button>
                        </div>
                        @if ($data->photo!=null)
                        @foreach ($photo->file as $key => $item)
                        <div id="selected{{ $key }}" class="item">
                            <a href="#" class="unuse-image" data-target="selected{{ $key }}">
                                <i class="bx bx-x"></i>
                            </a>
                            <img src="{{ url('storage/sm/'.$item) }}">
                            <input type="hidden" name="gallery_file[]" value="{{ $item }}">
                        </div>
                        @endforeach
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="bg-white shadow rounded p-3">
                    <div class="row">
                        <div class="col-lg-7">
                            <h4>Video</h4>
                            <div class="mb-2">
                                <div>
                                    <label for="video_title" class="form-label">
                                        <var dir="video_title">Judul</var>
                                    </label>
                                    <input type="text" name="video_title" id="video_title" class="form-control" value="{{ $data->video->title ?? null }}" placeholder="Judul">
                                </div>
                            </div>
                            <div class="mb-2">
                                <div>
                                    <label for="video_content" class="form-label">
                                        <var dir="video_content">Deskripsi</var>
                                    </label>
                                    <textarea name="video_content" id="video_content" class="form-control" rows="5" placeholder="Deskripsi">{{ $video->content ?? null }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5">
                            <div class="mb-2">
                                <div>
                                    <div class="d-flex justify-content-between">
                                        <h4>
                                            <var dir="video_url">Youtube share link</var>
                                        </h4>
                                        <span class="d-inline-block bg-light rounded px-2 py-1">
                                            <b>{{ $countVideo ?? 0 }}</b>/<b class="text-creasik-primary">{{ $data->limitVideo }}</b>
                                        </span>
                                    </div>
                                    <img src="" alt="">
                                    <div class="input-group">
                                        <span class="input-group-text bg-white"><i class="bx bx-link"></i></span>
                                        <input type="url" name="video_url" id="video_url" class="form-control border-start-0" value="https://youtu.be/{{ $video->url ?? null }}" placeholder="Link livestream">
                                    </div>
                                </div>
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
@include('member.layouts.component', ['content'=>'modal-storage', 'mode'=>'multiple'])
<script src="{{ asset('modules/datatable/datatables.min.js') }}" type="text/javascript"></script>
<script>
    $(document).on('click', '.use-image', function(e) {
        let count = $(".set_count_photo"),
            limit = count.data('limit'),
            counting = parseInt(count.text());
		var file = $("input[name='storage_file[]']").map(function() {
			if ($(this).prop('checked')) {
                counting = counting+1;
				let key = $(this).attr('id'),
					source = $(this).val(),
					url = $(this).siblings().children('img').attr('src'),
					image = `<div id="selected${key}" class="item"><a href="#" class="unuse-image" data-target="selected${key}"><i class="bx bx-x"></i></a><img src="${url}"><input type="hidden" name="gallery_file[]" value="${source}"></div>`;
				return image;
			}
		}).get();
        if (counting <= limit) {
            count.text(counting);
            $(".gallery-filler").append(file);
            $("#storage").modal('hide');
            $("input[name='storage_file[]']").prop('checked', false);
        } else {
            alert('kuota penuh');
        }
	});
</script>
@endpush