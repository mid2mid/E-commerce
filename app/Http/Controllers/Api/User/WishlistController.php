<?php

namespace App\Http\Controllers\Api\User;

use App\Data\RuleData;
use App\Data\TextData;
use App\Http\Controllers\Controller;
use App\Models\Produk;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WishlistController extends Controller
{
	function index(Request $request, Wishlist $wishlist)
	{
		$validator = Validator::make($request->only(['user']), [
			'user' => RuleData::of()->userId()->get(),
		]);
		if ($validator->fails()) return response()->json(TextData::failed(), 400);
		$wishlist = Wishlist::with('produk')->where('id_user', $request->input('user'))->get();
		if (empty($wishlist)) return response()->json(TextData::failed(), 400);
		$data = [];
		foreach ($wishlist as $v) {
			if ($v->produk) {
				array_push($data, ['id_produk' => $v->produk->id_produk, 'produk' => $v->produk->produk, 'cover' => $v->produk->cover]);
			}
		}
		return response()->json(TextData::ok($data));
	}

	function store(Request $request, Wishlist $wishlist)
	{
		$validator = Validator::make($request->only(['produk', 'user']), [
			'produk' => RuleData::of()->produkId()->get(),
			'user' => RuleData::of()->userId()->get(),
		]);
		if ($validator->fails()) return response()->json(TextData::failed(), 400);
		if (!empty($wishlist->where(['id_produk' => $request->input('produk'), 'id_user' => $request->input('user')])->first())) return response()->json(TextData::ok());
		return $wishlist->insert(['id_produk' => $request->input('produk'), 'id_user' => $request->input('user')]) ? response()->json(TextData::ok()) : response()->json(TextData::failed(), 400);
	}

	function destroy(Request $request, Wishlist $wishlist)
	{
		$validator = Validator::make($request->only(['produk', 'user']), [
			'produk' => RuleData::of()->produkId()->get(),
			'user' => RuleData::of()->userId()->get(),
		]);
		if ($validator->fails()) return response()->json(TextData::failed(), 400);
		if (empty($wishlist = $wishlist->where(['id_produk' => $request->input('produk'), 'id_user' => $request->input('user')]))) response()->json(TextData::failed(), 400);
		return $wishlist->delete() ? response()->json(TextData::ok()) : response()->json(TextData::failed(), 400);
	}
}
