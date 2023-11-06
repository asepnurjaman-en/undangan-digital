@extends('member.layouts.app')
@section('title', Str::title('akun profil'))
@php
	if ($data['activation']!=null) :
		$activation = $data['activation']->date;
	else :
		$activation = date('Y-m-d');
	endif;
@endphp
@section('content')
<section class="py-3">
	<ul class="nav nav-pills creasik-nav-pill mb-3">
		<li class="nav-item">
			<a class="nav-link active" aria-current="page">
				<i class="bx bx-user-circle"></i>
				<span>Profil</span>
			</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="{{ route('transaction') }}">
				<i class="bx bx-receipt"></i>
				<span>Transaksi</span>
			</a>
		</li>
	</ul>
	<div class="row g-3">
		<div class="col-md-3 border-right">
			<div class="bg-white rounded shadow-sm d-flex flex-column align-items-center text-center p-3">
				<figure class="img-profile">
					<img class="rounded-circle my-2" src="@if (Auth::user()->third_party=='google')
					{{ Auth::user()->acc->file }}
					@else
					{{ url('storage/'.Auth::user()->acc->file) }}
					@endif">
					@if (isexpired($activation, $data['active'])===false)
					<button type="button" data-bs-toggle="modal" data-bs-target="#storage">
						<i class="bx bx-edit"></i>
					</button>
					@endif
				</figure>
				<span class="font-weight-bold">{{ Auth::user()->name }}</span>
				<span class="text-black-50">{{ Auth::user()->email }}</span>
				<span></span>
			</div>
		</div>
		<div class="col-md-5 border-right">
			<div class="bg-white rounded shadow-sm p-3 mb-2">
				<div class="d-flex justify-content-between align-items-center">
					<h4 class="text-right">
						<i class="bx bx-edit"></i>
						Ubah profil
					</h4>
				</div>
				<form action="{{ route('profile.update') }}" class="save-menu" method="post">
					@csrf
					@method('PUT')
					<div class="mt-2">
						<div class="mb-2">
							<label for="name" class="form-label">
								<var dir="name">Nama</var>
							</label>
							<input type="text" name="name" id="name" class="form-control" value="{{ Auth::user()->name }}" placeholder="Nama lengkap">
						</div>
						<div class="mb-2">
							<label for="email" class="form-label">
								<var dir="email">Email</var>
							</label>
							<input type="text" name="email" id="email" class="form-control" value="{{ Auth::user()->email ?? null }}" placeholder="Email">
						</div>
						<div class="mb-2">
							<label for="phone" class="form-label">
								<var dir="phone">Nomor telepon (Whatsapp)</var>
							</label>
							<input type="text" name="phone" id="phone" class="form-control" value="{{ $account->phone ?? null }}" placeholder="Nomor whatsapp">
						</div>
						<div class="mb-2">
							<label for="address" class="form-label">
								<var dir="address">Alamat</var>
							</label>
							<textarea name="address" id="address" class="form-control" placeholder="Alamat">{{ $account->address ?? null }}</textarea>
						</div>
					</div>
					<div class="my-2">
						<input type="hidden" name="file" value="{{ Auth::user()->acc->file }}" readonly>
						<button class="btn btn-creasik-primary w-100" type="submit">
							<i class="bx bx-check-circle"></i>
							<span>Simpan</span>
						</button>
					</div>
				</form>
			</div>
		</div>
		<div class="col-md-4">
			@if ($data['activation']!=null)				
			<div class="bg-white rounded shadow-sm p-3 mb-2">
				<div class="d-flex justify-content-between align-items-center experience">
					<span>
						<small class="d-block">Paket</small>
						<b>{{ Auth::user()->invoice[0]->pack->title }}</b>
					</span>
					<a href="{{ route('packages') }}" class="btn btn-creasik-primary">
						<i class="bx bx-chevrons-up"></i>
						<span>Upgrade</span>
					</a>
				</div>
			</div>
			<div class="bg-white rounded shadow-sm p-3 mb-2">
				<div class="d-flex justify-content-between align-items-center experience">
					<span>Undangan</span>
					<a href="{{ route('invitation.index', Auth::user()->inv->slug) }}" target="_BLANK" class="btn btn-creasik-primary">
						<i class="bx bx-link-external"></i>
						<span>Buka</span>
					</a>
				</div>
			</div>
			<hr>
			@endif
			<button type="button" class="btn btn-danger w-100">
				<i class="bx bx-log-out"></i>
				<span>Keluar</span>
			</button>
			<ul class="list-inlined list-unstyled my-2">
				<li>
					<a href="{{ route('info', '1') }}">Kebijakan &amp; ketentuan</a>
				</li>
				<li>
					<a href="{{ route('info', '2') }}">Persetujuan pengguna</a>
				</li>
				<li>
					<a href="{{ route('info', '3') }}">Informasi lainnya</a>
				</li>
			</ul>
		</div>
	</div>
</section>
@endsection

@push('style')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{ asset('modules/datatable/datatables.min.css') }}">
@endpush

@push('script')
@include('member.layouts.component', ['content'=>'modal-storage', 'mode'=>'single'])
<script src="{{ asset('modules/datatable/datatables.min.js') }}" type="text/javascript"></script>
<script>
	$(document).on('click', '.use-image', function(e) {
		var file = $("input[name='storage_file[]']").map(function() {
			if ($(this).prop('checked')) {
				let source = $(this).val(),
					url = $(this).siblings().children('img').attr('src');
				$(".img-profile").children('img').attr('src', url);
				$(".save-menu").find('input[name=file]').val(source);
				return true;
			}
		}).get();
		$("#storage").modal('hide');
		$("input[name='storage_file[]']").prop('checked', false);
	});
</script>
@endpush
