@if ($content=='breadcrumb')
<div class="d-flex creasik-breadcrumb">
    <div>
        <a href="{{ route('guestbook') }}" class="btn-simplied d-flex align-items-center bg-white rounded small text-creasik-primary text-decoration-none py-1 px-3 me-1 mb-1">
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
