<?php

namespace App\Http\Controllers\Api\User;

use App\Data\RuleData;
use App\Data\TextData;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ProfilController extends Controller
{
	function show(User $user, Request $request)
	{
		return response()->json(TextData::ok($user->makeHidden(['password', 'verified_at', 'status'])->toArray()));
	}

	function update(User $user, Request $request)
	{
		$validator = Validator::make($request->only(['jk', 'lahir', 'user']), [
			'lahir' => RuleData::of()->userLahir()->get(),
			'user' => RuleData::of()->userUser()->get(),
			'jk' => RuleData::of()->userJenisKelamin()->get(),
		]);
		if ($validator->fails()) dd($validator->errors());
		if ($validator->fails()) return response()->json(TextData::failed(), 400);
		$user->jk = $request->input('jk');
		$user->lahir = $request->input('lahir');
		return $user->save() ?  response()->json(TextData::ok()) : response()->json(TextData::failed(), 400);
	}

	function gambar(User $user, Request $request)
	{
		$validator = Validator::make($request->only(['jk', 'lahir', 'user']), [
			'lahir' => RuleData::of()->userLahir()->withoutRequired()->nullable()->get(),
			'user' => RuleData::of()->userUser()->get(),
			'jk' => RuleData::of()->userJenisKelamin()->withoutRequired()->nullable()->get(),
		]);
		if ($validator->fails()) dd($validator->errors());
		if ($validator->fails()) return response()->json(TextData::failed(), 400);
		$user->jk = $request->input('jk');
		$user->lahir = $request->input('lahir');
		return $user->save ?  response()->json(TextData::ok()) : response()->json(TextData::failed(), 400);
	}

	function password(User $user, Request $request)
	{
		$validator = Validator::make($request->only(['user', 'old', 'password', 'password_confirmation']), [
			'user' => RuleData::of()->userId()->get(),
			'old' => RuleData::of()->userPassword()->get(),
			'password' => RuleData::of()->userPassword()->get(),
			'password_confirmation' => RuleData::of()->userPassword()->same('password')->get(),
		]);
		if ($validator->fails()) return response()->json(TextData::failed('failed validation'), 400);
		$user = User::where(['id_user' => $request->input('user')])->Verified()->first();
		if (!$user || !Hash::check($request->input('old'), $user->password)) return response()->json(TextData::failed(), 400);
		$user->password = bcrypt($request->input('password'));
		return $user->save() ? response()->json(TextData::ok()) : response()->json(TextData::failed(), 400);
	}
}
