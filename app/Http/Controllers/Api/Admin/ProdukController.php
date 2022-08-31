<?php

namespace App\Http\Controllers\Api\Admin;

use App\Data\RuleData;
use App\Data\TextData;
use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\KategoriList;
use App\Models\Produk;
use App\Services\ProdukService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class ProdukController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Produk $pm, Request $request)
	{
		$validator = Validator::make($request->only(['limit', 'query', 'orderBy', 'sort', 'only']), [
			'limit' => ['numeric', 'min:1'],
			'query' => ['regex:/^[a-zA-Z0-9\s]+$/i', 'min:3', 'max:15'],
			'sort' => [Rule::in(['asc', 'desc'])],
			'orderBy' => [Rule::in(['produk', 'id_produk', 'published_at'])],
			'only' => [Rule::in(['restore'])],
		]);
		if ($validator->fails()) return response()->json(TextData::failed('failed validation'), 400);
		$result = $pm->with('kategori');
		if (!empty($request->input('query'))) {
			foreach (preg_split('/[\s]/', Str::squish($request->input('query'))) as $i => $v) {
				$i == 0 ? $result->where('produk', 'LIKE', '%' . $v . '%') : $result->orWhere('produk', 'LIKE', '%' . $v . '%');
			}
		}
		if ($request->input('only')) {
			$result = $result->onlyTrashed();
		}
		$result = $result->limit($request->input('limit') ?? null)->orderBy($request->input('orderBy') ?? 'created_at', $request->input('sort') ?? 'desc')->get();
		return $result ? response()->json(TextData::ok($result->toArray(), option: ['link' => ["produk" => route('v1.admin.produk.index') . '/id_produk']])) : response()->json(TextData::failed('result not found'), 400);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request, Produk $produk)
	{
		$validator = Validator::make($request->only(['produk', 'deskripsi', 'jumlah', 'harga', 'berat', 'cover', 'publish', 'kategori']), [
			'produk' => RuleData::of()->produkProduk()->get(),
			'deskripsi' => RuleData::of()->produkDeskripsi()->get(),
			'harga' => RuleData::of()->produkHarga()->get(),
			'jumlah' => RuleData::of()->produkJumlah()->get(),
			'berat' => RuleData::of()->produkBerat()->get(),
			'publish' => RuleData::of()->produkPublish()->get(),
			'kategori' => RuleData::of()->produkKategoriArray()->get(),
			'kategori.*' => RuleData::of()->produkKategori()->get(),
			'cover' => RuleData::of()->produkCover()->get(),
		]);
		if ($validator->fails()) return response()->json(TextData::failed('failed validated', $validator->errors()->toArray()), 400);
		if (strtotime($request->input('publish')) < strtotime('-1 hour')) return response()->json(TextData::failed('publish produk invalid'), 400);
		$produk->id_produk = Str::random(3);
		$produk->produk = Str::squish($request->input('produk'));
		$produk->deskripsi = Str::squish($request->input('deskripsi'));
		$produk->jumlah = $request->input('jumlah');
		$produk->harga = $request->input('harga');
		$produk->berat = $request->input('berat');
		$produk->published_at = $request->input('publish');
		if (Produk::where(['produk' => $produk->produk])->withTrashed()->first()) return response()->json(TextData::failed('produk sudah ada'), 400);

		$kategori = collect($request->input('kategori'))->map(function ($item, $key) {
			return ["id_kategori" => $item];
		})->toArray();
		try {
			DB::beginTransaction();
			$cover = $request->file('cover');
			$produk->cover = str_replace('image/produk/' . $produk->id_produk . '/', '', $cover->store('image/produk/' . $produk->id_produk));
			$produk->save();
			$produk->kategoriList()->createMany($kategori);
			DB::commit();
			return response()->json(TextData::ok());
		} catch (Exception $e) {
			DB::rollBack();
			if (!empty($produk->cover)) {
				Storage::deleteDirectory('image/produk/' . $produk->id_produk);
			}
			return response()->json(TextData::failed(), 400);
		}
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show(Produk $produk)
	{
		$result = $produk->toarray();
		$result += ['kategori' => $produk->kategoriList->toArray()];
		return response()->json(TextData::ok($result));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Produk $produk)
	{
		$validator = Validator::make($request->only(['produk', 'deskripsi', 'jumlah', 'harga', 'berat', 'cover', 'kategori']), [
			'produk' => RuleData::of()->produkProduk()->get(),
			'deskripsi' => RuleData::of()->produkDeskripsi()->get(),
			'harga' => RuleData::of()->produkHarga()->get(),
			'jumlah' => RuleData::of()->produkJumlah()->get(),
			'berat' => RuleData::of()->produkBerat()->get(),
			'kategori' => RuleData::of()->produkKategoriArray()->get(),
			'kategori.*' => RuleData::of()->produkKategori()->get(),
			'cover' => RuleData::of()->produkCover()->withoutRequired()->nullable()->get()
		]);
		if ($validator->fails()) return response()->json(TextData::failed('failed validation', $validator->errors()->toArray()), 400);
		$produk->produk = Str::squish($request->input('produk'));
		$produk->deskripsi = $request->input('deskripsi');
		$produk->harga = $request->input('harga');
		$produk->jumlah = $request->input('jumlah');
		$produk->berat = $request->input('berat');
		if ($produk->isDirty('produk')) {
			if (Produk::where(['produk' => $produk->produk])->withTrashed()->first()) return response()->json(TextData::failed('produk sudah ada'), 400);
		}

		$kategori = $produk->kategoriList;

		try {
			$cover = $request->file('cover');
			if (!empty($cover)) $produk->cover = str_replace('image/produk/' . $produk->id_produk . '/', '', $cover->store('image/produk/' . $produk->id_produk));
			$kategoriUpdate = array_diff(array_column($kategori->toArray(), 'id_kategori'), $request->input('kategori')) + array_diff($request->input('kategori'), array_column($kategori->toArray(), 'id_kategori'));
			if ($produk->isClean() && empty($kategoriUpdate)) return response()->json(TextData::failed('no update'), 400);
			$kategori = collect($request->input('kategori'))->map(function ($item) {
				return ["id_kategori" => (int)$item];
			})->toArray();
			DB::beginTransaction();
			$produk->save();
			if (!empty($kategoriUpdate)) {
				$produk->kategoriList()->delete();
				$produk->kategoriList()->createMany($kategori);
			}
			DB::commit();
			return response()->json(TextData::ok());
		} catch (\Throwable $th) {
			if ($produk->isDirty('cover')) {
				Storage::delete("image/produk/$produk->id_produk/$produk->coveer");
			}
			return response()->json(TextData::failed(), 400);
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Produk $produk)
	{
		return $produk->delete() ? response()->json(TextData::ok()) : response()->json(TextData::failed());
	}

	public function restore(Produk $produkTrashed)
	{
		return $produkTrashed->restore() ? response()->json(TextData::ok()) : response()->json(TextData::failed());
	}
}
