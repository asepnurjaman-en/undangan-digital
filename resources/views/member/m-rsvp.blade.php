@extends('member.layouts.app')
@section('title', Str::title($menu['title']))   
@section('content')
<section class="position-relative py-3">
    @include('member.layouts.component', ['content'=>'breadcrumb', 'menu'=>$menu])
    <form action="{{ route('save.setting', 'rsvp') }}" class="save-menu" method="post">
        @csrf
        @method('put')
        <div class="row g-3">
            <div class="col-lg-6">
                <div class="bg-white shadow rounded p-3 mb-2">
                    <h4>Keterangan</h4>
                    <div class="mb-2">
                        <div>
                            <label for="rsvp_title" class="form-label">
                                <var dir="rsvp_title">Judul</var>
                            </label>
                            <input type="text" name="rsvp_title" id="rsvp_title" class="form-control" value="{{ $data->preset->title }}" placeholder="Judul">
                        </div>
                    </div>
                    <div class="mb-2">
                        <div>
                            <label for="rsvp_content" class="form-label">
                                <var dir="rsvp_content">Deskripsi</var>
                            </label>
                            <textarea name="rsvp_content" id="rsvp_content" class="form-control" placeholder="Deskripsi">{{ $data->preset->content }}</textarea>
                        </div>
                    </div>
                </div>
                <a href="{{ route('menu.presenting') }}" class="btn btn-creasik-outline-primary w-100">
                    <i class="bx bx-question-mark"></i>
                    <span>Lihat konfirmasi kehadiran</span>
                </a>
            </div>
            <div class="col-lg-6">
                <div class="bg-white shadow rounded p-3">
                    <h4>Tombol</h4>
                    <div class="border rounded px-2 pb-2 mb-2">
                        <label for="rsvp_yes_option" class="fw-bold pt-2">
                            <var dir="rsvp_yes_content">Teks hadir</var>
                        </label>
                        <div class="select-tab">
                            <div>
                                <label for="rsvp_yes_option" class="form-label">
                                    <var dir="rsvp_yes_option">Teks hadir</var>
                                </label>
                            </div>
                            <div>
                                <input type="text" name="rsvp_yes_option" id="rsvp_yes_option" class="form-control" value="{{ $data->preset->yes->option }}" placeholder="Teks hadir">
                            </div>
                        </div>
                        <textarea name="rsvp_yes_content" id="rsvp_yes_content" class="form-control" placeholder="Pesan ketika memilih hadir">{{ $data->preset->yes->content }}</textarea>
                    </div>
                    <div class="border rounded px-2 pb-2">
                        <label for="rsvp_no_option" class="fw-bold pt-2">
                            <var dir="rsvp_no_content">Teks tidak hadir</var>
                        </label>
                        <div class="select-tab">
                            <div>
                                <label for="rsvp_no_option" class="form-label">
                                    <var dir="rsvp_no_option">Teks tidak hadir</var>
                                </label>
                            </div>
                            <div>
                                <input type="text" name="rsvp_no_option" id="rsvp_no_option" class="form-control" value="{{ $data->preset->no->option }}" placeholder="Teks tidak hadir">
                            </div>
                        </div>
                        <textarea name="rsvp_no_content" id="rsvp_no_content" class="form-control" placeholder="Pesan ketika memilih tidak hadir">{{ $data->preset->no->content }}</textarea>
                    </div>
                    <div class="select-tab border-bottom">
                        <div>
                            <label for="rsvp_date" class="form-label">
                                <var dir="rsvp_date">Tanggal terakhir mengisi</var>
                            </label>
                        </div>
                        <div>
                            <input type="date" name="rsvp_date" id="rsvp_date" value="{{ $data->preset->date }}" class="form-control">
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
@endpush

@push('script')
@endpush