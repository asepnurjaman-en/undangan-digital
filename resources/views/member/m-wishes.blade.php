@extends('member.layouts.app')
@section('title', Str::title($menu['title']))
@php
	$display = [
		'wishes_public__check' => ($data->preset->public===true) ? true : false,
	];
@endphp
@section('content')
<section class="position-relative py-3">
	@include('member.layouts.component', ['content'=>'breadcrumb', 'menu'=>$menu])
	<form action="{{ route('save.setting', 'wishes') }}" class="save-menu" method="post">
		@csrf
		@method('put')
		<div class="row g-3">
			<div class="col-lg-7">
				<div class="bg-white shadow rounded p-3 mb-2">
					<div class="form-check form-switch d-flex flex-row-reverse justify-content-between ps-0 py-1">
						<input class="form-check-input change-style" type="checkbox" name="wishes_public" id="wishes_public" @checked($display['wishes_public__check'])>
						<label class="form-check-label" for="wishes_public">Izinkan mengirim <b>Ucapan</b>?</label>
					</div>
				</div>
				<div class="bg-white shadow rounded p-3">
					<h4>Keterangan</h4>
					<div class="mb-2">
						<div>
							<label for="wishes_title" class="form-label">
								<var dir="wishes_title">Judul</var>
							</label>
							<input type="text" name="wishes_title" id="wishes_title" class="form-control" value="{{ $data->preset->title }}" placeholder="Judul">
						</div>
					</div>
					<div class="mb-2">
						<div>
							<label for="wishes_content" class="form-label">
								<var dir="wishes_content">Deskripsi</var>
							</label>
							<textarea name="wishes_content" id="wishes_content" class="form-control" placeholder="Deskripsi">{{ $data->preset->content }}</textarea>
						</div>
					</div>
				</div>
			</div>
			<div class="col-lg-5">
				@forelse ($data->wishes as $item)
				@php
					$item->prop = json_decode($item->content);
				@endphp
				<div class="bg-white shadow rounded p-2 mb-1">
					<h6 class="mb-1">{{ $item->prop->name }}</h6>
					<ul class="list-unstyled">
						<li class="text-muted">
							No. whatsapp :
							<b>{{ $item->prop->phone }}</b>
						</li>
						<li>
							<blockquote class="bg-light rounded p-2">{{ $item->prop->message }}</blockquote>
						</li>
					</ul>
					<small class="text-muted">Diisi pada {{ date('d/m/Y H:i', strtotime($item->created_at)) }}</small>
				</div>
				@empty
				<div class="empty">belum ada ucapan</div>
				@endforelse
			</div>
		</div>
		<div class="save-button">
			<button type="submit">
				<i class="bx bx-save"></i>
				<span>simpan</span>
			</button>
		</div>
	</form>
</section>
@endsection

@push('style')
@endpush

@push('script')
@endpush