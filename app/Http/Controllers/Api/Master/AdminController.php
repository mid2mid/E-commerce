<?php

namespace App\Http\Controllers\Api\Master;

use App\Data\RuleData;
use App\Data\TextData;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
	function index(Request $request, Admin $admin)
	{
		$validator = Validator::make($request->only(['only']), [
			'only' => [Rule::in(['trashed']), 'nullable'],
		]);
		if ($validator->fails()) return response()->json(TextData::failed('gagal validasi'), 400);
		if ($request->input('only')) $admin = $admin->onlyTrashed();
		$admin = $admin->orderBy('email')->get(['email', 'id_admin', 'admin']);
		return !empty($admin) ? response()->json(TextData::ok($admin->makeHidden(['password'])->toArray()), 200) : response()->json(TextData::failed('result not found'), 400);
	}

	function show(Request $request, Admin $admin)
	{
		return response()->json(TextData::ok($admin->makeHidden(['password'])->toArray()), 200);
	}

	function store(Request $request, Admin $admin)
	{
		$validator = Validator::make($request->only(['email', 'admin', 'password', 'password_confirmation']), [
			'email' => RuleData::of()->adminEmail(':dns')->get(),
			'admin' => RuleData::of()->adminAdmin()->get(),
			'password' => RuleData::of()->adminPassword()->get(),
			'password_confirmation' => RuleData::of()->adminPassword()->same('password')->get(),
		]);
		if ($validator->fails()) return response()->json(TextData::failed('failed validation', $validator->errors()->toArray()), 400);
		$admin->id_admin = Str::random(3);
		$admin->admin  = Str::squish($request->input('admin'));
		$admin->password = bcrypt($request->input('password'));
		$admin->email = $request->input('email');
		if (!empty(Admin::where(['email' => $admin->email])->withTrashed()->first())) return response()->json(TextData::failed('email sudah terpakai'), 400);
		return $admin->save() ? response()->json(TextData::ok()) : response()->json(TextData::failed(), 400);
	}

	function update(Request $request, Admin $admin)
	{
		$validator = Validator::make($request->only(['admin', 'password']), [
			'admin' => RuleData::of()->adminAdmin()->get(),
			'password' => RuleData::of()->adminPassword()->withoutRequired()->nullable()->get(),
		]);
		if ($validator->fails()) return response()->json(TextData::failed('failed validation', $validator->errors()->toArray()), 400);
		$admin->admin  = Str::squish($request->input('admin'));
		if (!empty($request->input('password'))) $admin->password = bcrypt($request->input('password'));
		if ($admin->isClean()) return response()->json(TextData::failed('no update'), 400);
		return $admin->save() ? response()->json(TextData::ok()) : response()->json(TextData::failed(), 400);
	}

	function destroy(Request $request, Admin $admin)
	{
		return $admin->delete() ? response()->json(TextData::ok()) : response()->json(TextData::failed(), 400);
	}

	function restore(Request $request, Admin $adminTrashed)
	{
		return $adminTrashed->restore() ? response()->json(TextData::ok()) : response()->json(TextData::failed(), 400);
	}
}
