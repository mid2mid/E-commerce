<?php

namespace App\Http\Controllers\Api\Admin;

use App\Data\RuleData;
use App\Data\TextData;
use App\Http\Controllers\Controller;
use App\Models\Promo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PromoController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Promo $promo, Request $request)
	{
		$validator = Validator::make($request->only(['limit', 'kode', 'orderBy', 'sort']), [
			'limit' => ['numeric', 'min:1'],
			'kode' => ['alpha_num', 'min:3', 'max:8'],
			'sort' => [Rule::in(['asc', 'desc'])],
			'orderBy' => [Rule::in(['kode', 'promo'])],
		]);
		if ($validator->fails()) return response()->json(TextData::failed('erorr validasi'), 400);
		if (!empty($request->input('kode'))) $promo->where('kode', 'LIKE', '%' . $request->input('kode') . '%');
		$promo = $promo->limit($request->input('limit') ?? null)->orderBy($request->input('orderBy') ?? 'created_at', $request->input('sort') ?? 'asc')->get();
		return !empty($promo) ? response()->json(TextData::ok($promo->toArray()), 200) : response()->json(TextData::failed('result not found'), 400);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store(Request $request, Promo $promo)
	{
		$validator = Validator::make($request->only(['promo', 'deskripsi', 'kode', 'persen', 'publish_start', 'max', 'publish_end']), [
			'promo' => RuleData::of()->promoPromo()->get(),
			'deskripsi' => RuleData::of()->promoDeskripsi()->get(),
			'kode' => RuleData::of()->promoKode()->get(),
			'persen' => RuleData::of()->promoPersen()->get(),
			'max' => RuleData::of()->promoMax()->get(),
			'publish_start' => RuleData::of()->promoPublishStart(Carbon::today())->get(),
			'publish_end' => RuleData::of()->promoPublishEnd()->get(),
		]);
		if ($validator->fails()) return response()->json(TextData::failed('validation failed', $validator->errors()->toArray()), 400);
		if ($promo->where('kode', $request->input('kode'))->first()) return response()->json(TextData::failed('kode sudah ada'), 400);
		$promo->promo = Str::squish($request->input('promo'));
		$promo->deskripsi = Str::squish($request->input('deskripsi'));
		$promo->kode = Str::of($request->input('kode'))->squish()->upper()->toString();
		$promo->max = $request->input('max');
		$promo->persen = $request->input('persen');
		$promo->publish_start = Carbon::create($request->input('publish_start'))->toDateString();
		$promo->publish_end = Carbon::create($request->input('publish_end'))->toDateString();
		return $promo->save() ? response()->json(TextData::ok()) : response()->json(TextData::failed(), 400);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function show(promo $promo)
	{
		return response()->json(TextData::ok($promo->toArray()));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, promo $promo)
	{
		$a = Carbon::createFromTimestamp($promo->publish_start);
		$a = Carbon::today()->lessThanOrEqualTo($a) ? Carbon::today() : $a;
		$validator = Validator::make($request->only(['promo', 'deskripsi', 'kode', 'persen', 'publish_start', 'max', 'publish_end']), [
			'promo' => RuleData::of()->promoPromo()->get(),
			'deskripsi' => RuleData::of()->promoDeskripsi()->get(),
			'kode' => RuleData::of()->promoKode()->withoutRequired()->get(),
			'persen' => RuleData::of()->promoPersen()->withoutRequired()->get(),
			'max' => RuleData::of()->promoMax()->withoutRequired()->get(),
			'publish_start' => RuleData::of()->promoPublishStart($a->toDateString())->withoutRequired()->get(),
			'publish_end' => RuleData::of()->promoPublishEnd()->withoutRequired()->get(),
		]);
		if ($validator->fails()) return response()->json(TextData::failed('validation failed', $validator->errors()->toArray()), 400);
		$promo->promo = str::squish($request->input('promo'));
		$promo->deskripsi = str::squish($request->input('deskripsi'));
		if ($promo->status == 'coming soon') {
			$promo->max = $request->input('max');
			$promo->persen = $request->input('persen');
			$promo->kode = Str::upper($request->input('kode'));
			$promo->publish_start = Carbon::createFromDate($request->input('publish_start'))->toDateString() . ' 00:00:00';
			$promo->publish_end = Carbon::createFromDate($request->input('publish_end'))->toDateString() . ' 00:00:00';
		}
		if ($promo->isClean()) return response()->json(TextData::failed('no update'), 400);
		if ($promo->isDirty('kode')) {
			if ($promo->where('kode', $promo->kode)->first()) return response()->json(TextData::failed('kode sudah ada'), 400);
		}
		return $promo->save() ? response()->json(TextData::ok(), 200) : response()->json(TextData::failed(), 400);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy(promo $promo)
	{
		// dd('destroy');
		return $promo->delete() ? response()->json(TextData::ok()) : response()->json(TextData::failed());
	}
}
