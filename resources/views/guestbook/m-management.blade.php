@extends('guestbook.layouts.app')
@section('title', Str::title($menu['title']))   
@section('content')
<section class="position-relative py-3">
    @include('guestbook.layouts.component', ['content'=>'breadcrumb', 'menu'=>$menu])
    <form action="" method="post">
        @csrf
        @method('put')
        <div class="row g-3">
            <div class="col-lg-7">
                <div class="bg-white shadow rounded p-3">
                    <h4>Keterangan</h4>
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
@endpush