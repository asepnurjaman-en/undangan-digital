@extends('member.layouts.app')
@section('title', Str::title($menu['title']))
@php
    $display = [
        'additional_show__check' => ($data->preset->additional->show===true) ? false : true,
        'countdown_show__check' => ($data->preset->countdown->show===true) ? false : true,
        'save_show__check' => ($data->preset->calendar->save->show===true) ? false : true,
    ];
@endphp
@section('content')
<section class="position-relative py-3">
    @include('member.layouts.component', ['content'=>'breadcrumb', 'menu'=>$menu])
    <form action="{{ route('save.setting', 'detail') }}" class="save-menu" method="post">
        @csrf
        @method('put')
        <div class="row g-3">
            <div class="col-lg-7">
                <div class="bg-white shadow rounded p-3">
                    <h4>Waktu &amp; Tanggal</h4>
                    <div class="select-tab border-bottom">
                        <div>
                            <label for="detail_calendar_date" class="form-label">
                                <var dir="detail_calendar_date">Tanggal</var>
                            </label>
                        </div>
                        <div>
                            <input type="date" name="detail_calendar_date" id="detail_calendar_date" class="form-control" value="{{ $data->preset->calendar->date }}">
                        </div>
                    </div>
                    <div class="select-tab border-bottom">
                        <div>
                            <label for="detail_calendar_time" class="form-label">
                                <var dir="detail_calendar_time">Jam</var>
                            </label>
                        </div>
                        <div>
                            <input type="time" name="detail_calendar_time" id="detail_calendar_time" class="form-control" value="{{ $data->preset->calendar->time }}">
                        </div>
                    </div>
                    <div class="select-tab">
                        <div>
                            <label for="detail_calendar_timezone" class="form-label">
                                <var dir="detail_calendar_timezone">Zona waktu</var>
                            </label>
                        </div>
                        <div class="flex-column align-items-start">
                            @foreach ($data->timezone as $key => $item)
                            <span class="form-check">
                                <input type="radio" name="detail_calendar_timezone" id="timezone{{ $key }}" class="form-check-input" value="{{ $key }}" @checked(isitsame($key, $data->preset->calendar->timezone))>
                                <label for="timezone{{ $key }}" class="form-check-label me-2">{{ $item }}</label>
                            </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="bg-white shadow rounded p-3">
                    <h4>Alamat</h4>
                    <div class="mb-2">
                        <div>
                            <label for="detail_location_address" class="form-label">
                                <var dir="detail_location_address">Alamat lengkap</var>
                            </label>
                            <textarea name="detail_location_address" id="detail_location_address" class="form-control" placeholder="Alamat lengkap">{{ $data->preset->location->address }}</textarea>
                        </div>
                    </div>
                    <div class="mb-2">
                        <div>
                            <label for="detail_location_map" class="form-label">
                                <var dir="detail_location_map">Link lokasi</var>
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="bx bx-link"></i>
                                </span>
                                <input type="text" name="detail_location_map" id="detail_location_map" class="form-control border-start-0" value="{{ $data->preset->location->map }}" placeholder="Link lokasi">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="bg-white shadow rounded p-3 mb-3">
                    <h4>Hitung mundur</h4>
                    <div class="select-tab mb-2">
                        <div>
                            <label for="detail_countdown_style" class="form-label">
                                <var dir="detail_countdown_style">Style</var>
                            </label>
                        </div>
                        <div class="flex-column align-items-start">
                            @foreach ($data->style as $key => $item)
                            <span class="form-check">
                                <input type="radio" name="detail_countdown_style" id="style{{ $key }}" class="form-check-input" value="{{ $key }}" @checked(isitsame($key, $data->preset->countdown->style))>
                                <label for="style{{ $key }}" class="form-check-label me-2">{{ $item }}</label>
                            </span>
                            @endforeach
                        </div>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="detail_countdown_show" id="detail_countdown_show" @checked($display['countdown_show__check'])>
                        <label class="form-check-label" for="detail_countdown_show">Sembunyikan</label>
                    </div>
                </div>
                <div class="bg-white shadow rounded p-3">
                    <h4>Tombol simpan ke kalender</h4>
                    <div class="select-tab border-bottom mb-2">
                        <div>
                            <label for="detail_calendar_save_content" class="form-label">
                                <var dir="detail_calendar_save_content">Tombol simpan</var>
                            </label>
                        </div>
                        <div>
                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="bx bx-calendar"></i>
                                </span>
                                <input type="text" name="detail_calendar_save_content" id="detail_calendar_save_content" class="form-control border-start-0" value="{{ $data->preset->calendar->save->content }}" placeholder="Tombol simpan">
                            </div>
                        </div>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="detail_calendar_save_show" id="detail_calendar_save_show" @checked($display['save_show__check'])>
                        <label class="form-check-label" for="detail_calendar_save_show">Sembunyikan</label>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="bg-white shadow rounded p-3">
                    <h4>Kata penutup</h4>
                    <div class="mb-2">
                        <div>
                            <label for="detail_additional_closing" class="form-label">
                                <var dir="detail_additional_closing">Penutup</var>
                            </label>
                            <textarea name="detail_additional_closing" id="detail_additional_closing" class="form-control" placeholder="Penutup">{{ $data->preset->additional->closing }}</textarea>
                        </div>
                    </div>
                    <div class="mb-2">
                        <div>
                            <label for="detail_additional_specialguest" class="form-label">
                                <var dir="detail_additional_specialguest">Turut mengundang</var>
                            </label>
                            <input type="text" name="detail_additional_specialguest" id="detail_additional_specialguest" class="form-control add-tag" placeholder="Turut mengundang">
                        </div>
                        <div class="py-2 set_detail_additional_specialguest">
                            @forelse ($data->preset->additional->special as $key => $guest)
                            <div id="tamu-{{ $key }}" class="badge bg-creasik-primary p-2 mb-1">
                                {{ $guest }}
                                <input type="hidden" name="detail_additional_special[]" value="{{ $guest }}">
                                <a href="#" class="text-white ms-1 remove-text" data-target="tamu-{{ $key }}">&times;</a>
                            </div>
                            @empty
                            
                            @endforelse
                        </div>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="detail_additional_show" id="detail_additional_show" @checked($display['additional_show__check'])>
                        <label class="form-check-label" for="detail_additional_show">Sembunyikan</label>
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
<script>
    $(".add-tag").on('keypress', function(e) {
		if (e.which == 13) {
			e.preventDefault();
			if (e.target.name=='detail_additional_specialguest') {
				let code = e.timeStamp.toString().split('.').join('');
				$(".set_detail_additional_specialguest").append(`<div id="${code}" class="badge bg-primary p-2 me-1 mb-1">${e.target.value}<input type="hidden" name="detail_additional_special[]" value="${e.target.value}"><a href="#" class="text-white ms-1 remove-text" data-target="${code}">&times;</a></div>`);
				$(this).val(null);
			}
		}
	});
    $(document).on('click', '.remove-text', function(e) {
		e.preventDefault();
		let target = $(this).data('target');
		$(`#${target}`).remove();
	});
</script>
@endpush