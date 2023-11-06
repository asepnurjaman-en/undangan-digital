<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Strbox;
use App\Models\Account;
use App\Models\Invitation;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\AccountInvoice;
use App\Models\TemplateAssets;
use App\Models\InvitationEvent;
use App\Models\InvitationGuest;
use App\Models\InvitationStory;
use App\Models\InvitationGallery;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class InvitationController extends Controller
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

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $data = [
			'title'	=> 'undangan',
			'list'	=> route('member.list'),
		];

		return response()->view('panel.invitation.index', compact('data'));
	}

    public function list(Request $request): JsonResponse
	{
		$totalFilteredRecord = $totalDataRecord = $draw_val = "";
		$column = [0 => 'id', 1 => 'title', 2 => 'info', 3 => 'log'];
		$totalDataRecord = User::count();
		$totalFilteredRecord = $totalDataRecord;
		$limit_val	= $request->input('length');
		$start_val	= $request->input('start');
		if (empty($request->input('search.value'))) :
			$datatable = User::with('inv')->whereRole('member')->offset($start_val)->latest()->limit($limit_val)->get();
		else :
			$search_text = $request->input('search.value');
			$datatable =  User::with('inv')->whereRole('member')->where('id', 'LIKE', "%{$search_text}%")->orWhere('name', 'LIKE', "%{$search_text}%")->offset($start_val)->limit($limit_val)->get();
			$totalFilteredRecord = User::whereRole('member')->where('id', 'LIKE', "%{$search_text}%")->orWhere('name', 'LIKE', "%{$search_text}%")->count();
		endif;
		$data_val = [];
		if (!empty($datatable)) :
			foreach ($datatable as $key => $item) :
                if ($item->acc->actived==1) :
                    $actived = ['color'=>'bg-success', 'text'=>'aktif'];
                elseif ($item->acc->actived==0) :
                    $actived = ['color'=>'bg-gray', 'text'=>'non-aktif'];
                endif;
				$data_val[$key]['id'] = anchor(text:$item->name, href:route('member.show', $item->inv->id))."<small class=\"d-block\">{$item->email}</small>";
				$data_val[$key]['title'] = 'Undangan '.implode(' & ', json_decode($item->inv->title, true));
				$data_val[$key]['info'] = "<div class=\"d-flex\"><span class=\"badge {$actived['color']}\">{$actived['text']}</span></div>";;
				$data_val[$key]['log'] = date_info($item->created_at);
			endforeach;
		endif;
		$draw_val = $request->input('draw');
		$get_json_data = ["draw" => intval($draw_val), "recordsTotal" => intval($totalDataRecord), "recordsFiltered" => intval($totalFilteredRecord), "data" => $data_val];

		return response()->json($get_json_data);
	}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Int $id)
    {
        $invitation = Invitation::with('event')->with('story')->with('photo')->with('video')->with('guest')->findOrFail($id);
        $user = User::with('inv')->with('invoice')->whereId($invitation->user_id)->first();
        $account = Account::select('id', 'content', 'file', 'actived', 'guestbook')->where('invitation_id', $id)->firstOrFail();
        $invoice = AccountInvoice::select('id', 'content', 'date', 'status', 'payment_code')->with('pack')->where('user_id', $invitation->user_id)->latest()->get();
        $invoice_current = AccountInvoice::select('content', 'date', 'status', 'package_id')->with('pack')->current($invitation->user_id)->where('user_id', $invitation->user_id)->first();
        $data = [
			'title'	=> 'undangan '.implode(' & ', json_decode($invitation->title, true)),
			'delete'=> ['action' => route('member.destroy', 0), 'message' => 'Hapus dari member?']
		];

		return response()->view('panel.invitation.show', compact('data', 'invitation', 'user', 'account', 'invoice', 'invoice_current'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invitation $invitation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invitation $invitation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invitation $invitation)
    {
        //
    }

    public function user_purger(int $id, string $type): JsonResponse
    {
        $response = [];
        if ($type=='delete') :
            // foreach (Strbox::where('user_id', $id)->get() as $image) :
            //     if (Storage::disk('public')->exists($image->file)) :
            //         Storage::delete('public/'.$image->file, 'public/md/'.$image->file, 'public/sm/'.$image->file, 'public/xs/'.$image->file);
            //     endif;
            // endforeach;
            User::where('id', $id)->delete();
            // $delete = [];
            // $delete['step-1'][0] = TemplateAssets::where('user_id', $id)->delete();
            // $delete['step-1'][1] = AccountInvoice::where('user_id', $id)->delete();
            // $delete['step-1'][2] = Strbox::where('user_id', $id)->delete();
            // if ($delete['step-1']) :
            //     $delete['step-2'][0] = Account::where('user_id', $id)->delete();
            //     $delete['step-2'][1] = InvitationGallery::where('user_id', $id)->delete();
            //     $delete['step-2'][2] = InvitationGuest::where('user_id', $id)->delete();
            //     $delete['step-2'][3] = InvitationEvent::where('user_id', $id)->delete();
            //     $delete['step-2'][4] = InvitationStory::where('user_id', $id)->delete();
            //     if ($delete['step-2']) :
            //         $delete['step-3'][0] = Invitation::where('user_id', $id)->delete();
            //         if ($delete['step-3']) :
            //             $delete['step-4'][0] = User::where('id', $id)->delete();
            //         endif;
            //     endif;
            // endif;
            $response['redirect'] = route('member.index');
        elseif ($type=='active') :
            Account::find($id)->update(['actived' => '1']);
            $response['toast'] = ['icon'=>'success', 'title'=>'Akun diaktifkan', 'html'=>"<b>{$id}</b> telah diaktifkan."];
        elseif ($type=='deactive') :
            Account::find($id)->update(['actived' => '0']);
            $response['toast'] = ['icon'=>'success', 'title'=>'Akun dinon-aktifkan', 'html'=>"<b>{$id}</b> telah dinon-aktifkan."];
        endif;

        return response()->json($response);
    }
}
