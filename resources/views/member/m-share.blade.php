@extends('member.layouts.app')
@section('title', Str::title($menu['title']))   
@section('content')
<section class="position-relative py-3">
    @include('member.layouts.component', ['content'=>'breadcrumb', 'menu'=>$menu])
    <div class="row g-3">
        <div class="col-lg-7">
            <div class="bg-white shadow rounded p-3 mb-2">
                <div class="mb-2">
                    <div>
                        <label for="share_link" class="form-label">Bagikan link berikut</label>
                        <div class="input-group">
                            <input type="text" name="share_link" id="share_link" class="form-control border-end-0" value="{{ route('invitation.index', Auth::user()->inv->slug) }}" placeholder="Share link" readonly>
                            <span class="input-group-text bg-white border-start-0 copy-text" data-text="{{ route('invitation.index', Auth::user()->inv->slug) }}">
                                <i class="bx bx-copy"></i>
                                <span @style('display:none')>Disalin</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white shadow rounded p-3 mb-2">
                <form action="{{ route('menu.share-add') }}" method="post" class="add-guest">
                    @csrf
                    <div class="mb-2">
                        <var dir="share_guest_type"></var>
                        <div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="share_guest_type" id="share_guest_type_1" value="personal" @checked(true)>
                                <label class="form-check-label" for="share_guest_type_1">Individu</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="share_guest_type" id="share_guest_type_2" value="group">
                                <label class="form-check-label" for="share_guest_type_2">Grup</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="share_guest_type" id="share_guest_type_3" value="private">
                                <label class="form-check-label" for="share_guest_type_3">Privasi</label>
                            </div>
                        </div>
                    </div>
                    <div class="mb-2">
                        <div>
                            <label for="share_guest_name" class="form-label">
                                <var dir="share_guest_name">Nama</var>
                            </label>
                            <input type="text" name="share_guest_name" id="share_guest_name" class="form-control form-control-sm" placeholder="Nama lengkap">
                        </div>
                    </div>
                    <div class="mb-2">
                        <div>
                            <label for="share_guest_location" class="form-label">
                                <var dir="share_guest_location">Lokasi</var>
                            </label>
                            <input type="text" name="share_guest_location" id="share_guest_location" class="form-control form-control-sm" placeholder="Lokasi">
                        </div>
                    </div>
                    <div class="mb-1">
                        <button type="submit" class="btn btn-creasik-primary w-100">
                            <i class="bx bxs-plus-circle"></i>
                            <span>Tambah tamu</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-lg-5">
            @forelse ($data->guest as $item)
            <div class="guest-item bg-white shadow-sm rounded p-2 mb-1">
                <div class="name">
                    <span><small class="text-muted">u/</small> {{ json_decode($item->name)->name }} <small class="text-muted">di</small> {{ json_decode($item->name)->location }}</span>
                    <small>{{ route('invitation.index', Auth::user()->inv->slug) }}?to={{ $item->slug }}</small>
                    <b class="badge bg-warning">{{ Str::title($item->type) }}</b>
                </div>
                <button type="button" class="copy-text" data-text="{{ route('invitation.index', Auth::user()->inv->slug) }}?to={{ $item->slug }}">
                    <i class="bx bx-copy"></i>
                    <span @style('display:none')></span>
                </button>
            </div>
            @empty
            <div class="empty">belum ada undangan tamu</div>
            @endforelse
        </div>
    </div>
</section>
@endsection

@push('style')
@endpush

@push('script')
<script>
    $(".add-guest").on('submit', function(e) {
        e.preventDefault();
        let action = $(this).attr('action'),
			submit = $(this).find('button[type=submit]');
		$.ajax({
			type: 'post',
			url : action,
			data: $(this).serialize(),
			error: function(q,w,e) {
				submit.children('span').text('Coba Lagi');
				submit.prop('disabled', false);
				$.each(q.responseJSON.errors, function(index, value) {
					$(`var[dir=${index}]`).after(`<sup role="alert" title="${value}">!</sup>`);
				});
				console.log(q);
				console.log(w);
				console.log(e);
			},
			beforeSend: function() {
				$("sup[role=alert]").remove();
				submit.children('span').text('Memeriksa data...');
				submit.prop('disabled', true);
			},
			success: function(response) {
                window.location.reload();
			}
		});
    });
    $(".copy-text").on('click', function(e) {
        e.preventDefault();
        var text = $(this).data('text');
        $(this).children('i').fadeOut();
        $(this).children('span').fadeIn();
		$("body").append('<textarea name="selected-text"></textarea>');
		$("textarea[name=selected-text]").css('position', 'absolute').css('transform', 'scale(0,0)').val(text).select();
		if (document.execCommand('copy')) {
            setTimeout(() => {
                $(this).children('i').fadeIn();
                $(this).children('span').fadeOut();
            }, 1000);
			$("textarea[name=selected-text]").remove();
		}
    });
</script>
@endpush