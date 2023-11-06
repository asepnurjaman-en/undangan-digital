@if ($mode=='multiple')
<div class="modal fade" id="multiple-storage-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
		<div class="modal-content">
			<div class="modal-header p-2">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">
					<i class="bx bx-x"></i>
					<span>{{ Str::title('batal') }}</span>
				</button>
				<form action="{{ route('home.put-storage-modal', 'multiple') }}" method="post" class="choose-images">
					<input type="hidden" name="id_image" value/>
					<button type="submit" class="btn btn-info" disabled>
						<i class="bx bx-check"></i>
						{{ Str::title('pilih') }}
						<b></b>
					</button>
				</form>
			</div>
			<div class="modal-body position-relative py-2 px-0">
				<table class="dataTables" data-list="{{ route('home.storage-modal', 'multiple') }}">
					<thead>
						<tr>
							<th><i class="bx bx-image"></i></th>
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
@elseif ($mode=='single')	
<div class="modal fade" id="single-storage-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
		<div class="modal-content">
			<div class="modal-header p-2">
				<button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">
					<i class="bx bx-x"></i>
					<span>{{ Str::title('batal') }}</span>
				</button>
				<form action="{{ route('home.put-storage-modal', 'single') }}" method="post" class="choose-images">
					<input type="hidden" name="id_image" value/>
					<button type="submit" class="btn btn-info" disabled>
						<i class="bx bx-check"></i>
						{{ Str::title('pilih') }}
						<b></b>
					</button>
				</form>
			</div>
			<div class="modal-body position-relative py-2 px-0" @style('overflow-x:hidden')>
				<table class="dataTables dtu-column" data-list="{{ route('home.storage-modal', 'single') }}">
					<thead>
						<tr>
							<th></th>
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
@endif
