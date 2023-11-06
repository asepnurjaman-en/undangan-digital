<?php

namespace App\Http\Controllers;

use App\Models\Strbox;
use App\Models\Template;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\TemplateAssets;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TemplateController extends Controller
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
			'title'	=> 'template',
			'list'	=> route('template.list'),
			'create'=> ['action' => route('template.create')],
			'delete'=> ['action' => route('template.destroy', 0), 'message' => 'Hapus template?']
		];

		return response()->view('panel.template.index', compact('data'));
    }

    public function list(Request $request): JsonResponse
	{
		$totalFilteredRecord = $totalDataRecord = $draw_val = "";
		$column = [0 => 'id', 1 => 'title', 2 => 'info', 3 => 'log'];
		$totalDataRecord = Template::count();
		$totalFilteredRecord = $totalDataRecord;
		$limit_val	= $request->input('length');
		$start_val	= $request->input('start');
		if (empty($request->input('search.value'))) :
			$datatable = Template::offset($start_val)->orderBy('publish', 'DESC')->latest()->limit($limit_val)->get();
		else :
			$search_text = $request->input('search.value');
			$datatable =  Template::where('id', 'LIKE', "%{$search_text}%")->orWhere('title', 'LIKE', "%{$search_text}%")->offset($start_val)->limit($limit_val)->get();
			$totalFilteredRecord = Template::where('id', 'LIKE', "%{$search_text}%")->orWhere('title', 'LIKE', "%{$search_text}%")->count();
		endif;
		$data_val = [];
		if (!empty($datatable)) :
			foreach ($datatable as $key => $item) :
				$grade = ['title' => Str::title($item->grade), 'basic' => 'bg-gray', 'premium' => 'bg-info', 'exclusive' => 'bg-primary'];
				$publish = ['title' => Str::title($item->publish), 'publish' => 'd-none', 'draft' => 'bg-warning'];
				$data_val[$key]['id'] = null;
				$data_val[$key]['title'] = anchor(text:$item->title, href:route('template.edit', $item->id));
				$data_val[$key]['info'] = "<div class=\"d-flex\"><span class=\"badge me-2 {$grade[$item->grade]}\">{$grade['title']}</span><span class=\"badge {$publish[$item->publish]}\">{$publish['title']}</span></div>";
				$data_val[$key]['log'] = date_info($item->created_at);
			endforeach;
		endif;
		$draw_val = $request->input('draw');
		$get_json_data = ["draw" => intval($draw_val), "recordsTotal" => intval($totalDataRecord), "recordsFiltered" => intval($totalFilteredRecord), "data" => $data_val];

		return response()->json($get_json_data);
	}

	public function component(string $slug = 'avatar'): Response
	{
		$data = [
			'title'	=> $slug,
			'create'=> ['action' => route('template.component.store', $slug)],
			'delete'=> ['action' => route('template.component.destroy', $slug), 'message' => 'Hapus komponen?']
		];
		if ($slug=='avatar') :
			$component = TemplateAssets::select('id', 'type', 'title', 'content')->whereIn('type', ['avatar', 'avatar male', 'avatar female'])->get();
		elseif ($slug=='decoration') :
			$component = TemplateAssets::select('id', 'title', 'content')->whereIn('type', ['decoration'])->get();
		elseif ($slug=='frame') :
			$component = TemplateAssets::select('id', 'title', 'content')->whereIn('type', ['frame'])->get();
		elseif ($slug=='music') :
			$component = TemplateAssets::select('id', 'title', 'content', 'user_id')->with('user')->whereIn('type', ['music'])->get();
		elseif ($slug=='quote') :
			$component = TemplateAssets::select('id', 'title', 'content')->whereIn('type', ['quote'])->get();
		endif;

		return response()->view('panel.template.component', compact('data', 'component'));
	}

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $data = [
			'title'	=> 'tambah template',
			'form' => ['action' => route('template.store'), 'class' => 'form-insert'],
		];

		return response()->view('panel.template.form', compact('data'));
	}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $this->validate($request, [
			'title'		=> 'required|max:110',
			'file_type'	=> 'required',
			'grade'	    => 'required'
		],
		[
			'required'	=> '<code>:attribute</code> harus diisi.',
			'max'		=> '<code>:attribute</code> tidak boleh lebih dari <b>:max</b> huruf.'
		]);
        $column = [
			'title'		=> $request->title,
			'slug'		=> clean_str($request->title),
			'preset'	=> json_encode([]),
			'url'		=> 'no-file',
            'grade'     => $request->grade,
			'publish'	=> 'draft',
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
				$column['file_type'] = 'image';
				// strbox
				Strbox::create(['title' => $request->title, 'file' => $image_name, 'file_type' => 'image', 'user_id' => Auth::user()->id, 'ip_addr' => $_SERVER['REMOTE_ADDR']]);
			endif;
		elseif ($request->file_type == 'image') :
			$this->validate($request, ['file' => 'required'], ['required' => '<code>:attribute</code> harus diisi.']);
			$column['file']	= $request->file;
			$column['file_type'] = 'image';
		endif;
		Template::create($column);

        return response()->json([
			'toast'		=> ['icon' => 'success', 'title' => ucfirst('disimpan'), 'html' => "\"<b>{$request->title}</b>\" telah ditambahkan"],
			'redirect'	=> ['type' => 'assign', 'value' => route('template.index')]
		]);
    }

	public function component_store(Request $request, string $slug = 'avatar'): JsonResponse
	{
		$this->validate($request, [
			'title'	=> 'required|max:110'
		],
		[
			'required'	=> '<code>:attribute</code> harus diisi.',
			'max'		=> '<code>:attribute</code> tidak boleh lebih dari <b>:max</b> huruf.'
		]);
		$column = [
			'title'		=> $request->title,
			'publish'	=> 'publish',
			'user_id'	=> Auth::user()->id,
			'ip_addr'	=> $_SERVER['REMOTE_ADDR']
		];
		if ($slug=='avatar') :
			$this->validate($request, ['file' => 'required|image|mimes:jpg,jpeg,png'], ['mimes' => 'hanya file <b>jpg, jpeg</b> atau <b>png</b> saja.']);
			$file_name = $request->file('file')->hashName();
			Storage::disk('public')->put('avatar/'.$file_name, file_get_contents($request->file('file')));
			$column['type'] = $request->which_gender;
			$column['content'] = $file_name;
		elseif ($slug=='decoration') :
			$this->validate($request, ['file' => 'required|image|mimes:jpg,jpeg,png'], ['mimes' => 'hanya file <b>jpg, jpeg</b> atau <b>png</b> saja.']);
			$file_name = $request->file('file')->hashName();
			Storage::disk('public')->put('decoration/'.$file_name, file_get_contents($request->file('file')));
			$column['type'] = 'decoration';
			$column['content'] = $file_name;
		elseif ($slug=='frame') :
			$this->validate($request, ['file' => 'required|image|mimes:jpg,jpeg,png'], ['mimes' => 'hanya file <b>jpg, jpeg</b> atau <b>png</b> saja.']);
			$file_name = $request->file('file')->hashName();
			Storage::disk('public')->put('frame/'.$file_name, file_get_contents($request->file('file')));
			$column['type'] = 'frame';
			$column['content'] = $file_name;
		elseif ($slug=='music') :
			// $this->validate($request, ['file' => 'required|mimes:application/octet-stream,audio/mpeg,mpga,mp3,wav']);
			$this->validate($request, ['file' => 'required']);
			$file_name = $request->file('file')->hashName();
			Storage::disk('public')->put('audio/'.$file_name, file_get_contents($request->file('file')));
			$column['type'] = 'music';
			$column['content'] = $file_name;
		elseif ($slug=='quote') :
			$column['type'] = 'quote';
			$column['content'] = $request->content;
		endif;
		TemplateAssets::create($column);

		return response()->json([
			'toast'		=> ['icon' => 'success', 'title' => ucfirst('disimpan'), 'html' => "\"<b>{$request->title}</b>\" telah ditambahkan"],
			'redirect'	=> ['type' => 'reload']
		]);
	}

    /**
     * Display the specified resource.
     */
    public function show(Template $template)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Template $template)
    {
		$font = TemplateAssets::select('title', 'content')->whereType('font')->publish()->get();
		$male = TemplateAssets::select('title', 'content')->publish()->whereType('avatar male')->get();
		$female = TemplateAssets::select('title', 'content')->publish()->whereType('avatar female')->get();
		$data = [
			'title'	=> 'edit template',
			'form'	=> ['action' => route('template.update', $template), 'class' => 'form-update'],
			'font'	=> $font,
			'avatar-male' => $male,
			'avatar-female' => $female,
		];
		$template->preset = json_decode($template->preset);

		return response()->view('panel.template.form', compact('data', 'template'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Template $template): JsonResponse
    {
        $this->validate($request, [
			'title'		=> 'required|max:110',
			'file_type'	=> 'required',
			'grade'	    => 'required'
		],
		[
			'required'	=> '<code>:attribute</code> harus diisi.',
			'max'		=> '<code>:attribute</code> tidak boleh lebih dari <b>:max</b> huruf.'
		]);
        $column = [
			'title'		=> $request->title,
			'slug'		=> clean_str($request->title),
            'grade'     => $request->grade,
			'ip_addr'	=> $_SERVER['REMOTE_ADDR'],
			'user_id'	=> Auth::user()->id
		];
		$preset = json_decode($template->preset, true);
		$preset['design']['title']['color'] = $request->title_color;
		$preset['design']['title']['font'] = $request->title_font;
		$preset['design']['content']['color'] = $request->content_color;
		$preset['design']['content']['font'] = $request->content_font;
		$preset['design']['button']['color'] = $request->button_color;
		$preset['design']['button']['background'] = $request->button_background;
		$preset['design']['background'] = $request->background;
		$preset['profile']['photo']['male']['image'] = $request->photo_male;
		$preset['profile']['photo']['female']['image'] = $request->photo_female;
		$column['preset'] = json_encode($preset);
		if ($template->url!='no-file') :
			$column['publish'] = ($request->publish=='publish') ? 'publish' : 'draft';
		endif;
        if ($request->file_type == 'upload-file') :
			$this->validate($request, ['upload_file' => 'required|mimes:jpg,jpeg,png'], ['mimes' => 'hanya file <b>jpg, jpeg</b> atau <b>png</b> saja.']);
			if (!empty($request->file)) :
				$image_name = $request->file('upload_file')->hashName();
				Storage::disk('public')->put($image_name, file_get_contents($request->file('upload_file')));
				image_reducer(file_get_contents($request->file('upload_file')), $image_name);
				$column['file']	= $image_name;
				$column['file_type'] = 'image';
				// strbox
				Strbox::create(['title' => $request->title, 'file' => $image_name, 'file_type' => 'image', 'user_id' => Auth::user()->id, 'ip_addr' => $_SERVER['REMOTE_ADDR']]);
			endif;
		elseif ($request->file_type == 'image') :
			$this->validate($request, ['file' => 'required'], ['required' => '<code>:attribute</code> harus diisi.']);
			$column['file']	= $request->file;
			$column['file_type'] = 'image';
		endif;
		$template->update($column);

        return response()->json([
			'toast'		=> ['icon' => 'success', 'title' => ucfirst('disimpan'), 'html' => "\"<b>{$request->title}</b>\" telah disimpan"],
			'redirect'	=> ['type' => 'assign', 'value' => route('template.index')]
		]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Template $template)
    {
        //
    }

	public function component_destroy(Request $request, string $slug = 'avatar'): JsonResponse
    {
        $ids = explode(',', $request->id);
		$ids_count = count($ids);
		foreach (TemplateAssets::whereIn('id', $ids)->get() as $item) :
			if (in_array($item->type, ['avatar', 'avatar male', 'avatar female'])) :
				if (Storage::disk('public')->exists('avatar/'.$item->content)) :
					Storage::disk('public')->delete('avatar/'.$item->content);
				endif;
			elseif (in_array($item->type, ['decoration'])) :
				if (Storage::disk('public')->exists('decoration/'.$item->content)) :
					Storage::disk('public')->delete('decoration/'.$item->content);
				endif;
			elseif (in_array($item->type, ['frame'])) :
				if (Storage::disk('public')->exists('frame/'.$item->content)) :
					Storage::disk('public')->delete('frame/'.$item->content);
				endif;
			elseif (in_array($item->type, ['music'])) :
				if (Storage::disk('public')->exists('audio/'.$item->content)) :
					Storage::disk('public')->delete('audio/'.$item->content);
				endif;
			endif;
			TemplateAssets::whereId($item->id)->delete();
		endforeach;

		return response()->json([
			'toast'		=> ['icon' => 'success', 'title' => ucfirst('dihapus'), 'html' => "<b>{$ids_count}</b> data telah buang"],
			'redirect'	=> ['type' => 'reload']
		]);
	}
}
