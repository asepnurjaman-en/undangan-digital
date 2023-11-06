@extends('guestbook.layouts.app')
@section('title', Str::title($menu['title']))   
@section('content')
<section class="position-relative py-3">
    @include('guestbook.layouts.component', ['content'=>'breadcrumb', 'menu'=>$menu])
    <div class="row g-3">
        <div class="col-lg-7">
            <div class="bg-white shadow rounded position-relative p-3">
                <a href="#" data-create="{{ route('menu.souvenir-add') }}" class="d-inline-block btn btn-creasik-outline-primary w-lg-auto mb-2 new-souvenir">
                    <i class="bx bx-plus"></i>
                    <span>Souvenir baru</span>
                </a>
                <div class="overlay-story overlay">memuat</div>
                <form action="{{ route('menu.souvenir-add') }}" class="save-menu" method="post">
                    @csrf
                    <div class="mb-2">
                        <div>
                            <label for="souvenir_title" class="form-label">
                                <var dir="souvenir_title">Nama souvenir</var>
                            </label>
                            <input type="text" name="souvenir_title" id="souvenir_title" class="form-control" placeholder="Nama souvenir">
                        </div>
                    </div>
                    <div class="mb-2">
                        <div class="row g-3">
                            <div class="col-12 col-lg-6">
                                <label for="souvenir_stock" class="form-label">
                                    <var dir="souvenir_stock">Stok</var>
                                </label>
                                <input type="number" name="souvenir_stock" id="souvenir_stock" class="form-control" placeholder="Stok souvenir" min="0">
                            </div>
                            <div class="col-12 col-lg-6">
                                <label for="souvenir_grade" class="form-label">
                                    <var dir="souvenir_grade">untuk tamu</var>
                                </label>
                                <select name="souvenir_grade" id="souvenir_grade" class="form-select">
                                    @foreach (['basic', 'premium', 'exclusive'] as $item)
                                    <option value="{{ $item }}">{{ Str::title($item) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-2">
                        <button type="button" class="btn btn-creasik-secondary mb-2 change-data" data-bs-toggle="modal" data-bs-target="#storage">
                            <i class="bx bx-images"></i>
                            <span>Penyimpanan</span>
                        </button>
                        <div class="souvenir-filler gallery-filler">
                            <div id="selected0" class="item" @style('display:none')>
                                <a href="#" class="unuse-image" data-target="selected0">
                                    <i class="bx bx-x"></i>
                                </a>
                                <img src="">
                                <input type="hidden" name="souvenir_file" value="">
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
                            <button type="button" class="btn btn-danger w-100 delete-souvenir" data-url="0">
                                <i class="bx bxs-trash"></i>
                                <span>Hapus cerita</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-lg-5">
            @forelse ($souvenir as $item)
            <div class="d-flex align-items-center justify-content-between gap-2 bg-white rounded shadow-sm p-3 mb-2">
                <div class="desc">
                    <h4 class="fs-5 mb-0">{{ $item->title }}</h4>
                    <span class="badge bg-light text-dark text-capitalize">{{ $item->grade }}</span>
                    <span class="small">Stok: <b>{{ $item->stock }}</b></span>
                </div>
                <div class="action">
                    <a href="{{ route('menu.souvenir-show', $item->id) }}" data-edit="{{ route('menu.souvenir-edit', $item->id) }}" class="show-souvenir text-creasik-primary fw-bold text-decoration-none me-2">
                        <i class="bx bx-edit"></i>
                        <span>Edit</span>
                    </a>
                </div>
            </div>
            @empty
            <div class="empty">
                Belum ada souvenir
            </div>
            @endforelse
            {!! $souvenir->links() !!}
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
    $(".new-souvenir").on('click', function(e) {
        e.preventDefault();
        let form = $(".save-menu"),
            create_btn = $(".create-mode"),
            edit_btn = $(".edit-mode");
        create_btn.css('display', 'block');
        edit_btn.css('display', 'none');
        form.attr('action', $(this).data('create'));
        form.children('input[name=_method]').remove();
        $("sup[role=alert]").remove();
        $("form.save-menu")[0].reset();
        $("input[name=souvenir_title]").focus();
        $("#selected0").css('display', 'none');
    });

    $(".show-souvenir").on('click', function(e) {
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
                $("button.delete-souvenir").attr('data-url', response.url);
                $("input[name=souvenir_title]").val(response.title).focus();
                $("input[name=souvenir_stock]").val(response.stock);
                $("select[name=souvenir_grade]").val(response.grade).change();
                if (response.file!='') {
                    $("input[name=souvenir_file]").val(response.file);
                    $(".souvenir-filler").children('.item').css('display', 'block');
                    $(".souvenir-filler").children('.item').children('img').attr('src', response.image);
                    console.log(response);
                } else {
                    $(".souvenir-filler").children('.item').css('display', 'none');
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
					image = `<div id="selected0" class="item"><a href="#" class="unuse-image" data-target="selected0"><i class="bx bx-x"></i></a><img src="${url}"><input type="hidden" name="souvenir_file" value="${source}"></div>`;
				return image;
			}
		}).get();
        $(".souvenir-filler").html(file);
        $("#storage").modal('hide');
        $("input[name='storage_file[]']").prop('checked', false);
	});
</script>
@endpush