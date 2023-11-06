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
                        <b>{{ count($data->event) }}</b>/<b class="text-creasik-primary">{{ $data->limitEvent }}</b> Acara
                    </div>
                    <div>
                        <a href="#" data-create="{{ route('menu.event-add') }}" class="d-block btn btn-creasik-primary w-lg-auto w-100 new-event">
                            <i class="bx bxs-bookmark-plus"></i>
                            <span>Acara baru</span>
                        </a>
                    </div>
                </div>
                @forelse ($data->event as $item)
                @php
                    $item->content = json_decode($item->content);
                    $item->content->time->end = ($item->content->time->done==true) ? 'selesai' : date('H:i', strtotime($item->content->time->end));
                    $item->content->location->address = ($item->content->location->sync==true) ? Str::ucfirst('sama dengan lokasi pernikahan') : $item->content->location->address;
                @endphp
                <div class="border-bottom mb-1 p-1 event-item">
                    <a href="{{ route('menu.event-show', $item->id) }}" data-edit="{{ route('menu.event-edit', $item->id) }}" class="icon mb-1 show-event"><i class="bx bxs-bookmark"></i></a>
                    <div class="title">
                        <a href="{{ route('menu.event-show', $item->id) }}" data-edit="{{ route('menu.event-edit', $item->id) }}" class="show-event">
                            {{ $item->title }}
                            <i class="bx bxs-edit text-dark"></i>
                        </a>
                        <div>
                            <i class="bx bx-time"></i>
                            Dari <span>{{ date('H:i', strtotime($item->content->time->start)) }}</span> s/d <span>{{ $item->content->time->end }}</span>
                        </div>
                        <div>
                            <i class="bx bx-map"></i>
                            {{ $item->content->location->address }}
                        </div>
                    </div>
                </div>
                @empty
                <div class="empty">Belum ada acara</div>
                @endforelse
            </div>
        </div>
        <div class="col-lg-7">
            <div class="bg-white shadow rounded position-relative p-3">
                <div class="overlay-event overlay">memuat</div>
                <form action="{{ route('menu.event-add') }}" class="save-menu" method="post">
                    @csrf
                    <div class="bg-light border rounded mb-2 p-2">
                        <div class="d-flex flex-row-reverse justify-content-between form-check form-switch ps-0">
                            <input class="form-check-input" type="checkbox" name="event_primary" id="event_primary" @checked(true)>
                            <label class="form-check-label" for="event_primary">Acara utama</label>
                        </div>
                    </div>
                    <div class="mb-2">
                        <div>
                            <label for="event_title" class="form-label">
                                <var dir="event_title">Judul acara</var>
                            </label>
                            <input type="text" name="event_title" id="event_title" class="form-control" placeholder="Judul acara">
                        </div>
                    </div>
                    <div class="mb-2">
                        <div>
                            <label for="event_content" class="form-label">
                                <var dir="event_content">Deskripsi</var>
                            </label>
                            <textarea name="event_content" id="event_content" class="form-control" placeholder="Deskripsi acara"></textarea>
                        </div>
                    </div>
                    <div class="mb-2">
                        <div class="border rounded">
                            <label for="event_time" class="fw-bold pt-2 ps-2">Waktu</label>
                            <div class="select-tab border-bottom">
                                <div class="ps-2">
                                    <label for="event_time_start" class="form-label">
                                        <var dir="event_time_start">Jam mulai</var>
                                    </label>
                                </div>
                                <div class="pe-2">
                                    <input type="time" name="event_time_start" id="event_time_start" class="form-control">
                                </div>
                            </div>
                            <div class="select-tab border-bottom">
                                <div class="ps-2">
                                    <label for="event_time_end" class="form-label">
                                        <var dir="event_time_end">Sampai</var>
                                    </label>
                                </div>
                                <div class="pe-2">
                                    <input type="time" name="event_time_end" id="event_time_end" class="form-control">
                                </div>
                            </div>
                            <div class="p-2">
                                <div class="form-check form-switch">
                                    <input class="form-check-input change-style" type="checkbox" name="event_time_done" id="event_time_done">
                                    <label class="form-check-label" for="event_time_done">Sampai selesai</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-2">
                        <div class="border rounded">
                            <label for="event_location" class="fw-bold pt-2 ps-2">Tempat</label>
                            <div class="select-tab border-bottom">
                                <div class="ps-2">
                                    <label for="event_location_address" class="form-label">
                                        <var dir="event_location_address">Alamat lengkap</var>
                                    </label>
                                </div>
                                <div class="pe-2">
                                    <textarea name="event_location_address" id="event_location_address" class="form-control" placeholder="Alamat lengkap"></textarea>
                                </div>
                            </div>
                            <div class="select-tab border-bottom">
                                <div class="ps-2">
                                    <label for="event_location_map" class="form-label">
                                        <var dir="event_location_map">Link lokasi</var>
                                    </label>
                                </div>
                                <div class="pe-2">
                                    <div class="input-group">
                                        <span class="input-group-text bg-white">
                                            <i class="bx bx-link"></i>
                                        </span>
                                        <input type="text" name="event_location_map" id="event_location_map" class="form-control border-start-0" placeholder="Link lokasi">
                                    </div>
                                </div>
                            </div>
                            <div class="p-2">
                                <div class="form-check form-switch">
                                    <input class="form-check-input change-style" type="checkbox" name="event_location_sync" id="event_location_sync">
                                    <label class="form-check-label" for="event_location_sync">Sama dengan alamat pernikahan</label>
                                </div>
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
                            <button type="button" class="btn btn-danger w-100 delete-event" data-url="0">
                                <i class="bx bxs-trash"></i>
                                <span>Hapus acara</span>
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
@endpush

