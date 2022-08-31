<?php

namespace App\Http\Controllers;

use App\Data\JwtData;
use App\Models\Admin;
use App\Models\Kategori;
use Illuminate\Http\Request;

class ViewAdminController extends Controller
{
	function home(Request $request)
	{
		$data = [
			'page' => 'home',
			'key' => $request->cookie('admin'),
		];
		return view('admin.home', $data);
	}

	function admin(Request $request)
	{
		$data = [
			'page' => 'admin',
			'key' => $request->cookie('admin'),
		];
		return view('admin.admin', $data);
	}

	function produk(Request $request)
	{
		$data = [
			'page' => 'produk',
			'key' => $request->cookie('admin'),
			'kategori' => Kategori::all(['id_kategori', 'kategori']),
		];
		return view('admin.produk', $data);
	}

	function kategori(Request $request)
	{
		$data = [
			'page' => 'kategori',
			'key' => $request->cookie('admin'),
		];
		return view('admin.kategori', $data);
	}

	function login(Request $request)
	{
		if ($request->cookie('admin')) {
			if (!empty($jwt = JwtData::validateAdmin($request->cookie('admin')))) {
				if (!empty(Admin::where(['token' => $jwt['token']])->first())) return redirect()->route('view.admin.home');
			}
		}
		$data = [
			// 
		];
		return view('admin.login', $data);
	}

	function logout(Request $request)
	{
		return redirect('/admin/login')->withoutCookie('admin', '/admin');
	}

	function pesanan(Request $request)
	{
		$data = [
			'page' => 'pesanan',
			'key' => $request->cookie('admin'),
		];
		return view('admin.pesanan', $data);
	}

	function pelanggan(Request $request)
	{
		$data = [
			'page' => 'pelanggan',
			'key' => $request->cookie('admin'),
		];
		return view('admin.pelanggan', $data);
	}

	function promo(Request $request)
	{
		$data = [
			'page' => 'promo',
			'key' => $request->cookie('admin'),
		];
		return view('admin.promo', $data);
	}

	function laporan(Request $request)
	{
		$data = [
			'page' => 'laporan',
			'key' => $request->cookie('admin'),
		];
		return view('admin.laporan', $data);
	}
}
