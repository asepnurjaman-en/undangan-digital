@extends('member.layouts.app')
@section('title', Str::title($menu['title']))
@php
    $display = [
        'live_show__check' => ($data->preset->live->show===true) ? true : false,
        'protocol_show__check' => ($data->preset->protocol->show===true) ? true : false,
    ];
@endphp
@section('content')
<section class="position-relative py-3">
    @include('member.layouts.component', ['content'=>'breadcrumb', 'menu'=>$menu])
    <form action="{{ route('save.setting', 'additional') }}" class="save-menu" method="post">
        @csrf
        @method('put')
        <div class="row g-3">
            <div class="col-lg-6">
                <div class="bg-white shadow rounded">
                    <div class="border-bottom p-3">
                        <h4>
                            <var dir="slug">Subdomain</var>
                        </h4>
                        <div class="mb-2">
                            <div>
                                <label for="slug" class="form-label">Ubah subdomain undanganmu</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0 pe-0" data-text="">
                                        {{ route('invitation.index') }}/
                                    </span>
                                    <input type="text" name="slug" id="slug" class="form-control border-start-0 ps-0" value="{{ Auth::user()->inv->slug }}" placeholder="Subdomain">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-3">
                        <div class="form-check form-switch d-flex flex-row-reverse justify-content-between ps-0 py-1">
                            @if ($data->liveAccess===true)
                            <input class="form-check-input change-style" type="checkbox" name="additional_live_show" id="additional_live_show" @checked($display['live_show__check'])>
                            @elseif ($data->liveAccess===false)
                            <input class="form-check-input" type="checkbox" name="additional_live_show" id="additional_live_show" @disabled(true)>
                            @endif
                            <label class="form-check-label" for="additional_live_show">Tampilkan <b>Live Stream</b></label>
                        </div>
                        <div class="form-check form-switch d-flex flex-row-reverse justify-content-between ps-0 py-1">
                            <input class="form-check-input change-style" type="checkbox" name="additional_protocol_show" id="additional_protocol_show" @checked($display['protocol_show__check'])>
                            <label class="form-check-label" for="additional_protocol_show">Tampilkan <b>Protokol</b></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="bg-white shadow rounded p-3">
                    <h4>
                        <var dir="additional_live_app">Live Stream</var>
                    </h4>
                    @if ($data->liveAccess===true)
                    <div class="d-grid select-grid">
                        @foreach (['youtube', 'google', 'zoom', 'twitch'] as $item)                            
                        <div>
                            <input type="radio" name="additional_live_app" id="{{ $item }}" value="{{ $item }}" @checked(isitsame($item, $data->preset->live->app))>
                            <label for="{{ $item }}">
                                <i class="bx bxl-{{ $item }}"></i>
                            </label>
                        </div>
                        @endforeach
                    </div>
                    <div class="select-tab">
                        <div>
                            <label for="additional_live_link" class="form-label">
                                <var dir="additional_live_link">Link live</var>
                            </label>
                        </div>
                        <div>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="bx bx-link"></i></span>
                                <input type="url" name="additional_live_link" id="additional_live_link" class="form-control border-start-0" value="{{ $data->preset->live->link }}" placeholder="Link livestream">
                            </div>
                        </div>
                    </div>
                    <div class="select-tab">
                        <div>
                            <label for="additional_live_content" class="form-label">
                                <var dir="additional_live_content">Teks keterangan</var>
                            </label>
                        </div>
                        <div>
                            <textarea name="additional_live_content" id="additional_live_content" class="form-control" placeholder="Teks keterangan">{{ $data->preset->live->content }}</textarea>
                        </div>
                    </div>                        
                    @elseif ($data->liveAccess===false)
                    <div class="p-5 lock">
                        <span class="d-block small text-muted text-center pt-5 pb-3">Live stream</span>
                    </div>
                    @endif
                </div>
            </div>
            <div class="col-12">
                <div class="bg-white shadow rounded p-3">
                    <h4>
                        <var dir="additional_protocol_code">Protocol Kesehatan</var>
                    </h4>
                    <div class="mb-2">
                        <div>
                            <label for="additional_protocol_title" class="form-label">
                                <var dir="additional_protocol_title">Judul</var>
                            </label>
                            <input type="text" name="additional_protocol_title" id="additional_protocol_title" class="form-control" value="{{ $data->preset->protocol->title }}" placeholder="Judul">
                        </div>
                    </div>
                    <div class="mb-2">
                        <div>
                            <label for="additional_protocol_content" class="form-label">
                                <var dir="additional_protocol_content">Deskripsi</var>
                            </label>
                            <textarea name="additional_protocol_content" id="additional_protocol_content" class="form-control" placeholder="Deskripsi">{{ $data->preset->protocol->content }}</textarea>
                        </div>
                    </div>
                    @forelse ($data->protocol as $item)
                    <div class="protocol-item bg-light border rounded mb-2">
                        <div class="p-2">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="additional_protocol_code" id="additional_protocol_code" value="{{ $item->id }}" @checked(isitsame($item->id, $data->preset->protocol->code))>
                                <label class="form-check-label" for="additional_protocol_code">{{ $item->title }}</label>
                            </div>
                        </div>
                        <div>
                            @forelse (json_decode($item->content) as $img)
                            <img src="{{ url('storage/protocol/'.$img) }}" alt="{{ $img }}">
                            @empty
                            <div class="empty">kosong</div>
                            @endforelse
                        </div>
                    </div>
                    @empty
                    <div class="empty">kosong</div>
                    @endforelse
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
@endpush

@push('script')
@endpush