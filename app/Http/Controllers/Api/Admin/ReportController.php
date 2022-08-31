<?php

namespace App\Http\Controllers\Api\Admin;

use App\Data\TextData;
use App\Http\Controllers\Controller;
use App\Models\Penjualan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class ReportController extends Controller
{
	function index(Request $request, Penjualan $penjualan)
	{
		$validator = Validator::make($request->only(['start', 'end', 'orderBy', 'sort']), [
			'start' => ['numeric', 'min:1', "nullable"],
			'end' => ['numeric', 'min:1', "nullable"],
			'sort' => [Rule::in(['asc', 'desc'])],
			'orderBy' => [Rule::in(['produk'])],
		]);
		if ($validator->fails()) dd($validator->errors());
		if ($validator->fails()) return response()->json(TextData::failed('failed validation'), 400);
		$laporan = $penjualan;
		if ($request->input("start") && $request->input("start")) $laporan = $laporan->whereBetween('created_at', [Carbon::createFromTimestamp($request->input("start")), Carbon::createFromTimestamp($request->input("end"))]);
		$laporan = $laporan->orderBy($request->input('orderBy') ?? 'created_at', $request->input('sort') ?? 'desc')->get();
		$data = [];
		foreach ($laporan->toArray() as $v) {
			if (isset($data[$v['id_produk']])) {
				$data[$v['id_produk']]["jumlah"] = (int)$data[$v['id_produk']]["jumlah"] + (int)$v['jumlah'];
			} else {
				$data += [
					$v['id_produk'] => [
						"id_produk" => $v['id_produk'],
						"produk" => $v['produk'],
						"jumlah" => (int)$v['jumlah'],
					]
				];
			}
		}
		return !empty($data) ? response()->json(TextData::ok($data, option: ['link' => ["produk" => route('v1.admin.produk.index') . '/id_produk']])) : response()->json(TextData::failed('result not found'), 400);
	}
}
