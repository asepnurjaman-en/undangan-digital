@extends('member.layouts.app')
@section('title', Str::title($menu['title']))   
@section('content')
<section class="position-relative py-3">
    @include('member.layouts.component', ['content'=>'breadcrumb', 'menu'=>$menu])
    <form action="{{ route('save.setting', 'quote') }}" class="save-menu" method="post">
        @csrf
        @method('put')
        <div class="row g-3">
            <div class="col-lg-7">
                <div class="bg-white shadow rounded p-3 mb-2">
                    <div class="mb-2">
                        <div>
                            <label for="quote_content" class="form-label">
                                <var dir="quote_content">Quote kamu</var>
                            </label>
                            <textarea name="quote_content" id="quote_content" class="form-control set-text" rows="5" placeholder="Quote kamu">{{ $data->preset->content }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="bg-white shadow rouned p-3">
                    <figure class="assets-figure border p-2 mb-0">
                        @foreach ($data->decoration as $key => $item)
                        <label for="decoration_{{ $key }}" class="item">
                            <input type="radio" name="quote_decoration" id="decoration_{{ $key }}" value="{{ $item->content }}" @checked($data->preset->decoration==$item->content)>
                            {!! image(src:url('storage/decoration/'.$item->content), alt:$item->title) !!}
                        </label>
                        @endforeach
                    </figure>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="bg-white shadow rounded p-3">
                    @foreach ($data->quote as $key => $item)
					<div class="quote-item border rounded mb-1">
						<blockquote class="p-2">{{ $item->content }}</blockquote>
						<div class="bg-light p-2">
                            <button type="button" class="btn btn-creasik-primary btn-sm get-text">
                                <i class="bx bx-edit"></i>
								<span>Gunakan</span>
							</button>
                            <span>{{ $item->title }}</span>
						</div>
					</div>
					@endforeach
                </div>
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
<script>
    $(".get-text").on('click', function(e) {
        e.preventDefault();
        let text = $(this).parent().parent().children('blockquote').text();
        $(".set-text").val(text);
    });
</script>
@endpush