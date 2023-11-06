@extends('panel.layouts.app')
@section('title', Str::title($data['title']))
@section('content')
<div class="container">
	<div class="row">
		<div class="col-12">
			<div class="my-3 mb-0">
				<a href="{{ $data['create']['action'] }}" class="btn btn-success d-none">
					<i class="bx bx-plus"></i>
					<span>{{ Str::title('tambah baru') }}</span>
				</a>
			</div>
			<div class="card border-0 my-3">
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
					</table>
				</div>
			</div>
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
@endpush