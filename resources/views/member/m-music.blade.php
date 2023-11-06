@extends('member.layouts.app')
@section('title', Str::title($menu['title']))   
@php
    $display = [
        'music_show__check' => ($data->preset->show===true) ? true : false,
    ];
@endphp
@section('content')
<section class="position-relative py-3">
    @include('member.layouts.component', ['content'=>'breadcrumb', 'menu'=>$menu])
    <div class="row g-3">
        <div class="col-lg-7">
            <form action="{{ route('save.setting', 'music') }}" class="save-menu" method="post">
                @csrf
                @method('put')
                <div class="bg-white shadow rounded p-3 mb-2">
                    <div class="form-check form-switch d-flex flex-row-reverse justify-content-between ps-0 py-1">
                        <input class="form-check-input change-style" type="checkbox" name="music_show" id="music_show" @checked($display['music_show__check'])>
                        <label class="form-check-label" for="music_show">Pakai <b>musik latar</b></label>
                    </div>
                </div>
                <div class="bg-white shadow rounded p-3 mb-2">
                    <h4>
                        <var dir="music_url">Pilih musik</var>
                    </h4>
                    <div class="current-music music-item mb-3">
                        <div>
                            <span class="set_music_title">{{ $data->preset->title }}</span>
                        </div>
                        @if(file_exists('storage/audio/'.$data->preset->url))
                        <audio src="{{ url('storage/audio/'.$data->preset->url) }}" class="set_music_url" controls controlsList="nodownload noplaybackrate"></audio>
                        @else
                        <small>Audio tidak ditemukan.</small>
                        @endif
                        <input type="hidden" name="music_title" id="music_title" class="set_music_title" value="{{ $data->preset->title }}">
                        <input type="hidden" name="music_url" id="music_url" class="set_music_url" value="{{ url('storage/audio/'.$data->preset->url) }}">
                    </div>
                    <div class="mb-1">
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bx bx-save"></i>
                            <span>Simpan</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-lg-5">
            @if ($data->custom=='custom')                    
            <div class="bg-white shadow rounded mb-2">
                <div class="accordion" id="expandMusic">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#expandUploadMusic" aria-expanded="false" aria-controls="expandUploadMusic">
                                <i class="bx bx-music me-1"></i>
                                <span>Pakai musik favorit kamu</span>
                            </button>
                        </h2>
                        <div id="expandUploadMusic" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#expandMusic">
                            <div class="accordion-body">
                                <form action="{{ route('menu.music-add') }}" method="post" class="save-menu" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-2">
                                        <div>
                                            <label for="music_title" class="form-label">
                                                <var dir="music_title">Judul musik</var>
                                            </label>
                                            <input type="text" name="music_title" id="music_title" class="form-control" placeholder="Judul">
                                        </div>
                                    </div>
                                    <div class="mb-2">
                                        <div>
                                            <label for="music_file" class="form-label">
                                                <var dir="music_file">File musik</var>
                                            </label>
                                            <input type="file" name="music_file" id="music_file" class="form-control" accept="audio/mpeg">
                                        </div>
                                    </div>
                                    <div class="mb-1">
                                        <button type="submit" class="btn btn-primary w-100">
                                            <i class="bx bx-upload"></i>
                                            <span>Unggah</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-3">
                    @if ($data->my_music!=null)
                    <div class="music-item">
                        <div>
                            <span>{{ $data->my_music->title }}</span>
                            <button type="button" class="btn btn-creasik-primary btn-sm use-music" data-url="{{ $data->my_music->content }}">Gunakan</button>
                        </div>
                        @if(file_exists('storage/audio/'.$data->preset->url))
                        <audio src="{{ url('storage/audio/'.$data->my_music->content) }}" controls controlsList="nodownload noplaybackrate"></audio>
                        @else
                        <small>Audio tidak ditemukan.</small>
                        @endif
                    </div>
                    @else
                    <div class="empty">
                        Belum ada musik pribadi
                    </div>
                    @endif
                </div>
            </div>
            @endif
            <div class="bg-white shadow rounded p-3">
                @forelse ($data->music as $item)
                <div class="music-item border-bottom">
                    <div>
                        <span>{{ $item->title }}</span>
                        <button type="button" class="btn btn-creasik-primary btn-sm use-music" data-url="{{ $item->content }}">Gunakan</button>
                    </div>
                    @if(file_exists('storage/audio/'.$data->preset->url))
                    <audio src="{{ url('storage/audio/'.$item->content) }}" controls controlsList="nodownload noplaybackrate"></audio>              
                    @else
                    <small>Audio tidak ditemukan.</small>
                    @endif
                </div>
                @empty
                <div class="empty">belum ada musik</div>
                @endforelse
            </div>
        </div>
    </div>
</section>
@endsection

@push('style')
@endpush

@push('script')
<script>
    $(".use-music").on('click', function(e) {
		e.preventDefault();
		let title, url, source, current_audio;
		title = $(this).siblings('span').text();
		url = $(this).data('url');
		source = $(this).parent().siblings('audio').attr('src');
		current_audio = document.getElementsByTagName('audio');
		$(".current-music").find('span.set_music_title').text(title);
		$(".current-music").find('audio.set_music_url').attr('src', source);
		$(".current-music").find('input.set_music_title').val(title);
		$(".current-music").find('input.set_music_url').val(url);
		$(".use-music").prop('disabled', false).text('Gunakan');
		$(this).prop('disabled', true).text('Digunakan');
		// stop audio
		for (var i = 0; i < current_audio.length; i++) {
			if (current_audio[i].paused==false) {
				current_audio[i].pause();
			}
		}
	});
</script>
@endpush