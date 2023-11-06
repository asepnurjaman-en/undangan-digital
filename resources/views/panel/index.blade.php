@extends('panel.layouts.app')
@section('title', Str::title($data['title']))
@section('content')
<div class="container">
	<div class="my-3">
		<div class="mb-3">{!! $greating !!}</div>
			<div class="row g-2">
				<div class="col-12">
					<div class="row g-2">
						@foreach ($transaction as $item)
						<div class="col-12 col-md-4">
							<div class="card">
								<div class="card-body">
									<div class="card-title d-flex align-items-start justify-content-between">
										<div class="d-flex align-items-center">
											<i class="{{ $item['icon'] }} me-1 fs-2"></i>
											<span class="fw-semibold d-block mb-1">{{ Str::title($item['title']) }}</span>
										</div>
									</div>
									<h3 class="card-title mb-2">{{ $item['data'] }}</h3>
									{!! anchor(href:$item['url'], text:Str::title('lihat')) !!}
								</div>
							</div>
						</div>
						@endforeach
					</div>
				</div>
				<div class="col-12">
					<div class="row g-2">
						@foreach ($dashboard as $item)
						<div class="col-12 col-md-4">
							<div class="card">
								<div class="card-body">
									<div class="card-title d-flex align-items-start justify-content-between">
										<div class="d-flex align-items-center">
											<i class="{{ $item['icon'] }} me-1 fs-2"></i>
											<span class="fw-semibold d-block mb-1">{{ Str::title($item['title']) }}</span>
										</div>
									</div>
									<h3 class="card-title mb-2">{{ $item['data'] }}</h3>
									{!! anchor(href:$item['url'], text:Str::title('kelola')) !!}
								</div>
							</div>
						</div>
						@endforeach
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@push('style')
@endpush

@push('script')
@endpush