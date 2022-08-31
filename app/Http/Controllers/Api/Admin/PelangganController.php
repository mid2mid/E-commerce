<?php

namespace App\Http\Controllers\Api\Admin;

use App\Data\TextData;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PelangganController extends Controller
{
	function index(User $user, Request $request)
	{
		$user = $user->withTrashed()->verified()->get();
		return $user ? response()->json(TextData::ok($user->toArray())) : response()->json(TextData::failed('result not found'), 400);
	}

	function ban(User $pelanggan)
	{
		return $pelanggan->delete() ? response()->json(TextData::ok()) : response()->json(TextData::failed(), 400);
	}

	function unban(User $pelanggan)
	{
		return $pelanggan->restore() ? response()->json(TextData::ok()) : response()->json(TextData::failed(), 400);
	}
}
