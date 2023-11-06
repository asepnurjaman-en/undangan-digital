@extends('member.layouts.app')
@section('title', Str::title('upgrade paket'))
@php
	use Carbon\Carbon;
    $menu = [
        'gift'=>true,
        'e-invitation'=>true,
        'filter-ig'=>true,
        'story'=>true,
        'live-stream'=>true,
        'private-invitation'=>true,
        'event'=>true,
        'free-text'=>false,
        'event-count'=>"unlimited",
        'gallery-photo'=>"unlimited",
        'smart-wa'=>false,
        'manual-wa'=>"unlimited",
        'guest'=>"unlimited",
        'gallery-video'=>1,
        'music'=>"custom",
        'premium-template'=>"all",
        'active'=>370
    ];
@endphp
@section('content')
<section class="py-3">
    <nav class="mb-3">
        <div class="creasik-nav-pill nav nav-pills justify-content-center" id="nav-package" role="tablist">
            <button class="nav-link active" id="nav-digitalInvitation-tab" data-bs-toggle="tab" data-bs-target="#nav-digitalInvitation" type="button" role="tab" aria-controls="nav-digitalInvitation" aria-selected="true">
                <span>Undangan Digital</span>
            </button>
            <button class="nav-link" id="nav-guestBook-tab" data-bs-toggle="tab" data-bs-target="#nav-guestBook" type="button" role="tab" aria-controls="nav-guestBook" aria-selected="false">
                <span>Buku Tamu</span>
            </button>
        </div>
    </nav>
    <div class="bg-white shadow rounded overflow-hidden">
        <div class="tab-content" id="pills-package">
            <div class="tab-pane fade show active" id="nav-digitalInvitation" role="tabpanel" aria-labelledby="nav-digitalInvitation-tab">
                <div class="table-responsive">
                    <table class="table-package table mb-0">
                        <thead>
                            <tr>
                                <th class="text-center">Fitur</th>
                                @foreach ($data->package as $item)
                                <th class="text-center position-relative">
                                    @if ($item->id==$data->activation->package_id)
                                    <small class="position-absolute top-0 end-0 start-0 d-inline-block m-auto text-muted fs-6 p-1">Saat ini</small>
                                    @endif
                                    <b class="d-block">{{ $item->title }}</b>
                                </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data->feature->content as $key => $feature)
                            <tr>
                                <th class="bg-light text-left fw-bold">{{ $feature }}</th>
                                @foreach ($data->package as $item)
                                @php
                                    $fill = json_decode($item->content, true)[$key];
                                    if ($fill===true) :
                                        $value = "<i class=\"bx bx-check text-success fs-3\"></i>";
                                    elseif ($fill===false) :
                                        $value = "<i class=\"bx bx-x text-danger fs-3\"></i>";
                                    else :
                                        if ($key=='active') :
                                            $long_days = explode(' ', Carbon::now()->locale('id')->addDays($fill)->diffForHumans());
                                            $value = $long_days[0].' '.$long_days[1];
                                        elseif ($key=='template') :
                                            $value = Str::title(implode(', ', $fill));
                                        else :
                                            $value = Str::title($fill);
                                        endif;
                                    endif;
                                @endphp
                                <td class="text-center">{!! $value !!}</td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th></th>
                                @foreach ($data->package as $item)
                                <th class="text-center">
                                    <h5>{!! idr($item->price) !!}</h5>
                                    <a href="{{ route('packages.payment', $item->id) }}" class="btn btn-creasik-primary">
                                        <i class="bx bxs-check-circle"></i>
                                        <span>Pilih</span>
                                    </a>
                                </th>
                                @endforeach
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="tab-pane fade" id="nav-guestBook" role="tabpanel" aria-labelledby="nav-guestBook-tab">
                <h3 class="text-center text-muted p-3">Coming Soon</h3>
            </div>
        </div>
    </div>
</section>
@endsection