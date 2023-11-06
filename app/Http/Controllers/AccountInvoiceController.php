<?php

namespace App\Http\Controllers;

use App\Models\Bank;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\AccountInvoice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class AccountInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
		$data = [
			'title'	=> 'transaksi',
			'list'	=> route('invoice-transaction.list'),
			'create'=> ['action' => route('invoice-transaction.create')],
			'delete'=> ['action' => route('invoice-transaction.destroy', 0), 'message' => 'Hapus layanan?']
		];

		return response()->view('panel.invitation.transaction', compact('data'));
    }

    public function list(Request $request): JsonResponse
	{
		$totalFilteredRecord = $totalDataRecord = $draw_val = "";
		$column = [0 => 'id', 1 => 'title', 2 => 'info', 3 => 'log'];
		$totalDataRecord = AccountInvoice::count();
		$totalFilteredRecord = $totalDataRecord;
		$limit_val	= $request->input('length');
		$start_val	= $request->input('start');
		if (empty($request->input('search.value'))) :
			$datatable = AccountInvoice::offset($start_val)->latest()->limit($limit_val)->get();
		else :
			$search_text = $request->input('search.value');
			$datatable =  AccountInvoice::where('id', 'LIKE', "%{$search_text}%")->orWhere('payment_code', 'LIKE', "%{$search_text}%")->offset($start_val)->limit($limit_val)->get();
			$totalFilteredRecord = AccountInvoice::where('id', 'LIKE', "%{$search_text}%")->orWhere('payment_code', 'LIKE', "%{$search_text}%")->count();
		endif;
		$data_val = [];
		if (!empty($datatable)) :
			foreach ($datatable as $key => $item) :
                if ($item->status=='CONFIRMED') :
                    $status = ['color'=>'bg-success', 'text'=>'selesai'];
                elseif ($item->status=='PENDING' AND empty(json_decode($item->content)->payment)) :
                    $status = ['color'=>'bg-info', 'text'=>'menunggu pembayaran'];
                elseif ($item->status=='PENDING' AND !empty(json_decode($item->content)->payment)) :
                    $status = ['color'=>'bg-warning', 'text'=>'menunggu konfirmasi'];
                endif;
				$data_val[$key]['id'] = input_check(name:"check[]", value:$item->id, class:['form-check-input', 'check-row'], mode:'multiple');
				$data_val[$key]['title'] = anchor(text:json_decode($item->content)->invoice_number.':'.$item->payment_code, href:route('invoice-transaction.show', $item->id));
				$data_val[$key]['info'] = $item->pack->title." &mdash; ".date('d/m/Y', strtotime($item->date));
				$data_val[$key]['log'] = "<span class=\"badge {$status['color']}\">".Str::upper($status['text'])."</span>";
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
        $invoice = AccountInvoice::find($id);
        $invoice->content = json_decode($invoice->content);
        if ($invoice->status=='CONFIRMED') :
            $status = ['color'=>'bg-success', 'text'=>'selesai'];
        elseif ($invoice->status=='PENDING' AND empty($invoice->content->payment)) :
            $status = ['color'=>'bg-info', 'text'=>'menunggu pembayaran'];
        elseif ($invoice->status=='PENDING' AND !empty($invoice->content->payment)) :
            $status = ['color'=>'bg-warning', 'text'=>'menunggu konfirmasi'];
        endif;
        if ($invoice->payment_link=='#manual') :
            $bank = Bank::select('name', 'content', 'file')->whereId(base64_decode($invoice->content->bank))->first();
        else :
            $bank = null;
        endif;
        $data = [
			'title'	=> 'transaksi',
		];

		return response()->view('panel.invitation.transaction-show', compact('data', 'invoice', 'bank', 'status'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AccountInvoice $accountInvoice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AccountInvoice $accountInvoice)
    {
        //
    }

    public function confirm(int $id, string $status): RedirectResponse
    {
        $invoice = AccountInvoice::find($id);
        if ($status=='approve') :
            $column = [
                'status' => Str::upper('confirmed')
            ];
        elseif ($status=='decline') :
            $prove = json_decode($invoice->content, true);
            if (!empty($prove['payment']['image'])) :
                if (Storage::disk('public')->exists($prove['payment']['image'])) :
                    Storage::disk('public')->delete($prove['payment']['image']);
                endif;
            endif;
            $prove['payment']['date'] = null;
            $prove['payment']['image'] = null;
            $column = [
                'content' => json_encode($prove)
            ];
        endif;
        $invoice = $invoice->update($column);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id): JsonResponse
    {
        $ids = explode(',', $request->id);
		$ids_count = count($ids);
		foreach (AccountInvoice::whereIn('id', $ids)->get() as $item) :
            $item->content = json_decode($item->content);
            if (Storage::disk('public')->exists($item->content->payment->image)) :
                Storage::disk('public')->delete($item->content->payment->image);
            endif;
			AccountInvoice::whereId($item->id)->delete();
		endforeach;

		return response()->json([
			'toast'		=> ['icon' => 'success', 'title' => ucfirst('dihapus'), 'html' => "<b>{$ids_count}</b> data telah buang"],
			'redirect'	=> ['type' => 'dataTables']
		]);
	}
}
