<?php

use Carbon\Carbon;
use Intervention\Image\Image;
use Illuminate\Support\Facades\Storage;

function clean_str(?string $data): string
{ // membuat URl format
	$data = str_replace(' ', '-', strtolower($data));
	return preg_replace('/[^A-Za-z0-9\-]/', '', $data);
}

function check_yt($params = null): string
{ // saring youtube
	$params_splited = explode('/', $params);
	if (in_array($params_splited[2], ['www.youtube.com', 'youtube.com']) && $params_splited[3]=='shorts') :
		$value = $params_splited[4];
	elseif (in_array($params_splited[2], ['www.youtube.com', 'youtube.com']) && $params_splited[3]!='shorts') :
		parse_str(parse_url($params, PHP_URL_QUERY), $data_url);
		$value = $data_url['v'];
	elseif (in_array($params_splited[2], ['youtu.be', 'www.youtu.be'])) :
		$value = $params_splited[3];
	else :
		$value = $params;
	endif;
	return $value;
}

function idr(string $params = null): string
{
	return "<sup class=\"text-muted\">Rp</sup>".number_format($params, 0, ',', '.');
}

function date_info($params = null): string
{
	if (!empty($params)) :
		$value = "<span class=\"d-block text-dark lh-1 small text-nowrap\">{$params->isoFormat('dddd, D MMMM Y')}</span>";
		if (Carbon::parse($params)->diffInDays(Carbon::now()) > 1) :
			$value .= "<small class=\"text-muted\">{$params->locale('id')->isoFormat('HH : mm')} WIB</small>";
		else :
			$value .= "<small>{$params->locale('id')->diffForHumans()}</small>";
		endif;
		return $value;
	else :
		return "-";
	endif;
}

function image_info($params = null): string
{
	return Storage::size($params);
}

function image_reducer($data, string $file_name): void
{
	$size = [
		'xs' => [
			'size'	=> 60,
			'folder'=> '/app/public/xs/'
		],
		'sm' => [
			'size'	=> 280,
			'folder'=> '/app/public/sm/'
		],
		'md' => [
			'size'	=> 650,
			'folder'=> '/app/public/md/'
		]
	];
	// xs start
	$size['xs']['image'] = \Image::make($data);
	$size['xs']['ratio'] = $size['xs']['image']->width() / $size['xs']['size'];
	$size['xs']['width'] = $size['xs']['image']->width() / $size['xs']['ratio'];
	$size['xs']['height'] = $size['xs']['image']->height() / $size['xs']['ratio'];
	$size['xs']['image']->resize($size['xs']['width'], $size['xs']['height'], function($prop) {
		$prop->aspectRatio();
		$prop->upsize();
	});
	$canvas = \Image::canvas($size['xs']['width'], $size['xs']['height']);
	$canvas->insert($size['xs']['image'], 'center');
	$canvas->save(storage_path().$size['xs']['folder'].$file_name);
	// sm start
	$size['sm']['image'] = \Image::make($data);
	$size['sm']['ratio'] = $size['sm']['image']->width() / $size['sm']['size'];
	$size['sm']['width'] = $size['sm']['image']->width() / $size['sm']['ratio'];
	$size['sm']['height'] = $size['sm']['image']->height() / $size['sm']['ratio'];
	$size['sm']['image']->resize($size['sm']['width'], $size['sm']['height'], function($prop) {
		$prop->aspectRatio();
		$prop->upsize();
	});
	$canvas = \Image::canvas($size['sm']['width'], $size['sm']['height']);
	$canvas->insert($size['sm']['image'], 'center');
	$canvas->save(storage_path().$size['sm']['folder'].$file_name);
	// md start
	$size['md']['image'] = \Image::make($data);
	$size['md']['ratio'] = $size['md']['image']->width() / $size['md']['size'];
	$size['md']['width'] = $size['md']['image']->width() / $size['md']['ratio'];
	$size['md']['height'] = $size['md']['image']->height() / $size['md']['ratio'];
	$size['md']['image']->resize($size['md']['width'], $size['md']['height'], function($prop) {
		$prop->aspectRatio();
		$prop->upsize();
	});
	$canvas = \Image::canvas($size['md']['width'], $size['md']['height']);
	$canvas->insert($size['md']['image'], 'center');
	$canvas->save(storage_path().$size['md']['folder'].$file_name);
}

function isitsame($needle, $target): bool
{
	return ($needle==$target) ? true : false;	
}

function isexpired($needle, $days): bool
{
	return (Carbon::parse($needle)->addDays($days) >= now()) ? false : true;
}

function anchor(string $text = null, string $href = "#", array $class = [], array $data = [], bool $new_tab = false): string
{ // <a href>
	$class = implode(' ', $class);
	$new_tab = ($new_tab) ? '_BLANK' : '_SELF';
	$attribute = implode(' ', array_map(
		function ($v, $k) {
			if (is_array($v)) :
				return $k."[]=".implode('&'.$k."[]=", $v);
			else :
				return "data-$k=\"$v\"";
			endif;
		}, 
		$data, 
		array_keys($data)
	));
	return "<a href=\"{$href}\" class=\"{$class}\" target=\"{$new_tab}\" $attribute>{$text}</a>";
}

function image(string $src = null, string $alt = null, array $class = []): string
{ // <img src>
	$class = implode(' ', $class);
	return "<img src=\"{$src}\" alt=\"{$alt}\" class=\"{$class}\"/>";
}

function video(string $src = null, string $ogg = null, array $class = []): string
{
	$class = implode(' ', $class);
	$src_vid = "<source src=\"{$src}\" type=\"video/mp4\">" ?? null;
	$src_ogg = "<source src=\"{$ogg}\" type=\"video/ogg\">" ?? null;
	return "<video width=\"100%\" controls>{$src_vid}{$src_ogg}</video>";
}

function input_check(string $name = null, string $value = null, array $class = [], string $mode = 'single', string $label = null): string
{ // <input type>
	$class = implode(' ', $class);
	$idfor = "check".clean_str($value);
	$mode = ($mode=='single') ? 'radio' : 'checkbox';
	return "<input type=\"{$mode}\" name=\"{$name}\" id=\"{$idfor}\" class=\"{$class}\" value=\"{$value}\"/><label class=\"form-check-label\" for=\"{$idfor}\">{$label}</label>";
}
