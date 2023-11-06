<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(): Response
    {
        $data = [
            'title' => 'dashboard',
        ];

        return response()->view('home', compact('data'));
    }

    public function info(string $slug): Response
	{
		return response()->view('info');
	}
}
