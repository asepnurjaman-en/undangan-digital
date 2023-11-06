@extends('member.layouts.app')
@section('title', Str::title('invoice '.$invoice->content->invoice_number))
@php
	use Carbon\Carbon;
	setlocale(LC_ALL, 'IND');
@endphp
@section('content')
<section class="py-5">
	<div class="row justify-content-center g-3">
		<div class="col-lg-7">
			<form action="{{ route('invoice-pay', $invoice->id) }}">
				@csrf
				@method('put')
				<div class="invoice bg-white rounded shadow">
					<div class="text-white text-center text-uppercase p-2 {{ $status['color'] }}">{{ $status['text'] }}</div>
					<div class="text-center p-3">
						<h2>Undangan {{ $invoice->user->name }}</h2>
						<h5>
							<b>Invoice #: {{ $invoice->payment_code }}</b>
							<span>{{ Carbon::parse($invoice->date)->formatLocalized('%A, %d %B %Y'); }}</span>
							<span>{{ Carbon::parse($invoice->created_at)->isoFormat('| H:m') }}</span>
						</h5>
					</div>
					<div class="text-left px-3 pb-3 border-bottom">
						Kepada
						<span class="d-block">{{ $invoice->user->name }}</span>
						<span class="d-block">{{ $invoice->user->email }}</span>
					</div>
					<div class="d-flex justify-content-between">
						<div class="d-block py-2 px-3">
							<h5 class="mb-0">{{ $invoice->pack->title }}</h5>
							<span>{{ $invoice->pack->title }}</span>
						</div>
						<div class="d-block py-2 px-3">
							{!! idr($invoice->pack->price) !!}
						</div>
					</div>
					<div class="text-center p-3">
						Total pembayaran
						<span class="d-block fs-3">{!! idr($invoice->amount) !!}</span>
					</div>
					@if ($invoice->status=='PENDING')
					@if ($invoice->payment_link=='#manual')
					<div class="d-flex align-items-center flex-column flex-lg-row border rounded p-3 mb-2 mx-3">
						<div class="bank-item {{ $bank_pay->file }}">
							<code>{{ $bank_pay->file }}</code>
						</div>
						<div class="info p-2">
							<p class="lh-2 mb-0">
								Silakan lakukan pembayaran via <b>{{ $bank_pay->bank }}</b><br>
								ke <b>{{ $bank_pay->content->code }}</b> <i class="bx bx-copy copy-text" data-text="{{ $bank_pay->content->code }}"></i><br>
								<span>atas nama <b>{{ $bank_pay->name }}</b></span>
							</p>
						</div>
					</div>
					<div class="px-3 py-2 text-center text-muted">
						<small>*Jika sudah melakukan pembayaran, harap konfirmasi pembayaran dengan mengirim <i>screenshot</i> bukti pembayaran ke admin untuk mengaktifkan akun.</small>
					</div>
					<a href="#" class="btn btn-primary btn-lg rounded-0 shadow w-100" data-bs-toggle="modal" data-bs-target="#confirmation-of-payment">Konfirmasi Pembayaran</a>
					@else
					<a href="{{ $invoice->payment_link }}" target="_BLANK" class="btn btn-primary btn-lg rounded-0 shadow w-100">Bayar sekarang</a>
					@endif
					@endif
					{{-- <button type="submit" class="btn btn-primary btn-lg rounded-0 shadow w-100">Bayar sekarang</button> --}}
				</div>
			</form>
		</div>
		<div class="col-lg-7">
			@if (!empty($invoice->content->payment) AND $invoice->content->payment->image!=null)
			<div class="d-flex justify-content-between align-items-center bg-white rounded shadow p-3">
				<span>{{ Carbon::parse($invoice->content->payment->date)->formatLocalized('%A, %d %B %Y') }}</span>
				<a href="{{ url('storage/'.$invoice->content->payment->image) }}" data-fancybox="preview" class="border rounded text-dark text-decoration-none py-1 px-2">
					Lihat gambar
					<img src="{{ url('storage/'.$invoice->content->payment->image) }}" alt="prove" @style('height:30px;width:40px;object-fit:cover') class="mx-2 rounded shadow">
				</a>
			</div>
			@endif
		</div>
	</div>
</section>
@endsection

@push('style')
<link rel="stylesheet" href="{{ asset('modules/@fancyapps/fancybox/dist/jquery.fancybox.min.css') }}">
<style>
.bank-item {
	background-image: url('{{ url('images/bank/banks.png') }}') }
</style>
@endpush

@push('script')
@include('member.layouts.component', ['content'=>'confirmation-of-payment', 'id' => request()->id])
<script src="{{ asset('modules/@fancyapps/fancybox/dist/jquery.fancybox.min.js') }}"></script>
<script>
	$('[data-fancybox="preview"]').fancybox();
	 $(".change-img").on('change', function(e) {
		if (e.target.name=='prove_image') {
			const file = e.target.files[0];
			if (file) {
				let reader = new FileReader();
				reader.onload = function(render) {
					$(".set_prove_image").attr('src', render.target.result);
				}
				reader.readAsDataURL(file);
			}
		}
	});
</script>
@endpush