<?php

namespace App\Http\Controllers\Api\Admin;

use App\Data\RuleData;
use App\Data\TextData;
use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class KategoriController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request, Kategori $kategori)
	{
		$res = $kategori->orderBy('kategori')->get();
		return $res ? response()->json(TextData::ok($res->toArray())) : response()->json(TextData::failed(), 400);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request, Kategori $kategori)
	{
		$validator = Validator::make($request->only(['kategori', 'deskripsi']), [
			'kategori' => RuleData::of()->KategoriKategori()->get(),
			'deskripsi' => RuleData::of()->KategoriDeskripsi()->withoutRequired()->nullable()->get(),
		]);
		if ($validator->fails()) return response()->json(TextData::failed('failed validation', $validator->errors()->toArray()), 400);
		$kategori->kategori = Str::squish($request->input('kategori'));
		$kategori->deskripsi = Str::squish($request->input('deskripsi'));
		if (Kategori::where(['kategori' => $kategori->kategori])->first()) return response()->json(TextData::failed('kategori sudah ada'), 400);
		return $kategori->save() ? response()->json(TextData::ok(), 200) : response()->json(TextData::failed(), 400);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show(Kategori $kategori)
	{
		return response()->json(TextData::ok($kategori->toArray()));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, Kategori $kategori)
	{
		$validator = Validator::make($request->only(['kategori', 'deskripsi']), [
			'kategori' => RuleData::of()->KategoriKategori()->get(),
			'deskripsi' => RuleData::of()->KategoriDeskripsi()->withoutRequired()->nullable()->get(),
		]);
		if ($validator->fails()) return response()->json(TextData::failed('failed validation', $validator->errors()->toArray()), 400);
		$kategori->kategori = Str::squish($request->input('kategori'));
		$kategori->deskripsi = Str::squish($request->input('deskripsi'));
		if ($kategori->isClean()) return response()->json(TextData::failed('no update'), 400);
		if ($kategori->isDirty('kategori')) {
			if (Kategori::where('kategori', $kategori->kategori)->first()) return response()->json(TextData::failed('kategori sudah ada'), 400);
		}
		return $kategori->save() ? response()->json(TextData::ok()) : response()->json(TextData::failed());
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(Kategori $kategori)
	{
		return $kategori->delete() ? response()->json(TextData::ok()) : response()->json(TextData::failed());
	}
}
