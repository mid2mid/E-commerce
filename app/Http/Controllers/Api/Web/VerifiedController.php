<?php

namespace App\Http\Controllers\Api\Web;

use App\Data\RuleData;
use App\Data\TextData;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserVerified;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VerifiedController extends Controller
{
	/**
	 * Handle the incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function __invoke(User $um, Request $request)
	{
		$validator = Validator::make($request->only(['user', 'kode']), [
			'user' => RuleData::of()->userUser()->get(),
			'kode' => RuleData::of()->userKode()->get(),
		]);
		if ($validator->fails()) return response()->json(TextData::failed('validation'), 400);
		$user = $um->where(['id_user' => $request->input('user'), 'kode' => $request->input('kode')])->where('verified_at', '=', null)->first();
		if (empty($user)) return response()->json(TextData::failed(), 400);
		$user->verified_at = Carbon::now();
		return $user->save() ? response()->json(TextData::ok()) : response()->json(TextData::failed(), 400);
	}
}
