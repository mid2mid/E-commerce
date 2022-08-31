<?php

namespace App\Http\Controllers;

use App\Data\JwtData;
use App\Data\MidtransData;
use App\Data\RuleData;
use App\Models\Pesanan;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ViewWebController extends Controller
{
	function home(Request $request)
	{
		$data = [
			'page' => 'home',
			'key' => null,
			'user' => null,
		];
		if ($request->cookie('user')) {
			$jwt = JwtData::validateUser($request->cookie('user'));
			if (!empty($jwt)) {
				$data['key'] = $request->cookie('user');
				$data['user'] = [
					'id_user' => $jwt['id_user'],
					'user' => $jwt['user'],
				];
			}
		}
		return view('web.index', $data);
	}

	function invoice(Request $request)
	{
		$validator = Validator::make($request->only(['id']), [
			'id' => ['required', "regex:/^[a-zA-Z0-9']+$/i"],
		]);
		if ($validator->fails()) return abort(404);
		$pesanan = Pesanan::where('id_pesanan', $request->input('id'))->first();
		if (empty($pesanan)) return abort(404);
		if ($pesanan->status !== 'settlement') return abort(404);
		// dd($pesanan->toArray());
		$total = collect($pesanan->produk)->sum(function ($product) {
			return $product['jumlah'] * $product['harga'];
		});
		$total = ($total + $pesanan->ongkir['harga']) - $pesanan->promo;
		$data = [
			'page' => 'invoice',
			'invoice' => $pesanan,
			'total' => $total,
		];
		return view('web.invoice', $data);
	}

	function promo(Request $request)
	{
		$data = [
			'page' => 'promo',
			'key' => null,
			'user' => null,
		];
		if ($request->cookie('user')) {
			$jwt = JwtData::validateUser($request->cookie('user'));
			if (!empty($jwt)) {
				$data['key'] = $request->cookie('user');
				$data['user'] = [
					'id_user' => $jwt['id_user'],
					'user' => $jwt['user'],
				];
			}
		}
		return view('web.promo', $data);
	}

	function produk(Request $request)
	{
		$data = [
			'page' => 'produk',
			'key' => null,
			'user' => null,
			'param' => null,
			'search' => null,
		];
		$validator = Validator::make($request->only(['query', 'page']), [
			'query' => ['required', 'regex:/^[a-zA-Z0-9\s]+$/i', 'min:1'],
			'page' => ['required', 'numeric', 'min:1'],
		]);
		if (!$validator->errors()->has('query')) {
			$data['param'] = "query=" . str_replace(' ', '+', $request->input('query'));
			$data['search'] = $request->input('query');
		}
		if (!$validator->errors()->has('page')) {
			$data['param'] = !empty($data['param']) ? $data['param'] . "&page=" . $request->input('page') : "page=" . $request->input('page');
		}
		if ($request->cookie('user')) {
			$jwt = JwtData::validateUser($request->cookie('user'));
			if (!empty($jwt)) {
				$data['key'] = $request->cookie('user');
				$data['user'] = [
					'id_user' => $jwt['id_user'],
					'user' => $jwt['user'],
				];
			}
		}
		return view('web.produk', $data);
	}

	function wishlist($id, Request $request)
	{
		$data = [
			'page' => 'wishlist',
			'key' => null,
			'user' => null,
			'kategori' => null,
		];
		if ($request->cookie('user')) {
			$jwt = JwtData::validateUser($request->cookie('user'));
			if (!empty($jwt)) {
				$data['key'] = $request->cookie('user');
				$data['user'] = [
					'id_user' => $jwt['id_user'],
					'user' => $jwt['user'],
				];
			}
		}
		return view('web.wishlist', $data);
	}

	function keranjang($id, Request $request)
	{
		$data = [
			'page' => 'keranjang',
			'key' => null,
			'user' => null,
			'kategori' => null,
		];
		if ($request->cookie('user')) {
			$jwt = JwtData::validateUser($request->cookie('user'));
			if (!empty($jwt) && $jwt['id_user'] === $id) {
				$data['key'] = $request->cookie('user');
				$data['user'] = [
					'id_user' => $jwt['id_user'],
					'user' => $jwt['user'],
				];
			}
		}
		return view('web.keranjang', $data);
	}

	function checkout($user, $checkout, Request $request)
	{
		$data = [
			'page' => 'checkout',
			'key' => null,
			'user' => null,
			'checkout' => $checkout,
			'kategori' => null,
			"midtrans" => [
				"snap" => MidtransData::getSnap(),
				"key" => MidtransData::getClientKey(),
			],
			"user" => [
				'id_user' => $request->jwt['id_user'],
				'user' => $request->jwt['user'],
				"email" => $request->jwt['email'],
			],
			"key" => $request->cookie('user'),
		];
		return view('web.checkout', $data);
	}

	function pesanan($user, Request $request)
	{
		$data = [
			'page' => 'pesanan',
			'key' => null,
			'user' => null,
		];
		if ($request->cookie('user')) {
			$jwt = JwtData::validateUser($request->cookie('user'));
			if (!empty($jwt) && $jwt['id_user'] === $user) {
				$data['key'] = $request->cookie('user');
				$data['user'] = [
					'id_user' => $jwt['id_user'],
					'user' => $jwt['user'],
				];
			}
		}
		return view('web.pesanan', $data);
	}

	function profil($user, Request $request)
	{
		$data = [
			'page' => 'profil',
			'key' => null,
			'user' => null,
		];
		if ($request->cookie('user')) {
			$jwt = JwtData::validateUser($request->cookie('user'));
			if (!empty($jwt) && $jwt['id_user'] === $user) {
				$data['key'] = $request->cookie('user');
				$data['user'] = [
					'id_user' => $jwt['id_user'],
					'user' => $jwt['user'],
				];
			}
		}
		return view('web.profil', $data);
	}

	function details($produk, Request $request)
	{
		$produk = Produk::where(['id_produk' => $produk])->tersedia()->publish()->first();
		if (empty($produk)) abort(404);
		$data = [
			'page' => 'produk',
			'key' => null,
			'user' => null,
			'produk' => $produk->toArray(),
		];
		if ($request->cookie('user')) {
			$jwt = JwtData::validateUser($request->cookie('user'));
			if (!empty($jwt)) {
				$data['key'] = $request->cookie('user');
				$data['user'] = [
					'id_user' => $jwt['id_user'],
					'user' => $jwt['user'],
				];
			}
		}
		return view('web.details', $data);
	}

	function login(Request $request)
	{
		$data = [];
		if ($request->cookie('user')) {
			if (!empty(JwtData::validateUser($request->cookie('user')))) return redirect('/');
		}
		return view('web.login', $data);
	}

	function logout()
	{
		return redirect('/login')->withoutCookie('user', '/');
	}

	function register(Request $request)
	{
		$data = [];
		if ($request->cookie('user')) {
			if (!empty(JwtData::validateUser($request->cookie('user')))) return redirect('/');
		}
		return view('web.register', $data);
	}

	function verifikasi(User $userVerified, Request $request)
	{
		$data = [];
		if ($request->cookie('user')) {
			if (!empty(JwtData::validateUser($request->cookie('user')))) return redirect('/');
		}
		$data = [
			'kode' => $request->input('kode'),
			'user' => $userVerified->id_user,
		];
		return view('web.verifikasi', $data);
	}

	function password(Request $request)
	{
		$data = [];
		if ($request->cookie('user')) {
			if (!empty(JwtData::validateUser($request->cookie('user')))) return redirect('/');
		}
		return view('web.password', $data);
	}

	function passwordReset($kode, Request $request)
	{
		$data = [];
		if ($request->cookie('user')) {
			if (!empty(JwtData::validateUser($request->cookie('user')))) return redirect('/');
		}
		$data = [
			'user' => $request->input('user'),
			'kode' => $kode ?? null,
		];
		return view('web.passwordConfirmation', $data);
	}
}
