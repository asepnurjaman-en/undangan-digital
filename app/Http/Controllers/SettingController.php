<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Strbox;
use App\Models\Setting;
use App\Models\Template;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\AccountInvoice;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;

class SettingController extends Controller
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
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Contracts\Support\Renderable
	 */
	public function dashboard(Request $request): Response
	{
		$hour = Carbon::now()->format('H');
		$greating = "<span class=\"fs-3\">Hai <b class=\"text-primary\">";
		$greating .= Auth::user()->name;
		$greating .= "</b></span>,<br><span class=\"fs-5\">";
		if ($hour >= 5 && $hour < 11) :
			$greating .= "Selamat pagi";
		elseif ($hour >= 11 && $hour < 15) :
			$greating .= "Selamat siang";
		elseif ($hour >= 15 && $hour < 18) :
			$greating .= "Selamat sore";
		elseif ($hour >= 18 && $hour < 00) :
			$greating .= "Selamat Malam";
		else :
			$greating .= "Masih dini hari, gunakan waktu sebaik mungkin untuk istirahat";
		endif;
		$greating .= "</span>.";
		$transaction = [
			[
				'icon'	=> 'bx bx-user-circle',
				'title'	=> 'menunggu pembayaran',
				'url'	=> route('invoice-transaction.index'),
				'data'	=> AccountInvoice::whereStatus('PENDING')->count()
			],
			[
				'icon'	=> 'bx bx-box',
				'title'	=> 'menunggu konfirmasi',
				'url'	=> route('invoice-transaction.index'),
				'data'	=> AccountInvoice::whereStatus('PENDING')->count()
			]
		];
		$dashboard = [
			[
				'icon'	=> 'bx bx-user-circle',
				'title'	=> 'undangan',
				'url'	=> route('member.index'),
				'data'	=> Invitation::count()
			],
			[
				'icon'	=> 'bx bx-box',
				'title'	=> 'template',
				'url'	=> route('template.index'),
				'data'	=> Template::count()
			]
		];
		$data = ['title' => 'Dasbor'];

		return response()->view('panel.index', compact('data', 'greating', 'dashboard', 'transaction'));
	}

    //** Storage */ 

    public function storage(string $type = 'image'): Response
	{
		$data = [
			'title'	=> 'penyimpanan',
			'list'	=> route('home.storage-list', $type),
			'delete'=> ['action' => route('home.storage-delete', 0), 'message' => 'Hapus dari penyimpanan?'],
			'form'	=> ['action' => route('home.storage-store', $type), 'class' => 'form-insert']
		];
		$view = 'panel.storage.';
		if ($type=='image') :
			$view .= 'image';
		elseif ($type=='video') :
			$view .= 'video';
		endif;

		return response()->view($view, compact('data'));
	}

	public function storage_list(string $type = 'image', Request $request): JsonResponse
	{
		$totalFilteredRecord = $totalDataRecord = $draw_val = "";
		$column = [0 => 'id', 1 => 'title', 2 => 'info', 3 => 'log'];
		$totalDataRecord = Strbox::where('file_type', $type)->count();
		$totalFilteredRecord = $totalDataRecord;
		$limit_val	= $request->input('length');
		$start_val	= $request->input('start');
		if (empty($request->input('search.value'))) :
			$datatable = Strbox::where('file_type', $type)->offset($start_val)->latest()->limit($limit_val)->get();
		else :
			$search_text = $request->input('search.value');
			$datatable =  Strbox::where('file_type', $type)->where('id', 'LIKE', "%{$search_text}%")->orWhere('title', 'LIKE', "%{$search_text}%")->offset($start_val)->limit($limit_val)->get();
			$totalFilteredRecord = Strbox::where('file_type', $type)->where('id', 'LIKE', "%{$search_text}%")->orWhere('title', 'LIKE', "%{$search_text}%")->count();
		endif;
		$data_val = [];
		if (!empty($datatable)) :
			foreach ($datatable as $key => $item) :
				$data_val[$key]['id'] = input_check(name:"check[]", value:$item->id, class:['form-check-input', 'check-row'], mode:'multiple');
				$data_val[$key]['title'] = anchor(text:$item->title, href:route('home.storage-edit', $item->id));
				$data_val[$key]['info'] = null;
				$data_val[$key]['log'] = date_info($item->created_at);
			endforeach;
		endif;
		$draw_val = $request->input('draw');
		$get_json_data = ["draw" => intval($draw_val), "recordsTotal" => intval($totalDataRecord), "recordsFiltered" => intval($totalFilteredRecord), "data" => $data_val];

		return response()->json($get_json_data);
	}

	public function storage_store(string $type = 'image', Request $request): JsonResponse
	{
		$this->validate($request, [
			'title'	=> 'required|max:110'
		],
		[
			'required'	=> '<code>:attribute</code> harus diisi.',
			'max'		=> '<code>:attribute</code> tidak boleh lebih dari <b>:max</b> huruf.'
		]);
		if ($type=='image') :
			$this->validate($request, ['file' => 'required|image|mimes:jpg,jpeg,png'], ['mimes' => 'hanya file <b>jpg, jpeg</b> atau <b>png</b> saja.']);
			$file_name = $request->file('file')->hashName();
			Storage::disk('public')->put($file_name, file_get_contents($request->file('file')));
			image_reducer(file_get_contents($request->file('file')), $file_name);
		elseif ($type=='video') :
			$this->validate($request, ['file' => 'required|mimes:mp4|max:20480'], ['mimes' => 'hanya file <b>mp4</b> saja.', 'max' => 'maksimal ukuran file adalah <b>20Mb</b>']);
			$file_name = "video-".$request->file('file')->hashName();
			Storage::disk('public')->put($file_name, file_get_contents($request->file('file')));
		endif;
		$column = [
			'title'		=> $request->title,
			'file'		=> $file_name,
			'file_type'	=> $type,
			'user_id'	=> Auth::user()->id,
			'ip_addr'	=> $_SERVER['REMOTE_ADDR']
		];
		Strbox::create($column);

		return response()->json([
			'toast'		=> ['icon' => 'success', 'title' => ucfirst('disimpan'), 'html' => "\"<b>{$request->title}</b>\" telah ditambahkan"],
			'redirect'	=> ['type' => 'dataTables']
		]);
	}

	public function storage_delete(Request $request, $id): JsonResponse
	{
		$ids = explode(',', $request->id);
		$ids_count = count($ids);
		foreach (Strbox::whereIn('id', $ids)->get() as $item) :
			Storage::delete('public/'.$item->file, 'public/md/'.$item->file, 'public/sm/'.$item->file, 'public/xs/'.$item->file);
			Strbox::whereId($item->id)->delete();
		endforeach;

		return response()->json([
			'toast'		=> ['icon' => 'success', 'title' => ucfirst('dihapus'), 'html' => "<b>{$ids_count}</b> data telah buang"],
			'redirect'	=> ['type' => 'dataTables']
		]);
	}

	public function storage_edit(string $id): Response
	{
		$strbox = Strbox::findOrFail($id);
		$data = [
			'title'	=> 'penyimpanan',
			'form'	=> ['action' => route('home.storage-update', $id), 'class' => 'form-update']
		];

		return response()->view('panel.storage.form', compact('data', 'strbox'));
	}

	public function storage_update(Request $request, string $id): JsonResponse
	{
		$this->validate($request, [
			'title'		=> 'required|max:110',
		],
		[
			'required'	=> '<code>:attribute</code> harus diisi.',
			'max'		=> '<code>:attribute</code> tidak boleh lebih dari <b>:max</b> huruf.'
		]);

		$column = [
			'title'		=> $request->title,
			'ip_addr'	=> $_SERVER['REMOTE_ADDR'],
			'user_id'	=> Auth::user()->id
		];
		$strbox = Strbox::findOrFail($id);
		$strbox->update($column);

		return response()->json([
			'toast'		=> ['icon' => 'success', 'title' => ucfirst('disimpan'), 'html' => "\"<b>{$request->title}</b>\" telah disimpan"],
			'redirect'	=> ['type' => 'assign', 'value' => route('home.storage', $strbox->file_type)]
		]);
	}

	public function storage_modal(Request $request, string $mode = 'single'): JsonResponse
	{
		$type = 'image';
		$totalFilteredRecord = $totalDataRecord = $draw_val = "";
		$column = [0 => 'id', 1 => 'title', 2 => 'info', 3 => 'log'];
		$totalDataRecord = Strbox::where('file_type', $type)->count();
		$totalFilteredRecord = $totalDataRecord;
		$limit_val	= $request->input('length');
		$start_val	= $request->input('start');
		if (empty($request->input('search.value'))) :
			$datatable = Strbox::where('file_type', $type)->offset($start_val)->latest()->limit($limit_val)->get();
		else :
			$search_text = $request->input('search.value');
			$datatable =  Strbox::where('file_type', $type)->where('id', 'LIKE', "%{$search_text}%")->orWhere('title', 'LIKE', "%{$search_text}%")->offset($start_val)->latest()->limit($limit_val)->get();
			$totalFilteredRecord = Strbox::where('file_type', $type)->where('id', 'LIKE', "%{$search_text}%")->orWhere('title', 'LIKE', "%{$search_text}%")->count();
		endif;
		$data_val = [];
		if (!empty($datatable)) :
			foreach ($datatable as $key => $item) :
				$data_val[$key]['id'] = input_check(name:"check[$mode]", mode:$mode, value:$item->id, class:['check-image'], label:image(src:url('storage/sm/'.$item->file), alt:"gambar", class:['img-fluid', 'img-fit']));
				$data_val[$key]['title'] = "<label for=\"check{$item->id}\">{$item->title}</label>";
				$data_val[$key]['info'] = null;
				$data_val[$key]['log'] = date_info($item->created_at);
			endforeach;
		endif;
		$draw_val = $request->input('draw');
		$get_json_data = ["draw" => intval($draw_val), "recordsTotal" => intval($totalDataRecord), "recordsFiltered" => intval($totalFilteredRecord), "data" => $data_val];

		return response()->json($get_json_data);
	}

	public function put_storage_modal(Request $request, string $mode = 'single'): JsonResponse
	{
		$id = explode(',', $request->id);
		$data = [];
		if ($mode == 'single') :
			foreach (Strbox::whereIn('id', $id)->get() as $key => $item) :
				$data[$key]['title'] = $item->title;
				$data[$key]['file'] = $item->file;
				$data[$key]['url'] = url('storage/sm/'.$item->file);
			endforeach;
		elseif ($mode == 'multiple') :
			foreach (Strbox::whereIn('id', $id)->get() as $key => $item) :
				$data[$key]['title'] = $item->title;
				$data[$key]['file'] = $item->file;
				$data[$key]['url'] = url('storage/sm/'.$item->file);
			endforeach;
		endif;
		
		return response()->json($data);
	}

	public function from_youtube(Request $request): JsonResponse
	{
		$youtube = check_yt($request->id);
		$data = [['title' => "Youtube", 'file' => $youtube, 'url' => "https://img.youtube.com/vi/{$youtube}/hqdefault.jpg"]];

		return response()->json($data);
	}

    //** Setting */

	public function account(string $tab = 'account'): Response
	{
		$data = ['title' => 'akun saya'];
		$tablist = [
			['url' => route('setting.account', 'profile'), 'icon' => 'bx bx-user', 'title' => 'akun saya'],
			['url' => route('setting.account', 'password'), 'icon' => 'bx bx-lock', 'title' => 'kata sandi']
		];
		if ($tab == 'profile') :
			$data['form'] = ['action' => route('setting.profile.update'), 'class' => 'form-update'];
		elseif ($tab == 'password') :
			$data['form'] = ['action' => route('setting.password.update'), 'class' => 'form-update'];
		endif;

		return response()->view('panel.setting.'.$tab, compact('data', 'tablist'));
	}

	public function profile_update(Request $request): JsonResponse
	{
		$this->validate($request, [
			'name'	=> 'required',
			'email'	=> 'required|email'
		],
		[
			'required'	=> '<code>:attribute</code> harus diisi.',
			'email'		=> 'format email tidak tepat.'
		]);
		$column = [
			'name'	=> $request->name,
			'email'	=> $request->email
		];
		$user = User::findOrFail(Auth::user()->id);
		$user->update($column);

		return response()->json([
			'toast'		=> ['icon' => 'success', 'title' => ucfirst('disimpan'), 'html' => "<b>Profil</b> telah disimpan"],
			'redirect'	=> ['type' => 'nothing']
		]);
	}

	public function password_update(Request $request): JsonResponse
	{
		$this->validate($request, [
			'password'		=> 'required|confirmed',
			'password_old'	=> 'required'
		],
		[
			'required'			=> '<code>:attribute</code> harus diisi.',
			'confirmed'			=> 'kata sandi lama tidak sesuai.'
		]);
		if (!Hash::check($request->password_old, Auth::user()->password)) {
			return response()->json([
				'toast'		=> ['icon' => 'error', 'title' => ucfirst('galat'), 'html' => "Kata sandi lama tidak sesuai."],
				'redirect'	=> ['type' => 'nothing']
			]);
		}
		User::whereId(Auth::user()->id)->update(['password' => Hash::make($request->password_new)]);

		return response()->json([
			'toast'		=> ['icon' => 'success', 'title' => ucfirst('disimpan'), 'html' => "<b>Kata sandi</b> baru telah disimpan"],
			'redirect'	=> ['type' => 'reload']
		]);
	}

	public function site(string $tab = 'site'): Response
	{
		$data = ['title' => 'pengaturan'];
		$tablist = [
			['url' => route('setting.site', 'site'), 'icon' => 'bx bx-world', 'title' => 'website'],
			['url' => route('setting.site', 'icon'), 'icon' => 'bx bx-message-square-dots', 'title' => 'icon'],
			['url' => route('setting.site', 'logo'), 'icon' => 'bx bx-message-square', 'title' => 'logo'],
			['url' => route('setting.site', 'meta'), 'icon' => 'bx bx-code', 'title' => 'meta'],
			['url' => route('setting.site', 'maintenance'), 'icon' => 'bx bx-wrench', 'title' => 'pemeliharaan']
		];
		if ($tab == 'site') :
			$data['form'] = ['action' => route('setting.site.update'), 'class' => 'form-update'];
		elseif ($tab == 'icon') :
			$data['form'] = ['action' => route('setting.icolo.update', 2), 'class' => 'form-update'];
		elseif ($tab == 'logo') :
			$data['form'] = ['action' => route('setting.icolo.update', 3), 'class' => 'form-update'];
		elseif ($tab == 'meta') :
			$data['form'] = ['action' => route('setting.meta.update'), 'class' => 'form-update'];
		elseif ($tab == 'maintenance') :
			$data['form'] = ['action' => route('setting.maintenance.update'), 'class' => 'form-update'];
		endif;

		return response()->view('panel.setting.site', compact('data', 'tablist'));
	}

	public function site_update(Request $request): JsonResponse
	{
		$this->validate($request, [
			'title'	=> 'required',
			'color'	=> 'required'
		],
		[
			'required'	=> '<code>:attribute</code> harus diisi.',
		]);
		$title = Setting::findOrFail(1);
		$title->update(['content'=>$request->title]);
		$color = Setting::findOrFail(4);
		$color->update(['content'=>$request->color]);

		return response()->json([
			'toast'		=> ['icon' => 'success', 'title' => ucfirst('disimpan'), 'html' => "Pengaturan telah disimpan"],
			'redirect'	=> ['type' => 'nothing']
		]);
	}

	public function icolo_update(Request $request, string $id): JsonResponse
	{
		$this->validate($request, [
			'file_type'	=> 'required',
		],
		[
			'required'	=> '<code>:attribute</code> harus diisi.',
		]);
		$column = [
			'ip_addr'	=> $_SERVER['REMOTE_ADDR'],
			'user_id'	=> Auth::user()->id
		];
		$icolo = Setting::findOrFail($id);
		if ($request->file_type == 'upload-file') :
			$this->validate($request, ['upload_file' => 'required|mimes:jpg,jpeg,png'], ['mimes' => 'hanya file <b>jpg, jpeg</b> atau <b>png</b> saja.']);
			if (!empty($request->file)) :
				$image_name = $request->file('upload_file')->hashName();
				Storage::disk('public')->put($image_name, file_get_contents($request->file('upload_file')));
				image_reducer(file_get_contents($request->file('upload_file')), $image_name);
				$column['content']	= $image_name;
				// strbox
				Strbox::create(['title' => Str::title($icolo->type), 'file' => $image_name, 'file_type' => 'image', 'user_id' => Auth::user()->id, 'ip_addr' => $_SERVER['REMOTE_ADDR']]);
			endif;
		elseif ($request->file_type == 'image') :
			$this->validate($request, ['file' => 'required'], ['required' => '<code>:attribute</code> harus diisi.']);
			$column['content']	= $request->file;
		endif;
		$icolo->update($column);

		return response()->json([
			'toast'		=> ['icon' => 'success', 'title' => ucfirst('disimpan'), 'html' => "Pengaturan telah disimpan"],
			'redirect'	=> ['type' => 'nothing']
		]);
	}

	public function meta_update(Request $request): JsonResponse
	{
		$this->validate($request, [
			'keywords'		=> 'required',
			'description'	=> 'required'
		],
		[
			'required'	=> '<code>:attribute</code> harus diisi.',
		]);
		$description = Setting::findOrFail(5);
		$description->update(['content'=>$request->description]);
		$keywords = Setting::findOrFail(6);
		$keywords->update(['content'=>$request->keywords]);

		return response()->json([
			'toast'		=> ['icon' => 'success', 'title' => ucfirst('disimpan'), 'html' => "Pengaturan telah disimpan"],
			'redirect'	=> ['type' => 'nothing']
		]);
	}

	public function maintenance_update(Request $request): JsonResponse
	{
		$request->maintenance = ($request->maintenance=='on') ? 'on' : 'off';
		$maintenance = Setting::findOrFail(7);
		$maintenance->update(['content'=>$request->maintenance]);

		return response()->json([
			'toast'		=> ['icon' => 'success', 'title' => ucfirst('disimpan'), 'html' => "Pengaturan telah disimpan"],
			'redirect'	=> ['type' => 'nothing']
		]);
	}

	public function log_activity(): Response
	{
		$data = [
			'title' => 'pengaturan',
			'list'	=> route('setting.log_activity.list'),
			'delete'=> ['action' => route('setting.log_activity.clear'), 'message' => 'Hapus semua log?']
		];

		return response()->view('panel.setting.log', compact('data'));
	}

	public function log_list(Request $request): JsonResponse
	{
		$totalFilteredRecord = $totalDataRecord = $draw_val = "";
		$column = [0 => 'id', 1 => 'title', 2 => 'info', 3 => 'log'];
		$totalDataRecord = Activity::count();
		$totalFilteredRecord = $totalDataRecord;
		$limit_val	= $request->input('length');
		$start_val	= $request->input('start');
		if (empty($request->input('search.value'))) :
			$datatable = Activity::offset($start_val)->latest()->limit($limit_val)->get();
		else :
			$search_text = $request->input('search.value');
			$datatable =  Activity::where('id', 'LIKE', "%{$search_text}%")->orWhere('subject_type', 'LIKE', "%{$search_text}%")->offset($start_val)->limit($limit_val)->get();
			$totalFilteredRecord = Activity::where('id', 'LIKE', "%{$search_text}%")->orWhere('subject_type', 'LIKE', "%{$search_text}%")->count();
		endif;
		$data_val = [];
		if (!empty($datatable)) :
			foreach ($datatable as $key => $item) :
				$icon = ($item->event=='updated') ? '<i class="bx bxs-message-alt-edit text-warning me-1"></i>' : '<i class="bx bxs-message-alt-add text-success me-1"></i>';
				$subject = explode('\\', $item->subject_type);
				$item->title = "<u>{$subject[2]}</u> ".$item->event;
				$data_val[$key]['id'] = $icon;
				$data_val[$key]['title'] = anchor(text:$item->title, href:route('setting.log_activity.show', $item->id));
				$data_val[$key]['info'] = "<code>ID: {$item->subject_id}</code>";
				$data_val[$key]['log'] = "<u>".date('j M Y h:i a', strtotime($item->created_at))."</u>";
			endforeach;
		endif;
		$draw_val = $request->input('draw');
		$get_json_data = ["draw" => intval($draw_val), "recordsTotal" => intval($totalDataRecord), "recordsFiltered" => intval($totalFilteredRecord), "data" => $data_val];

		return response()->json($get_json_data);
	}

	public function log_detail(string $id): Response
	{
		$activity = Activity::findOrFail($id);
		$activity->subject_type = explode('\\', $activity->subject_type);
		$causer = User::select('name')->whereId($activity->causer_id)->firstOr(function() {
			return ['name' => '<i>user deleted</i>'];
		});
		$data = [
			'title' => 'pengaturan'
		];

		return response()->view('panel.setting.log-detail', compact('data', 'activity', 'causer'));
	}

	public function log_clear(Request $request): JsonResponse
	{
		Activity::truncate();

		return response()->json([
			'toast'		=> ['icon' => 'success', 'title' => ucfirst('dihapus'), 'html' => "Semua data telah buang"],
			'redirect'	=> ['type' => 'dataTables']
		]);
	}
}
