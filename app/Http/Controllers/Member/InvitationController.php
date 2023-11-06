<?php

namespace App\Http\Controllers\Member;

use App\Models\Strbox;
use App\Models\Package;
use App\Models\Feedback;
use App\Models\Template;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\AccountInvoice;
use App\Models\TemplateAssets;
use App\Models\InvitationEvent;
use App\Models\InvitationGuest;
use App\Models\InvitationStory;
use App\Models\InvitationGallery;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class InvitationController extends Controller
{
	private $menu = [
		'design' => [
			'id'	=> 'design',
			'icon'	=> 'social-media_2065064.png',
			'title'	=> 'ubah desain',
			'notif'	=> 0,
			'url'	=> 'menu.design'
		],
		'cover' => [
			'id'	=> 'cover',
			'icon'	=> 'social-media_2065064.png',
			'title'	=> 'sampul undangan',
			'notif'	=> 0,
			'url'	=> 'menu.cover',
		],
		'profile' => [
			'id'	=> 'profile',
			'icon'	=> 'social-media_2065064.png',
			'title'	=> 'profil pasangan',
			'notif'	=> 0,
			'url'	=> 'menu.profile',
		],
		'detail' => [
			'id'	=> 'detail',
			'icon'	=> 'social-media_2065064.png',
			'title'	=> 'detail undangan',
			'notif'	=> 0,
			'url'	=> 'menu.detail',
		],
		'quote' => [
			'id'	=> 'quote',
			'icon'	=> 'social-media_2065064.png',
			'title'	=> 'quote',
			'notif'	=> 0,
			'url'	=> 'menu.quote',
		],
		'event' => [
			'id'	=> 'event',
			'icon'	=> 'social-media_2065064.png',
			'title'	=> 'sesi acara',
			'notif'	=> 0,
			'url'	=> 'menu.event',
		],
		'story' => [
			'id'	=> 'story',
			'icon'	=> 'social-media_2065064.png',
			'title'	=> 'kisah cinta',
			'notif'	=> 0,
			'url'	=> 'menu.story',
		],
		'gallery' => [
			'id'	=> 'gallery',
			'icon'	=> 'social-media_2065064.png',
			'title'	=> 'galeri',
			'notif'	=> 0,
			'url'	=> 'menu.gallery',
		],
		'music' => [
			'id'	=> 'music',
			'icon'	=> 'social-media_2065064.png',
			'title'	=> 'musik',
			'notif'	=> 0,
			'url'	=> 'menu.music',
		],
		'rsvp' => [
			'id'	=> 'rsvp',
			'icon'	=> 'social-media_2065064.png',
			'title'	=> 'rsvp',
			'notif'	=> 0,
			'url'	=> 'menu.rsvp',
		],
		'additional-info' => [
			'id'	=> 'additional-info',
			'icon'	=> 'social-media_2065064.png',
			'title'	=> 'info tambahan',
			'notif'	=> 0,
			'url'	=> 'menu.additional',
		],
		'gift' => [
			'id'	=> 'gift',
			'icon'	=> 'social-media_2065064.png',
			'title'	=> 'Amlop Digital',
			'notif'	=> 0,
			'url'	=> 'menu.gift',
		],
		'wishes' => [
			'id'	=> 'wishes',
			'icon'	=> 'social-media_2065064.png',
			'title'	=> 'Ucapan',
			'notif'	=> 0,
			'url'	=> 'menu.wishes',
		],
		'presenting' => [
			'id'	=> 'presenting',
			'icon'	=> 'social-media_2065064.png',
			'title'	=> 'Konfirmasi Kehadiran',
			'notif'	=> 0,
			'url'	=> 'menu.presenting',
		],
		'share' => [
			'id'	=> 'share',
			'icon'	=> 'social-media_2065064.png',
			'title'	=> 'Bagikan',
			'notif'	=> 0,
			'url'	=> 'menu.share',
		],
		'reservation' => [
			'id'	=> 'reservation',
			'icon'	=> 'receptionist-icon.png',
			'title'	=> 'penerima tamu',
			'notif'	=> 0,
			'url'	=> 'menu.reservation',
		],
		'table-management' => [
			'id'	=> 'table-management',
			'icon'	=> 'table-icon.svg',
			'title'	=> 'kelola meja',
			'notif'	=> 0,
			'url'	=> 'menu.management',
		],
		'souvenir' => [
			'id'	=> 'souvenir',
			'icon'	=> 'souvenir-icon.png',
			'title'	=> 'souvenir',
			'notif'	=> 0,
			'url'	=> 'menu.souvenir',
		],
		'e-invitation' => [
			'id'	=> 'e-invitation',
			'icon'	=> 'social-media_2065064.png',
			'title'	=> 'e-invitation',
			'notif'	=> 0,
			'url'	=> 'menu.einvitation',
		]
	];

	public function __construct(private $activation = null, private $active = null)
	{
		$this->middleware(function ($request, $next) {
			$this->activation = AccountInvoice::select('date', 'package_id')->with('pack')->current()->first();
			if ($this->activation!=null) :
				$this->active = json_decode($this->activation->pack->content)->active;
				if (isexpired($this->activation->date, $this->active)===false) :
					return $next($request);
				elseif (isexpired($this->activation->date, $this->active)===true) :
					Redirect::to('dashboard/upgrade')->send();
				endif;
			else :
				Redirect::to('dashboard/account-transaction')->send();
			endif;
        });
	}

	public function main(): Response|RedirectResponse
	{
		$menu = $this->menu;
		$conditional_menu = json_decode($this->activation->pack->content, true);
		$bagpack = [
			'name' => Auth::user()->inv->title,
			'date' => json_decode(Auth::user()->inv->preset)->detail->calendar,// Preset
			'subdomain' => Auth::user()->inv->slug,
			'template' => Auth::user()->inv->temp->grade
		];
		$bagpack['name'] = implode(' & ', json_decode($bagpack['name'], true));
		$data = json_decode(json_encode($bagpack));
		$conditional = [
			'e-invitation' => $conditional_menu['e-invitation'],
			'story' => $conditional_menu['story'],
			'event' => $conditional_menu['event']
		];

		return response()->view('member.dashboard', compact('menu', 'data', 'conditional'));
	}

	public function m_design(): Response
	{
		$menu = $this->menu['design'];
		$access = AccountInvoice::select('package_id')->with('pack')->current()->first();
		$bagpack = [
			'template' => [
				'basic' => Template::select('id', 'title', 'slug', 'file')->where('grade', 'basic')->publish()->get(),
				'premium' => Template::select('id', 'title', 'slug', 'file')->where('grade', 'premium')->publish()->get(),
				'exclusive' => Template::select('id', 'title', 'slug', 'file')->where('grade', 'exclusive')->publish()->get()
			],
			'limit' => json_decode($access->pack->content, true)['template'],
			'font' => TemplateAssets::select('title', 'content')->where('type', 'font')->publish()->get(),
			'preset' => json_decode(Auth::user()->inv->preset)->design,// Preset
		];
		$data = json_decode(json_encode($bagpack));

		return response()->view('member.m-design', compact('menu', 'data'));
	}

	public function m_cover(): Response
	{
		$menu = $this->menu['cover'];
		$bagpack = [
			'avatar' => [
				'none' => TemplateAssets::select('title', 'content')->where('type', 'avatar')->get(),
			],
			'style' => [
				'default' => 'Bawaan',
				'stack' => 'Bertumpuk',
			],
			'preset' => json_decode(Auth::user()->inv->preset)->cover,// Preset
		];
		$data = json_decode(json_encode($bagpack));

		return response()->view('member.m-cover', compact('menu', 'data'));
	}

	public function m_profile(): Response
	{
		$menu = $this->menu['profile'];
		$bagpack = [
			'avatar' => [
				'male' => TemplateAssets::select('title', 'content')->where('type', 'avatar male')->get(),
				'female' => TemplateAssets::select('title', 'content')->where('type', 'avatar female')->get(),
			],
			'frame' => TemplateAssets::select('title', 'content')->where('type', 'frame')->latest()->get(),
			'preset' => json_decode(Auth::user()->inv->preset)->profile,// Preset
		];
		$data = json_decode(json_encode($bagpack));

		return response()->view('member.m-profile', compact('menu', 'data'));
	}

	public function m_detail(): Response
	{
		$menu = $this->menu['detail'];
		$bagpack = [
			'timezone'  => ['wib' => 'WIB', 'wita' => 'WITA', 'wit' => 'WIT', 'none' => 'Kosongkan'],
			'style'  => ['deafult' => 'Bawaan', 'stack' => 'Bertumpuk'],
			'preset' => json_decode(Auth::user()->inv->preset)->detail,// Preset
		];
		$data = json_decode(json_encode($bagpack));

		return response()->view('member.m-detail', compact('menu', 'data'));
	}

	public function m_quote(): Response
	{
		$menu = $this->menu['quote'];
		$bagpack = [
			'quote' => TemplateAssets::select('title', 'content')->where('type', 'quote')->latest()->get(),
			'decoration' => TemplateAssets::select('title', 'content')->where('type', 'decoration')->latest()->get(),
			'preset' => json_decode(Auth::user()->inv->preset)->quote,// Preset
		];
		$data = json_decode(json_encode($bagpack));

		return response()->view('member.m-quote', compact('menu', 'data'));
	}

	public function m_event(): Response|RedirectResponse
	{
		if (json_decode($this->activation->pack->content, true)['event']) :
			$menu = $this->menu['event'];
			$access = AccountInvoice::select('package_id')->with('pack')->current()->first();
			$bagpack = [
				'style' => ['deafult' => 'Bawaan', 'none' => 'Sembunyikan'],
				'event' => InvitationEvent::select('id', 'title', 'content', 'publish')->where('invitation_id', Auth::user()->inv->id)->get(),
				'limitEvent' => (json_decode($access->pack->content)->{'event-count'}=='unlimited') ? "∞" : json_decode($access->pack->content)->{'event-count'},
			];
			$data = json_decode(json_encode($bagpack));

			return response()->view('member.m-event', compact('menu', 'data'));
		else :
			return redirect()->route('packages');
		endif;
	}

	public function m_event_add(Request $request): JsonResponse
	{
		$access = AccountInvoice::select('package_id')->with('pack')->current()->first();
		$column = [
			'event_title' => 'required',
			'event_content' => 'required',
			'event_time_start' => 'required'
		];
		if ($request->event_time_done!='on') :
			$column['event_time_end'] = 'required';
		endif;
		if ($request->event_location_sync!='on') :
			$column['event_location_address'] = 'required';
			$column['event_location_map'] = 'required';
		endif;
		$this->validate($request, $column);
		$preset = [
			'title' => $request->event_title,
			'slug' => clean_str($request->event_title),
			'content' => json_encode([
				'primary' => ($request->event_primary=='on') ? true : false,
				'content' => $request->event_content,
				'time' => [
					'start' => $request->event_time_start,
					'end' => $request->event_time_end,
					'done' => ($request->event_time_done=='on') ? true : false
				],
				'location' => [
					'address' => $request->event_location_address,
					'map' => $request->event_location_map,
					'sync' => ($request->event_location_sync=='on') ? true : false
				]
			]),
			'publish' => 'publish',
			'invitation_id' => Auth::user()->inv->id,
			'ip_addr' => $_SERVER['REMOTE_ADDR'],
			'user_id' => Auth::user()->id
		];
		$check_event = InvitationEvent::where('invitation_id', Auth::user()->inv->id)->count();
		$limit_event = (json_decode($access->pack->content)->{'event-count'}=='unlimited') ? 500 : json_decode($access->pack->content)->{'event-count'};
		if ($check_event<$limit_event) :
			InvitationEvent::create($preset);
			$response = ['toast'=>['icon'=>'success', 'title'=>'Disimpan!', 'text'=>'Acara '.$request->event_title.' telah ditambahkan.'], 'page' => 'reload'];
		else :
			$response = ['toast'=>['icon'=>'warning', 'title'=>'Kuota penuh', 'text'=>'Acara '.$request->event_title.' tidak bisa ditambahkan.'], 'page' => 'idle'];
		endif;
		
		return response()->json($response);
	}

	public function m_event_edit(Request $request, int $id): JsonResponse
	{
		$column = [
			'event_title' => 'required',
			'event_content' => 'required',
			'event_time_start' => 'required'
		];
		if ($request->event_time_done!='on') :
			$column['event_time_end'] = 'required';
		endif;
		if ($request->event_location_sync!='on') :
			$column['event_location_address'] = 'required';
			$column['event_location_map'] = 'required';
		endif;
		$this->validate($request, $column);
		$preset = [
			'title' => $request->event_title,
			'slug' => clean_str($request->event_title),
			'content' => json_encode([
				'primary' => ($request->event_primary=='on') ? true : false,
				'content' => $request->event_content,
				'time' => [
					'start' => $request->event_time_start,
					'end' => $request->event_time_end,
					'done' => ($request->event_time_done=='on') ? true : false
				],
				'location' => [
					'address' => $request->event_location_address,
					'map' => $request->event_location_map,
					'sync' => ($request->event_location_sync=='on') ? true : false
				]
			]),
			'ip_addr' => $_SERVER['REMOTE_ADDR'],
			'user_id' => Auth::user()->id
		];
		InvitationEvent::whereId($id)->update($preset);
		$response = ['toast'=>['icon'=>'success', 'title'=>'Disimpan!', 'text'=>'Perubahan telah disimpan.'], 'page' => 'idle'];

		return response()->json($response);
	}

	public function m_event_delete(int $id): JsonResponse
	{
		InvitationEvent::whereId($id)->delete();

		return response()->json(['deleted']);
	}

	public function m_event_show(int $id): JsonResponse
	{
		$event = InvitationEvent::select('id', 'title', 'content')->whereId($id)->where('invitation_id', Auth::user()->inv->id)->first();
		$event->content = json_decode($event->content);
		$event->url = route('menu.event-delete', $event->id);

		return response()->json($event);
	}

	public function m_story(): Response|RedirectResponse
	{
		if (json_decode($this->activation->pack->content, true)['story']) :
			$menu = $this->menu['story'];
			$access = AccountInvoice::select('package_id')->with('pack')->current()->first();
			$bagpack = [
				'style'  => ['deafult' => 'Bawaan', 'none' => 'Sembunyikan'],
				'story' => InvitationStory::select('id', 'title', 'content', 'publish')->where('invitation_id', Auth::user()->inv->id)->get(),
				'limitStory' => (json_decode($access->pack->content)->{'story-count'}=='unlimited') ? "∞" : json_decode($access->pack->content)->{'story-count'},
			];
			$data = json_decode(json_encode($bagpack));

			return response()->view('member.m-story', compact('menu', 'data'));
		else :
			return redirect()->route('packages');
		endif;
	}

	public function m_story_add(Request $request): JsonResponse
	{
		$access = AccountInvoice::select('package_id')->with('pack')->current()->first();
		$column = [
			'story_title' => 'required',
			'story_content' => 'required'
		];
		$this->validate($request, $column);
		$preset = [
			'title' => $request->story_title,
			'slug' => clean_str($request->story_title),
			'content' => $request->story_content,
			'file' => $request->story_file ?? NULL,
			'publish' => 'publish',
			'invitation_id' => Auth::user()->inv->id,
			'ip_addr' => $_SERVER['REMOTE_ADDR'],
			'user_id' => Auth::user()->id
		];
		
		$check_story = InvitationStory::where('invitation_id', Auth::user()->inv->id)->count();
		$limit_story = (json_decode($access->pack->content)->{'story-count'}=='unlimited') ? 500 : json_decode($access->pack->content)->{'story-count'};
		if ($check_story<$limit_story) :
			InvitationStory::create($preset);
			$response = ['toast'=>['icon'=>'success', 'title'=>'Disimpan!', 'text'=>'Cerita '.$request->story_title.' telah ditambahkan.'], 'page' => 'reload'];
		else :
			$response = ['toast'=>['icon'=>'warning', 'title'=>'Kuota penuh', 'text'=>'Cerita '.$request->story_title.' tidak bisa ditambahkan.'], 'page' => 'idle'];
		endif;
		
		return response()->json($response);
	}

	public function m_story_edit(Request $request, int $id): JsonResponse
	{
		$column = [
			'story_title' => 'required',
			'story_content' => 'required'
		];
		$this->validate($request, $column);
		$preset = [
			'title' => $request->story_title,
			'slug' => clean_str($request->story_title),
			'content' => $request->story_content,
			'file' => $request->story_file ?? NULL,
			'ip_addr' => $_SERVER['REMOTE_ADDR'],
			'user_id' => Auth::user()->id
		];
		InvitationStory::whereId($id)->update($preset);
		$response = ['toast'=>['icon'=>'success', 'title'=>'Disimpan!', 'text'=>'Perubahan telah disimpan.'], 'page' => 'idle'];

		return response()->json($response);
	}

	public function m_story_delete(int $id): JsonResponse
	{
		InvitationStory::whereId($id)->delete();

		return response()->json(['hapus']);
	}

	public function m_story_show(int $id): JsonResponse
	{
		$story = InvitationStory::select('id', 'title', 'content', 'file')->whereId($id)->where('invitation_id', Auth::user()->inv->id)->first();
		$story->image = url('storage/sm/', $story->file);
		$story->url = route('menu.story-delete', $story->id);

		return response()->json($story);
	}

	public function m_gallery(): Response
	{
		$menu = $this->menu['gallery'];
		$access = AccountInvoice::select('package_id')->with('pack')->current()->first();
		$bagpack = [
			'style'  => ['default' => 'Bawaan', 'none' => 'Sembunyikan'],
			'limitPhoto' => (json_decode($access->pack->content)->{'gallery-photo'}=='unlimited') ? "∞" : json_decode($access->pack->content)->{'gallery-photo'},
			'limitVideo' => (json_decode($access->pack->content)->{'gallery-video'}=='unlimited') ? "∞" : json_decode($access->pack->content)->{'gallery-video'},
			'photo' => InvitationGallery::select('title', 'content')->where('type', 'photo')->where('invitation_id', Auth::user()->inv->id)->first(),
			'video' => InvitationGallery::select('title', 'content')->where('type', 'video')->where('invitation_id', Auth::user()->inv->id)->first(),
		];
		$data = json_decode(json_encode($bagpack));

		return response()->view('member.m-gallery', compact('menu', 'data'));
	}

	public function m_gallery_add(Request $request): JsonResponse
	{
		$this->validate($request, [
			'gallery_title' => 'required',
			'gallery_content' => 'required',
			'gallery_style' => 'required',
			'video_title' => 'required',
			'video_content' => 'required',
			'video_url' => 'required',
		]);
		$preset = [
			'photo' => [
				'title' => $request->input('gallery_title'),
				'content' => json_encode([
					'content' => $request->input('gallery_content'),
					'style' => $request->input('gallery_style'),
					'file' => $request->input('gallery_file'),
					'show' => ($request->input('gallery_show')=='on') ? false : true,
				]),
			],
			'video' => [
				'title' => $request->input('video_title'),
				'content' => json_encode([
					'content' => $request->input('video_content'),
					'url' => check_yt($request->input('video_url')),
				])
			]
		];
		$photo = InvitationGallery::where('type', 'photo')->where('invitation_id', Auth::user()->inv->id)->count();
		if ($photo>=1) :
			InvitationGallery::where('type', 'photo')->where('invitation_id', Auth::user()->inv->id)->update($preset['photo']);
		elseif ($photo==0) :
			$preset['photo']['type'] = 'photo';
			$preset['photo']['invitation_id'] = Auth::user()->inv->id;
			$preset['photo']['ip_addr'] = $_SERVER['REMOTE_ADDR'];
			$preset['photo']['user_id'] = Auth::user()->id;
			InvitationGallery::create($preset['photo']);
		endif;
		$video = InvitationGallery::where('type', 'video')->where('invitation_id', Auth::user()->inv->id)->count();
		if ($video>=1) :
			InvitationGallery::where('type', 'video')->where('invitation_id', Auth::user()->inv->id)->update($preset['video']);
		elseif ($photo==0) :
			$preset['video']['type'] = 'video';
			$preset['video']['invitation_id'] = Auth::user()->inv->id;
			$preset['video']['ip_addr'] = $_SERVER['REMOTE_ADDR'];
			$preset['video']['user_id'] = Auth::user()->id;
			InvitationGallery::create($preset['video']);
		endif;
		$response = ['toast'=>['icon'=>'success', 'title'=>'Disimpan!', 'text'=>'Perubahan telah disimpan.'], 'page' => 'idle'];

		return response()->json($response);
	}
	
	public function m_music(): Response
	{
		$menu = $this->menu['music'];
		$access = AccountInvoice::select('package_id')->with('pack')->current()->first();
		$bagpack = [
			'music' => TemplateAssets::select('title', 'content')->where('type', 'music')->where('user_id', 1)->publish()->get(),
			'my_music' => TemplateAssets::select('title', 'content')->where('type', 'music')->where('user_id', Auth::user()->id)->publish()->first(),
			'custom' => json_decode($access->pack->content)->{'music'},
			'preset' => json_decode(Auth::user()->inv->preset)->music,// Preset
		];
		$data = json_decode(json_encode($bagpack));

		return response()->view('member.m-music', compact('menu', 'data'));
	}

	public function m_music_add(Request $request): JsonResponse
	{
		$this->validate($request, [
			'music_title' => 'required',
			'music_file' => 'required|mimes:mpeg,mp3',
		]);
		$preset = [
			'title' => $request->input('music_title'),
			'publish' => 'publish',
			'user_id' => Auth::user()->id,
			'ip_addr' => $_SERVER['REMOTE_ADDR']
		];
		if (!empty($request->music_file)) :
			$music_name = $request->file('music_file')->hashName();
			Storage::disk('public')->put('audio/'.$music_name, file_get_contents($request->file('music_file')));
			$preset['content'] = $music_name;
			$music = TemplateAssets::where('type', 'music')->where('user_id', Auth::user()->id)->publish();
			$count = $music->count();
			if ($count>=1) :
				$music = $music->first();
				if (Storage::disk('public')->exists('audio/'.$music->content)) :
					Storage::disk('public')->delete('audio/'.$music->content);
				endif;
				TemplateAssets::whereId($music->id)->update($preset);
			elseif ($count==0) :
				$preset['type'] = 'music';
				TemplateAssets::create($preset);
			endif;
		endif;
		
		return response()->json([]);
	}
	
	public function m_rsvp(): Response
	{
		$menu = $this->menu['rsvp'];
		$bagpack = [
			'preset' => json_decode(Auth::user()->inv->preset)->rsvp,// Preset
		];
		$data = json_decode(json_encode($bagpack));

		return response()->view('member.m-rsvp', compact('menu', 'data'));
	}
	
	public function m_additional(): Response
	{
		$menu = $this->menu['additional-info'];
		$access = AccountInvoice::select('package_id')->with('pack')->current()->first();
		$bagpack = [
			'protocol' => TemplateAssets::select('id', 'title', 'content')->where('type', 'protocol')->publish()->get(),
			'liveAccess' => json_decode($access->pack->content)->{'live-stream'},
			'preset' => json_decode(Auth::user()->inv->preset)->additional,// Preset
		];
		$data = json_decode(json_encode($bagpack));

		return response()->view('member.m-additional', compact('menu', 'data'));
	}
	
	public function m_einvitation(): Response|RedirectResponse
	{
		if (json_decode(Auth::user()->invoice[0]->pack->content)->{'e-invitation'}===true) :
			$menu = $this->menu['e-invitation'];
			$bagpack = [
				'preset' => json_decode(Auth::user()->inv->preset)->design,// Preset
				'date' => json_decode(Auth::user()->inv->preset)->detail->calendar->date,
			];
			$data = json_decode(json_encode($bagpack));

			return response()->view('member.m-einvitation', compact('menu', 'data'));
		elseif (Auth::user()->acc->guestbook==0) :
			return redirect()->route('packages');
		endif;
	}

	public function m_einvitation_edit(Request $request): JsonResponse|RedirectResponse
	{
		if (json_decode(Auth::user()->invoice[0]->pack->content)->{'e-invitation'}===true) :
			list($type, $data) = explode(';', $request->base64data);
			list(, $data)      = explode(',', $data);
			$data = base64_decode($data);
			$image_name = "meta_".Auth::user()->id.clean_str(implode('-', json_decode(Auth::user()->inv->title, true))).".webp";
			if (Storage::disk('public')->exists($image_name)) :
				Storage::disk('public')->delete($image_name);
			endif;
			Storage::disk('public')->put($image_name, $data);
			Invitation::find(Auth::user()->inv->id)->update(['file'=>$image_name]);

			return response()->json(['toast'=>['icon'=>'success', 'title'=>'Gambar telah dibuat', 'text'=>'Gambar baru telah disimpan.']]);
		elseif (Auth::user()->acc->guestbook==0) :
			return redirect()->route('packages');
		endif;
	}
	
	public function m_gift(): Response
	{
		$menu = $this->menu['gift'];
		$bagpack = [
			'gift' => Feedback::select('content', 'created_at')->where('type', 'gift')->where('invitation_id', Auth::user()->inv->id)->get(),
			'preset' => json_decode(Auth::user()->inv->preset)->gift,// Preset
		];
		$data = json_decode(json_encode($bagpack));

		return response()->view('member.m-gift', compact('menu', 'data'));
	}
	
	public function m_wishes(): Response
	{
		$menu = $this->menu['wishes'];
		$bagpack = [
			'wishes' => Feedback::select('content', 'created_at')->where('type', 'wishes')->where('invitation_id', Auth::user()->inv->id)->get(),
			'preset' => json_decode(Auth::user()->inv->preset)->wishes,// Preset
		];
		$data = json_decode(json_encode($bagpack));

		return response()->view('member.m-wishes', compact('menu', 'data'));
	}
	
	public function m_presenting(): Response
	{
		$menu = $this->menu['presenting'];
		$bagpack = [
			'present' => Feedback::select('content', 'created_at')->where('type', 'present')->where('invitation_id', Auth::user()->inv->id)->get()
		];
		$data = json_decode(json_encode($bagpack));

		return response()->view('member.m-presenting', compact('menu', 'data'));
	}
	
	public function m_share(): Response
	{
		$menu = $this->menu['share'];
		$bagpack = [
			'guest' => InvitationGuest::select('id', 'name', 'slug', 'type')->where('invitation_id', Auth::user()->inv->id)->get(),
		];
		$data = json_decode(json_encode($bagpack));

		return response()->view('member.m-share', compact('menu', 'data'));
	}

	public function m_share_add(Request $request): void
	{
		$this->validate($request, [
			'share_guest_type' => 'required',
			'share_guest_name' => 'required',
			'share_guest_location' => 'required'
		]);
		$column = [
			'type' => $request->share_guest_type,
			'name' => json_encode(['name'=>$request->share_guest_name, 'location'=>$request->share_guest_location]),
			'slug' => clean_str($request->share_guest_name).clean_str(substr($request->share_guest_location,0,3).strlen($request->share_guest_name)),
			'invitation_id' => Auth::user()->inv->id,
			'user_id' => Auth::user()->id,
			'ip_addr' => $_SERVER['REMOTE_ADDR']
		];
		InvitationGuest::create($column);
	}

	public function save_setting(Request $request, string $menu): JsonResponse
	{
		$recent_inv = Invitation::select('id', 'preset')->whereId(Auth::user()->inv->id)->firstOrFail();
		$save_inv_column = [];
		$column = [];
		$preset = json_decode($recent_inv->preset, true);
		if ($menu=='design') :
			// validation
			$column['design_template'] = 'required';
			$column['design_title_color'] = 'required';
			$column['design_content_color'] = 'required';
			$column['design_background'] = 'required';
			$column['design_button_background'] = 'required';
			$column['design_button_color'] = 'required';
			$column['design_title_font'] = 'required';
			$column['design_content_font'] = 'required';
			// new preset
			if (isitsame($request->input('design_template'), $preset['design']['template'])===false) :
				$access = AccountInvoice::select('package_id')->with('pack')->current()->first();
				$template = Template::select('grade')->whereId($request->input('design_template'))->first();
				$limit = json_decode($access->pack->content)->{'template'};
				if (in_array($template->grade, $limit)) :
					$preset['design']['template'] = $request->input('design_template');
					Invitation::whereId(Auth::user()->inv->id)->update(['template_id'=>$request->input('design_template')]);
				else :
					return response()->json(['toast'=>['icon'=>'error','title'=>'>_<','text'=>'Access denied!!']]);
				endif;
			endif;
			$preset['design']['title']['color'] = $request->input('design_title_color');
			$preset['design']['content']['color'] = $request->input('design_content_color');
			$preset['design']['background'] = $request->input('design_background');
			$preset['design']['button']['background'] = $request->input('design_button_background');
			$preset['design']['button']['color'] = $request->input('design_button_color');
			$preset['design']['title']['font'] = $request->input('design_title_font');
			$preset['design']['content']['font'] = $request->input('design_content_font');
		elseif ($menu=='cover') :
			// validation
			$column['cover_name_female'] = 'required';
			$column['cover_name_male'] = 'required';
			$column['cover_name_size'] = 'required';
			$column['cover_name_style'] = 'required';
			$column['cover_content'] = 'required';
			$column['cover_button'] = 'required';
			$column['cover_description_top'] = 'required';
			$column['cover_description_bottom'] = 'required';
			// $column['cover_description_image'] = 'required';
			// new preset
			$preset['cover']['name']['female'] = $request->input('cover_name_female');
			$preset['cover']['name']['male'] = $request->input('cover_name_male');
			$preset['cover']['name']['size'] = $request->input('cover_name_size');
			$preset['cover']['name']['style'] = $request->input('cover_name_style');
			$preset['cover']['content'] = $request->input('cover_content');
			$preset['cover']['button'] = $request->input('cover_button');
			$preset['cover']['description']['top'] = $request->input('cover_description_top');
			$preset['cover']['description']['bottom'] = $request->input('cover_description_bottom');
			// $preset['cover']['description']['image'] = $request->input('');
			if ($request->cover_description_image__method=='upload') :
				$column['cover_description_image'] = 'required|mimes:jpg,jpeg,png';
				// new preset
				if (!empty($request->cover_description_image)) :
					$image_name = $request->file('cover_description_image')->hashName();
					Storage::disk('public')->put($image_name, file_get_contents($request->file('cover_description_image')));
					image_reducer(file_get_contents($request->file('cover_description_image')), $image_name);
					$preset['cover']['description']['image']['method'] = 'storage';
					$preset['cover']['description']['image']['image'] = $image_name;
					// strbox
					Strbox::create(['title' => $request->cover_description_image, 'file' => $image_name, 'file_type' => 'image', 'user_id' => Auth::user()->id, 'ip_addr' => $_SERVER['REMOTE_ADDR']]);
				endif;
			elseif ($request->cover_description_image__method=='avatar' || $request->cover_description_image__method=='storage') :
				$column['cover_description_image__filename'] = 'required';
				// new preset
				$preset['cover']['description']['image']['method'] = $request->input('cover_description_image__method');
				$preset['cover']['description']['image']['image'] = $request->input('cover_description_image__filename');
			endif;
			$save_inv_column['title'] = json_encode([$request->input('cover_name_male'), $request->input('cover_name_female')]);
		elseif ($menu=='profile') :
			$column['profile_name_male'] = 'required';
			$column['profile_name_female'] = 'required';
			$column['profile_photo_male__method'] = 'required';
			$column['profile_photo_female__method'] = 'required';
			if ($request->profile_instagram_show!='on') :
				$column['profile_instagram_male'] = 'required';
				$column['profile_instagram_female'] = 'required';
				// new preset
				$preset['profile']['instagram']['male'] = $request->input('profile_instagram_male');
				$preset['profile']['instagram']['female'] = $request->input('profile_instagram_female');
			endif;
			if ($request->profile_parent_show!='on') :
				$column['profile_parent_male_father'] = 'required';
				$column['profile_parent_male_mother'] = 'required';
				$column['profile_parent_male_childhood'] = 'required';
				$column['profile_parent_female_father'] = 'required';
				$column['profile_parent_female_mother'] = 'required';
				$column['profile_parent_female_childhood'] = 'required';
				// new preset
				$preset['profile']['parent']['male']['father'] = $request->input('profile_parent_male_father');
				$preset['profile']['parent']['male']['mother'] = $request->input('profile_parent_male_mother');
				$preset['profile']['parent']['male']['childhood'] = $request->input('profile_parent_male_childhood');
				$preset['profile']['parent']['female']['father'] = $request->input('profile_parent_female_father');
				$preset['profile']['parent']['female']['mother'] = $request->input('profile_parent_female_mother');
				$preset['profile']['parent']['female']['childhood'] = $request->input('profile_parent_female_childhood');
			endif;
			if ($request->profile_photo_male__method=='upload') :
				$column['profile_photo_male'] = 'required|mimes:jpg,jpeg,png';
				// new preset
				if (!empty($request->profile_photo_male)) :
					$image_name = $request->file('profile_photo_male')->hashName();
					Storage::disk('public')->put($image_name, file_get_contents($request->file('profile_photo_male')));
					image_reducer(file_get_contents($request->file('profile_photo_male')), $image_name);
					$preset['profile']['photo']['male']['method'] = 'storage';
					$preset['profile']['photo']['male']['image'] = $image_name;
					// strbox
					Strbox::create(['title' => $request->profile_name_male, 'file' => $image_name, 'file_type' => 'image', 'user_id' => Auth::user()->id, 'ip_addr' => $_SERVER['REMOTE_ADDR']]);
				endif;
			elseif ($request->profile_photo_male__method=='avatar' || $request->profile_photo_male__method=='storage') :
				$column['profile_photo_male__filename'] = 'required';
				// new preset
				$preset['profile']['photo']['male']['method'] = $request->input('profile_photo_male__method');
				$preset['profile']['photo']['male']['image'] = $request->input('profile_photo_male__filename');
			endif;
			if ($request->profile_photo_female__method=='upload') :
				$column['profile_photo_female'] = 'required|mimes:jpg,jpeg,png';
				// new preset
				if (!empty($request->profile_photo_female)) :
					$image_name = $request->file('profile_photo_female')->hashName();
					Storage::disk('public')->put($image_name, file_get_contents($request->file('profile_photo_female')));
					image_reducer(file_get_contents($request->file('profile_photo_female')), $image_name);
					$preset['profile']['photo']['female']['method'] = 'storage';
					$preset['profile']['photo']['female']['image'] = $image_name;
					// strbox
					Strbox::create(['title' => $request->profile_name_female, 'file' => $image_name, 'file_type' => 'image', 'user_id' => Auth::user()->id, 'ip_addr' => $_SERVER['REMOTE_ADDR']]);
				endif;
			elseif ($request->profile_photo_female__method=='avatar' || $request->profile_photo_female__method=='storage') :
				$column['profile_photo_female__filename'] = 'required';
				// new preset
				$preset['profile']['photo']['female']['method'] = $request->input('profile_photo_female__method');
				$preset['profile']['photo']['female']['image'] = $request->input('profile_photo_female__filename');
			endif;
			// new preset
			$preset['profile']['name']['male'] = $request->input('profile_name_male');
			$preset['profile']['name']['female'] = $request->input('profile_name_female');
			$preset['profile']['photo']['male']['frame'] = $request->input('profile_photo_male_frame');
			$preset['profile']['photo']['female']['frame'] = $request->input('profile_photo_female_frame');
			$preset['profile']['instagram']['show'] = ($request->input('profile_instagram_show')=='on') ? false : true;
			$preset['profile']['parent']['show'] = ($request->input('profile_parent_show')=='on') ? false : true;
		elseif ($menu=='detail') :
			$column['detail_calendar_date'] = 'required';
			$column['detail_calendar_time'] = 'required';
			$column['detail_calendar_timezone'] = 'required';
			$column['detail_location_address'] = 'required';
			$column['detail_location_map'] = 'required';
			if ($request->detail_countdown_show!='on') :
				$column['detail_countdown_style'] = 'required';
				$preset['detail']['countdown']['style'] = $request->input('detail_countdown_style');
			endif;
			if ($request->detail_calendar_save_show!='on') :
				$column['detail_calendar_save_content'] = 'required';
				$preset['detail']['calendar']['save']['content'] = $request->input('detail_calendar_save_content');
			endif;
			// new preset
			$preset['detail']['calendar']['date'] = $request->input('detail_calendar_date');
			$preset['detail']['calendar']['time'] = $request->input('detail_calendar_time');
			$preset['detail']['calendar']['timezone'] = $request->input('detail_calendar_timezone');
			$preset['detail']['calendar']['save']['show'] = ($request->input('detail_calendar_save_show')=='on') ? false : true;
			$preset['detail']['countdown']['show'] = ($request->input('detail_countdown_show')=='on') ? false : true;
			$preset['detail']['location']['address'] = $request->input('detail_location_address');
			$preset['detail']['location']['map'] = $request->input('detail_location_map');
			$preset['detail']['additional']['show'] = ($request->input('detail_additional_show')=='on') ? false : true;
			$preset['detail']['additional']['closing'] = $request->input('detail_additional_closing') ?? null;
			if ($request->detail_additional_special!='') :
				$preset['detail']['additional']['special'] = $request->input('detail_additional_special') ?? [];
			endif;
		elseif ($menu=='quote') :
			$column['quote_content'] = 'required';
			// new preset
			$preset['quote']['content'] = $request->input('quote_content');
			$preset['quote']['decoration'] = $request->input('quote_decoration');
		elseif ($menu=='event') :
			// gallery redirected to menu.event-add
		elseif ($menu=='story') :
			// gallery redirected to menu.story-add
		elseif ($menu=='gallery') :
			// gallery redirected to menu.gallery-add
		elseif ($menu=='music') :
			if ($request->music_show=='on') :
				$column['music_url'] = 'required';
				$preset['music']['title'] = $request->input('music_title');
				$preset['music']['url'] = $request->input('music_url');
			endif;
			$preset['music']['show'] = ($request->input('music_show')=='on') ? true : false;
		elseif ($menu=='rsvp') :
			$column['rsvp_title'] = 'required';
			$column['rsvp_content'] = 'required';
			$column['rsvp_yes_option'] = 'required';
			$column['rsvp_yes_content'] = 'required';
			$column['rsvp_no_option'] = 'required';
			$column['rsvp_no_content'] = 'required';
			$column['rsvp_date'] = 'required';
			// new preset
			$preset['rsvp']['title'] = $request->input('rsvp_title');
			$preset['rsvp']['content'] = $request->input('rsvp_content');
			$preset['rsvp']['date'] = $request->input('rsvp_date');
			$preset['rsvp']['yes']['option'] = $request->input('rsvp_yes_option');
			$preset['rsvp']['yes']['content'] = $request->input('rsvp_yes_content');
			$preset['rsvp']['no']['option'] = $request->input('rsvp_no_option');
			$preset['rsvp']['no']['content'] = $request->input('rsvp_no_content');
		elseif ($menu=='additional') :
			$column['slug'] = 'required';
			if ($request->additional_live_show=='on') :
				$column['additional_live_app'] = 'required';
				$column['additional_live_link'] = 'required';
				$column['additional_live_content'] = 'required';
				$preset['additional']['live']['app'] = $request->input('additional_live_app');
				$preset['additional']['live']['link'] = $request->input('additional_live_link');
				$preset['additional']['live']['content'] = $request->input('additional_live_content');
			endif;
			if ($request->additional_protocol_show=='on') :
				$column['additional_protocol_code'] = 'required';
				$column['additional_protocol_title'] = 'required';
				$column['additional_protocol_content'] = 'required';
				$preset['additional']['protocol']['code'] = $request->input('additional_protocol_code');
				$preset['additional']['protocol']['title'] = $request->input('additional_protocol_title');
				$preset['additional']['protocol']['content'] = $request->input('additional_protocol_content');
			endif;
			$preset['additional']['live']['show'] = ($request->input('additional_live_show')=='on') ? true : false;
			$preset['additional']['protocol']['show'] = ($request->input('additional_protocol_show')=='on') ? true : false;
		elseif ($menu=='gift') :
			if ($request->gift_show=='on') :
				$column['gift_title'] = 'required';
				$column['gift_content'] = 'required';
				$column['gift_bank_name'] = 'required';
				$column['gift_bank_code'] = 'required';
				$column['gift_bank_option'] = 'required';
				// new preset
				$preset['gift']['title'] = $request->input('gift_title');
				$preset['gift']['content'] = $request->input('gift_content');
				$preset['gift']['bank']['name'] = $request->input('gift_bank_name');
				$preset['gift']['bank']['code'] = $request->input('gift_bank_code');
				$preset['gift']['bank']['option'] = $request->input('gift_bank_option');
			endif;
			$preset['gift']['show'] = ($request->input('gift_show')=='on') ? true : false;
		elseif ($menu=='wishes') :
			$column['wishes_title'] = 'required';
			$column['wishes_content'] = 'required';
			// new preset
			$preset['wishes']['title'] = $request->input('wishes_title');
			$preset['wishes']['content'] = $request->input('wishes_content');
			$preset['wishes']['public'] = ($request->input('wishes_public')=='on') ? true : false;
		endif;
		$this->validate($request, $column);
		$save_inv_column['preset'] = json_encode($preset);
		$save_inv = $recent_inv->update($save_inv_column);
		$response = ['toast'=>['icon'=>'success', 'title'=>'Disimpan!', 'text'=>'Perubahan telah disimpan.'], 'page' => 'idle'];

		return response()->json($response);
	}
}
