@extends('panel.layouts.app')
@section('title', Str::title($data['title']))
@section('content')
<div class="container">
	<form action="{{ $data['form']['action'] }}" method="post" class="{{ $data['form']['class'] }} my-3">
		@csrf
		@method('PATCH')
		<div class="card border-0 my-3">
			<div class="card-header p-3">
				<div class="form-group d-flex justify-content-between">
					<h3 class="mb-0">{{ Str::title($data['title']) }}</h3>
					<button type="submit" class="btn btn-primary" data-bs-toggle="tooltip" data-bs-original-title="CTRL + S" data-bs-placement="bottom">
						<i class="bx bx-save"></i>
						<span>{{ Str::title('simpan') }}</span>
					</button>
				</div>
			</div>
			<div class="card-body p-3">
				<div class="row g-3">
					<div class="col-12 col-md-2">
						<ul class="nav nav-pills" role="tablist">
							@foreach ($tablist as $item)
							<li class="nav-item w-100">
								<button type="button" class="nav-link py-3 {{ (request()->fullUrl()==$item['url']) ? 'active' : null }}" role="tab" data-bs-toggle="tab" data-bs-target="#nav-{{ $item['title'] }}" aria-controls="navs-{{ $item['title'] }}" aria-selected="true">
									<i class="{{ $item['icon'] }} fs-2 d-block"></i>
									<small>{{ Str::title($item['title']) }}</small>
								</button>
							</li>
							@endforeach
						</ul>
					</div>
					<div class="col-12 col-md-10">
						<div class="tab-content p-0">
							<div class="tab-pane fade active show" id="nav-{{ $tablist[0]['title'] }}" role="tabpanel">
								<div class="link-external_form border shadow rounded p-2 mb-3">
									<div class="d-flex justify-content-between py-2 px-3">
										<div>
											<i class="bx bx-map-alt"></i>
											{{ Str::title('alamat') }}
										</div>
										<div class="form-check form-switch">
											<input class="form-check-input change-input-status change-input-edited" data-edit="{{ $contact['address']->type.$contact['address']->id }}" type="checkbox" name="active_{{ $contact['address']->type.$contact['address']->id }}" data-brand="{{ $contact['address']->type.$contact['address']->id }}" value="true" {{ ($contact['address']->actived=='1') ? 'checked' : null }}>
										</div>
									</div>
									<div class="input-group input-group-merge input-group-{{ $contact['address']->type.$contact['address']->id }} {{ ($contact['address']->actived=='0') ? 'disabled' : null }} mb-2">
										<div class="input-group-text">
											<i class="bx {{ ($contact['address']->actived=='0') ? 'bx' : 'text-primary bxs' }}-message-square-edit"></i>
										</div>
										<input type="text" name="title_{{ $contact['address']->type.$contact['address']->id }}" id="title-{{ $contact['address']->type.$contact['address']->id }}" class="form-control change-input-edited input-{{ $contact['address']->type.$contact['address']->id }}" value="{{ $contact['address']->title }}" data-edit="{{ $contact['address']->type.$contact['address']->id }}" placeholder="isi disini" {{ ($contact['address']->actived=='0') ? 'readonly' : null }}>
									</div>
									<textarea name="content_{{ $contact['address']->type.$contact['address']->id }}" id="content-{{ $contact['address']->type.$contact['address']->id }}" class="form-control change-input-edited input-{{ $contact['address']->type.$contact['address']->id }}" data-edit="{{ $contact['address']->type.$contact['address']->id }}" placeholder="isi disini" {{ ($contact['address']->actived=='0') ? 'readonly' : null }}>{{ $contact['address']->content }}</textarea>
									<input type="radio" name="edit_{{ $contact['address']->type.$contact['address']->id }}" id="edit-{{ $contact['address']->type.$contact['address']->id }}" value="true">
								</div>
								<div class="link-external_form border shadow rounded p-2 mb-3">
									<div class="d-flex justify-content-between py-2 px-3">
										<div>
											<i class="bx bx-map"></i>
											{{ Str::title('google map') }}
										</div>
										<div class="form-check form-switch">
											<input class="form-check-input change-input-status change-input-edited" data-edit="{{ $contact['map']->type.$contact['map']->id }}" type="checkbox" name="active_{{ $contact['map']->type.$contact['map']->id }}" data-brand="{{ $contact['map']->type.$contact['map']->id }}" value="true" {{ ($contact['map']->actived=='1') ? 'checked' : null }}>
										</div>
									</div>
									<div class="input-group input-group-merge input-group-{{ $contact['map']->type.$contact['map']->id }} {{ ($contact['map']->actived=='0') ? 'disabled' : null }} mb-2">
										<div class="input-group-text">
											<i class="bx {{ ($contact['map']->actived=='0') ? 'bx' : 'text-primary bxs' }}-message-square-edit"></i>
										</div>
										<input type="text" name="title_{{ $contact['map']->type.$contact['map']->id }}" id="title-{{ $contact['map']->type.$contact['map']->id }}" class="form-control change-input-edited input-{{ $contact['map']->type.$contact['map']->id }}" value="{{ $contact['map']->title }}" data-edit="{{ $contact['map']->type.$contact['map']->id }}" placeholder="isi disini" {{ ($contact['map']->actived=='0') ? 'readonly' : null }}>
									</div>
									<textarea name="content_{{ $contact['map']->type.$contact['map']->id }}" id="content-{{ $contact['map']->type.$contact['map']->id }}" class="form-control change-input-edited input-{{ $contact['map']->type.$contact['map']->id }}" data-edit="{{ $contact['map']->type.$contact['map']->id }}" placeholder="isi disini" {{ ($contact['map']->actived=='0') ? 'readonly' : null }}>{{ $contact['map']->content }}</textarea>
									<input type="radio" name="edit_{{ $contact['map']->type.$contact['map']->id }}" id="edit-{{ $contact['map']->type.$contact['map']->id }}" value="true">
								</div>
							</div>
							<div class="tab-pane fade" id="nav-{{ $tablist[1]['title'] }}" role="tabpanel">
								@foreach ($contact['phone'] as $key => $item)
								<div class="link-external_form border shadow rounded p-2 mb-3">
									<div class="d-flex justify-content-between py-2 px-3">
										<div>
											<i class="bx bx-phone"></i>
											{{ Str::title('telepon ').($key+1) }}
										</div>
										<div class="form-check form-switch">
											<input class="form-check-input change-input-status change-input-edited" data-edit="{{ $item->type.$item->id }}" type="checkbox" name="active_{{ $item->type.$item->id }}" data-brand="{{ $item->type.$item->id }}" value="true" {{ ($item->actived=='1') ? 'checked' : null }}>
										</div>
									</div>
									<div class="input-group input-group-merge input-group-{{ $item->type.$item->id }} {{ ($item->actived=='0') ? 'disabled' : null }} mb-2">
										<div class="input-group-text">
											<i class="bx {{ ($item->actived=='0') ? 'bx' : 'text-info bxs' }}-message-square-edit"></i>
										</div>
										<input type="text" name="title_{{ $item->type.$item->id }}" id="title-{{ $item->type.$item->id }}" class="form-control change-input-edited input-{{ $item->type.$item->id }}" value="{{ $item->title }}" data-edit="{{ $item->type.$item->id }}" placeholder="isi disini" {{ ($item->actived=='0') ? 'readonly' : null }}>
										<input type="text" name="content_{{ $item->type.$item->id }}" id="content-{{ $item->type.$item->id }}" class="form-control change-input-edited input-{{ $item->type.$item->id }}" value="{{ $item->content }}" data-edit="{{ $item->type.$item->id }}" placeholder="isi disini" {{ ($item->actived=='0') ? 'readonly' : null }}>
									</div>
									<input type="radio" name="edit_{{ $item->type.$item->id }}" id="edit-{{ $item->type.$item->id }}" value="true">
								</div>
								@endforeach
								<hr>
								@foreach ($contact['whatsapp'] as $key => $item)
								<div class="link-external_form border shadow rounded p-2 mb-3">
									<div class="d-flex justify-content-between py-2 px-3">
										<div>
											<i class="bx bxl-whatsapp"></i>
											{{ Str::title('whatsapp ').($key+1) }}
										</div>
										<div class="form-check form-switch">
											<input class="form-check-input change-input-status change-input-edited" data-edit="{{ $item->type.$item->id }}" type="checkbox" name="active_{{ $item->type.$item->id }}" data-brand="{{ $item->type.$item->id }}" value="true" {{ ($item->actived=='1') ? 'checked' : null }}>
										</div>
									</div>
									<div class="input-group input-group-merge input-group-{{ $item->type.$item->id }} {{ ($item->actived=='0') ? 'disabled' : null }} mb-2">
										<div class="input-group-text">
											<i class="bx {{ ($item->actived=='0') ? 'bx' : 'text-success bxs' }}-message-square-edit"></i>
										</div>
										<input type="text" name="title_{{ $item->type.$item->id }}" id="title-{{ $item->type.$item->id }}" class="form-control change-input-edited input-{{ $item->type.$item->id }}" value="{{ $item->title }}" data-edit="{{ $item->type.$item->id }}" placeholder="isi disini" {{ ($item->actived=='0') ? 'readonly' : null }}>
										<input type="text" name="content_{{ $item->type.$item->id }}" id="content-{{ $item->type.$item->id }}" class="form-control change-input-edited input-{{ $item->type.$item->id }}" value="{{ $item->content }}" data-edit="{{ $item->type.$item->id }}" placeholder="isi disini" {{ ($item->actived=='0') ? 'readonly' : null }}>
									</div>
									<input type="radio" name="edit_{{ $item->type.$item->id }}" id="edit-{{ $item->type.$item->id }}" value="true">
								</div>
								@endforeach
							</div>
							<div class="tab-pane fade" id="nav-{{ $tablist[2]['title'] }}" role="tabpanel">
								@foreach ($contact['email'] as $key => $item)
								<div class="link-external_form border shadow rounded p-2 mb-3">
									<div class="d-flex justify-content-between py-2 px-3">
										<div>
											<i class="bx bx-envelope"></i>
											{{ Str::title('email ').($key+1) }}
										</div>
										<div class="form-check form-switch">
											<input class="form-check-input change-input-status change-input-edited" data-edit="{{ $item->type.$item->id }}" type="checkbox" name="active_{{ $item->type.$item->id }}" data-brand="{{ $item->type.$item->id }}" value="true" {{ ($item->actived=='1') ? 'checked' : null }}>
										</div>
									</div>
									<div class="input-group input-group-merge input-group-{{ $item->type.$item->id }} {{ ($item->actived=='0') ? 'disabled' : null }} mb-2">
										<div class="input-group-text">
											<i class="bx {{ ($item->actived=='0') ? 'bx' : 'text-warning bxs' }}-message-square-edit"></i>
										</div>
										<input type="text" name="title_{{ $item->type.$item->id }}" id="title-{{ $item->type.$item->id }}" class="form-control change-input-edited input-{{ $item->type.$item->id }}" value="{{ $item->title }}" data-edit="{{ $item->type.$item->id }}" placeholder="isi disini" {{ ($item->actived=='0') ? 'readonly' : null }}>
										<input type="email" name="content_{{ $item->type.$item->id }}" id="content-{{ $item->type.$item->id }}" class="form-control change-input-edited input-{{ $item->type.$item->id }}" value="{{ $item->content }}" data-edit="{{ $item->type.$item->id }}" placeholder="isi disini" {{ ($item->actived=='0') ? 'readonly' : null }}>
									</div>
									<input type="radio" name="edit_{{ $item->type.$item->id }}" id="edit-{{ $item->type.$item->id }}" value="true">
								</div>
								@endforeach
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>
@endsection

@push('style')
	<meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@push('script')
@endpush