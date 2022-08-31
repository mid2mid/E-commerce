<?php

namespace App\Http\Controllers\Api\Web;

use App\Data\JwtData;
use App\Data\RuleData;
use App\Data\TextData;
use App\Data\UserData;
use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class LoginController extends Controller
{
	/**
	 * Handle the incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function __invoke(Request $request, User $user)
	{
		$validator = Validator::make($request->only(['email', 'password']), [
			'email' => RuleData::of()->userEmail()->get(),
			'password' => RuleData::of()->userPassword()->get(),
		]);
		if ($validator->fails()) return response()->json(TextData::failed(), 400);
		$user = $user->where('email', Str::squish($request->input('email')))->verified()->first();
		if (!$user || !Hash::check($request->input('password'), $user->password)) return response()->json(TextData::failed(), 400);
		$data = [
			'iat' => Carbon::now()->getTimestamp(),
			'exp' => Carbon::tomorrow()->getTimestamp(),
			'token' => Str::random(20),
			'refresh' => Str::random(UserData::getTokenLength()),
			'email' => $user->email,
			'id_user' => $user->id_user,
			'user' => $user->user,
			'role' => 'user',
		];
		$user->token = $data['token'];
		return $user->save() ? response()->json(TextData::ok(), 200)->cookie('user', JwtData::encode($data), carbon::tomorrow()->diffInMinutes(Carbon::now())) : response()->json(TextData::failed(), 400);
	}
}
