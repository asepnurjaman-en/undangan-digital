<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class BankController extends Controller
{
    private $bank = [
        'bca' => 'BCA',
        'hsbc' => 'HSBC',
        'digibank' => 'Digibank',
        'danamon' => 'Danamon',
        'paninbank' => 'PaninBank',
        'jenius' => 'Jenius',
        'permatabank' => 'PermataBank',
        'mandiri' => 'Mandiri',
        'cimb' => 'CIMB',
        'bukopin' => 'Bank Bukopin',
        'bri' => 'BRI',
        'bni' => 'BNI',
        'gopay' => 'Gopay',
        'ovo' => 'OVO',
        'bluepay' => 'BluePay',
        'dana' => 'Dana',
        'mastercard' => 'MasterCard',
        'paypal' => 'Paypal',
        'visa' => 'Visa',
        'jcb' => 'JCB'
    ];

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $data = [
			'title'	=> 'bank',
			'list'	=> route('bank.list'),
			'create'=> ['action' => route('bank.create')],
			'delete'=> ['action' => route('bank.destroy', 0), 'message' => 'Hapus bank?']
		];

		return response()->view('panel.bank.index', compact('data'));
    }

    public function list(Request $request): JsonResponse
	{
		$totalFilteredRecord = $totalDataRecord = $draw_val = "";
		$column = [0 => 'id', 1 => 'title', 2 => 'info', 3 => 'log'];
		$totalDataRecord = Bank::count();
		$totalFilteredRecord = $totalDataRecord;
		$limit_val	= $request->input('length');
		$start_val	= $request->input('start');
		if (empty($request->input('search.value'))) :
			$datatable = Bank::offset($start_val)->orderBy('publish', 'DESC')->latest()->limit($limit_val)->get();
		else :
			$search_text = $request->input('search.value');
			$datatable =  Bank::where('id', 'LIKE', "%{$search_text}%")->orWhere('name', 'LIKE', "%{$search_text}%")->offset($start_val)->limit($limit_val)->get();
			$totalFilteredRecord = Bank::where('id', 'LIKE', "%{$search_text}%")->orWhere('name', 'LIKE', "%{$search_text}%")->count();
		endif;
		$data_val = [];
		if (!empty($datatable)) :
			foreach ($datatable as $key => $item) :
				$publish = ['title' => Str::title($item->publish), 'publish' => 'bg-primary', 'draft' => 'bg-warning'];
				$data_val[$key]['id'] = input_check(name:"check[]", value:$item->id, class:['form-check-input', 'check-row'], mode:'multiple');
				$data_val[$key]['title'] = anchor(text:$item->name." <b class=\"badge bg-info\">".$this->bank[$item->file]."</b>", href:route('bank.edit', $item->id));
				$data_val[$key]['info'] = "<div class=\"d-flex\"><span class=\"badge {$publish[$item->publish]}\">{$publish['title']}</span></div>";
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
    public function create(): Response
    {
        $data = [
			'title'	=> 'tambah bank',
			'form' => ['action' => route('bank.store'), 'class' => 'form-insert'],
            'bank_list' => $this->bank
		];

		return response()->view('panel.bank.form', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $this->validate($request, [
			'name'		=> 'required|max:110',
			'file'	    => 'required',
			'content_code' => 'required'
		],
		[
			'required'	=> '<code>:attribute</code> harus diisi.',
			'max'		=> '<code>:attribute</code> tidak boleh lebih dari <b>:max</b> huruf.'
		]);
        $column = [
			'name'		=> $request->name,
            'content'   => json_encode(['code'=>$request->content_code]),
			'file'	    => $request->file,
			'publish'	=> ($request->publish=='publish') ? 'publish' : 'draft',
			'ip_addr'	=> $_SERVER['REMOTE_ADDR'],
			'user_id'	=> Auth::user()->id
		];
		Bank::create($column);

        return response()->json([
			'toast'		=> ['icon' => 'success', 'title' => ucfirst('disimpan'), 'html' => "\"<b>{$request->name}</b>\" telah ditambahkan"],
			'redirect'	=> ['type' => 'assign', 'value' => route('bank.index')]
		]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Bank $bank)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Bank $bank): Response
    {
        $data = [
			'title'	=> 'edit paket',
			'form'	=> ['action' => route('bank.update', $bank->id), 'class' => 'form-update'],
            'bank_list' => $this->bank
		];
        $bank->content = json_decode($bank->content);

		return response()->view('panel.bank.form', compact('data', 'bank'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Bank $bank): JsonResponse
    {
        $this->validate($request, [
			'name'		=> 'required|max:110',
			'file'	    => 'required',
			'content_code' => 'required'
		],
		[
			'required'	=> '<code>:attribute</code> harus diisi.',
			'max'		=> '<code>:attribute</code> tidak boleh lebih dari <b>:max</b> huruf.'
		]);

		$column = [
			'name'		=> $request->name,
            'content'   => json_encode(['code'=>$request->content_code]),
			'file'	    => $request->file,
			'publish'	=> ($request->publish=='publish') ? 'publish' : 'draft',
			'ip_addr'	=> $_SERVER['REMOTE_ADDR'],
			'user_id'	=> Auth::user()->id
		];
		$bank->update($column);

		return response()->json([
			'toast'		=> ['icon' => 'success', 'title' => ucfirst('disimpan'), 'html' => "\"<b>{$request->name}</b>\" telah disimpan"],
			'redirect'	=> ['type' => 'assign', 'value' => route('bank.index')]
		]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id): JsonResponse
	{
		$ids = explode(',', $request->id);
		$ids_count = count($ids);
		Bank::whereIn('id', $ids)->delete();

		return response()->json([
			'toast'		=> ['icon' => 'success', 'title' => ucfirst('dihapus'), 'html' => "<b>{$ids_count}</b> data telah buang"],
			'redirect'	=> ['type' => 'dataTables']
		]);
	}
}
