<?php

namespace App\Http\Controllers\Api\Web;

use App\Data\TextData;
use App\Http\Controllers\Controller;
use App\Mail\VerifikasiMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ResendController extends Controller
{
	/**
	 * Handle the incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function __invoke(Request $request)
	{
		$user = User::where(['id_user' => $request->input('user'), 'verified_at' => null])->first();
		if (empty($user)) return response()->json(TextData::failed());
		$user->kode = Str::random(8);
		if (!$user->save()) return response()->json(TextData::failed());
		$link = route('view.web.verifikasi', ['userVerified' => $user->id_user]);
		try {
			Mail::to($user->email)->send(new VerifikasiMail(['link' => $link . "?kode=" . $user->kode, "kode" => $user->kode]));
			return response()->json(TextData::ok());
		} catch (\Throwable $th) {
			return response()->json(TextData::ok());
		}
	}
}
