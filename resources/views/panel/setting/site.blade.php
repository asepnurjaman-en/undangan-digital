@extends('panel.layouts.app')
@section('title', Str::title($data['title']))
@section('content')
<div class="content-wrapper">
	<div class="container-fluid flex-grow-1 container-p-y position-relative">
		<ul class="nav nav-pills flex-column flex-md-row mb-3">
			@foreach ($tablist as $tab)
            <li class="nav-item">
				<a class="nav-link {{ (request()->fullUrl()==$tab['url']) ? 'active' : null }}" href="{{ $tab['url'] }}"><i class="{{ $tab['icon'] }} me-1"></i> {{ Str::title($tab['title']) }}</a>
			</li>    
            @endforeach
		</ul>
		<div class="card mb-4">
			<form action="{{ $data['form']['action'] }}" method="POST" class="{{ $data['form']['class'] }}" enctype="multipart/form-data">
				@csrf
				@method('PATCH')
				<div class="card-body p-3">
					@if (request()->tab=='site')
					<div class="mb-3 form-group">
						<label for="title" class="form-label">{{ $global['setting'][0]->title }}</label>
						<input class="form-control" type="text" id="title" name="title" value="{{ $global['setting'][0]->content }}" required autofocus>
					</div>
					<div class="mb-3 form-group">
						<label for="color" class="form-label">{{ $global['setting'][3]->title }}</label>
						<input class="d-block border-0" type="color" id="color" name="color" value="{{ $global['setting'][3]->content }}" placeholder="isi disini" required>
					</div>
					@elseif (request()->tab=='icon')
					<div class="col-12 col-md-3">
						<div class="btn-group d-flex justify-content-between">
							<label for="upload-file" class="btn btn-outline-primary change-file-type" data-file-type="upload-file">
								<i class="bx bx-upload" data-bs-toggle="tooltip" data-bs-original-title="Unggah Foto" data-bs-placement="bottom"></i>
								<input type="file" name="upload_file" id="upload-file" class="choose-image" hidden="" accept="image/png, image/jpeg">
							</label>
							<button type="button" class="btn btn-outline-info border-start-0 change-file-type" data-bs-toggle="modal" data-bs-target="#single-storage-modal" data-file-type="image">
								<i class="bx bx-image" data-bs-toggle="tooltip" data-bs-original-title="Buka Penyimpanan" data-bs-placement="bottom"></i>
							</button>
						</div>
						<input type="hidden" name="file_type" id="input-file-type" value="image" readonly>
						<div id="thumbail-preview" class="mb-3">
							<div>
								<div class="item-image">
									{!! image(src:url('storage/sm/'.$global['setting'][1]->content), alt:$global['setting'][1]->content) !!}
									<div class="overlay">
										<button title="button" class="remove unchoose-image">&times;</button>
										<h4>{{ Str::title('icon') }}</h4>
										<input type="hidden" name="file" value="{{ $global['setting'][1]->content }}">
									</div>
								</div>
							</div>
						</div>
					</div>
					@elseif (request()->tab=='logo')
					<div class="col-12 col-md-3">
						<div class="btn-group d-flex justify-content-between">
							<label for="upload-file" class="btn btn-outline-primary change-file-type" data-file-type="upload-file">
								<i class="bx bx-upload" data-bs-toggle="tooltip" data-bs-original-title="Unggah Foto" data-bs-placement="bottom"></i>
								<input type="file" name="upload_file" id="upload-file" class="choose-image" hidden="" accept="image/png, image/jpeg">
							</label>
							<button type="button" class="btn btn-outline-info border-start-0 change-file-type" data-bs-toggle="modal" data-bs-target="#single-storage-modal" data-file-type="image">
								<i class="bx bx-image" data-bs-toggle="tooltip" data-bs-original-title="Buka Penyimpanan" data-bs-placement="bottom"></i>
							</button>
						</div>
						<input type="hidden" name="file_type" id="input-file-type" value="image" readonly>
						<div id="thumbail-preview" class="mb-3">
							<div>
								<div class="item-image">
									{!! image(src:url('storage/sm/'.$global['setting'][2]->content), alt:$global['setting'][2]->content) !!}
									<div class="overlay">
										<button title="button" class="remove unchoose-image">&times;</button>
										<h4>{{ Str::title('logo') }}</h4>
										<input type="hidden" name="file" value="{{ $global['setting'][2]->content }}">
									</div>
								</div>
							</div>
						</div>
					</div>
					@elseif (request()->tab=='meta')
					<div class="mb-3 form-group">
						<label for="keywords" class="form-label">{{ $global['setting'][5]->title }}</label>
						<input class="form-control" type="text" id="keywords" name="keywords" value="{{ $global['setting'][5]->content }}" required>
					</div>
					<div class="mb-3 form-group">
						<label for="description" class="form-label">{{ $global['setting'][4]->title }}</label>
						<textarea class="form-control" id="description" name="description" placeholder="isi disini" required>{{ $global['setting'][4]->content }}</textarea>
					</div>
					@elseif (request()->tab=='maintenance')
					<div class="panel-maintenance p-3 text-center">
						<h3>Aktifkan mode `Pemeliharaan`</h3>
						<p>Ketika mode `Pemeliharaan` aktif, website akan masuk pada <b>Mode Pemeliharaan</b> dan tidak dapat akses.</p>
						<div class="form-switch mb-2">
							<input class="form-check-input" type="checkbox" id="maintenance" name="maintenance" value="on" {{ ($global['setting'][6]->content=='on') ? 'checked' : null }}>
							<label class="form-check-label" for="maintenance">Aktifkan</label>
						</div>
					</div>
					@endif
					<div class="mt-2">
						<button type="submit" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-original-title="CTRL + S" data-bs-placement="bottom">
							<i class="bx bx-save"></i>
							<span>{{ Str::title('simpan') }}</span>
						</button>
						<button type="reset" class="btn btn-outline-secondary">
							<i class="bx bx-reset"></i>
							<span>{{ Str::title('batal') }}</span>
						</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection

@push('style')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{ asset('modules/datatable/datatables.min.css') }}">
@endpush

@push('script')
<script src="{{ asset('modules/datatable/datatables.min.js') }}" type="text/javascript"></script>
@include('panel.layouts.storage-modal', ['mode'=>'single'])
@endpush