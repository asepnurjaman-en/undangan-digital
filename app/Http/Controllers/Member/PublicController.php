<?php

namespace App\Http\Controllers\Member;

use Carbon\Carbon;
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

class PublicController extends Controller
{
	public function invitation(string $slug): Response|RedirectResponse
	{
		$invitation = Invitation::select('id', 'title', 'file', 'preset', 'user_id', 'template_id')->where('slug', $slug)->firstOr(function() {
			return abort(404, 'tidak ditemukan');
		});
		$invitation->title = implode(' & ', json_decode($invitation->title, true));
		$invitation_activation = AccountInvoice::select('date', 'package_id')->where('status', 'confirmed')->where('user_id', $invitation->user_id)->latest()->first();
		if (!empty($invitation_activation->pack)) :
			$invitation_activation->date = $invitation_activation->date;
			$invitation_active = json_decode($invitation_activation->pack->content)->active;
		else :
			$invitation_active = 0;
		endif;
		if (isexpired($invitation_activation->date ?? date('Y-m-d'), $invitation_active)===false) :
			$data = json_decode($invitation->preset);
			$other = [
				'video' => InvitationGallery::where('type', 'video')->where('invitation_id', $invitation->id)->first(),
				'photo' => InvitationGallery::where('type', 'photo')->where('invitation_id', $invitation->id)->first(),
				'protocol' => TemplateAssets::select('content')->where('type', 'protocol')->whereId($data->additional->protocol->code)->first(),
			];
			if (json_decode($invitation_activation->pack->content)->event) :
				$other['event'] = InvitationEvent::where('invitation_id', $invitation->id)->get();
			else :
				$other['event'] = [];
			endif;
			if (json_decode($invitation_activation->pack->content)->story) :
				$other['story'] = InvitationStory::where('invitation_id', $invitation->id)->get();
			else :
				$other['story'] = [];
			endif;
			if ($other['photo']!=null) :
				$other['photo']->prop = json_decode($other['photo']->content);
			endif;
			if ($other['video']!=null) :
				$other['video']->prop = json_decode($other['video']->content);
			endif;
			if (request()->get('to')!='') :
				$guest = InvitationGuest::select('type', 'name')->where('slug', request()->get('to'))->where('invitation_id', $invitation->id)->first();
				if (!empty($guest)) :
					$other['guest'] = json_decode($guest->name, true);
				else :
					$other['guest'] = null;
				endif;
			else :
				$other['guest'] = null;
			endif;

			return response()->view('template.'.$invitation->temp->url, compact('invitation', 'data', 'other'));
		else :
			return abort(402, "apa?");
		endif;
	}

	public function invitation_present(Request $request, string $slug): JsonResponse
	{
		$this->validate($request, [
			'name' => 'required',
			'option' => 'required',
			'amount' => 'numeric|min:0|not_in:0',
		],
		[
			'required' => 'Kolom harus diisi.'
		]);
		$invitation_id = Invitation::select('id', 'preset')->where('slug', $slug)->first();
		$preset = json_decode($invitation_id->preset, true)['rsvp'];
		$spam = Feedback::whereDate('created_at', Carbon::today())->where('type', 'present')->where('ip_addr', $_SERVER['REMOTE_ADDR'])->count();
		if ($spam <= 5) :
			$feedback = Feedback::create([
				'type' => 'present',
				'content' => json_encode(['name'=>$request->name, 'option'=>$request->option, 'amount'=>$request->amount ?? 1]),
				'invitation_id' => $invitation_id->id,
				'ip_addr' => $_SERVER['REMOTE_ADDR']
			]);
			return response()->json(['message'=>$preset[$request->option]['content']]);
		elseif ($spam > 5) :
			return response()->json(['message'=>"Batas pengiriman tercapai."]);
		endif;
	}

	public function invitation_wish(Request $request, string $slug): JsonResponse
	{
		$this->validate($request, [
			'name' => 'required',
			'phone' => 'required',
			'message' => 'required',
		],
		[
			'required' => ' - Kolom harus diisi.'
		]);
		$invitation_id = Invitation::select('id', 'preset')->where('slug', $slug)->first();
		$preset = json_decode($invitation_id->preset, true)['rsvp'];
		$spam = Feedback::whereDate('created_at', Carbon::today())->where('type', 'wishes')->where('ip_addr', $_SERVER['REMOTE_ADDR'])->count();
		if ($spam <= 5) :
			$feedback = Feedback::create([
				'type' => 'wishes',
				'content' => json_encode(['name'=>$request->name, 'phone'=>$request->phone, 'message'=>$request->message]),
				'invitation_id' => $invitation_id->id,
				'ip_addr' => $_SERVER['REMOTE_ADDR']
			]);
			return response()->json(['message'=>'Terkirim']);
		elseif ($spam > 5) :
			return response()->json(['message'=>"Batas pengiriman tercapai."]);
		endif;
	}

    // Preview Template
    public function template(string $slug): Response
	{
		$invitation = Template::select('title', 'slug', 'preset', 'url', 'file')->where('slug', $slug)->publish()->firstOrFail();
		$data = json_decode($invitation->preset);
		$other = [
			'event' => [],
			'story' => [],
			'photo' => null,
			'video' => null,
			'guest' => null,
		];

		return response()->view('template.'.$invitation->url, compact('data', 'invitation', 'other'));
	}
}
