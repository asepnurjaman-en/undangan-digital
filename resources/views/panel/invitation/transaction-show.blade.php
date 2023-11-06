@extends('panel.layouts.app')
@section('title', Str::title($data['title']))
@php
	use Carbon\Carbon;
	setlocale(LC_ALL, 'IND');
@endphp
@section('content')
<div class="container">
    <div class="mt-3">
        <div class="form-group d-flex justify-content-between">
            <button type="button" class="btn-back btn btn-outline-secondary">
                <i class="bx bx-chevron-left"></i>
                <span>{{ Str::title('kembali') }}</span>
            </button>
        </div>
    </div>
    <div class="card border-0 mt-3">
        <div class="card-header p-3">
            <h4>Transaksi</h4>
        </div>
        <div class="card-body p-3">
            <div class="row g-3 justify-content-between mb-3">
                <div class="col-lg-4">
                    <table class="table">
                        <tr>
                            <td>Nama</td>
                            <td>{{ $invoice->user->name }}</td>
                        </tr>
                        <tr>
                            <td>Email</td>
                            <td>{{ $invoice->user->email }}</td>
                        </tr>
                    </table>
				</div>
                <div class="col-lg-4">
                    <table class="table border">
                        <tr>
                            <td>No. Invoice</td>
                            <td><b class="text-primary">{{ $invoice->content->invoice_number.':'.$invoice->payment_code }}</b></td>
                        </tr>
                        <tr>
                            <td>Tanggal</td>
                            <td>{{ date('d/m/Y', strtotime($invoice->date)) }}</td>
                        </tr>
                        <tr>
                            <td>Status</td>
                            <td><span class="badge {{ $status['color'] }}">{{ Str::upper($status['text']) }}</span></td>
                        </tr>
                    </table>
				</div>
                <div class="col-12">
                    <table class="table table-striped border">
                        <thead>
                            <tr>
                                <td>No.</td>
                                <td>Paket</td>
                                <td>Harga</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>{{ $invoice->pack->title }}</td>
                                <td>{!! idr($invoice->amount) !!}</td>
                            </tr>
                        </tbody>
                    </table>
				</div>
            </div>
            <div class="row g-3 flex-row-reverse justify-content-between">
                <div class="col-lg-4">
                    <table class="table border">
                        <tr>
                            <td>Total bayar</td>
                            <td><b>{!! idr($invoice->amount) !!}</b></td>
                        </tr>
                    </table>
				</div>
                <div class="col-lg-4">
                    @if ($invoice->status=='PENDING')
                    <div class="border rounded p-3">
                        <h6 class="mb-2"><i class="bx bx-error me-2"></i>Konfirmasi</h6>
                        <p>Terima konfirmasi pembayaran untuk mengaktifkan akun <b>{{ $invoice->user->name }}</b>.</p>
                        <div>
                            <a href="{{ route('invoice-transaction.confirm', ['id'=>$invoice->id, 'status'=>'approve']) }}" class="btn btn-success w-100 mb-2">
                                <i class="bx bx-check-double"></i>
                                <span>Terima</span>
                            </a>
                            <a href="{{ route('invoice-transaction.confirm', ['id'=>$invoice->id, 'status'=>'decline']) }}" class="btn btn-danger w-100">
                                <i class="bx bx-x"></i>
                                <span>Tolak</span>
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="col-lg-4">
                    @if ($invoice->payment_link=='#manual')
                    <div class="border rounded p-3">
                        <h6 class="mb-2"><i class="bx bx-credit-card me-2"></i>Pembayaran</h6>
                        <div class="">
                            via <b>{{ Str::upper($bank->file) }}</b> ke <b>{{ $bank->name }}</b>
                            @if (!empty($invoice->content->payment))
                            <div class="border rounded my-2 p-2">
                                <img src="{{ url('storage/'.$invoice->content->payment->image) }}" alt="" class="rounded img-fluid mb-2">
                                <span>Dikirim <b>{{ Carbon::parse($invoice->content->payment->date)->formatLocalized('%A, %d %B %Y') }}</b></span>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('style')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@push('script')
@endpush