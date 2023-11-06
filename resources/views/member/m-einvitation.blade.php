@extends('member.layouts.app')
@section('title', Str::title($menu['title']))   
@section('content')
<section class="position-relative py-3">
	@include('member.layouts.component', ['content'=>'breadcrumb', 'menu'=>$menu])
	<div class="row g-3">
		<div class="col-lg-7">
			<div class="bg-white shadow rounded p-3 mb-3">
				<h4>Foto</h4>
				<div class="image-selector">
					<div>
						<img src="{{ url('storage/'.Auth::user()->inv->file) }}" class="set_invitation_image" alt="">
					</div>
					<div>
						<div class="border-bottom py-2">
							<label class="form-label">Ganti</label>
							<button type="button" class="btn btn-creasik-primary mb-1 change-data" data-image="for-invitation" data-bs-toggle="modal" data-bs-target="#storage">
								<i class="bx bx-images"></i>
								<span>Penyimpanan</span>
							</button>
						</div>
						<div class="py-2">
							<button type="button" class="btn btn-light mb-1" @disabled(true)>
								<i class="bx bx-crop"></i>
								<span>Potong</span>
							</button>
							<button type="button" class="btn btn-outline-danger remove-image" data-target="invitation_image">
								<i class="bx bx-trash"></i>
								<span>Hilangkan</span>
							</button>
						</div>
					</div>
				</div>
			</div>
			<div class="row g-2">
				<div class="col-lg-6">
					<button type="button" class="btn btn-success w-100 figure-catcher" data-url="{{ route('menu.einvitation-edit') }}">
						<i class="bx bx-analyse"></i>
						<span>Generate</span>
					</button>
				</div>
				<div class="col-lg-6">
					<a href="{{ url('storage/'.Auth::user()->inv->file) }}" download="WeddingOf{{ ucwords(clean_str(implode('-', json_decode(Auth::user()->inv->title, true)))) }}.jpeg" class="btn btn-light w-100">
						<i class="bx bx-download"></i>
						<span>Unduh</span>
					</a>
				</div>
			</div>
		</div>
		<div class="col-lg-5">
			<div class="meta-image bg-white shadow mb-2">
				<figure class="figure-image theme">
					<img src="{{ url('storage/'.Auth::user()->inv->file) }}" class="set_invitation_image" alt="">
					<div class="name">
						<h1>{{ implode(' & ', json_decode(Auth::user()->inv->title, true)) }}</h1>
						<span>{{ date('d . m . Y', strtotime($data->date)) }}</span>
					</div>
				</figure>
				<div class="form-check form-switch d-flex flex-row-reverse justify-content-between p-2">
					<input class="form-check-input change-style" type="checkbox" name="e-invitation_text" id="e-invitation_text">
					<label class="form-check-label" for="e-invitation_text">Sembunyikan teks</label>
				</div>
				<div class="form-check form-switch d-flex flex-row-reverse justify-content-between p-2">
					<input class="form-check-input change-style" type="checkbox" name="e-invitation_color" id="e-invitation_color" @checked(true)>
					<label class="form-check-label" for="e-invitation_color">Warna teks sesuai tema</label>
				</div>
			</div>
		</div>
	</div>
	<div class="test"></div>
</section>
@endsection

@push('style')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{ asset('modules/datatable/datatables.min.css') }}">
<style>
	.meta-image > figure .name > h1 {
		font-family: {{ $data->preset->title->font }}; }
	.meta-image > figure .name > span {
		font-family: {{ $data->preset->content->font }}; }
	.meta-image > figure.theme .name > h1 {
		color: {{ $data->preset->title->color }}; }
	.meta-image > figure.theme .name > span {
		color: {{ $data->preset->content->color }}; }
</style>
@endpush

@push('script')
@include('member.layouts.component', ['content'=>'modal-storage', 'mode'=>'single'])
<script src="{{ asset('modules/datatable/datatables.min.js') }}" type="text/javascript"></script>
<script>
	$(".change-data").on('click', function(e) {
		e.preventDefault();
		let data = $(this).data('image');
		$(".use-image").attr('data-image', data);
	});
	$(".change-style").on('click', function(e) {
		if (e.target.name=='e-invitation_color') {
			$(".meta-image").children('figure').toggleClass('theme');
		} else if (e.target.name=='e-invitation_text') {
			$(".meta-image").children('figure').children('.name').toggleClass('d-none');
		}
	});
	$(".remove-image").on('click', function(e) {
		e.preventDefault();
		if ($(this).data('target')=='invitation_image') {
			$(".set_invitation_image").attr('src', null);
		}
		$(this).prop('disabled', true);
	});
	$(".use-image").on('click', function(e) {
		var file = $("input[name='storage_file[]']").map(function() {
			if ($(this).prop('checked')) {
				let key = $(this).attr('id'),
					source = $(this).val(),
					url = $(this).siblings().children('img').attr('src');
				if (e.target.dataset.image=='for-invitation') {
					$(".set_invitation_image").attr('src', `{{ url('storage/') }}/${source}`);
					$('.remove-image').prop('disabled', false);
				}
			}
		});
		$("#storage").modal('hide');
	});
</script>
@endpush