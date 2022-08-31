<?php

namespace App\Http\Controllers\Api\Master;

use App\Data\JwtData;
use App\Data\MasterData;
use App\Data\RuleData;
use App\Data\TextData;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
	/**
	 * Handle the incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function __invoke(Request $request)
	{
		$validator = Validator::make($request->only(['email', 'password']), [
			'email' => RuleData::of()->masterEmail()->get(),
			'password' => RuleData::of()->masterPassword()->get(),
		]);
		if ($validator->fails()) return response()->json(TextData::failed(), 400);
		$data = [
			'iss' => 'samid natlus',
			'sub' => 'mid',
			'iat' => Carbon::now()->getTimestamp(),
			'exp' => Carbon::tomorrow()->getTimestamp(),
			'token' => Str::random(MasterData::getTokenLength()),
			'email' => $request->input('email'),
			'username' => MasterData::getUsername(),
			'role' => 'master',
		];
		return MasterData::check($request->input('email'), $request->input('password')) ? response()->json(TextData::ok(), 200)->cookie('master', JwtData::encode($data), carbon::tomorrow()->diffInMinutes(Carbon::now()), '/master') : response()->json(TextData::failed(), 400);
	}
}
