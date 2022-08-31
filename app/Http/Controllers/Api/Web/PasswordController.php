<?php

namespace App\Http\Controllers\Api\Web;

use App\Data\RuleData;
use App\Data\TextData;
use App\Http\Controllers\Controller;
use App\Mail\PasswordMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PasswordController extends Controller
{
	function store(Request $request)
	{
		$validator = Validator::make($request->only(['email']), [
			'email' => RuleData::of()->userEmail()->get(),
		]);
		if ($validator->fails()) return response()->json(TextData::failed('failed validation'), 400);
		$user = User::where(['email' => $request->input('email')])->verified()->first();
		if (empty($user))  return response()->json(TextData::failed('User Not Found'), 400);
		$user->kode = Str::random(8);
		if (!$user->save()) return response()->json(TextData::failed(), 400);

		$link = route('view.web.passwordreset', ['kode' => $user->kode]) . "?user=" . $user->id_user;
		try {
			Mail::to($request->input('email'))->send(new PasswordMail(['link' => $link, "kode" => $user->kode]));
			return response()->json(TextData::ok(['link' => $link]));
		} catch (\Throwable $th) {
			return response()->json(TextData::ok(['link' => $link]));
		}
	}

	function update(Request $request)
	{
		$validator = Validator::make($request->only(['user', 'kode', 'password', 'password_confirmation']), [
			'user' => RuleData::of()->userId()->get(),
			'kode' => RuleData::of()->userKode()->get(),
			'password' => RuleData::of()->userPassword()->get(),
			'password_confirmation' => RuleData::of()->userPassword()->same('password')->get(),
		]);
		if ($validator->fails()) return response()->json(TextData::failed('failed validation'), 400);
		$user = User::where(['kode' => $request->input('kode'), 'id_user' => $request->input('user')])->verified()->first();
		if (empty($user))  return response()->json(TextData::failed('User Not Found'), 400);
		$user->password = bcrypt($request->input('password'));
		$user->kode = null;
		return $user->save() ? response()->json(TextData::ok()) : response()->json(TextData::failed(), 400);
	}
}
