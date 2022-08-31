<?php

namespace App\Http\Middleware;

use App\Data\JwtData;
use App\Data\TextData;
use App\Models\Admin;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class ApiMiddleware
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
	 * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
	 */
	public function handle(Request $request, Closure $next, $role)
	{
		$jwt = null;
		$user = null;
		if ($role === 'admin') {
			if (!$key = $request->header('ADMIN-KEY')) return response()->json(TextData::failed('unauthorized'), 401);
			if (!$jwt = JwtData::validateAdmin($key)) return response()->json(TextData::failed('unauthorized'), 401);
			$user = Admin::where(['token' => $jwt['token']])->first();
		} else if ($role === 'user') {
			if (!$key = $request->header('USER-KEY')) return response()->json(TextData::failed('unauthorized'), 401);
			if (!$jwt = JwtData::validateUser($key)) return response()->json(TextData::failed('unauthorized'), 401);
			$user = User::where(['token' => $jwt['token']])->verified()->first();
		} else if ($role === 'master') {
			if (!$key = $request->header('MASTER-KEY')) return response()->json(TextData::failed('unauthorized'), 401);
			if (!$jwt = JwtData::validateMaster($key)) return response()->json(TextData::failed('unauthorized'), 401);
			$user = 'master';
		}
		return $jwt && !empty($user) ? $next($request->merge(['jwt' => $jwt])) :  response()->json(TextData::failed('unauthorized'), 401);
	}
}
