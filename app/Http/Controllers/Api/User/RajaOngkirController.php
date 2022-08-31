<?php

namespace App\Http\Controllers\Api\User;

use App\Data\RajaongkirData;
use App\Data\TextData;
use App\Http\Controllers\Controller;
use App\Models\PesananTunggu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class RajaOngkirController extends Controller
{
	function provinsi(Request $request)
	{
		$validator = Validator::make($request->only(['province_id']), [
			'province_id' => ['numeric', 'min:0'],
		]);
		if ($validator->fails()) return response()->json(TextData::failed(), 400);
		$id = $request->input('province_id') ? '?id=' . $request->input('province_id') : '';
		$rajaongkir = RajaongkirData::getProvince($id);
		if (!$rajaongkir) return response()->json(TextData::failed(), 400);
		if (empty($rajaongkir['results'])) return response()->json(TextData::failed(), 400);
		return response()->json(TextData::ok($rajaongkir['results']));
	}

	function kota(Request $request)
	{
		$validator = Validator::make($request->only(['province_id', 'city_id']), [
			'province_id' => ['numeric', 'min:0'],
			'city_id' => ['numeric', 'min:0'],
		]);
		if ($validator->fails()) return response()->json(TextData::failed(), 400);
		$rajaongkir = RajaongkirData::getCity($request->input('province_id') ?? null, $request->input('city_id') ?? null);
		if (!$rajaongkir) return response()->json(TextData::failed(), 400);
		if (empty($rajaongkir['results'])) return response()->json(TextData::failed(), 400);
		return response()->json(TextData::ok($rajaongkir['results']));
	}

	function cost(Request $request)
	{
		$validator = Validator::make($request->only(['city_id', 'checkout', 'courier']), [
			'checkout' => ['required', 'regex:/^[a-zA-Z0-9]+$/i'],
			'city_id' => ['required', 'numeric', 'min:0'],
			'courier' => ['required', Rule::in(RajaongkirData::getKurir())],
		]);
		if ($validator->fails()) return response()->json(TextData::failed(), 400);
		$checkout = PesananTunggu::where(['id_pesanan_tunggu' => $request->input('checkout')])->first();
		if (empty($checkout)) return response()->json(TextData::failed(), 400);
		$berat = (int)collect($checkout->produk)->sum(function ($product) {
			return $product['jumlah'] * $product['berat'];
		});
		$rajaongkir = RajaongkirData::getCost($request->input('city_id'), $berat, $request->input('courier'));
		if (!$rajaongkir) return response()->json(TextData::failed(), 400);
		$data = Arr::map($rajaongkir['results'][0]['costs'], function ($v, $i) {
			return [
				'paket' => $v['service'],
				'deskripsi' => $v['description'],
				'harga' => $v['cost'][0]['value'],
			];
		});
		return response()->json(TextData::ok($data));
	}
}
