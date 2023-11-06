<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

class ContactController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}
	
    public function index(string $tab): Response
    {
        $contact = [
			'address'	=> Contact::whereType('address')->firstOrFail(),
			'map'		=> Contact::whereType('map')->firstOrFail(),
			'phone'		=> Contact::whereType('phone')->get(),
			'whatsapp'	=> Contact::whereType('whatsapp')->get(),
			'email'		=> Contact::whereType('email')->get()
		];
		$tablist = [
			['url' => route('contact.index', 'address'), 'icon' => 'bx bx-map', 'title' => 'alamat'],
			['url' => route('contact.index', 'phone'), 'icon' => 'bx bx-phone', 'title' => 'telepon'],
			['url' => route('contact.index', 'email'), 'icon' => 'bx bx-envelope', 'title' => 'email']
		];
        $data = [
			'title'	=> 'kontak',
			'form'	=> ['action' => route('contact.update'), 'class' => 'form-update']
		];

		return response()->view('panel.contact.index', compact('data', 'tablist', 'contact'));
    }

    public function update(Request $request): JsonResponse
    {
        $type = [];
		foreach (Contact::get() as $item) :
			$edit = "edit_".$item->type.$item->id;
			$title = "title_".$item->type.$item->id;
			$content = "content_".$item->type.$item->id;
			$active = "active_".$item->type.$item->id;
			if ($request->{$edit} == 'true') :
				$type[$item->id] = ucwords($item->title);
				$contacts = Contact::whereId($item->id)->firstOrFail();
				$contacts->update(['actived'=>($request->{$active}=='true') ? '1' : '0', 'title'=>$request->{$title}, 'content'=>$request->{$content}]);
			endif;
		endforeach;
		$type_saved = implode(', ', $type);

		return response()->json([
			'toast'		=> ['icon' => 'success', 'title' => ucfirst('disimpan'), 'html' => "Perubahan <b>{$type_saved}</b> disimpan"],
			'redirect'	=> ['type' => 'nothing']
		]);
	}
}
