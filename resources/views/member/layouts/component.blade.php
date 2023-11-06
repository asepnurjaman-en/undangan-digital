@if ($content=='breadcrumb')
<div class="d-flex creasik-breadcrumb">
    <div>
        <a href="{{ route('member.main') }}" class="btn-simplied d-flex align-items-center bg-white rounded small text-creasik-primary text-decoration-none py-1 px-3 me-1 mb-1">
            <i class="bx bx-chevron-left fs-4 me-1"></i>
			<span>Dashboard</span>
        </a>
    </div>
    <h3>
        <img src="{{ url('images/icons/'.$menu['icon']) }}" alt="{{ $menu['title'] }}">
        <span>{{ Str::upper($menu['title']) }}</span>
    </h3>
</div>
@endif

@if ($content=='confirmation-of-payment')
<div class="modal fade" id="confirmation-of-payment" tabindex="-1" aria-labelledby="presetsLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title small" id="presetsLabel">
					<i class="bx bx-camera me-1"></i>
					<span>Bukti pembayaran</span>
				</h5>
				<div>
					<button type="button" class="btn btn-light btn-sm me-1" data-bs-dismiss="modal" aria-label="Close">Batal</button>
				</div>
			</div>
			<div class="modal-body">
				<form action="{{ route('invoice-prove', $id) }}" method="post" class="save-menu" enctype="multipart/form-data">
					@csrf
					@method('PUT')
					<div class="bg-white shadow rounded p-3 mb-3">
						<div class="image-selector single">
							<div>
								<img src="{{ url('storage/') }}" class="set_prove_image" alt="">
							</div>
							<div>
								<div class="border-bottom py-2">
									<input type="file" name="prove_image" id="prove_image" class="change-img" accept=".jpg,.png,.jpeg">
									<label class="form-label">
										<var dir="prove_image">Ambil bukti pembayaran</var>
									</label>
									<label for="prove_image" class="btn btn-creasik-primary d-inline-block mb-1">
										<i class="bx bx-upload"></i>
										<span>Unggah</span>
									</label>
								</div>
							</div>
						</div>
					</div>
					<div class="form-group">
						<button type="submit" class="btn btn-primary w-100">
							<i class="bx bx-check-double"></i>
							<span>Kirim</span>
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endif

@if ($content=='modal-avatar')
<div class="modal fade" id="avatar-{{ $gender }}" tabindex="-1" aria-labelledby="presetsLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title small" id="presetsLabel">{{ Str::title('avatar') }}</h5>
				<button type="button" class="bg-transparent border-0 p-0" data-bs-dismiss="modal" aria-label="Close">
					<i class="bx bx-x"></i>
				</button>
			</div>
			<div class="modal-body">
				<figure class="assets-figure border p-2">
					@if ($gender=='female')
					@foreach ($data->avatar->female as $key => $item)
					<label for="avatar_{{ $gender.$key }}" class="item">
						<input type="radio" name="profile_avatar_female" id="avatar_{{ $gender.$key }}" value="{{ $item->content }}" class="change-asset">
						{!! image(src:url('storage/avatar/'.$item->content), alt:$item->title) !!}
					</label>
					@endforeach
					@elseif ($gender=='male')
					@foreach ($data->avatar->male as $key => $item)
					<label for="avatar_{{ $gender.$key }}" class="item">
						<input type="radio" name="profile_avatar_male" id="avatar_{{ $gender.$key }}" value="{{ $item->content }}" class="change-asset">
						{!! image(src:url('storage/avatar/'.$item->content), alt:$item->title) !!}
					</label>
					@endforeach
					@elseif ($gender=='none')
					@foreach ($data->avatar->none as $key => $item)
					<label for="avatar_{{ $gender.$key }}" class="item">
						<input type="radio" name="cover_avatar_none" id="avatar_{{ $gender.$key }}" value="{{ $item->content }}" class="change-asset">
						{!! image(src:url('storage/avatar/'.$item->content), alt:$item->title) !!}
					</label>
					@endforeach
					@endif
				</figure>
			</div>
		</div>
	</div>
</div>
@endif

@if ($content=='modal-storage')
<div class="modal modal-xl fade" id="storage" tabindex="-1" aria-labelledby="presetsLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title small" id="presetsLabel">
					<i class="bx bx-images me-1"></i>
					<span>Penyimpanan</span>
				</h5>
				<div>
					<button type="button" class="btn btn-light btn-sm me-1" data-bs-dismiss="modal" aria-label="Close">Batal</button>
					<button type="button" class="btn btn-creasik-primary btn-sm px-5 use-image">
						<span>Pilih</span>
						<i class="bx bx-check-circle ms-1"></i>
					</button>
				</div>
			</div>
			<div class="modal-body">
				<div class="row g-3">
					<div class="col-lg-3">
						<form action="{{ route('strbox.store') }}" method="post" class="strbox-store" enctype="multipart/form-data">
							@csrf
							<div class="px-1 mb-1">
								{{-- start --}}
								<label for="storage_image_bg" class="button_outer rounded">
									<div class="btn_upload">
										<input type="file" name="storage_image_bg" id="storage_image_bg" class="change-storage-name" accept=".jpg,.jpeg,.png">
										Pilih gambar
									</div>
									<div class="processing_bar"></div>
								</label>
								<div class="error_msg"></div>
								<div class="uploaded_file_view" id="uploaded_view">
									<span class="file_remove">&times;</span>
								</div>
								{{-- end --}}
								<input type="text" name="storage_title_text" id="storage_title_text" class="form-control form-control-sm" placeholder="Keterangan">
							</div>
							<div class="px-1 mb-3">
								<button type="submit" class="btn btn-primary btn-sm w-100">
									<i class="bx bx-upload me-1"></i>
									<span>{{ Str::title('unggah gambar') }}</span>
								</button>
							</div>
						</form>
					</div>
					<div class="col-lg-9">
						<table class="dataTables storage-table" data-list="{{ route('strbox.list', $mode) }}">
						</table>
					</div>
				</div>
			</div>
        </div>
    </div>
</div>
@endif