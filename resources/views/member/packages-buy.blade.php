@extends('member.layouts.app')
@section('title', Str::title('beli paket'))
@section('content')
<section class="py-3">
    <form action="{{ route('invoice-add', request()->id) }}" class="pay-for-upgrade" method="post">
        @csrf
        <div class="bg-white shadow rounded p-3 mb-3">
			<h5>Paket</h5>
			<div class="row align-items-center g-2">
				<div class="col-4 col-lg-1">
					<figure class="border rounded p-2 mb-0">
						<img src="{{ url('storage/xs/'.$data->package->file) }}" alt="{{ $data->package->title }}" class="w-100 rounded">
					</figure>
				</div>
				<div class="col-8 col-lg-5">
					<b class="d-block">{{ $data->package->title }}</b>
					<span>{!! idr($data->package->price) !!}</span>
				</div>
			</div>
		</div>
        <div class="bg-white shadow rounded p-3 mb-3">
            <div class="payment-method mb-2">
                <label for="payment" class="form-label">
                    <var dir="payment">{{ Str::title('pembayaran') }}</var>
                </label>
                <div class="package-list">
                    <input type="radio" name="payment" id="fastPayment" value="fast">
                    <label for="fastPayment" class="d-flex justify-content-between">
                        <b>Pembayaran cepat</b>
                        <span class="modal-info" data-bs-toggle="modal" data-bs-target="#modal-info" data-text="Setelah pembayaran dilakukan, akun kamu otomatis aktif.">
                            <i class="bx bx-info-circle"></i>
                        </span>
                    </label>
                    <input type="radio" name="payment" id="manualPayment" value="manual">
                    <label for="manualPayment" class="d-flex justify-content-between">
                        <b>Pembayaran manual</b>
                        <span class="modal-info" data-bs-toggle="modal" data-bs-target="#modal-info" data-text="Setelah pembayaran dilakukan, kamu harus mengkonfirmasi bukti pembayaran pada admin kami untuk mengaktifkan akun.">
                            <i class="bx bx-info-circle"></i>
                        </span>
                    </label>
                </div>
                <var dir="bank"></var>
                <div class="bank-list wide" @style('display:none')>
                    @foreach ($data->bank as $key => $item)
                    <label for="bank{{ $key }}">
                        <input type="radio" name="bank" id="bank{{ $key }}" value="{{ base64_encode($item->id) }}">
                        <div class="bank-item {{ $item->file }}">
                            <code>{{ $item->file }}</code>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="text-center">
            <button type="submit" class="d-flex align-items-center justify-content-center btn btn-creasik-primary w-100">
                <span>Pembayaran</span>
				<i class="bx bx-right-arrow-circle ms-1 fs-4"></i>
            </button>
        </div>
    </form>
</section>
<div class="modal fade" id="modal-info" tabindex="-1" aria-labelledby="presetsLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-body text-center"></div>
			<div class="modal-footer p-2">
				<button type="button" class="btn btn-light" data-bs-dismiss="modal" aria-label="Close">
					<i class="bx bx-thumbs-up"></i>
					<span>Oke</span>
				</button>
			</div>
		</div>
	</div>
</div>
@endsection

@push('style')
<style>
	.bank-list .bank-item {
		background-image: url('{{ url('images/bank/banks.png') }}') }
</style>
@endpush


@push('script')
<script>
	$(".modal-info").on('click', function(e) {
		let info = $(this).data('text');
		$("#modal-info").find('.modal-body').text(info);
	});
	$("input[name=bundle]").on('change', function(e) {
		let next = $(".payment-method");
		if (e.target.value=='1') {
			next.fadeOut();
		} else {
			next.fadeIn();
		}
	});
	$("input[name=payment]").on('change', function(e) {
		let next = $(".bank-list");
		if (e.target.value=='manual') {
			next.fadeIn();
		} else {
			next.fadeOut();
		}
	});
    $(".pay-for-upgrade").on('submit', function(e) {
		e.preventDefault();
		let action = $(this).attr('action'),
			submit = $(this).find('button[type=submit]');
		$.ajax({
			type: 'post',
			url : action,
			dataType: 'json',
			data: $(this).serialize(),
			error: function(q,w,e) {
				submit.text('Coba Lagi');
				submit.prop('disabled', false);
				if (q.responseJSON) {
					$.each(q.responseJSON.errors, function(index, value) {
						$(`var[dir=${index}]`).after(`<sup role="alert" data-bs-toggle="tooltip" data-bs-placement="right" title="${value}">!</sup>`);
					});
				}
				console.log(q);
			},
			beforeSend: function() {
				$("sup[role=alert]").remove();
				submit.prop('disabled', true);
				submit.text('Memeriksa data...');
			},
			success: function(response) {
				if (response.code==200) {
					location.href=response.redirect;
				} else if (response.code==500) {
					submit.text('Coba Lagi');
					submit.prop('disabled', false);
					$.each(response.message, function(index, value) {
						$(`[name=${index}]`).after(`<sup>${value}</sup>`);
					});
				}
			}
		});
	});
</script>
@endpush