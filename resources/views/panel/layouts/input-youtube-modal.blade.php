<div class="modal fade" id="input-youtube-modal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <form action="{{ route('home.from-youtube') }}" method="post" class="choose-youtube">
                <div class="modal-header p-2">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal" aria-label="Close">
                        <i class="bx bx-x"></i>
                        <span>{{ Str::title('batal') }}</span>
                    </button>
                    <button type="submit" class="btn btn-danger">
                            <i class="bx bx-check"></i>
                            {{ Str::title('pilih') }}
                    </button>
                </div>
                <div class="modal-body p-2">
                    <div class="input-group input-group-lg input-group-merge">
                        <input type="url" name="url-input-youtube" class="form-control paste-input" placeholder="tempel url youtube disini" required>
                        <span class="input-group-text cursor-pointer paste-button"><i class="bx bx-paste"></i></span>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>