@push('script')
<script>
    var enable = true;
    $(".change-style").on('change', function(e) {
        if (e.target.name=='event_time_done') {
            enable = (e.target.checked) ? true : false;
            $("input[name=event_time_end]").prop('disabled', enable);
        } else if (e.target.name=='event_location_sync') {
            enable = (e.target.checked) ? true : false;
            $("textarea[name=event_location_address]").prop('disabled', enable);
            $("input[name=event_location_map]").prop('disabled', enable);
        }
    });

    $(".new-event").on('click', function(e) {
        e.preventDefault();
        let form = $(".save-menu"),
            create_btn = $(".create-mode"),
            edit_btn = $(".edit-mode");
        create_btn.css('display', 'block');
        edit_btn.css('display', 'none');
        form.attr('action', $(this).data('create'));
        form.children('input[name=_method]').remove();
        $("sup[role=alert]").remove();
        $("input[name=event_title]").val(null).focus();
        $("textarea[name=event_content]").val(null);
        $("input[name=event_time_start]").val(null);
        $("input[name=event_time_end]").val(null);
        $("input[name=event_time_done]").prop('checked', false);
        $("textarea[name=event_location_address]").val(null);
        $("input[name=event_location_map]").val(null);
        $("input[name=event_location_sync]").prop('checked', false);
    });
    
    $(".show-event").on('click', function(e) {
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
                $(".overlay-event").css('display', 'flex');
            },
            success: function(response) {
                $(".overlay-event").css('display', 'none');
                $("button.delete-event").attr('data-url', response.url);
                $("input[name=event_primary]").prop('checked', response.content.primary);
                $("input[name=event_title]").val(response.title).focus();
                $("textarea[name=event_content]").val(response.content.content);
                $("input[name=event_time_start]").val(response.content.time.start);
                $("input[name=event_time_end]").val(response.content.time.end);
                $("input[name=event_time_done]").prop('checked', response.content.time.done);
                $("textarea[name=event_location_address]").val(response.content.location.address);
                $("input[name=event_location_map]").val(response.content.location.map);
                $("input[name=event_location_sync]").prop('checked', response.content.location.sync);
            }
        })
    })
</script>
@endpush