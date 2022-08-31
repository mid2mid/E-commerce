<?php

namespace App\Http\Controllers;

use App\Data\JwtData;
use Illuminate\Http\Request;

class ViewMasterController extends Controller
{
	function home(Request $request)
	{
		$data = [
			'page' => 'home',
			'key' => $request->cookie('master'),
		];
		return view('master.home', $data);
	}

	function admin(Request $request)
	{
		$data = [
			'page' => 'admin',
			'key' => $request->cookie('master'),
		];
		return view('master.admin', $data);
	}

	function login(Request $request)
	{
		if ($request->cookie('master')) {
			if (!empty(JwtData::validateMaster($request->cookie('master')))) return redirect()->route('view.master.home');
		}
		$data = [
			// 
		];
		return view('master.login', $data);
	}

	function logout(Request $request)
	{
		return redirect('/master/login')->withoutCookie('master', '/master');
	}
}
