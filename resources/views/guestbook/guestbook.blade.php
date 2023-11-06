@extends('guestbook.layouts.app')
@section('title', Str::title('buku tamu'))
@section('content')
<section class="py-3">
    <div class="row g-3">
        <div class="col-12 col-lg-5">
            <div class="bg-white rounded shadow text-center p-3">
                <form action="" method="post">
                    <h3 class="fw-bold mb-3">Tamu undangan</h3>
                    <p>Cari nama tamu atau <b>scan barcode</b> undangan.</p>
                    <div>
                        <input type="text" class="form-control form-control-lg mb-2 text-center" placeholder="Cari tamu">
                        <button type="submit" class="btn btn-lg btn-creasik-primary d-block w-100">
                            <i class="bx bx-search"></i>
                            Cari
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-12 col-lg-7">
            <div class="bg-white rounded shadow p-3">
                <div class="preset-menu grid-3">
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
        
                    @endphp
                    <a href="{{ route($item['url']) }}" @class(['shadow-sm', 'rounded', 'p-3', 'lock' => $lock[$item['id']]])>
                        <img src="{{ url('images/icons/'.$item['icon']) }}" alt="{{ $item['title'] }}">
                        <span>{{ Str::upper($item['title']) }}</span>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="bg-white rounded shadow p-3">
                <div class="row g-3">
                    <div class="col-12 col-lg-4">
                        <a href="{{ route('menu.presenting') }}" class="btn btn-creasik-secondary w-100 mb-2">
                            <i class="bx bx-question-mark"></i>
                            <span>Kelola data tamu undangan</span>
                        </a>
                        <div class="bg-white shadow-sm rounded p-3 mb-2">
                            <h4>Kuota tamu</h4>
                            <div class="d-flex justify-content-between">
                                @if ((int)$data->limitGuest>0)
                                <div class="progress" data-max="{{ $data->limitGuest }}" data-value="{{ $data->guest }}"></div>
                                @else
                                <div class="infinity-box">
                                    <h1 class="text-creasik-primary mb-0">{{ $data->limitGuest }}</h1>
                                </div>
                                @endif
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
                        <a href="{{ route('menu.presenting') }}" class="btn btn-creasik-outline-primary w-100">
                            <i class="bx bx-question-mark"></i>
                            <span>Lihat konfirmasi kehadiran</span>
                        </a>
                    </div>
                    <div class="col-12 col-lg-8">
                        <table class="dataTables" data-list="{{ route('strbox.list', 'multiple') }}">
                        </table>
                    </div>
                </div>
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
<script src="{{ asset('modules/datatable/datatables.min.js') }}" type="text/javascript"></script>
@endpush