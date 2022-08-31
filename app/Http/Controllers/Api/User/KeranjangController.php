<?php

namespace App\Http\Controllers\Api\User;

use App\Data\RuleData;
use App\Data\TextData;
use App\Http\Controllers\Controller;
use App\Models\Keranjang;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class KeranjangController extends Controller
{
	function index(Request $request, Keranjang $keranjang)
	{
		$validator = Validator::make($request->only(['user']), [
			'user' => RuleData::of()->userId()->get(),
		]);
		// if ($validator->fails()) dd($validator->errors());
		if ($validator->fails()) return response()->json(TextData::failed(), 400);
		$keranjang = $keranjang->with(['produk' => function ($q) {
			$q->select()->publish()->tersedia();
		}])->where('id_user', $request->input('user'))->where('keranjang', '>', 0)->get();
		if (empty($keranjang)) return response()->json(TextData::failed('Not Found'), 400);
		$data = [
			'produk' => [],
			'details' => [
				'keranjang_harga' => 0,
				'keranjang_berat' => 0,
			],
		];
		foreach ($keranjang as $v) {
			if ($v->produk) {
				$harga = $v->keranjang * $v->produk->harga;
				$berat = $v->keranjang * $v->produk->berat;
				array_push($data['produk'], [
					'id_produk' => $v->produk->id_produk,
					'produk' => $v->produk->produk,
					'cover' => $v->produk->cover,
					'jumlah' => $v->keranjang,
					'harga' => $v->produk->harga,
					'berat' => $v->produk->berat,
					'total_harga' =>  $harga,
					'total_berat' =>  $berat,
				]);
				$data['details']['keranjang_harga'] += $harga;
				$data['details']['keranjang_berat'] += $berat;
			}
		}
		return response()->json(TextData::ok($data));
	}

	function store(Request $request)
	{
		$validator = Validator::make($request->only(['produk', 'user']), [
			'produk' => RuleData::of()->produkId()->get(),
			'user' => RuleData::of()->userId()->get(),
		]);
		if ($validator->fails()) return response()->json(TextData::failed(), 400);
		$keranjang = Keranjang::where(['id_user' => $request->input('user'), 'id_produk' => $request->input('produk')])->first();
		if (!empty($keranjang)) {
			if ($keranjang->keranjang == 0) {
				return Keranjang::where(['id_user' => $request->input('user'), 'id_produk' => $request->input('produk')])->update(['keranjang' => 1]) ? response()->json(TextData::ok()) : response()->json(TextData::failed(), 400);
			}
			return response()->json(TextData::ok());
		}
		return Keranjang::create(['id_user' => $request->input('user'), 'id_produk' => $request->input('produk'), 'keranjang' => 1]) ? response()->json(TextData::ok()) : response()->json(TextData::failed(), 400);
	}

	function set(Request $request)
	{
		$validator = Validator::make($request->only(['produk', 'user', "jumlah"]), [
			'produk' => RuleData::of()->produkId()->get(),
			'user' => RuleData::of()->userId()->get(),
			"jumlah" => ['required', "numeric", "min:0"],
		]);
		if ($validator->fails()) return response()->json(TextData::failed(), 400);
		$keranjang = Keranjang::with(['produk' => function ($q) {
			$q->publish()->tersedia()->first();
		}])->where(['id_user' => $request->input('user'), 'id_produk' => $request->input('produk')])->tersedia()->first();
		if (empty($keranjang) || empty($keranjang->produk)) return response()->json(TextData::failed(), 400);
		$keranjang->keranjang = (int)$request->input('jumlah');
		if (!$keranjang->isDirty("keranjang") || $keranjang->keranjang < 1) return response()->json(TextData::failed("No Update"), 400);
		if ($keranjang->keranjang > $keranjang->produk->jumlah) return response()->json(TextData::failed("Out of Stok. Stok Hanya " . $keranjang->produk->jumlah), 400);
		return Keranjang::where(['id_user' => $request->input('user'), 'id_produk' => $request->input('produk')])->update(['keranjang' => $keranjang->keranjang]) ? response()->json(TextData::ok()) : response()->json(TextData::failed(), 400);
	}

	function tambah(Request $request)
	{
		$validator = Validator::make($request->only(['produk', 'user']), [
			'produk' => RuleData::of()->produkId()->get(),
			'user' => RuleData::of()->userId()->get(),
		]);
		if ($validator->fails()) return response()->json(TextData::failed(), 400);
		$keranjang = Keranjang::with(['produk' => function ($q) {
			$q->publish()->tersedia()->first();
		}])->where(['id_user' => $request->input('user'), 'id_produk' => $request->input('produk')])->tersedia()->first();
		if (empty($keranjang) || empty($keranjang->produk)) return response()->json(TextData::failed(), 400);
		$keranjang->keranjang += 1;
		if ($keranjang->keranjang > $keranjang->produk->jumlah) $keranjang->keranjang = $keranjang->produk->jumlah;
		return Keranjang::where(['id_user' => $request->input('user'), 'id_produk' => $request->input('produk')])->update(['keranjang' => $keranjang->keranjang]) ? response()->json(TextData::ok()) : response()->json(TextData::failed(), 400);
	}

	function kurang(Request $request)
	{
		$validator = Validator::make($request->only(['produk', 'user']), [
			'produk' => RuleData::of()->produkId()->get(),
			'user' => RuleData::of()->userId()->get(),
		]);
		if ($validator->fails()) return response()->json(TextData::failed(), 400);
		$keranjang = Keranjang::with(['produk' => function ($q) {
			$q->publish()->tersedia()->first();
		}])->where(['id_user' => $request->input('user'), 'id_produk' => $request->input('produk')])->tersedia()->first();
		if (empty($keranjang) || empty($keranjang->produk)) return response()->json(TextData::failed(), 400);
		$keranjang->keranjang -= 1;
		if ($keranjang->keranjang < 1) return response()->json(TextData::failed(), 400);
		if ($keranjang->keranjang + 1 > $keranjang->produk->jumlah) $keranjang->keranjang = $keranjang->produk->jumlah;
		return Keranjang::where(['id_user' => $request->input('user'), 'id_produk' => $request->input('produk')])->update(['keranjang' => $keranjang->keranjang]) ? response()->json(TextData::ok()) : response()->json(TextData::failed(), 400);
	}

	function destroy(Request $request, Keranjang $keranjang)
	{
		$validator = Validator::make($request->only(['produk', 'user']), [
			'produk' => RuleData::of()->produkId()->get(),
			'user' => RuleData::of()->userId()->get(),
		]);
		if ($validator->fails()) return response()->json(TextData::failed(), 400);
		if (empty($keranjang->where(['id_user' => $request->input('user'), 'id_produk' => $request->input('produk')])->first())) return response()->json(TextData::failed(), 400);
		return $keranjang->where(['id_user' => $request->input('user'), 'id_produk' => $request->input('produk')])->delete() ? response()->json(TextData::ok()) : response()->json(TextData::failed(), 400);
	}
}
