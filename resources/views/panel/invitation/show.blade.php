@extends('panel.layouts.app')
@section('title', Str::title($data['title']))
@php
	use Carbon\Carbon;
	$account->bio = json_decode($account->content);
	$count = [
		'photo' => (!empty($invitation->photo)) ? count(json_decode($invitation->photo->content)->file) : 0,
		'video' => (!empty($invitation->video)) ? 1 : 0,
	];
	$limit = ['photo' => 0, 'video' => 0, 'event' => 0, 'story' => 0];
	if (!empty($invoice_current)) :
		$limit['photo'] = (json_decode($invoice_current->pack->content)->{'gallery-photo'}=='unlimited') ? "∞" : json_decode($invoice_current->pack->content)->{'gallery-photo'};
		$limit['video'] = (json_decode($invoice_current->pack->content)->{'gallery-video'}=='unlimited') ? "∞" : json_decode($invoice_current->pack->content)->{'gallery-video'};
		$limit['event'] = (json_decode($invoice_current->pack->content)->{'event-count'}=='unlimited') ? "∞" : json_decode($invoice_current->pack->content)->{'event-count'};
		$limit['story'] = (json_decode($invoice_current->pack->content)->{'story-count'}=='unlimited') ? "∞" : json_decode($invoice_current->pack->content)->{'story-count'};
	endif;
@endphp
@section('content')
<div class="container">
	<div class="row g-3 mt-1 mb-3">
		<div class="col-lg-4">
			<div class="card border-0">
				<div class="card-header p-3 pb-0">
					<i class="bx bx-user"></i>
					<span>Profil</span>
				</div>
				<div class="user-stat card-body text-center p-3">
					<img src="{{ url('storage/'.$account->file) }}" alt="{{ $account->file }}" class="img-fluid d-block m-auto mb-2 border rounded-pill" @style('height:125px;width:125px;object-fit:cover')>
					@if ($account->actived==1)
					<span class="badge bg-success">Aktif</span>
					@elseif ($account->actived==0)
					<span class="badge bg-gray">Non-Aktif</span>
					@endif
					<span class="d-block small text-muted pt-2">
						@if (!empty($invoice_current))
						Sampai
						{{ Carbon::parse($invoice_current->date)->locale('id')->addDays(json_decode($invoice_current->pack->content)->active)->settings(['formatFunction' => 'translatedFormat'])->format('j F Y') }}
						@else
						Kadaluwarsa
						@endif
					</span>
				</div>
			</div>
		</div>
		<div class="col-lg-8">
			<div class="card border-0">
				<div class="card-header p-3 pb-0">
					<i class="bx bx-user"></i>
					<span>Akun</span>
				</div>
				<div class="card-body p-3">
					<div class="form-group mb-2">
						<div class="input-group">
							<span class="input-group-text text-muted">
								{{ Str::title('nama') }}
							</span>
							<input type="text" class="form-control bg-white border-start-0" value="{{ $user->name }}" readonly>
						</div>
					</div>
					<div class="form-group mb-2">
						<div class="input-group">
							<span class="input-group-text text-muted">
								{{ Str::title('email') }}
							</span>
							<input type="text" class="form-control bg-white border-start-0" value="{{ $user->email }}" readonly>
						</div>
					</div>
					@if ($account->bio)
					<div class="form-group mb-2">
						<div class="input-group">
							<span class="input-group-text text-muted">
								{{ Str::title('no. hp') }}
							</span>
							<input type="text" class="form-control bg-white border-start-0" value="{{ $account->bio->phone }}" readonly>
						</div>
					</div>
					<div class="form-group mb-2">
						<div class="input-group">
							<span class="input-group-text text-muted">
								{{ Str::title('alamat') }}
							</span>
							<input type="text" class="form-control bg-white border-start-0" value="{{ $account->bio->address }}" readonly>
						</div>
					</div>
					@endif
				</div>
			</div>
		</div>
		<div class="col-12">
			<div class="card border-0">
				<div class="card-body p-3">
					<div class="row g-2">
						<div class="col-lg-5">
							<div class="form-group mb-2">
								<div class="input-group">
									<span class="input-group-text text-muted">
										{{ Str::title('template') }}
									</span>
									<input type="text" class="form-control bg-white border-start-0" value="{{ $invitation->temp->title }}" readonly>
									<a href="{{ route('template.edit', $invitation->temp->id) }}" class="btn border border-start-0 text-primary">
										<i class="bx bx-link-external"></i>
									</a>
								</div>
							</div>
							<div class="form-group mb-2">
								<div class="input-group">
									<span class="input-group-text text-muted">
										{{ Str::title('paket') }}
									</span>
									<input type="text" class="form-control bg-white border-start-0" value="{{ (!empty($invoice_current)) ? $invoice_current->pack->title : '-' }}" readonly>
								</div>
							</div>
							<div class="form-group">
								<a href="{{ route('invitation.index', $invitation->slug) }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-primary w-100">
									<i class="bx bx-link-external"></i>
									<span>Lihat undangan</span>
								</a>
							</div>
						</div>
						<div class="col-lg-4">
							<div class="row g-2">
								<div class="col-6">
									<div class="bg-light border-info border-bottom border-5 rounded shadow-sm text-center">
										<div class="bg-gradient p-1">
											<h2 class="text-info fs-4 fw-bold mb-0">{{ $count['photo'] }}<small class="text-lightest">/{{ $limit['photo'] }}</small></h2>
											<span class="text-dark small">Foto</span>
										</div>
									</div>
								</div>
								<div class="col-6">
									<div class="bg-light border-danger border-bottom border-5 rounded shadow-sm text-center">
										<div class="bg-gradient p-1">
											<h2 class="text-danger fs-4 fw-bold mb-0">{{ $count['video'] }}<small class="text-lightest">/{{ $limit['video'] }}</small></h2>
											<span class="text-dark small">Video</span>
										</div>
									</div>
								</div>
								<div class="col-6">
									<div class="bg-light border-warning border-bottom border-5 rounded shadow-sm text-center">
										<div class="bg-gradient p-1">
											<h2 class="text-warning fs-4 fw-bold mb-0">{{ count($invitation->story) }}<small class="text-lightest">/{{ $limit['story'] }}</small></h2>
											<span class="text-dark small">Cerita</span>
										</div>
									</div>
								</div>
								<div class="col-6">
									<div class="bg-light border-success border-bottom border-5 rounded shadow-sm text-center">
										<div class="bg-gradient p-1">
											<h2 class="text-success fs-4 fw-bold mb-0">{{ count($invitation->event) }}<small class="text-lightest">/{{ $limit['event'] }}</small></h2>
											<span class="text-dark small">Acara</span>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-3">
							<div class="border rounded shadow-sm text-center">
								<div class="bg-gradient p-3">
									<h2 class="text-dark fs-2 fw-bold mb-2">{{ count($invitation->guest) }}</h2>
									<span class="text-muted">Link undangan</span>
									<button type="button" class="btn btn-sm btn-outline-dark mt-1" data-bs-toggle="modal" data-bs-target="#guest-modal">
										<i class="bx bxs-user-detail"></i>
										<span>Lihat tamu undangan</span>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-12">
			<div class="card border-0">
				<div class="card-header p-3">
					<i class="bx bx-credit-card"></i>
					<span>Transaksi</span>
				</div>
				<div class="card-body p-0">
					<div class="table-responsive">
						<table class="table">
							<thead>
								<tr>
									<th>No</th>
									<th>Kode</th>
									<th>Tanggal</th>
									<th>Status</th>
								</tr>
							</thead>
							<tbody>
								@forelse ($invoice as $i => $item)
								@php
									$item->content = json_decode($item->content);
									if ($item->status=='CONFIRMED') :
										$status = ['color'=>'bg-success', 'text'=>'done'];
									elseif ($item->status=='PENDING' AND empty($item->content->payment)) :
										$status = ['color'=>'bg-info', 'text'=>'pending'];
									elseif ($item->status=='PENDING' AND !empty($item->content->payment)) :
										$status = ['color'=>'bg-warning', 'text'=>'waiting confirmation'];
									endif;
								@endphp
								<tr>
									<td>{{ $i+1 }}</td>
									<td>{!! anchor(text:$item->content->invoice_number.':'.$item->payment_code, href:route('invoice-transaction.show', $item->id)); !!}</td>
									<td>{{ date('d/m/Y', strtotime($item->date)) }}</td>
									<td><span class="badge {{ $status['color'] }}">{{ Str::upper($status['text']) }}</span></td>
								</tr>
								@empty
								<tr>
									<td colspan="3">belum ada transaksi</td>
								</tr>
								@endforelse
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="d-flex justify-content-end border-top py-3">
		<button type="button" class="btn btn-success me-2 active-member" data-url="{{ route('member.executor', ['id'=>$account->id, 'type'=>'active']) }}" @style(($account->actived==1) ? 'display:none' : null)>
			<i class="bx bx-user-check"></i>
			<span>Aktifkan akun</span>
		</button>
		<button type="button" class="btn btn-secondary me-2 deactive-member" data-url="{{ route('member.executor', ['id'=>$account->id, 'type'=>'deactive']) }}" @style(($account->actived==0) ? 'display:none' : null)>
			<i class="bx bx-user-minus"></i>
			<span>Non-aktifkan akun</span>
		</button>
		<button type="button" class="btn btn-danger delete-member" data-url="{{ route('member.executor', ['id'=>$user->id, 'type'=>'delete']) }}" data-message="Menghapus akun akan menghilangkan undangan, invoice, dan asset undangan yg dibuat oleh <b>{{ $user->name }}</b>.">
			<i class="bx bx-user-x"></i>
			<span>Hapus akun</span>
		</button>
	</div>
