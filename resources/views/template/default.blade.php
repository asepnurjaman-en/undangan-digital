@php
    use Carbon\Carbon;
    setlocale(LC_ALL, 'IND');
    $set = [
        'title' => "Wedding of ".$invitation->title." | Creasik Digital",
        'file' => url('storage/'.$invitation->file),
        'content' => Carbon::parse($data->detail->calendar->date)->formatLocalized('%A, %d %B %Y')
    ];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta name="description" content="{{ $set['content'] }}">
	<meta name="author" content="inovindo">
	<meta name="keywords" content="">
	<meta name="theme-color" content="{{ $data->design->title->color }}">
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ request()->fullUrl() }}">
    <meta property="og:title" content="{{ $set['title'] }}">
    <meta property="og:description" content="{{ $set['content'] }}">
    <meta property="og:image" content="{{ $set['file'] }}">
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ request()->fullUrl() }}">
    <meta property="twitter:title" content="{{ $set['title'] }}">
    <meta property="twitter:description" content="{{ $set['content'] }}">
    <meta property="twitter:image" content="{{ $set['file'] }}">
    <title>{{ $set['title'] }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Caveat&family=Dancing+Script&family=Great+Vibes&family=Kaushan+Script&family=Nova+Cut&family=Raleway&family=Righteous&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        :root {
            /* animate */
            --animate-duration: 3000ms;
            /* colorize */
            --light: #ffffff;
            --dark: #000000;
            --primary_color: {{ $data->design->title->color }};
            --secondary_color: {{ $data->design->content->color }};
            --bg_color: {{ $data->design->background }};
            --btn_text_color: {{ $data->design->button->color }};
            --btn_bg_color: {{ $data->design->button->background }};
            /* font */
            --primary_font: {{ $data->design->title->font }};
            --secondary_font: {{ $data->design->content->font }};
        }
    </style>
	@vite(['resources/css/template/default.css', 'resources/js/template/default.js'])
    {{-- <link rel="stylesheet" href="{{ asset('build/assets/default-97b83278.css') }}"> --}}
    {{-- <script src="{{ asset('build/assets/default-25a74223.js') }}" type="module"></script> --}}
</head>
<body>
    @if ($data->gift->show===true)
    <div id="gift">
        <a href="#gift-field" rel="modal:open">Hadiah</a>
    </div>
    @endif
    @if ($data->music->show===true)
    <div id="music">
        <button type="button" onclick="togglePlay()" style="--icon: url('{{ url('storage/icon/toggle-music.png') }}')">
            <span>play</span>
        </button>
		<audio id="music-source" src="{{ $data->music->url }}"></audio>
    </div>
    @endif
    <section id="cover">
        <div class="content" data-cover-style="{{ $data->cover->name->style }}">
            <div class="cover-assets">
                <img src="{{ url('storage/assets/top-orn-01-lg.png') }}" class="top-asset animate__animated animate__fadeInDown" alt="decoration">
                <img src="{{ url('storage/assets/top-orn-02-lg.png') }}" class="left-bottom-asset animate__animated animate__fadeInUp" alt="decoration">
                <img src="{{ url('storage/assets/top-orn-03-lg.png') }}" class="right-bottom-asset animate__animated animate__fadeInRight" alt="decoration">
            </div>
            <div class="cover-fill">
                <h1 class="animate__animated animate__fadeInDown" @style('font-size:'.$data->cover->name->size.'px')>
                    <span data-cover="name-male">{{ $data->cover->name->male }}</span>
                    <b>&amp;</b>
                    <span data-cover="name-female">{{ $data->cover->name->female }}</span>
                </h1>
                <p class="animate__animated animate__fadeInUp">{{ $data->cover->content }}</p>
                @if ($other['guest']!=null)
                <div class="cover-guest">
                    <sub>Kepada Yth./saudara/i/bapak/ibu:</sub>
                    <span>{{ $other['guest']['name'] }}</span>
                    <b>Di</b>
                    <span>{{ $other['guest']['location'] }}</span>
                </div>
                @endif
                <button type="button" class="cover-open animate__animated animate__rubberBand">{{ $data->cover->button }}</button>
            </div>
        </div>
    </section>
    <section id="banner">
        <div class="content">
            <div class="banner-assets">
                <img src="{{ url('storage/assets/orn-01.png') }}" class="left-top-asset animate__animated" alt="decoration">
                <img src="{{ url('storage/assets/orn-02.png') }}" class="left-bottom-asset animate__animated" alt="decoration">
                <img src="{{ url('storage/assets/orn-03.png') }}" class="bottom-asset animate__animated" alt="decoration">
                <img src="{{ url('storage/assets/orn-04.png') }}" class="right-top-asset animate__animated" alt="decoration">
                <img src="{{ url('storage/assets/orn-05.png') }}" class="right-bottom-asset animate__animated" alt="decoration">
            </div>
            <div class="inner">
                <div class="banner-fill animate__animated">
                    <p data-banner="text-sup">{{ $data->cover->description->top }}</p>
                    @if ($data->cover->description->image->method=='storage')
                    <img src="{{ url('storage/sm/'.$data->cover->description->image->image) }}" alt="">
                    @elseif ($data->cover->description->image->method=='avatar')
                    <img src="{{ url('storage/avatar/'.$data->cover->description->image->image) }}" alt="">
                    @endif
                    <p data-banner="text-sub">{{ $data->cover->description->bottom }}</p>
                </div>
                <div class="cover-fill animate__animated">
                    <h1>
                        <span data-cover="name-male">{{ $data->cover->name->male }}</span>
                        <b>&amp;</b>
                        <span data-cover="name-female">{{ $data->cover->name->female }}</span>
                    </h1>
                    <h2>{{ Carbon::parse($data->detail->calendar->date)->formatLocalized('%A, %d %B %Y') }}</h2>
                </div>
            </div>
        </div>
    </section>
    <section id="profile">
        <div class="content" data-profile-style="default">
            <div class="profile-fill" data-profile="male">
                <figure data-aos="fade-up">
                    <div class="inner-figure">
                        <img src="{{ url('storage/avatar/'.$data->profile->photo->male->image) }}" class="avatar" alt="">
                    </div>
                    <img src="{{ url('storage/frame/'.$data->profile->photo->male->frame) }}" class="frame" alt="">
                </figure>
                <h2 data-aos="fade-up">{{ $data->profile->name->male }}</h2>
                @if ($data->profile->parent->show===true)
                <div class="profile-parent" data-aos="fade-up">
                    <span>Anak ke-{{ $data->profile->parent->male->childhood }} dari</span>
                    <span>Bapak <b>{{ $data->profile->parent->male->father }}</b> &amp; Ibu <b>{{ $data->profile->parent->male->mother }}</b></span>
                </div>
                @endif
                @if ($data->profile->instagram->show===true)
                <div class="profile-instagram" data-aos="fade-up">
                    <a href="#">
                        <img src="{{ url('images/Instagram-Glyph-Color-Logo.wine.svg') }}" alt="">
                        &commat;{{ $data->profile->instagram->male }}
                    </a>
                </div>
                @endif
            </div>
            <div class="profile-and" data-aos="flip-up">&amp;</div>
            <div class="profile-fill animate__animated animate__fadeInDown" data-profile="male">
                <figure data-aos="fade-up">
                    <div class="inner-figure">
                        <img src="{{ url('storage/avatar/'.$data->profile->photo->female->image) }}" class="avatar" alt="">
                    </div>
                    <img src="{{ url('storage/frame/'.$data->profile->photo->female->frame) }}" class="frame" alt="">
                </figure>
                <h2 data-aos="fade-up">{{ $data->profile->name->female }}</h2>
                @if ($data->profile->parent->show===true)
                <div class="profile-parent" data-aos="fade-up">
                    <span>Anak ke-{{ $data->profile->parent->female->childhood }} dari</span>
                    <span>Bapak <b>{{ $data->profile->parent->female->father }}</b> &amp; Ibu <b>{{ $data->profile->parent->female->mother }}</b></span>
                </div>
                @endif
                @if ($data->profile->instagram->show===true)
                <div class="profile-instagram" data-aos="fade-up">
                    <a href="#">
                        <img src="{{ url('images/Instagram-Glyph-Color-Logo.wine.svg') }}" alt="">
                        &commat;{{ $data->profile->instagram->female }}
                    </a>
                </div>
                @endif
            </div>
        </div>
    </section>
    <section id="quote">
        <div class="content">
            <div class="quote-fill" data-aos="fade">
                <figure class="quote-decoration">
                    <img src="{{ url('storage/decoration/—Pngtree—romantic romantic flower flower flower_3930362.png') }}" alt="">
                </figure>
                <blockquote>{{ $data->quote->content }}</blockquote>
            </div>
        </div>
    </section>
    <section id="detail">
        <div class="content">
            <div class="countdown-fill" data-aos="fade-up">
                <h4>SAVE THE DATE</h4>
                @if ($data->detail->countdown->show===true)
                <div id="countdown" class="countdown" data-time="{{ date('m/d/Y', strtotime($data->detail->calendar->date)) }}">
                    <ul>
                        <li>
                            <b id="days">0</b>
                            <span>Days</span>
                        </li>
                        <li>
                            <b id="hours">0</b>
                            <span>Hours</span>
                        </li>
                        <li>
                            <b id="minutes">0</b>
                            <span>Minutes</span>
                        </li>
                        <li>
                            <b id="seconds">0</b>
                            <span>Seconds</span>
                        </li>
                    </ul>
                </div>
                @endif
            </div>
            <div class="date-fill" data-aos="fade-up">
                <span>
                    {{ Carbon::parse($data->detail->calendar->date)->formatLocalized('%A,') }}
                    {{ Carbon::parse($data->detail->calendar->date)->formatLocalized('%d %B %Y') }}
                </span>
                <span>{{ $data->detail->calendar->time." ".Str::upper($data->detail->calendar->timezone) }}</span>
                @if ($data->detail->calendar->save->show===true)
                <div>
                    <a href="http://www.google.com/calendar/event?action=TEMPLATE&dates={{ date('Ymd', strtotime($data->detail->calendar->date)) }}T010000Z%2F{{ date('Ymd', strtotime($data->detail->calendar->date." +1 days")) }}T010000Z&text=Wedding%20{{ $data->cover->name->male }}-{{ $data->cover->name->female }}&location={{ $data->detail->location->address }}&details=" target="_BLANK">{{ $data->detail->calendar->save->content }}</a>
                </div>
                @endif
            </div>
        </div>
    </section>
    @forelse ($other['event'] as $item)
    <section id="event">
        <div class="content" data-event-style="default">
            @php
                $item->prop = json_decode($item->content);
            @endphp
            <div class="event-fill" data-aos="flip-left">
                <img src="{{ url('storage/icon/01.png') }}" alt="">
                <h4 class="{{ (strlen($item->title)>30) ? 'small' : null }}">{{ $item->title }}</h4>
                <blockquote>{{ $item->prop->content }}</blockquote>
                <span>
                    <b>{{ date('H:i', strtotime($item->prop->time->start)) }}</b>
                    -
                    <b>{{ ($item->prop->time->done===true) ? 'selesai' : date('H:i', strtotime($item->prop->time->end)) }}</b>
                </span>
                @if ($item->prop->location->sync===false)
                <span>
                    <a href="{{ $item->prop->location->map }}" target="_BLANK">Lokasi</a>
                    <blockquote>{{ $item->prop->location->address }}</blockquote>
                </span>
                @else
                @endif
            </div>
        </div>
    </section>
    @empty
    <div class="empty"></div>
    @endforelse
    <section id="location">
        <div class="content">
            <div class="location-fill">
                <h4>Lokasi</h4>
                <address>{{ $data->detail->location->address }}</address>
                <a href="{{ $data->detail->location->map }}" target="_BLANK">Lihat di Google Maps</a>
            </div>
        </div>
    </section>
    @forelse ($other['story'] as $key => $item)
    <section id="story">
        <div class="content" data-story-style="default">
            <div class="timeline"> 
                <div class="timeline__event" data-aos="fade">
                    <div class="timeline__event__icon">
                        <img src="{{ url('storage/icon/love.png') }}">
                    </div>
                    <div class="timeline__event__date"></div>
                    <div class="timeline__event__content ">
                        <div class="timeline__event__image">
                            <img src="{{ url('storage/'.$item->file) }}" alt="{{ $item->title }}">
                        </div>
                        <div class="timeline__event__description">
                            <div class="timeline__event__title">{{ $item->title }}</div>
                            <p>{{ $item->content }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @empty
    <div class="empty"></div>
    @endforelse
    <section id="gallery">
        <div class="content" data-gallery-style="default">
            @if ($other['video']!=null)
            <div class="video-fill" data-aos="fade-up">
                <h4>{{ $other['video']->title }}</h4>
                <blockquote>{{ $other['video']->prop->content }}</blockquote>
                <iframe width="560" height="315" src="https://www.youtube.com/embed/{{ $other['video']->prop->url }}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
            </div>
            @endif
            @if ($other['photo']!=null)
            <div class="photo-fill" data-aos="fade-up">
                <h4>{{ $other['photo']->title }}</h4>
                <blockquote>{{ $other['photo']->prop->content }}</blockquote>
                <div>
                    @foreach ($other['photo']->prop->file as $key => $file)
                    <a>
                        <img src="{{ url('storage/'.$file) }}" alt="{{ $key }}">
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </section>
    <section id="wishes">
        <div class="content">
            <div class="present-fill">
                <a href="#present-field" rel="modal:open">Konfirmasi kehadiran</a>
            </div>
            @if ($data->wishes->public===true)
            <div class="wishes-fill" data-aos="fade">
                <h4>{{ $data->wishes->title }}</h4>
                <blockquote>{{ $data->wishes->content }}</blockquote>
                <form action="{{ route('invitation.wish', request()->slug) }}" class="sender" method="post">
                    <div>
                        @csrf
                        <label for="wishper-message">Harapan/Pesan/Ucapan <var dir="message"></var></label>
                        <textarea name="message" id="wishper-message"></textarea>
                    </div>
                    <div>
                        <label for="wishper-name">Nama <var dir="name"></var></label>
                        <input type="text" name="name" id="wishper-name">
                        <label for="wishper-phone">No. Whatsapp <var dir="phone"></var></label>
                        <input type="text" name="phone" id="wishper-phone">
                        <button type="submit">Sampaikan</button>
                    </div>
                </form>
            </div>
            @endif
        </div>
    </section>
    @if ($data->detail->additional->show===true)
    <section id="closing">
        <div class="content">
            <div class="closing-fill">
                <blockquote>{{ $data->detail->additional->closing }}</blockquote>
            </div>
            <div class="guest-fill">
                <h4>Turut mengundang</h4>
                <ul>
                    @foreach ($data->detail->additional->special as $guest)
                    <li>{{ $guest }}</li>
                    @endforeach
                </ul>
            </div>
            <div class="live-fill">
                <blockquote>{{ $data->additional->live->content }}</blockquote>
                <div class="live-app">
                    <img src="{{ url('images/icons/live/'.$data->additional->live->app) }}.png" alt="{{ $data->additional->live->app }}">
                    <a href="{{ $data->additional->live->link }}" target="_BLANK">Ikuti livestream</a>
                </div>
            </div>
        </div>
    </section>
    @endif
    <section id="protocol">
        @if ($data->additional->protocol->show===true)
        <div class="content" data-protocol-style="default">
            <div class="protocol-label" data-aos="fade-up">
                <h5>{{ $data->additional->protocol->content }}</h5>
                <h6>{{ $data->additional->protocol->title }}</h6>
            </div>
            <div class="protocol-fill">
                <ul>
                    @foreach (json_decode($other['protocol']->content) as $key => $item)    
                    <li data-aos="fade-up">
                        <img src="{{ url('storage/protocol/'.$item) }}" alt="{{ $key }}">
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif
        <div class="cover-fill" data-aos="fade-up">
            <h1>
                <span data-cover="name-male">{{ $data->cover->name->male }}</span>
                <b>&amp;</b>
                <span data-cover="name-female">{{ $data->cover->name->female }}</span>
            </h1>
            <h2>{{ Carbon::parse($data->detail->calendar->date)->formatLocalized('%d %B %Y') }}</h2>
        </div>
    </section>
    <footer>
        <small>Made with</small>
        <b>Creasik Digital</b>
    </footer>
    @if ($data->gift->show===true)
    <div id="gift-field" class="modal">
        <h3>{{ $data->gift->title }}</h3>
        <p>{{ $data->gift->content }}</p>
        <div class="bank">
            <div>
                <img src="" alt="{{ $data->gift->bank->option }}">
            </div>
            <div>
                <p>Transfer ke <b>{{ $data->gift->bank->option }}</b></p>
                <span>a.n <b>{{ $data->gift->bank->name }}</b></span>
                <span>Nomor rekening <b>{{ $data->gift->bank->code }}</b></span>
            </div>
        </div>
    </div>
    @endif
    <div id="present-field" class="modal">
		<h3>{{ $data->rsvp->title }}</h3>
        <p>{{ $data->rsvp->content }}</p>
        <form action="{{ route('invitation.present', request()->slug) }}" class="sender" method="post">
            @csrf
            <div>
                <input type="text" name="name" id="present-name" placeholder="Nama lengkap kamu">
                <var dir="name"></var>
            </div>
            <div class="present-select">
                <input type="radio" name="option" id="present-yes" class="present-option" value="yes">
                <label for="present-yes">
                    <span>{{ $data->rsvp->yes->option }}</span>
                </label>
                <input type="radio" name="option" id="present-no" class="present-option" value="no">
                <label for="present-no">
                    <span>{{ $data->rsvp->no->option }}</span>
                </label>
            </div>
            <var dir="option"></var>
            <div class="present-result" @style('display:none')>
                <label for="present-amount">
                    <var dir="amount">Jumlah tamu</var>
                </label>
                <input type="number" name="amount" id="present-amount" min="1" max="10" value="1">
            </div>
            <button type="submit">Kirim</button>
        </form>
	</div>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.0.0/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 3000
        });
        var cover = $("#cover"),
            cover_asset_top = cover.find('.top-asset'),
            cover_asset_left_bottom = cover.find('.left-bottom-asset'),
            cover_asset_right_bottom = cover.find('.right-bottom-asset');
        var banner = $("#banner"),
            banner_asset_left_top = banner.find('.left-top-asset'),
            banner_asset_left_bottom = banner.find('.left-bottom-asset'),
            banner_asset_bottom = banner.find('.bottom-asset'),
            banner_asset_right_top = banner.find('.right-top-asset'),
            banner_asset_right_bottom = banner.find('.right-bottom-asset'),
            banner_banner_fill = banner.find('.banner-fill'),
            banner_cover_fill = banner.find('.cover-fill');
        $(".cover-open").on('click', function(e) {
            e.preventDefault();
            $("html, body").animate({
                scrollTop: banner.offset().top
            }, 300);
            cover_asset_top.removeClass('animate__fadeInDown').addClass('animate__fadeOutUp');
            cover_asset_left_bottom.removeClass('animate__fadeInUp').addClass('animate__fadeOutDown');
            cover_asset_right_bottom.removeClass('animate__fadeInRight').addClass('animate__fadeOutRight');
            setTimeout(() => {
                $("body").addClass('opened');
                banner_asset_left_top.addClass('animate__fadeInLeft');
                banner_asset_left_bottom.addClass('animate__fadeInLeft');
                banner_asset_bottom.addClass('animate__fadeInUp');
                banner_asset_right_top.addClass('animate__fadeInDown');
                banner_asset_right_bottom.addClass('animate__fadeInRight');
                banner_banner_fill.addClass('animate__fadeInUp');
                banner_cover_fill.addClass('animate__zoomInUp');
            }, 1200);
        });
        $(".present-option").on('change', function(e) {
            if (e.target.value=='yes') {
                $(".present-result").css('display', 'block');
            } else if (e.target.value=='no') {
                $(".present-result").css('display', 'none');
            }
        });
        $(".sender").on('submit', function(e) {
            e.preventDefault();
            let action = $(this).attr('action'),
                submit = $(this).find('button[type=submit]');
            $.ajax({
                type: 'post',
                url : action,
                dataType: 'json',
                data: $(this).serialize(),
                error: function(q,w,e) {
                    submit.text('Coba Lagi');
                    submit.prop('disabled', false);
                    $.each(q.responseJSON.errors, function(index, value) {
                        $(`var[dir=${index}]`).after(`<small role="alert">${value}</small>`);
                    });
                },
                beforeSend: function() {
                    $(".feedbacker").remove();
                    $("small[role=alert]").remove();
                    submit.prop('disabled', true);
                    submit.text('Memeriksa data...');
                },
                success: function(response) {
                    submit.prop('disabled', false);
                    submit.text('Terkirim');
                    $(".sender")[0].reset();
                    submit.parent().append(`<div class="feedbacker">${response.message}</div>`);
                }
            });
        });
    </script>
    @if ($data->music->show===true)
	<script>
        $(".cover-open").on('click', function(e) {
            document.getElementById("music-source").play();
        });
		var myAudio = document.getElementById("music-source"),
			isPlaying = false;
			myAudioClass = document.getElementById("music").classList;
		function togglePlay() {
			isPlaying ? myAudio.pause() : myAudio.play();
			myAudioClass.toggle('paused');
		};
		myAudio.onplaying = function() {
			isPlaying = true;
		};
		myAudio.onpause = function() {
			isPlaying = false;
		};
	</script>
	@endif
</body>
</html>