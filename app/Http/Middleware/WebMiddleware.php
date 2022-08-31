<?php

namespace App\Http\Middleware;

use App\Data\JwtData;
use App\Data\TextData;
use App\Models\Admin;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;

class WebMiddleware
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
		$key = null;
		if ($role === 'admin') {
			if (!$key = $request->cookie('admin')) return redirect()->route("view.admin.login");
			if (!$jwt = JwtData::validateAdmin($key)) return redirect()->route("view.admin.login")->withoutCookie('admin', '/admin');
			return $jwt && !empty(Admin::where(['token' => $jwt['token']])->first()) ? $next($request->merge(['jwt' => $jwt])) : redirect()->route("view.admin.login")->withoutCookie('admin', '/admin');
		} else if ($role === 'user') {
			if (!$key = $request->cookie('user')) return redirect()->route("view.web.login");
			if (!$jwt = JwtData::validateUser($key)) return redirect()->route("view.web.login")->withoutCookie('user', '/');
			return $jwt && !empty(User::where(['token' => $jwt['token']])->first()) ? $next($request->merge(['jwt' => $jwt])) : redirect()->route("view.web.login")->withoutCookie('user', '/');
		} else if ($role === 'master') {
			if (!$key = $request->cookie('master')) return redirect()->route("view.master.login");
			if (!$jwt = JwtData::validateMaster($key)) return  redirect()->route("view.master.login")->withoutCookie('master', '/master');
			return $next($request->merge(['jwt' => $jwt]));
		}
		return redirect("/");
	}
}
