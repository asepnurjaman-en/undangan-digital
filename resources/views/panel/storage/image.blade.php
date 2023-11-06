@extends('panel.layouts.app')
@section('title', Str::title($data['title']))
@section('content')
<div class="content-wrapper">
	<div class="container-fluid flex-grow-1 container-p-y position-relative">
		<div class="card">
			<div class="card-body p-3">
				<div class="accordion">
					<div class="card accordion-item shadow-none">
						<h2 class="accordion-header border" id="headingUpload">
							<button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#accordionUpload" aria-expanded="false" aria-controls="accordionUpload">
								<i class="bx bx-upload me-2"></i>
								{{ Str::title('unggah foto') }}
							</button>
						</h2>
						<div id="accordionUpload" class="accordion-collapse collapse" aria-labelledby="headingUpload" data-bs-parent="#accordionUpload">
							<form action="{{ $data['form']['action'] }}" class="{{ $data['form']['class'] }}" method="post" enctype="multipart/form-data">
								@csrf
								<div class="form-group mb-2">
									<input type="file" name="file" id="file" class="dropify" accept="image/png, image/jpeg">
								</div>
								<div class="form-group input-group mb-2">
									<input type="text" name="title" id="title" class="form-control dropify-title" placeholder="Beri judul">
									<button type="submit" class="btn btn-primary dont-fixit">
										<i class="bx bx-upload"></i>
										<span>{{ Str::title('unggah') }}</span>
									</button>
								</div>
							</form>

						</div>
					</div>
				</div>
			</div>
			<div class="card-header p-3">
				<form action="{{ $data['delete']['action'] }}" method="post" class="form-delete mb-0" data-message="{{ $data['delete']['message'] }}">
					@method('DELETE')
					<input type="hidden" name="id_delete" value>
					<button type="submit" class="btn btn-danger" disabled>
						<i class="bx bx-trash"></i>
						<span>{{ ucfirst('hapus') }}</span>
						<b></b>
					</button>
				</form>
			</div>
			<div class="card-body p-0">
				<table class="dataTables" data-list="{{ $data['list'] }}">
					<thead>
						<tr>
							<th><i class="bx bx-cog"></i></th>
							<th></th>
							<th></th>
							<th></th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@endsection

@push('style')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{ asset('modules/datatable/datatables.min.css') }}">
<link rel="stylesheet" href="{{ asset('modules/dropify/dist/css/dropify.min.css') }}">
@endpush

@push('script')
<script src="{{ asset('modules/datatable/datatables.min.js') }}"></script>
<script src="{{ asset('modules/dropify/dist/js/dropify.min.js') }}"></script>
@endpush