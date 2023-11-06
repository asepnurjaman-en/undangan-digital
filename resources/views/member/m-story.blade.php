@extends('member.layouts.app')
@section('title', Str::title($menu['title']))   
@section('content')
<section class="position-relative py-3">
    @include('member.layouts.component', ['content'=>'breadcrumb', 'menu'=>$menu])
    <div class="row g-3">
        <div class="col-lg-5">
            <div class="bg-white shadow rounded p-3">
                <div class="d-lg-flex align-items-lg-center justify-content-lg-between d-block text-center border rounded mb-1 p-1">
                    <div class="fw-light py-2 ps-2">
                        <b>{{ count($data->story) }}</b>/<b class="text-creasik-primary">{{ $data->limitStory }}</b> Cerita
                    </div>
                    <div>
                        <a href="#" data-create="{{ route('menu.story-add') }}" class="d-block btn btn-creasik-primary w-lg-auto w-100 new-story">
                            <i class="bx bxs-bookmark-plus"></i>
                            <span>Cerita baru</span>
                        </a>
                    </div>
                </div>
                @forelse ($data->story as $item)
                <div class="border-bottom mb-1 p-1 story-item">
                    <a href="{{ route('menu.story-show', $item->id) }}" data-edit="{{ route('menu.story-edit', $item->id) }}" class="icon mb-1 show-story"><i class="bx bxs-bookmark-heart"></i></a>
                    <div class="title">
                        <a href="{{ route('menu.story-show', $item->id) }}" data-edit="{{ route('menu.story-edit', $item->id) }}" class="show-story">
                            {{ $item->title }}
                            <i class="bx bxs-edit text-dark"></i>
                        </a>
                        <div>
                            {{ substr($item->content, 0, 80) }}
                            &hellip;
                        </div>
                    </div>
                </div>
                @empty
                <div class="empty">Belum ada cerita</div>
                @endforelse
            </div>
        </div>
        <div class="col-lg-7">
            <div class="bg-white shadow rounded position-relative p-3">
                <div class="overlay-story overlay">memuat</div>
                <form action="{{ route('menu.story-add') }}" class="save-menu" method="post">
                    @csrf
                    <div class="mb-2">
                        <div>
                            <label for="story_title" class="form-label">
                                <var dir="story_title">Judul cerita</var>
                            </label>
                            <input type="text" name="story_title" id="story_title" class="form-control" placeholder="Judul cerita">
                        </div>
                    </div>
                    <div class="mb-2">
                        <div>
                            <label for="story_content" class="form-label">
                                <var dir="story_content">Cerita</var>
                            </label>
                            <textarea name="story_content" id="story_content" class="form-control" placeholder="Cerita"></textarea>
                        </div>
                    </div>
                    <div class="mb-2">
                        <button type="button" class="btn btn-creasik-primary mb-2 change-data" data-bs-toggle="modal" data-bs-target="#storage">
                            <i class="bx bx-images"></i>
                            <span>Penyimpanan</span>
                        </button>
                        <div class="story-filler gallery-filler">
                            <div id="selected0" class="item" @style('display:none')>
                                <a href="#" class="unuse-image" data-target="selected0">
                                    <i class="bx bx-x"></i>
                                </a>
                                <img src="">
                                <input type="hidden" name="story_file" value="">
                            </div>
                        </div>
                    </div>
                    <div class="border-top pt-2 mb-2">
                        <div class="create-mode">
                            <button type="submit" class="btn btn-creasik-primary w-100">
                                <i class="bx bx-check-circle"></i>
                                <span>Simpan</span>
                            </button>
                        </div>
                        <div class="edit-mode" @style('display:none')>
                            <button type="submit" class="btn btn-creasik-primary w-100 me-2">
                                <i class="bx bxs-check-circle"></i>
                                <span>Simpan</span>
                            </button>
                            <button type="button" class="btn btn-danger w-100 delete-story" data-url="0">
                                <i class="bx bxs-trash"></i>
                                <span>Hapus cerita</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@push('style')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{ asset('modules/datatable/datatables.min.css') }}">
@endpush

@push('script')
@include('member.layouts.component', ['content'=>'modal-storage', 'mode'=>'single'])
<script src="{{ asset('modules/datatable/datatables.min.js') }}" type="text/javascript"></script>
<script>
    $(".new-story").on('click', function(e) {
        e.preventDefault();
        let form = $(".save-menu"),
            create_btn = $(".create-mode"),
            edit_btn = $(".edit-mode");
        create_btn.css('display', 'block');
        edit_btn.css('display', 'none');
        form.attr('action', $(this).data('create'));
        form.children('input[name=_method]').remove();
        $("sup[role=alert]").remove();
        $("input[name=story_title]").val(null).focus();
        $("textarea[name=story_content]").val(null);
        $("input[name=story_file]").val(null);
        $("#selected0").css('display', 'none');
    });

    $(".show-story").on('click', function(e) {
        e.preventDefault();
        let action = $(this).attr('href'),
            form = $(".save-menu"),
            create_btn = $(".create-mode"),
            edit_btn = $(".edit-mode");
        create_btn.css('display', 'none');
        edit_btn.css('display', 'flex');
        form.attr('action', $(this).data('edit'));
        form.prepend(`<input type="hidden" name="_method" value="put">`);
        $("sup[role=alert]").remove();
        $.ajax({
            type: 'get',
			url : action,
			dataType: 'json',
            beforeSend: function() {
                console.log('loading');
                $(".overlay-story").css('display', 'flex');
            },
            success: function(response) {
                $(".overlay-story").css('display', 'none');
                $("button.delete-story").attr('data-url', response.url);
                $("input[name=story_title]").val(response.title).focus();
                $("textarea[name=story_content]").val(response.content);
                if (response.file!='') {
                    $("input[name=story_file]").val(response.file);
                    $(".story-filler").children('.item').css('display', 'block');
                    $(".story-filler").children('.item').children('img').attr('src', response.image);
                    console.log(response);
                } else {
                    $(".story-filler").children('.item').css('display', 'none');
                }
            }
        })
    });

    $(document).on('click', '.use-image', function(e) {
		var file = $("input[name='storage_file[]']").map(function() {
			if ($(this).prop('checked')) {
				let key = $(this).attr('id'),
					source = $(this).val(),
					url = $(this).siblings().children('img').attr('src'),
					image = `<div id="selected${key}" class="item"><a href="#" class="unuse-image" data-target="selected${key}"><i class="bx bx-x"></i></a><img src="${url}"><input type="hidden" name="story_file" value="${source}"></div>`;
				return image;
			}
		}).get();
        $(".story-filler").html(file);
        $("#storage").modal('hide');
        $("input[name='storage_file[]']").prop('checked', false);
	});
</script>
@endpush