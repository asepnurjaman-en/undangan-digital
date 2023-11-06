<?php

namespace App\Http\Controllers\Guest;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\AccountInvoice;
use App\Models\InvitationGuest;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Models\InvitationGuestSouvenir;
use Illuminate\Support\Facades\Redirect;

class GuestController extends Controller
{
    private $menu = [
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

    public function guestbook(): Response
	{
		$access = AccountInvoice::select('package_id')->with('pack')->current()->first();
		$menu = $this->menu;
		$bagpack = [
			'guest'		 => InvitationGuest::where('invitation_id', Auth::user()->inv->id)->count(),
			'limitGuest' => (json_decode($access->pack->content)->{'guest'}=='unlimited') ? "∞" : json_decode($access->pack->content)->{'guest'},
		];
		$data = json_decode(json_encode($bagpack));

		return response()->view('guestbook.guestbook', compact('menu', 'data'));
	}

    public function m_reservation(): Response|RedirectResponse
	{
		if (Auth::user()->acc->guestbook==1) :
			$menu = $this->menu['reservation'];
			$bagpack = [
			];
			$data = json_decode(json_encode($bagpack));

			return response()->view('guestbook.m-reservation', compact('menu', 'data'));
		elseif (Auth::user()->acc->guestbook==0) :
			return redirect()->route('packages');
		endif;
	}
	
	public function m_management(): Response|RedirectResponse
	{
		if (Auth::user()->acc->guestbook==1) :
			$menu = $this->menu['table-management'];
			$bagpack = [
			];
			$data = json_decode(json_encode($bagpack));

			return response()->view('guestbook.m-management', compact('menu', 'data'));
		elseif (Auth::user()->acc->guestbook==0) :
			return redirect()->route('packages');
		endif;
	}

    public function m_souvenir(): Response|RedirectResponse
	{
		if (Auth::user()->acc->guestbook==1) :
			$menu = $this->menu['souvenir'];
			$souvenir = InvitationGuestSouvenir::where('invitation_id', Auth::user()->inv->id)->latest()->paginate(10);
			$bagpack = [
			];
			$data = json_decode(json_encode($bagpack));
			return response()->view('guestbook.m-souvenir', compact('menu', 'data', 'souvenir'));
		elseif (Auth::user()->acc->guestbook==0) :
			return redirect()->route('packages');
		endif;
	}

	public function m_souvenir_add(Request $request): JsonResponse
	{
		$column = [
			'souvenir_title' => 'required',
			'souvenir_stock' => 'required',
			'souvenir_grade' => 'required'
		];
		$this->validate($request, $column);
		$preset = [
			'title' => $request->souvenir_title,
			'stock' => $request->souvenir_stock,
			'grade' => $request->souvenir_grade,
			'file' => $request->souvenir_file ?? NULL,
			'invitation_id'=> Auth::user()->inv->id,
			'ip_addr' => $_SERVER['REMOTE_ADDR'],
			'user_id' => Auth::user()->id
		];
		InvitationGuestSouvenir::create($preset);

		return response()->json(['toast'=>['icon'=>'success', 'title'=>'Disimpan!', 'text'=>'Souvenir '.$request->souvenir_title.' telah ditambahkan.'], 'page' => 'reload']);
	}

	public function m_souvenir_edit(Request $request, int $id): JsonResponse
	{
		$column = [
			'souvenir_title' => 'required',
			'souvenir_stock' => 'required',
			'souvenir_grade' => 'required'
		];
		$this->validate($request, $column);
		$preset = [
			'title' => $request->souvenir_title,
			'stock' => $request->souvenir_stock,
			'grade' => $request->souvenir_grade,
			'file' => $request->souvenir_file ?? NULL,
			'ip_addr' => $_SERVER['REMOTE_ADDR'],
			'user_id' => Auth::user()->id
		];
		InvitationGuestSouvenir::whereId($id)->update($preset);
		$response = ['toast'=>['icon'=>'success', 'title'=>'Disimpan!', 'text'=>'Perubahan telah disimpan.'], 'page' => 'idle'];

		return response()->json($response);
	}

	public function m_souvenir_delete(int $id): JsonResponse
	{
		InvitationGuestSouvenir::whereId($id)->delete();

		return response()->json(['hapus']);
	}

	public function m_souvenir_show(int $id): JsonResponse
	{
		$souvenir = InvitationGuestSouvenir::select('id', 'title', 'stock', 'grade', 'file')->whereId($id)->where('invitation_id', Auth::user()->inv->id)->first();
		$souvenir->image = url('storage/sm/', $souvenir->file);
		$souvenir->url = route('menu.souvenir-delete', $souvenir->id);

		return response()->json($souvenir);
	}
}
