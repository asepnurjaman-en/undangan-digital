<?php

namespace App\Http\Controllers\Member;

use App\Models\Bank;
use App\Models\Info;
use App\Models\User;
use App\Models\Strbox;
use App\Models\Account;
use App\Models\Package;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\AccountInvoice;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
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

    public function __construct()
    {
        $this->middleware('auth');
    }

    // Account
	public function profile(): Response
	{
		$account = json_decode(Auth::user()->acc->content);
		$data = [
			'activation' => AccountInvoice::select('date', 'package_id')->with('pack')->current()->first(),
		];
		if ($data['activation']!=null) :
			$data['active'] = json_decode($data['activation']->pack->content)->active;
		else :
			$data['active'] = 0;
		endif;

		return response()->view('member.account-profile', compact('account', 'data'));
	}

	public function profile_update(Request $request): JsonResponse
	{
		$this->validate($request, [
			'name' => 'required',
			'email' => 'required',
			'phone' => 'required',
			'address' => 'required'
		]);
		User::find(Auth::user()->id)->update([
			'name' => $request->name,
			'email' => $request->email
		]);
		Account::find(Auth::user()->acc->id)->update([
			'content' => json_encode(['phone' => $request->phone, 'address' => $request->address]),
			'file' => $request->file
		]);

		$response = ['toast' => ['icon' => 'success', 'title' => 'Profil disimpan', 'text' => 'Profile baru kamu disimpan.']];

		return response()->json($response);
	}
	
	public function account_storage(): Response
	{
		return response()->view('member.account-storage');
	}

	public function account_transaction(): Response
	{
		return response()->view('member.account-transaction');
	}

	public function account_transaction_list(Request $request): JsonResponse
	{
		$totalFilteredRecord = $totalDataRecord = $draw_val = "";
		$column = [0 => 'image', 1 => 'title', 2 => 'info'];
		$totalDataRecord = AccountInvoice::where('user_id', Auth::user()->id)->count();
		$totalFilteredRecord = $totalDataRecord;
		$limit_val	= $request->input('length');
		$start_val	= $request->input('start');
		if (empty($request->input('search.value'))) :
			$datatable = AccountInvoice::where('user_id', Auth::user()->id)->offset($start_val)->limit($limit_val)->latest()->get();
		else :
			$search_text = $request->input('search.value');
			$datatable =  AccountInvoice::where('user_id', Auth::user()->id)->where('id', 'LIKE', "%{$search_text}%")->orWhere('date', 'LIKE', "%{$search_text}%")->offset($start_val)->limit($limit_val)->get();
			$totalFilteredRecord = AccountInvoice::where('id', 'LIKE', "%{$search_text}%")->orWhere('date', 'LIKE', "%{$search_text}%")->count();
		endif;
		$data_val = [];
		if (!empty($datatable)) :
			$badge = ['PENDING'=>'bg-warning'];
			foreach ($datatable as $key => $item) :
				if ($item->status=='CONFIRMED') :
                    $status = ['color'=>'bg-creasik-primary', 'text'=>'done'];
                elseif ($item->status=='PENDING' AND empty(json_decode($item->content)->payment)) :
                    $status = ['color'=>'bg-info', 'text'=>'pending'];
                elseif ($item->status=='PENDING' AND !empty(json_decode($item->content)->payment)) :
                    $status = ['color'=>'bg-warning', 'text'=>'waiting confirmation'];
                endif;
				$data_val[$key]['image'] = anchor(text:"<b>#:".$item->payment_code."</b>", href:route('invoice', encrypt($item->id)))."<br/> Pembelian paket <u>".$item->pack->title."</u>";
				$data_val[$key]['title'] = $item->date;
				$data_val[$key]['info'] = "<span class=\"badge {$status['color']} w-100\">".Str::upper($status['text'])."</span>";
			endforeach;
		endif;
		$draw_val = $request->input('draw');
		$get_json_data = ["draw" => intval($draw_val), "recordsTotal" => intval($totalDataRecord), "recordsFiltered" => intval($totalFilteredRecord), "data" => $data_val];

		return response()->json($get_json_data);
	}

	// Upgrade
	public function package_up(): Response
	{
		$bagpack = [
			'activation' => AccountInvoice::select('date', 'package_id')->with('pack')->current()->first(),
			'feature' => Info::where('type', 'package')->first(),
			'package' => Package::select('id', 'title', 'content', 'price')->where('id', '!=', 1)->publish()->get()
		];
		$bagpack['feature']['content'] = json_decode($bagpack['feature']->content, true);
		$data = json_decode(json_encode($bagpack));

		return response()->view('member.packages', compact('data'));
	}

	public function package_payment(int $id): Response
	{
		$bagpack = [
			'bank' => Bank::select('id', 'name', 'content', 'file')->publish()->get(),
			'package' => Package::select('title', 'price', 'file')->whereId($id)->publish()->firstOrFail()
		];
		$data = json_decode(json_encode($bagpack));
		return response()->view('member.packages-buy', compact('data'));
	}

	// Invoice
	public function invoice(string $id): Response|RedirectResponse
	{
		try {
			$decrypted = decrypt($id);
			$invoice =  AccountInvoice::select('id', 'content', 'status', 'amount', 'payment_code', 'payment_link', 'package_id', 'user_id')->with('user')->where('user_id', Auth::user()->id)->where('id', $decrypted)->latest()->firstOrFail();
			$invoice->content = json_decode($invoice->content);
			if ($invoice->status=='CONFIRMED') :
				$status = ['color'=>'bg-success', 'text'=>'done'];
			elseif ($invoice->status=='PENDING' AND empty($invoice->content->payment)) :
				$status = ['color'=>'bg-info', 'text'=>'pending'];
			elseif ($invoice->status=='PENDING' AND !empty($invoice->content->payment)) :
				$status = ['color'=>'bg-warning', 'text'=>'waiting confirmation'];
			endif;
			if ($invoice->payment_link=='#manual') :
				$bank_pay = Bank::select('name', 'content', 'file')->whereId(base64_decode($invoice->content->bank))->publish()->first();
				$bank_pay->content = json_decode($bank_pay->content);
				$bank_pay->bank = $this->bank[$bank_pay->file];
			else :
				$bank_pay = null;
			endif;

			return response()->view('member.invoice', compact('invoice', 'bank_pay', 'status'));
		} catch (DecryptException $e) {
			return redirect()->route('member.main');
		}
	}

	public function invoice_add(Request $request, int $id): JsonResponse
	{
		$column = [
			'payment' => 'required'
		];	
		$invoice_number = AccountInvoice::count();
		$package = Package::select('id','price')->whereId($id)->firstOrFail();
		$invoice_column = [
			'date' => now(),
			'amount' => $package->price,
			'package_id'=> $package->id,
			'user_id'	=> Auth::user()->id,
			'ip_addr'	=> $_SERVER['REMOTE_ADDR'],
		];
		if ($request->payment=='fast') :
			// start xendit
			$secret_key = 'Basic '.config('xendit.key_auth');
			$external_id = Str::random(10);
			$data_request = Http::withHeaders([
				'Authorization' => $secret_key
			])->post('https://api.xendit.co/v2/invoices', [
				'external_id' => $external_id,
				'amount' => $package->price
			]);
			$data_response = $data_request->object();
			// end xendit
			$invoice_column['content'] = json_encode(['invoice_number'=>'#'.($invoice_number+1)]);
			$invoice_column['status'] = $data_response->status;
			$invoice_column['payment_link'] = $data_response->invoice_url;
			$invoice_column['payment_code'] = $external_id;
		elseif ($request->payment=='manual') :
			$column['bank'] = 'required';
			// start manual
			$external_id = Str::random(10);
			// end manual
			$invoice_column['content'] = json_encode(['invoice_number'=>'#'.($invoice_number+1), 'bank'=>$request->bank]);
			$invoice_column['status'] = 'PENDING';
			$invoice_column['payment_link'] = '#manual';
			$invoice_column['payment_code'] = $external_id;
		endif;

		$this->validate($request, $column, [
			'payment.required' => 'Pilih metode pembayaran.',
			'bank.required' => 'Pilih bank tujuan.'
		]);

		AccountInvoice::create($invoice_column);
		$last = AccountInvoice::select('id')->where('user_id', Auth::user()->id)->where('ip_addr', $_SERVER['REMOTE_ADDR'])->latest()->first();

		return response()->json(['code'=>200, 'redirect'=>route('invoice', encrypt($last->id))]);
	}

	public function invoice_provement(Request $request, string $id): JsonResponse|RedirectResponse
	{
		$this->validate($request, [
			'prove_image' => 'required|mimes:jpg,jpeg,png'
		]);
		try {
			$decrypted = decrypt($id);
			$invoice =  AccountInvoice::select('content')->where('user_id', Auth::user()->id)->where('id', $decrypted)->where('status', 'PENDING')->first();
			$prove = json_decode($invoice->content, true);
			$prove['payment']['date'] = now();
			if ($request->hasFile('prove_image')) :
				if (!empty($prove['payment']['image'])) :
					if (Storage::disk('public')->exists($prove['payment']['image'])) :
						Storage::disk('public')->delete($prove['payment']['image']);
					endif;
				endif;
				$image_name = $request->file('prove_image')->hashName();
				Storage::disk('public')->put($image_name, file_get_contents($request->file('prove_image')));
				$prove['payment']['image'] = $image_name;
			else :
				$prove['payment']['image'] = 'empty';
			endif;
			AccountInvoice::select('content')->where('user_id', Auth::user()->id)->where('id', $decrypted)->where('status', 'PENDING')->update(['content' => json_encode($prove)]);

			return response()->json(['toast'=>['icon'=>'success', 'title'=>'Dikirim!', 'text'=>'Bukti pembayaran telah dikirim.'], 'page' => 'reload']);
		} catch (DecryptException $e) {
			return response()->json(['toast'=>['icon'=>'error', 'title'=>'Proses tidak diketahui', 'text'=>'Proses tidak diketahui.'], 'page' => 'reload']);
		}
	}

	public function invoice_pay(Request $request, string $id): Response|RedirectResponse
	{
		try {
			$decrypted = decrypt($id);
			$invoice =  AccountInvoice::where('user_id', Auth::user()->id)->where('id', $decrypted)->where('status', 'PENDING')->update(['status'=>'confirmed']);
			
			return redirect()->route('member.main');
		} catch (DecryptException $e) {
			return redirect()->route('member.main');
		}
	}

	public function strbox_store(Request $request): JsonResponse
	{
		$this->validate($request, [
			'storage_image_bg'	=> 'required|mimes:jpg,jpeg,png',
			'storage_title_text'=> 'required'
		]);
		if (!empty($request->file('storage_image_bg'))) :
			$image_name = $request->file('storage_image_bg')->hashName();
			Storage::disk('public')->put($image_name, file_get_contents($request->file('storage_image_bg')));
			image_reducer(file_get_contents($request->file('storage_image_bg')), $image_name);
			// strbox
			Strbox::create(['title' => $request->storage_title_text, 'file' => $image_name, 'file_type' => 'image', 'user_id' => Auth::user()->id, 'ip_addr' => $_SERVER['REMOTE_ADDR']]);
		endif;

		return response()->json(['success']);
	}

	public function strbox_list(Request $request, string $mode = 'single'): JsonResponse
	{
		$totalFilteredRecord = $totalDataRecord = $draw_val = "";
		$column = [0 => 'image', 1 => 'title', 2 => 'info'];
		$totalDataRecord = Strbox::where('user_id', Auth::user()->id)->count();
		$totalFilteredRecord = $totalDataRecord;
		$limit_val	= $request->input('length');
		$start_val	= $request->input('start');
		if (empty($request->input('search.value'))) :
			$datatable = Strbox::where('user_id', Auth::user()->id)->offset($start_val)->limit($limit_val)->latest()->get();
		else :
			$search_text = $request->input('search.value');
			$datatable =  Strbox::where('user_id', Auth::user()->id)->where('title', 'LIKE', "%{$search_text}%")->offset($start_val)->limit($limit_val)->get();
			$totalFilteredRecord = Strbox::where('user_id', Auth::user()->id)->where('title', 'LIKE', "%{$search_text}%")->count();
		endif;
		$data_val = [];
		if (!empty($datatable)) :
			foreach ($datatable as $key => $item) :
				$data_val[$key]['image'] = input_check(name:"storage_file[]", value:$item->file, mode:$mode, label:image(src:url('storage/sm/'.$item->file)), class:['img-choice']);
				$data_val[$key]['title'] = $item->title;
				$data_val[$key]['info'] = null;
			endforeach;
		endif;
		$draw_val = $request->input('draw');
		$get_json_data = ["draw" => intval($draw_val), "recordsTotal" => intval($totalDataRecord), "recordsFiltered" => intval($totalFilteredRecord), "data" => $data_val];

		return response()->json($get_json_data);
	}
}
