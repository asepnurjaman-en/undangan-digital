@extends('member.layouts.app')
@section('title', Str::title('dashboard'))
@php
	use Carbon\Carbon;
    $activation = Auth::user()->invoice[0]->date;
    $active = json_decode(Auth::user()->invoice[0]->pack->content)->active;
    $template = json_decode(Auth::user()->invoice[0]->pack->content)->template;
@endphp
@section('content')
<section class="py-3">
    <div class="row flex-lg-row-reverse g-3 mb-3">
        <div class="col-12 col-lg-3">
            <div class="package-book bg-white shadow-sm rounded text-center p-2 mb-3">
                <span>Paket</span>
                <b class="text-creasik-primary">{{ Auth::user()->invoice[0]->pack->title }}</b>
                @if (isexpired($activation, $active)===false)
                <span class="text-muted">
                    Aktif
                    @if (Carbon::parse($activation)->addDays($active)->diffInDays() <= 1)
                    {{ Carbon::parse($activation)->locale('id')->addDays($active)->diffForHumans() }}
                    @else
                    sampai
                    {{ Carbon::parse($activation)->locale('id')->addDays($active)->settings(['formatFunction' => 'translatedFormat'])->format('j F Y') }}
                    @endif
                </span>
                @elseif (isexpired($activation, $active)===true)
                <span class="text-danger">Undangan telah kadaluwarsa.</span>
                @endif
                <a href="{{ route('packages') }}" class="bg-success text-white text-uppercase rounded w-100">
                    <i class="bx bx-chevrons-up"></i>
                    <span>upgrade</span>
                </a>
            </div>
            <div class="guest-book rounded p-2 {{ (Auth::user()->acc->guestbook==0) ? 'lock' : null }}">
                <a href="{{ route('guestbook') }}">
                    <img src="{{ url('images/icons/open-book_2702134.png') }}" alt="guestbook">
                    <span>Buku tamu</span>
                </a>
            </div>
        </div>
        <div class="col-12 col-lg-9">
            <div class="row g-3">
                <div class="col-lg-7">
                    <div class="dashboard-summary bg-white shadow-sm rounded">
                        <h4>{{ $data->name }}</h4>
                        <h6><a href="{{ route('invitation.index', $data->subdomain) }}" target="_BLANK">{{ route('invitation.index', $data->subdomain) }}</a></h6>
                        <img src="{{ url('storage/'.Auth::user()->inv->file) }}" alt="">
                        <div id="countdown" class="countdown" data-time="{{ date('Y-m-d', strtotime($data->date->date)) }}">
                            <ul class="list-unstyled mb-0">
                                <li><b id="days">0</b><span>Hari</span></li>
                                <li><b id="hours">0</b><span>Jam</span></li>
                                <li><b id="minutes">0</b><span>Menit</span></li>
                                <li><b id="seconds">0</b><span>Detik</span></li>
                            </ul>
                        </div>
                        <div class="text-center py-2">
                            <a href="{{ route('invitation.index', $data->subdomain) }}" class="btn btn-sm btn-creasik-primary me-1" target="_BLANK">
                                <i class="bx bx-link-external"></i>
                                <span>Tinjau</span>
                            </a>
                            <a href="{{ route('menu.detail') }}" class="btn btn-sm btn-creasik-primary">
                                <i class="bx bx-edit"></i>
                                <span>Ubah tanggal</span>
                            </a>
                        </div>
                    </div>
                    @if (!in_array($data->template, $template))
                    <div class="bg-warning rounded shadow p-3 mt-1">
                        <i class="bx bx-error"></i>
                        Kamu menggunakan template <b>{{ Str::title($data->template) }}</b>.
                    </div>
                    @endif
                </div>
                <div class="col-lg-5">
                    <div class="bg-white shadow-sm rounded p-3 {{ (Auth::user()->acc->guestbook==0) ? 'lock' : null }}">
                        <h4>Statistik</h4>
                        <div class="d-flex justify-content-between">
                            <div class="progress" data-max="3" data-value="1"></div>
                            <div class="progress-summary p-2">
                                <div>
                                    <span>Hadir</span>
                                    <b>0</b>
                                </div>
                                <div>
                                    <span>Tidak hadir</span>
                                    <b>0</b>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="preset-menu grid-5">
        @foreach ($menu as $item)
        @php
        $lock = [];
        // lock by guestbook
        if (in_array($item['id'], ['reservation', 'table-management', 'souvenir'])) :
            if (Auth::user()->acc->guestbook==1) :
                $lock[$item['id']] = false;
                $item['url'] = $item['url'];
            elseif (Auth::user()->acc->guestbook==0) :
                $lock[$item['id']] = true;
                $item['url'] = 'packages';
            endif;
        else :
            $lock[$item['id']] = false;
        endif;
        // **

        // lock by package
        if (in_array($item['id'], array_keys($conditional))) :
            $lock[$item['id']] = ($conditional[$item['id']]===false) ? true : false;
            $item['url'] = ($conditional[$item['id']]===false) ? 'packages' : $item['url'];
        endif;
        // **
        @endphp
        <a href="{{ route($item['url']) }}" @class(['shadow-sm', 'rounded', 'p-3', 'lock' => $lock[$item['id']]])>
            <img src="{{ url('images/icons/'.$item['icon']) }}" alt="{{ $item['title'] }}">
            <span>{{ Str::upper($item['title']) }}</span>
        </a>
        @endforeach
    </div>
</section>
@endsection

@push('script')
<script>
if ($("#countdown").length > 0) {
    (function () {
        const second = 1000,
        minute = second * 60,
        hour = minute * 60,
        day = hour * 24;
        let thetime = document.getElementById('countdown').getAttribute('data-time'),
            dateString = thetime+"T09:00:00+0000",
            dateStringISO = dateString.replace(/([+\-]\d\d)(\d\d)$/, "$1:$2");
        const countDown = new Date(dateStringISO).getTime(),
        x = setInterval(function() {
            const now = new Date().getTime(),
            distance = countDown - now;
            document.getElementById("days").innerText = Math.floor(distance / (day)),
            document.getElementById("hours").innerText = Math.floor((distance % (day)) / (hour)),
            document.getElementById("minutes").innerText = Math.floor((distance % (hour)) / (minute)),
            document.getElementById("seconds").innerText = Math.floor((distance % (minute)) / second);
            //do something later when date is reached
            if (distance < 0) {
                document.getElementById("countdown").innerText = `Acara telah selesai`;
                clearInterval(x);
            }
            // console.log(now);
        }, 0);
    }());
}
</script>
@endpush