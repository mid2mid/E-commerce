<?php

namespace App\Providers;

use App\Models\Admin;
use App\Models\Produk;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
	/**
	 * The path to the "home" route for your application.
	 *
	 * Typically, users are redirected here after authentication.
	 *
	 * @var string
	 */
	public const HOME = '/home';

	/**
	 * Define your route model bindings, pattern filters, and other route configuration.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->configureRateLimiting();

		$this->routes(function () {
			Route::middleware('api')
				->prefix('api')
				->group(base_path('routes/api.php'));

			Route::middleware('web')
				->group(base_path('routes/web.php'));
		});

		Route::bind('pelangganTrashed', function ($id) {
			return User::where('id_user', $id)->onlyTrashed()->first();
			// return User::findOrFail($id)->where('id', $id)->first();
		});
		Route::bind('userPassword', function ($id) {
			return User::where('id_user', $id)->where('verified_at', '!=', null)->onlyTrashed()->first();
			// return User::findOrFail($id)->where('id', $id)->first();
		});
		Route::bind('produkTrashed', function ($id) {
			return Produk::where('id_produk', $id)->onlyTrashed()->first();
		});
		Route::bind('userVerified', function ($id) {
			return User::where('id_user', $id)->whereNull('verified_at')->first();
		});
		Route::bind('adminTrashed', function ($id) {
			return Admin::where('id_admin', $id)->onlyTrashed()->first();
		});
	}

	/**
	 * Configure the rate limiters for the application.
	 *
	 * @return void
	 */
	protected function configureRateLimiting()
	{
		RateLimiter::for('api', function (Request $request) {
			return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
		});
	}
}
