<?php

namespace App\Http\Controllers;

use App\Models\LinkExternal;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class LinkExternalController extends Controller
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
	
	public function social(): Response
	{
		$social = LinkExternal::whereType('social')->orderBy('brand', 'ASC')->get();
		$data = [
			'title'	=> 'media sosial',
			'form'	=> ['action' => route('social.update'), 'class' => 'form-update']];

		return response()->view('panel.links.social', compact('data', 'social'));
	}

	public function update_social(Request $request): JsonResponse
	{
		$brand = [];
		foreach (LinkExternal::whereType('social')->get() as $item) :
			$edit = "edit_".$item->brand.$item->id;
			$url = "url_".$item->brand.$item->id;
			$active = "active_".$item->brand.$item->id;
			if ($request->{$edit} == 'true') :
				$brand[$item->id] = ucwords($item->brand);
				$links = LinkExternal::whereId($item->id)->firstOrFail();
				$links->update(['actived' => ($request->{$active}=='true') ? '1' : '0', 'url' => $request->{$url}]);
			endif;
		endforeach;
		$brand_saved = implode(', ', $brand);

		return response()->json([
			'toast'		=> ['icon' => 'success', 'title' => ucfirst('disimpan'), 'html' => "Perubahan <b>{$brand_saved}</b> disimpan"],
			'redirect'	=> ['type' => 'reload']
		]);
	}

	public function ecommerce(): Response
	{
		$ecommerce = LinkExternal::whereType('ecommerce')->orderBy('brand', 'ASC')->get();
		$data = [
			'title'	=> 'toko online',
			'form'	=> ['action' => route('ecommerce.update'), 'class' => 'form-update']
		];

		return response()->view('panel.links.ecommerce', compact('data', 'ecommerce'));
	}

	public function update_ecommerce(Request $request): JsonResponse
	{
		$brand = [];
		foreach (LinkExternal::whereType('ecommerce')->get() as $item) :
			$edit = "edit_".$item->brand.$item->id;
			$url = "url_".$item->brand.$item->id;
			$active = "active_".$item->brand.$item->id;
			if ($request->{$edit} == 'true') :
				$brand[$item->id] = ucwords($item->brand);
				$links = LinkExternal::whereId($item->id)->firstOrFail();
				$links->update(['actived' => ($request->{$active}=='true') ? '1' : '0', 'url' => $request->{$url}]);
			endif;
		endforeach;
		$brand_saved = implode(', ', $brand);

		return response()->json([
			'toast'		=> ['icon' => 'success', 'title' => ucfirst('disimpan'), 'html' => "Perubahan <b>{$brand_saved}</b> disimpan"],
			'redirect'	=> ['type' => 'reload']
		]);
	}
}
