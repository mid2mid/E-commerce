<?php

namespace App\Http\Controllers\Api\Admin;

use App\Data\TextData;
use App\Http\Controllers\Controller;
use App\Models\Pesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PesananController extends Controller
{
	function index(Request $request)
	{
		$pesanan = Pesanan::get();
		return response()->json(TextData::ok($pesanan->toArray()));
	}

	function show(Pesanan $pesanan)
	{
		return response()->json(TextData::ok($pesanan->toArray()));
	}

	function update(Request $request, Pesanan $pesanan)
	{

		$validator = Validator::make($request->only(['resi']), [
			'resi' => ['required', 'regex:/^[a-zA-Z0-9]+$/i'],
		]);
		if ($validator->fails()) return response()->json(TextData::failed('failed validation', $validator->errors()->toArray()), 400);
		$pesanan->resi = $request->input('resi');
		if ($pesanan->isClean()) return response()->json(TextData::failed('no update'), 400);
		return $pesanan->save() ? response()->json(TextData::ok()) : response()->json(TextData::failed());
	}
}
