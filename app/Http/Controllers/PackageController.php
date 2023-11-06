<?php

namespace App\Http\Controllers;

use App\Models\Info;
use App\Models\Strbox;
use App\Models\Package;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\AccountInvoice;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PackageController extends Controller
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
			'title'	=> 'paket',
			'list'	=> route('package.list'),
			'create'=> ['action' => route('package.create')],
			'delete'=> ['action' => route('package.destroy', 0), 'message' => 'Hapus pakat?']
		];

		return response()->view('panel.package.index', compact('data'));
	}

    public function list(Request $request): JsonResponse
	{
		$totalFilteredRecord = $totalDataRecord = $draw_val = "";
		$column = [0 => 'id', 1 => 'title', 2 => 'info', 3 => 'log'];
		$totalDataRecord = Package::count();
		$totalFilteredRecord = $totalDataRecord;
		$limit_val	= $request->input('length');
		$start_val	= $request->input('start');
		if (empty($request->input('search.value'))) :
			$datatable = Package::offset($start_val)->orderBy('publish', 'DESC')->latest()->limit($limit_val)->get();
		else :
			$search_text = $request->input('search.value');
			$datatable =  Package::where('id', 'LIKE', "%{$search_text}%")->orWhere('title', 'LIKE', "%{$search_text}%")->offset($start_val)->limit($limit_val)->get();
			$totalFilteredRecord = Package::where('id', 'LIKE', "%{$search_text}%")->orWhere('title', 'LIKE', "%{$search_text}%")->count();
		endif;
		$data_val = [];
		if (!empty($datatable)) :
			foreach ($datatable as $key => $item) :
				$invoice = AccountInvoice::where('package_id', $item->id)->count();
				$publish = ['title' => Str::title($item->publish), 'publish' => 'bg-primary', 'draft' => 'bg-warning'];
				$data_val[$key]['id'] = anchor(text:$item->title, href:route('package.edit', $item->id));
				$data_val[$key]['title'] = "<div class=\"d-flex\"><span class=\"badge bg-secondary me-1\">{$invoice} transaksi</span></div>";
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
			'title'	=> 'tambah paket',
			'form' => ['action' => route('package.store'), 'class' => 'form-insert'],
            'form_content' => Info::select('content')->where('type', 'package')->first(),
		];

		return response()->view('panel.package.form', compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $this->validate($request, [
			'title'		=> 'required|max:110',
			'file_type'	=> 'required',
			'price'	    => 'required'
		],
		[
			'required'	=> '<code>:attribute</code> harus diisi.',
			'max'		=> '<code>:attribute</code> tidak boleh lebih dari <b>:max</b> huruf.'
		]);
        $feature = Info::select('content')->where('type', 'package')->first();
        $content = [];
        foreach (json_decode($feature->content) as $key => $value) :
            if (in_array($key, ['active'])) :
                $content[$key] = $request->input('content')[$key] ?? 0;
            elseif (in_array($key, ['gift', 'e-invitation', 'filter-ig', 'story', 'event', 'live-stream', 'private-invitation', 'free-text', 'smart-wa', 'manual-wa'])) :
				if (isset($request->input('content')[$key])) :
                	$content[$key] = ($request->input('content')[$key]=='on') ? true : false;
				else :
					$content[$key] = false;
				endif;
            elseif (in_array($key, ['gallery-video', 'gallery-photo', 'guest', 'event-count', 'story-count'])) :
                if ($request->input('content_'.$key.'_unlimited')=='on') :
                    $content[$key] = 'unlimited';
                elseif ($request->input('content_'.$key.'_unlimited')!='on') :
                    $content[$key] = $request->input('content')[$key];
                endif;
            elseif (in_array($key, ['template'])) :
                $content[$key] = $request->input('content')[$key] ?? [];
            else :
                $content[$key] = $request->input('content')[$key] ?? null;
            endif;
        endforeach;
        $column = [
			'title'		=> $request->title,
			'slug'		=> clean_str($request->title),
            'content'   => json_encode($content),
            'grade'     => 1,
			'publish'	=> ($request->publish=='publish') ? 'publish' : 'draft',
			'price'	    => $request->price,
			'ip_addr'	=> $_SERVER['REMOTE_ADDR'],
			'user_id'	=> Auth::user()->id
		];
        if ($request->file_type == 'upload-file') :
			$this->validate($request, ['upload_file' => 'required|mimes:jpg,jpeg,png'], ['mimes' => 'hanya file <b>jpg, jpeg</b> atau <b>png</b> saja.']);
			if (!empty($request->file)) :
				$image_name = $request->file('upload_file')->hashName();
				Storage::disk('public')->put($image_name, file_get_contents($request->file('upload_file')));
				image_reducer(file_get_contents($request->file('upload_file')), $image_name);
				$column['file']	= $image_name;
				// strbox
				Strbox::create(['title' => $request->title, 'file' => $image_name, 'file_type' => 'image', 'user_id' => Auth::user()->id, 'ip_addr' => $_SERVER['REMOTE_ADDR']]);
			endif;
		elseif ($request->file_type == 'image') :
			$this->validate($request, ['file' => 'required'], ['required' => '<code>:attribute</code> harus diisi.']);
			$column['file']	= $request->file;
        endif;
		Package::create($column);

        return response()->json([
			'toast'		=> ['icon' => 'success', 'title' => ucfirst('disimpan'), 'html' => "\"<b>{$request->title}</b>\" telah ditambahkan"],
			'redirect'	=> ['type' => 'assign', 'value' => route('package.index')]
		]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Package $package)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Package $package): Response
    {
		$data = [
			'title'	=> 'edit paket',
			'form'	=> ['action' => route('package.update', $package->id), 'class' => 'form-update'],
            'form_content' => Info::select('content')->where('type', 'package')->first(),
		];

		return response()->view('panel.package.form', compact('data', 'package'));
	}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Package $package): JsonResponse
    {
        $this->validate($request, [
			'title'		=> 'required|max:110',
			'price'	    => 'required',
			'file_type'	=> 'required',
		],
		[
			'required'	=> '<code>:attribute</code> harus diisi.',
			'max'		=> '<code>:attribute</code> tidak boleh lebih dari <b>:max</b> huruf.'
		]);

		$feature = Info::select('content')->where('type', 'package')->first();
        $content = [];
        foreach (json_decode($feature->content) as $key => $value) :
            if (in_array($key, ['active'])) :
                $content[$key] = $request->input('content')[$key] ?? 0;
            elseif (in_array($key, ['gift', 'e-invitation', 'filter-ig', 'story', 'event', 'live-stream', 'private-invitation', 'free-text', 'smart-wa', 'manual-wa'])) :
                $content[$key] = (isset($request->input('content')[$key])) ? true : false;
            elseif (in_array($key, ['gallery-video', 'gallery-photo', 'guest', 'event-count', 'story-count'])) :
                if ($request->input('content_'.$key.'_unlimited')=='unlimited') :
                    $content[$key] = 'unlimited';
                elseif ($request->input('content_'.$key.'_unlimited')!='unlimited') :
                    $content[$key] = $request->input('content')[$key];
                endif;
            elseif (in_array($key, ['template'])) :
                $content[$key] = $request->input('content')[$key] ?? [];
            else :
                $content[$key] = $request->input('content')[$key] ?? null;
            endif;
        endforeach;

		$column = [
			'title'		=> $request->title,
			'slug'		=> clean_str($request->title),
			'content'	=> json_encode($content),
			'publish'	=> ($request->publish=='publish') ? 'publish' : 'draft',
			'price' 	=> $request->price,
			'ip_addr'	=> $_SERVER['REMOTE_ADDR'],
			'user_id'	=> Auth::user()->id
		];
		if ($request->file_type == 'upload-file') :
			$this->validate($request, ['upload_file' => 'required|mimes:jpg,jpeg,png'], ['mimes' => 'hanya file <b>jpg, jpeg</b> atau <b>png</b> saja.']);
			if (!empty($request->file)) :
				$image_name = $request->file('upload_file')->hashName();
				Storage::disk('public')->put($image_name, file_get_contents($request->file('upload_file')));
				image_reducer(file_get_contents($request->file('upload_file')), $image_name);
				$column['file']	= $image_name;
				// strbox
				Strbox::create(['title' => $request->title, 'file' => $image_name, 'file_type' => 'image', 'user_id' => Auth::user()->id, 'ip_addr' => $_SERVER['REMOTE_ADDR']]);
			endif;
		elseif ($request->file_type == 'image') :
			$this->validate($request, ['file' => 'required'], ['required' => '<code>:attribute</code> harus diisi.']);
			$column['file']	= $request->file;
		endif;
		$package->update($column);

		return response()->json([
			'toast'		=> ['icon' => 'success', 'title' => ucfirst('disimpan'), 'html' => "\"<b>{$request->title}</b>\" telah disimpan"],
			'redirect'	=> ['type' => 'assign', 'value' => route('package.index')]
		]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Package $package)
    {
        //
    }
}
