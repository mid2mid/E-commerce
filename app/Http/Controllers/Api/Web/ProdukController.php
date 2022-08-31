<?php

namespace App\Http\Controllers\Api\Web;

use App\Data\TextData;
use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class ProdukController extends Controller
{
	function index(Produk $pm, Request $request)
	{
		$validator = Validator::make($request->only(['query', 'orderBy', 'sort', 'page']), [
			'query' => ['regex:/^[a-zA-Z0-9\s]+$/i', 'min:1', 'max:15'],
			'page' => ['numeric', 'min:1'],
			'sort' => [Rule::in(['asc', 'desc'])],
			'orderBy' => [Rule::in(['produk', 'id_produk', 'published_at'])],
		]);
		if ($validator->fails()) return response()->json(TextData::failed('failed validation'), 400);
		$result = $pm->publish()->tersedia();
		$query = '';
		if (!empty($request->input('query'))) {
			foreach (preg_split('/[\s]/', Str::squish($request->input('query'))) as $i => $v) {
				$result = $i == 0 ? $result->where('produk', 'LIKE', '%' . $v . '%') : $result->orWhere('produk', 'LIKE', '%' . $v . '%');
			}
			$query = "&query=" . Str::squish($request->input('query'));
		}
		$result = $result->orderBy($request->input('orderBy') ?? 'created_at', $request->input('sort') ?? 'desc')->paginate(12, ['id_produk', 'cover', 'produk', 'harga', 'jumlah'], 'produk', $request->input('page') ?? 1);
		if (!$result) return response()->json(TextData::ok(pesan: 'result not found'), 200);
		return response()->json(TextData::ok($result->makeHidden(['gambar'])->toArray(), option: [
			'next' => $result->nextPageUrl() ? "/produk?page=" . $result->currentPage() + 1 . $query : null,
			'prev' => $result->previousPageUrl() ? "/produk?page=" . $result->currentPage() - 1 . $query : null,
		]));
	}

	function rekomendasi(Produk $produk)
	{
		$produk = $produk->select(['id_produk', 'produk', 'harga', 'cover'])->publish()->tersedia()->inRandomOrder()->limit(4)->get();
		return $produk ? response()->json(TextData::ok($produk->toArray())) : response()->json(TextData::failed(), 400);
	}
}
