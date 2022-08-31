<?php

namespace App\Http\Controllers\Api\Admin;

use App\Data\AdminData;
use App\Data\JwtData;
use App\Data\RuleData;
use App\Data\TextData;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
	public function __invoke(Request $request, Admin $admin)
	{
		$validator = Validator::make($request->only(['email', 'password']), [
			'email' => RuleData::of()->adminEmail()->get(),
			'password' => RuleData::of()->adminPassword()->get(),
		]);
		if ($validator->fails()) return response()->json(TextData::failed(), 400);
		$admin = $admin->where(['email' => $request->input('email')])->first();
		if (empty($admin) || !Hash::check($request->input('password'), $admin->password)) return response()->json(TextData::failed(), 400);
		$data = [
			'iat' => Carbon::now()->getTimestamp(),
			'exp' => Carbon::tomorrow()->getTimestamp(),
			'token' => Str::random(AdminData::getTokenLength()),
			'email' => $request->input('email'),
			'role' => 'admin',
		];
		$admin->token = $data['token'];
		return $admin->save() ? response()->json(TextData::ok())->cookie('admin', JwtData::encode($data), Carbon::tomorrow()->diffInMinutes(Carbon::now()), '/admin') : response()->json(TextData::failed(), 400);
	}
}
