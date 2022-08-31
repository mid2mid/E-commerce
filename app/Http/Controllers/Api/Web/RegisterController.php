<?php

namespace App\Http\Controllers\Api\Web;

use App\Data\RuleData;
use App\Data\TextData;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Mail\VerifiedMail;
use App\Mail\VerifikasiMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
	/**
	 * Handle the incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function __invoke(Request $request, User $um)
	{
		$validator = Validator::make($request->only(['user', 'email', 'password', 'password_confirmation']), [
			'user' => RuleData::of()->userUser()->get(),
			'email' => RuleData::of()->userEmail(":dns,rfc")->get(),
			'password' => RuleData::of()->userPassword()->get(),
			'password_confirmation' => RuleData::of()->userPassword()->same('password')->get(),
		]);
		if ($validator->fails()) return response()->json(TextData::failed('failed validation', $validator->errors()->toArray()), 400);
		$user = $um->where(['email' => $request->input('email')])->withTrashed()->first();
		if (!empty($user->verified_at)) return response()->json(TextData::failed('email sudah ada'), 400);
		if ($user) {
			$user->user = Str::squish($request->input('user'));
			$user->email = $request->input('email');
			$user->kode = Str::random(8);
			$user->password = bcrypt($request->input('password'));
			if (!$user->update()) return response()->json(TextData::failed(), 400);
		} else {
			$user = $um;
			$user->id_user = Str::random(3);
			$user->user = Str::squish($request->input('user'));
			$user->email = $request->input('email');
			$user->kode = Str::random(8);
			$user->password = bcrypt($request->input('password'));
			if (!$user->save()) return response()->json(TextData::failed(), 400);
		}
		$link = route('view.web.verifikasi', ['userVerified' => $user->id_user]);
		try {
			Mail::to($request->input('email'))->send(new VerifikasiMail(['link' => $link . "?kode=" . $user->kode, "kode" => $user->kode]));
			return response()->json(TextData::ok(['link' => $link]));
		} catch (\Throwable $th) {
			return response()->json(TextData::ok(['link' => $link]));
		}
	}
}
