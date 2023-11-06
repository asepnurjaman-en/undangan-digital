@extends('member.layouts.app')
@section('title', Str::title($menu['title']))
@php
    $amount = 0;
@endphp
@section('content')
<section class="position-relative py-3">
    @include('member.layouts.component', ['content'=>'breadcrumb', 'menu'=>$menu])
    <div class="bg-white p-3 mb-3">
        <h4>Ringkasan</h4>
        <ul class="list-unstyled">
            <li>
                Jumlah konfirmasi :
                <b>{{ count($data->present) }}</b>
            </li>
            <li>
                Jumlah tamu hadir:
                <b class="amount-sum">0</b>
            </li>
        </ul>
    </div>
    <div class="row g-3">
        @forelse ($data->present as $item)
        @php
            $item->prop = json_decode($item->content);
            if ($item->prop->option=='yes') :
                $amount = $amount + $item->prop->amount;
            endif;
        @endphp
        <div class="col-lg-4">
            <div class="bg-white shadow rounded p-2">
                <h6 class="mb-1">{{ $item->prop->name }}</h6>
                <div>
                    Hadir :
                    @if ($item->prop->option=='yes')
                    <span class="badge bg-success">Ya</span>
                    @elseif ($item->prop->option=='no')
                    <span class="badge bg-danger">Tidak</span>
                    @endif
                </div>
                <div>
                    @if ($item->prop->option=='yes')
                    Jumlah Tamu:
                    <span class="fw-bold text-dark">{{ $item->prop->amount }}</span>
                    @else
                    -
                    @endif
                </div>
                <small class="text-muted">Diisi pada {{ date('d/m/Y H:i', strtotime($item->created_at)) }}</small>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="empty">belum ada konfirmasi kehadiran</div>
        </div>
        @endforelse
    </div>
</section>
@endsection

@push('style')
@endpush

@push('script')
<script>
    $(".amount-sum").text({{ $amount }});
</script>
@endpush