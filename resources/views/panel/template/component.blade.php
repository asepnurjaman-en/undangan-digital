@extends('panel.layouts.app')
@section('title', Str::title($data['title']))
@section('content')
<div class="container">
	<div class="row">
		<div class="col-12">
			<form action="{{ $data['delete']['action'] }}" method="post" class="form-delete my-3 mb-0" data-message="{{ $data['delete']['message'] }}">
				@method('DELETE')
				<input type="hidden" name="id_delete" value/>
				<button type="submit" class="btn btn-danger" disabled>
					<i class="bx bx-trash"></i>
					<span>{{ Str::title('hapus') }}</span>
					<b></b>
				</button>
			</form>
			<div class="card border-0 my-3">
				<div class="card-body p-3">
					<div class="accordion">
						<div class="card accordion-item shadow-none">
							<h2 class="accordion-header border" id="headingUpload">
								<button type="button" class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#accordionUpload" aria-expanded="false" aria-controls="accordionUpload">
									<i class="bx bx-plus-circle me-2"></i>
									{{ Str::title('tambah baru') }}
								</button>
							</h2>
							<div id="accordionUpload" class="accordion-collapse collapse" aria-labelledby="headingUpload" data-bs-parent="#accordionUpload">
								<form action="{{ $data['create']['action'] }}" class="form-insert border p-2 pt-0" method="post" enctype="multipart/form-data">
									@csrf
									@if (in_array(request()->slug, ['avatar', 'decoration', 'frame']))
									<div class="form-group mb-2">
										<input type="file" name="file" id="file" class="dropify" accept="image/png, image/jpeg">
									</div>
									@elseif (in_array(request()->slug, ['music']))
									<div class="form-group my-2">
										<input type="file" name="file" id="file" class="form-control" accept="audio/mpeg">
									</div>
									@elseif (in_array(request()->slug, ['quote']))
									<div class="form-group my-2">
										<textarea name="content" id="content" class="form-control" rows="5" placeholder="Quote"></textarea>
									</div>
									@endif
									<div class="form-group input-group mb-2">
										@if (request()->slug=='avatar')
										<select name="which_gender" id="which_gender" class="form-select">
											@foreach (['avatar'=>'Couple avatar', 'avatar male'=>'Male avatar', 'avatar female'=>'Female avatar'] as $type => $value)
											<option value="{{ $type }}">{{ $value }}</option>
											@endforeach
										</select>
										@endif
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
				<div class="card-body p-3">
					@if (request()->slug=='avatar')
					<div class="btn-group mb-3">
						<span class="btn btn-light border none-filter">
							<i class="bx bx-filter"></i>
						</span>
						@foreach (['avatar'=>'Couple avatar', 'avatar male'=>'Male avatar', 'avatar female'=>'Female avatar'] as $type => $value)
						<button type="button" class="btn btn-outline-primary filter-figure" data-filter="filter-{{ clean_str($type) }}">
							{{ $value }}
						</button>
						@endforeach
					</div>
					@endif
					<div class="row g-2">
						@foreach ($component as $item)
						<div class="col-lg-3 col-6">
							<div class="border rounded overflow-hidden position-relative p-2">
								@if (in_array(request()->slug, ['avatar', 'decoration', 'frame']))
								<figure class="{{ (request()->slug=='avatar') ? clean_str('filter-'.$item->type) : null }}">
									<img src="{{ url('storage/'.request()->slug.'/'.$item->content) }}" class="d-block img-fluid m-auto" @style('height:150px;object-fit:contain')>
								</figure>
								@elseif (in_array(request()->slug, ['music']))
								<div class="position-relative mb-4 pb-2">
									@if ($item->user->role=='member')
									<span class="position-absolute z-1 badge bg-info" @style('top:-1em;right:-1em;font-size:10px')>{{ implode(' & ', json_decode($item->user->name, true)) }}</span>
									@endif
									<audio src="{{ url('storage/audio/'.$item->content) }}" class="w-100" controls></audio>
								</div>
								@elseif (in_array(request()->slug, ['quote']))
								<div class="mb-4">
									<blockquote @style('height:120px;overflow:auto')>{{ $item->content }}</blockquote>
								</div>
								@endif
								<div class="name bg-white shadow rounded text-nowrap position-absolute bottom-0 start-0 w-100 p-2">
									{!! input_check(name:"check[]", value:$item->id, class:['form-check-input', 'check-row', 'me-1'], mode:'multiple', label:$item->title) !!}
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
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{ asset('modules/dropify/dist/css/dropify.min.css') }}">
@endpush

@push('script')
<script src="{{ asset('modules/dropify/dist/js/dropify.min.js') }}"></script>
<script>
	$(".none-filter").on('click', function(e){
		e.preventDefault();
		$("figure").parent().parent().removeClass('d-none');
	});
	$(".filter-figure").on('click', function(e) {
		e.preventDefault();
		let figure = $("figure"),
			selected = $(this).data('filter');
		Object.entries(figure).forEach(element => {
			element[1].parentNode.parentElement.classList.remove('d-none');
			if (element[1].className!=selected) {
				element[1].parentNode.parentElement.classList.add('d-none');
			}
		});
	});
</script>
@endpush