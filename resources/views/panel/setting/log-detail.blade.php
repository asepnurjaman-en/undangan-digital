@extends('panel.layouts.app')
@section('title', Str::title($data['title']))
@section('content')
<div class="container">
	<div class="row">
		<div class="col-12">
            <div class="my-3 mb-0">
				<button type="button" class="btn-back btn btn-outline-secondary">
                    <i class="bx bx-chevron-left"></i>
                    <span>{{ Str::title('kembali') }}</span>
                </button>
			</div>
			<div class="card border-0 my-3">
                <div class="card-header d-flex justify-content-between flex-column flex-md-row p-3">
                    <div class="d-flex flex-column">
                        <span class="fs-5"><b>{{ $activity->subject_type[2] }}</b> {{ Str::title($activity->event) }}.</span>
                        <span>by <b class="text-primary">{!! $causer->name ?? '<i class="text-muted">deleted account</i>' !!}</b></span>
                    </div>
                    <div>
                        <span>at <b>{{ $activity->created_at }}</b></span>
                    </div>
                </div>
				<div class="card-body p-3">
                    @if ($activity->event=='updated')                        
                    <div class="log-activity horizonbar d-flex align-items-center justify-content-between flex-column flex-md-row">
                        <div class="bg-light border border-2 border-warning p-3">
                            @foreach (json_decode($activity->properties, true)['old'] as $key => $item)
                            <div>
                                <code>{{ Str::upper($key) }}</code>
                                @if ($key=='file')
                                <div class="p-1">{!! anchor(text:"<i class=\"bx bx-search me-1\"></i> Tinjau", class:['btn', 'btn-secondary', 'btn-sm'], data:['fancybox'=>'preview'], href:url('storage/'.$item)) !!}</div>
                                @elseif ($key=='content')
                                    @if (strlen($item)<1000)
                                    <div class="px-1"><i class="bx bx-subdirectory-right me-1"></i>{{ $item }}</div>                                    
                                    @else
                                    <div class="fst-italic fw-light"><i class="bx bx-error-circle text-danger me-1"></i>{{ __('cannot be loaded') }}</div>
                                    @endif
                                @else
                                <div class="px-1 break-all"><i class="bx bx-subdirectory-right me-1"></i>{{ $item }}</div>                                    
                                @endif
                            </div>
                            @endforeach
                        </div>
                        <span><i class="bx bx-right-arrow-alt my-2"></i></span>
                        <div class="bg-light border border-2 border-success p-3">
                            @foreach (json_decode($activity->properties, true)['attributes'] as $key => $item)
                            <div>
                                <code>{{ Str::upper($key) }}</code>
                                @if ($key=='file')
                                <div class="p-1">
                                    {!! anchor(text:"<i class=\"bx bx-search me-1\"></i> Tinjau", class:['btn', (json_decode($activity->properties, true)['old'][$key]==$item) ? 'btn-secondary' : 'btn-warning', 'btn-sm'], data:['fancybox'=>'preview'], href:url('storage/'.$item)) !!}
                                    
                                </div>
                                @elseif ($key=='content')
                                    @if (strlen($item)<1000)
                                    <div class="px-1"><i class="bx bx-subdirectory-right me-1"></i>{!! (json_decode($activity->properties, true)['old'][$key]==$item) ? $item : "<mark>{$item}</mark>" !!}</div>                                    
                                    @else
                                    <div class="fst-italic fw-light"><i class="bx bx-error-circle text-danger me-1"></i>{{ __('cannot be loaded') }}</div>
                                    @endif
                                @else
                                <div class="px-1 break-all"><i class="bx bx-subdirectory-right me-1"></i>{!! (json_decode($activity->properties, true)['old'][$key]==$item) ? $item : "<mark>{$item}</mark>" !!}</div>                                    
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @elseif ($activity->event=='created')
                    <div>
                        <div class="bg-light border border-2 border-success p-3">
                            @foreach (json_decode($activity->properties, true)['attributes'] as $key => $item)
                            <div>
                                <code>{{ Str::upper($key) }}</code>
                                @if ($key=='file')
                                <div class="p-1">{!! anchor(text:"<i class=\"bx bx-search me-1\"></i> Tinjau", class:['btn', 'btn-secondary', 'btn-sm'], data:['fancybox'=>'preview'], href:url('storage/'.$item)) !!}</div>
                                @elseif ($key=='content')
                                    @if (strlen($item)<1000)
                                    <div class="px-1"><i class="bx bx-subdirectory-right me-1"></i>{{ $item }}</div>                                    
                                    @else
                                    <div class="fst-italic fw-light"><i class="bx bx-error-circle text-danger me-1"></i>{{ __('cannot be loaded') }}</div>
                                    @endif
                                @else
                                <div class="px-1 break-all"><i class="bx bx-subdirectory-right me-1"></i>{{ $item }}</div>                                    
                                @endif
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@push('style')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="stylesheet" href="{{ asset('modules/@fancyapps/fancybox/dist/jquery.fancybox.min.css') }}">
@endpush

@push('script')
<script src="{{ asset('modules/@fancyapps/fancybox/dist/jquery.fancybox.min.js') }}"></script>
<script>
	$("[data-fancybox=preview]").fancybox();
</script>
@endpush
