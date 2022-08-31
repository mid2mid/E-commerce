<?php

namespace App\Http\Controllers\Api\Web;

use App\Data\TextData;
use App\Http\Controllers\Controller;
use App\Models\Promo;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PromoController extends Controller
{
	public function index(Promo $promo, Request $request)
	{
		$promo = Promo::where('publish_end', '>', Carbon::now())->get(['promo', 'max', 'persen', 'deskripsi', 'publish_end', 'publish_start', 'kode']);
		return !empty($promo) ? response()->json(TextData::ok($promo->toArray()), 200) : response()->json(TextData::failed(), 400);
	}
}
