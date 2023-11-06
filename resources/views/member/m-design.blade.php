@extends('member.layouts.app')
@section('title', Str::title($menu['title']))   
@section('content')
<section class="position-relative py-3">
    @include('member.layouts.component', ['content'=>'breadcrumb', 'menu'=>$menu])
    <form action="{{ route('save.setting', 'design') }}" class="save-menu" method="post">
        @csrf
        @method('put')
        <div class="row g-3">
            <div class="col-12">
                <div class="bg-white shadow rounded p-3">
                    <h4>Desain</h4>
                    @if (in_array('basic', $data->limit))
                    <div class="d-flex template-category mb-2 basic">
                        <div class="template-list">
                            @forelse ($data->template->basic as $item)
                            @php
                                $check = ($item->id==Auth::user()->inv->temp->id) ? true : false;
                                $label = ($item->id==Auth::user()->inv->temp->id) ? true : false;
                            @endphp
                            <figure>
                                @if ($label===true)
                                <span class="badge bg-warning">Saat ini</span>
                                @endif
                                <input type="radio" name="design_template" id="temp{{ $item->id }}" value="{{ $item->id }}" @checked($check)>
                                <label for="temp{{ $item->id }}" class="shadow-sm">
                                    <img src="{{ url('storage/'.$item->file) }}" alt="">
                                    <span>{{ $item->title }}</span>
                                </label>
                                <a href="{{ route('preview-template.index', $item->slug) }}" class="btn text-dark text-capitalize bg-white w-100 btn-sm my-1" target="_BLANK">pratinjau</a>
                            </figure>
                            @empty
                            <div class="empty m-2 py-5">Kosong</div>
                            @endforelse
                        </div>
                        <div class="template-label">
                            <span>Basic</span>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    @endif
                    @if (in_array('premium', $data->limit))
                    <div class="d-flex template-category mb-2 premium">
                        <div class="template-list">
                            @forelse ($data->template->premium as $item)
                            @php
                                $check = ($item->id==Auth::user()->inv->temp->id) ? true : false;
                                $label = ($item->id==Auth::user()->inv->temp->id) ? true : false;
                            @endphp
                            <figure>
                                @if ($label===true)
                                <span class="badge bg-warning">Saat ini</span>
                                @endif
                                <input type="radio" name="design_template" id="temp{{ $item->id }}" value="{{ $item->id }}" @checked($check)>
                                <label for="temp{{ $item->id }}" class="shadow-sm">
                                    <img src="{{ url('storage/sm/'.$item->file) }}" alt="">
                                    <span>{{ $item->title }}</span>
                                </label>
                                <a href="{{ route('preview-template.index', $item->slug) }}" class="btn text-dark text-capitalize bg-white w-100 btn-sm my-1" target="_BLANK">pratinjau</a>
                            </figure>
                            @empty
                            <div class="empty m-2 py-5">Kosong</div>
                            @endforelse
                        </div>
                        <div class="template-label">
                            <span>Premium</span>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    @endif
                    @if (in_array('exclusive', $data->limit))
                    <div class="d-flex template-category mb-2 exclusive">
                        <div class="template-list">
                            @forelse ($data->template->exclusive as $item)
                            @php
                                $check = ($item->id==Auth::user()->inv->temp->id) ? true : false;
                                $label = ($item->id==Auth::user()->inv->temp->id) ? true : false;
                            @endphp
                            <figure>
                                @if ($label===true)
                                <span class="badge bg-warning">Saat ini</span>
                                @endif
                                <input type="radio" name="design_template" id="temp{{ $item->id }}" value="{{ $item->id }}" @checked($check)>
                                <label for="temp{{ $item->id }}" class="shadow-sm">
                                    <img src="{{ url('storage/sm/'.$item->file) }}" alt="">
                                    <span>{{ $item->title }}</span>
                                </label>
                            </figure>
                            @empty
                            <div class="empty m-2 py-5">Kosong</div>
                            @endforelse
                        </div>
                        <div class="template-label">
                            <span>Exclusive</span>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    @endif
                </div>
            </div>
            <div class="col-lg-6">
                <div class="bg-white shadow rounded p-3">
                    <h4>Warna tema</h4>
                    <div class="select-tab border-bottom">
                        <div>
                            <var dir="design_title_color">Warna tema</var>
                        </div>
                        <div>
                            <label for="design_title_color">
                                <input type="color" name="design_title_color" id="design_title_color" value="{{ $data->preset->title->color }}">
                            </label>
                            <label for="design_content_color">
                                <input type="color" name="design_content_color" id="design_content_color" value="{{ $data->preset->content->color }}">
                            </label>
                        </div>
                    </div>
                    <div class="select-tab border-bottom">
                        <div>
                            <var dir="design_background">Warna latar</var>
                        </div>
                        <div>
                            <label for="design_background">
                                <input type="color" name="design_background" id="design_background" value="{{ $data->preset->background }}">
                            </label>
                        </div>
                    </div>
                    <div class="select-tab border-bottom">
                        <div>
                            <var dir="design_button_color">Warna tombol</var>
                        </div>
                        <div>
                            <label for="design_button_color">
                                <input type="color" name="design_button_color" id="design_button_color" value="{{ $data->preset->button->color }}">
                            </label>
                            <label for="design_btn_bg_color">
                                <input type="color" name="design_button_background" id="design_btn_bg_color" value="{{ $data->preset->button->background }}">
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="bg-white shadow rounded p-3">
                    <h4>Font</h4>
                    <div class="select-tab border-bottom">
                        <div>
                            <var dir="design_title_font">Font judul</var>
                        </div>
                        <div>
                            <select name="design_title_font" class="form-select">
                                @foreach ($data->font as $item)
                                @php
                                    $check = ($data->preset->title->font==$item->content) ? true : false;
                                @endphp
                                <option value="{{ $item->content }}" @style('font-family:'.$item->content) @selected($check)>{{ $item->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="select-tab border-bottom">
                        <div>
                            <var dir="design_content_font">Font deskripsi</var>
                        </div>
                        <div>
                            <select name="design_content_font" class="form-select">
                                @foreach ($data->font as $item)
                                @php
                                    $check = ($data->preset->content->font==$item->content) ? true : false;
                                @endphp
                                <option value="{{ $item->content }}" @style('font-family:'.$item->content) @selected($check)>{{ $item->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
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