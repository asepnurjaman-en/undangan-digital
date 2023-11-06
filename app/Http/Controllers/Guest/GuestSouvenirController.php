<?php

namespace App\Http\Controllers\Guest;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\InvitationGuestSouvenir;

class GuestSouvenirController extends Controller
{
    public function list(Request $request): JsonResponse
	{
		$totalFilteredRecord = $totalDataRecord = $draw_val = "";
		$column = [0 => 'id', 1 => 'title', 2 => 'info', 3 => 'log'];
		$totalDataRecord = InvitationGuestSouvenir::count();
		$totalFilteredRecord = $totalDataRecord;
		$limit_val	= $request->input('length');
		$start_val	= $request->input('start');
		if (empty($request->input('search.value'))) :
			$datatable = InvitationGuestSouvenir::offset($start_val)->latest()->limit($limit_val)->get();
		else :
			$search_text = $request->input('search.value');
			$datatable =  InvitationGuestSouvenir::where('id', 'LIKE', "%{$search_text}%")->orWhere('title', 'LIKE', "%{$search_text}%")->offset($start_val)->limit($limit_val)->get();
			$totalFilteredRecord = InvitationGuestSouvenir::where('id', 'LIKE', "%{$search_text}%")->orWhere('title', 'LIKE', "%{$search_text}%")->count();
		endif;
		$data_val = [];
		if (!empty($datatable)) :
			foreach ($datatable as $key => $item) :
				$data_val[$key]['id'] = input_check(name:"check[]", value:$item->id, class:['form-check-input', 'check-row'], mode:'multiple');
				$data_val[$key]['title'] = anchor(text:$item->title);
				$data_val[$key]['info'] = null;
				$data_val[$key]['log'] = date_info($item->created_at);
			endforeach;
		endif;
		$draw_val = $request->input('draw');
		$get_json_data = ["draw" => intval($draw_val), "recordsTotal" => intval($totalDataRecord), "recordsFiltered" => intval($totalFilteredRecord), "data" => $data_val];

		return response()->json($get_json_data);
	}
}