</div>
@endsection

@push('style')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{ asset('modules/datatable/datatables.min.css') }}">
@endpush

@push('script')
<script src="{{ asset('modules/datatable/datatables.min.js') }}" type="text/javascript"></script>
<div class="modal fade" id="guest-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
		<div class="modal-content">
			<div class="modal-header p-2">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">
					<i class="bx bx-x"></i>
					<span>{{ Str::title('tutup') }}</span>
				</button>
			</div>
			<div class="modal-body position-relative py-2 px-0">
				<table class="dataTables-origin">
					<thead>
						<tr>
							<th>Tamu</th>
							<th>Penerima</th>
							<th>Link</th>
							<th>Tanggal buat</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($invitation->guest as $item)
						<tr>
							<td>{{ Str::upper($item->type) }}</td>
							<td>
								<b class="d-block">{{ json_decode($item->name)->name }}</b>
								<span>di {{ json_decode($item->name)->location }}</span>
							</td>
							<td>
								<span class="badge bg-light border text-primary rounded p-2">{{ route('invitation.index', $invitation->slug) }}?to={{ $item->slug }}</span>
							</td>
							<td>
								<span class="d-block">{{ Carbon::parse($item->created_at)->locale('id')->addDays(json_decode($invoice_current->pack->content)->active)->settings(['formatFunction' => 'translatedFormat'])->format('j F Y') }}</span>
								<span>{{ date('H:i', strtotime($item->created_at)) }}</span>
							</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@endpush