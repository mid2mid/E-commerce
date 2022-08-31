<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::name('view.')->group(function () {
	// M A S T E R
	Route::prefix('master')->name('master.')->controller(\App\Http\Controllers\ViewMasterController::class)->middleware('web:master')->group(function () {
		Route::get('login', 'login')->name('login')->withoutMiddleware('web:master');
		Route::get('logout', 'logout')->name('logout')->withoutMiddleware('web:master');
		Route::get('home', 'home')->name('home');
		Route::get('admin', 'admin')->name('admin');
	});

	// A D M I N
	Route::prefix('admin')->name('admin.')->controller(\App\Http\Controllers\ViewAdminController::class)->middleware('web:admin')->group(function () {
		Route::get('home', 'home')->name('home');
		Route::get('produk', 'produk')->name('produk');
		Route::get('kategori', 'kategori')->name('kategori');
		Route::get('pelanggan', 'pelanggan')->name('pelanggan');
		Route::get('pesanan', 'pesanan')->name('pesanan');
		Route::get('promo', 'promo')->name('promo');
		Route::get('laporan', 'laporan')->name('laporan');
		Route::get('login', 'login')->name('login')->withoutMiddleware('web:admin');
		Route::get('logout', 'logout')->name('logout')->withoutMiddleware('web:admin');
	});

	// W E B S I T E
	Route::name('web.')->controller(App\Http\Controllers\ViewWebController::class)->group(function () {
		Route::get('/', 'home')->name('home');
		Route::get('/produk', 'produk')->name('produk');
		Route::get('/produk/{produk}', 'details')->name('details');
		Route::get('/promo', 'promo')->name('promo');
		Route::get('/invoice', 'invoice')->name('invoice');
		Route::get('/logout', 'logout')->name('logout');
		Route::get('/login', 'login')->name('login');
		Route::get('/register', 'register')->name('register');
		Route::get('/password', 'password')->name('password');
		Route::get('/password/{kode}', 'passwordReset')->name('passwordreset')->missing(function () {
			return abort(404);
		});
		Route::get('/verifikasi/{userVerified}', 'verifikasi')->name('verifikasi')->missing(function () {
			return abort(404);
		});
		Route::get('/user/{user}/profil', 'profil')->name('profil')->middleware('web:user');
		Route::get('/user/{user}/wishlist', 'wishlist')->name('wishlist')->middleware('web:user');
		Route::get('/user/{user}/keranjang', 'keranjang')->name('keranjang')->middleware('web:user');
		Route::get('/user/{user}/pesanan', 'pesanan')->name('pesanan')->middleware('web:user');
		Route::get('/user/{user}/checkout/{checkout}', 'checkout')->name('checkout')->middleware('web:user');
	});
});
