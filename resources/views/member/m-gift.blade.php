@extends('member.layouts.app')
@section('title', Str::title($menu['title']))
@php
    $display = [
        'gift_show__check' => ($data->preset->show===true) ? true : false,
    ];
@endphp
@section('content')
<section class="position-relative py-3">
    @include('member.layouts.component', ['content'=>'breadcrumb', 'menu'=>$menu])
    <form action="{{ route('save.setting', 'gift') }}" class="save-menu" method="post">
        @csrf
        @method('put')
        <div class="row g-3">
            <div class="col-lg-6">
                <div class="bg-white shadow rounded p-3 mb-2">
                    <div class="form-check form-switch d-flex flex-row-reverse justify-content-between ps-0 py-1">
                        <input class="form-check-input change-style" type="checkbox" name="gift_show" id="gift_show" @checked($display['gift_show__check'])>
                        <label class="form-check-label" for="gift_show">Tampilkan <b>hadiah</b></label>
                    </div>
                </div>
                <div class="bg-white shadow rounded p-3 mb-2">
                    <h4>Keterangan</h4>
                    <div class="mb-2">
                        <div>
                            <label for="gift_title" class="form-label">
                                <var dir="gift_title">Judul</var>
                            </label>
                            <input type="text" name="gift_title" id="gift_title" class="form-control" value="{{ $data->preset->title }}" placeholder="Judul">
                        </div>
                    </div>
                    <div class="mb-2">
                        <div>
                            <label for="gift_content" class="form-label">
                                <var dir="gift_content">Deskripsi</var>
                            </label>
                            <textarea name="gift_content" id="gift_content" class="form-control" placeholder="Deskripsi">{{ $data->preset->content }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="bg-white shadow rounded p-3 mb-2">
                    <h4>Rekening</h4>
                    <div class="mb-2">
                        <div>
                            <label for="gift_bank_name" class="form-label">
                                <var dir="gift_bank_name">Nama pemegang rekening</var>
                            </label>
                            <input type="text" name="gift_bank_name" id="gift_bank_name" class="form-control" value="{{ $data->preset->bank->name }}" placeholder="Nama pemegang rekening">
                        </div>
                    </div>
                    <div class="mb-2">
                        <div>
                            <label for="gift_bank_code" class="form-label">
                                <var dir="gift_bank_code">Nomor rekening</var>
                            </label>
                            <input type="text" name="gift_bank_code" id="gift_bank_code" class="form-control" value="{{ $data->preset->bank->code }}" placeholder="Nomor rekening">
                        </div>
                    </div>
                    <div class="mb-2">
                        <div>
                            <label for="gift_bank_option" class="form-label">
                                <var dir="gift_bank_option">Bank</var>
                            </label>
                            <select name="gift_bank_option" id="gift_bank_option" class="form-select">
                                @foreach (['bca', 'bri', 'bsi', 'mandiri'] as $item)
                                <option value="{{ $item }}" @selected(isitsame($item, $data->preset->bank->option))>{{ Str::upper($item) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-none col-lg-5">
                @forelse ($data->gift as $item)
                @empty
                <div class="empty">belum ada hadiah</div>
                @endforelse
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