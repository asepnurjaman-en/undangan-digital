@extends('member.layouts.app')
@section('title', Str::title(implode(' - ', json_decode(Auth::user()->name, true))).' | Penyimpanan')
@section('content')
<section class="py-3">
    <ul class="nav nav-pills mb-3">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('profile') }}">
                <i class="bx bx-user-circle"></i>
                <span>Profil</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" aria-current="page">
                <i class="bx bx-images"></i>
                <span>Penyimpanan</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('transaction') }}">
                <i class="bx bx-receipt"></i>
                <span>Transaksi</span>
            </a>
        </li>
    </ul>
    <div class="bg-white rounded shadow p-3">
        <div class="row g-0">
            <div class="col-12">
                <table class="dataTables" data-list="{{ route('strbox.list', 'multiple') }}">
                </table>
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
