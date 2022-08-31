<?php

use App\Data\TextData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::name('v1.')->prefix('v1')->group(function () {
	// M I D T R A N S
	Route::name('midtrans.')->prefix('midtrans')->controller(App\Http\Controllers\Api\User\PesananController::class)->group(function () {
		Route::post('', 'store')->name('store');
	});

	// M A S T E R
	Route::name('master.')->prefix('master')->middleware('api:master')->group(function () {
		Route::post('login', \App\Http\Controllers\Api\Master\LoginController::class)->name('login')->withoutMiddleware('api:master');
		Route::prefix('admin')->name('admin.')->controller(\App\Http\Controllers\Api\Master\AdminController::class)->group(function () {
			Route::get('', 'index')->name('index');
			Route::get('{admin}', 'show')->name('show');
			Route::post('', 'store')->name('store');
			Route::put('{admin}', 'update')->name('update');
			Route::delete('{admin}', 'destroy')->name('destroy');
			Route::put('{adminTrashed}/restore', 'restore')->name('restore')->missing(function () {
				return response()->json(TextData::failed(), 400);
			});
		});
	});

	// A D M I N
	Route::name('admin.')->prefix('admin')->middleware('api:admin')->group(function () {
		Route::post('login', \App\Http\Controllers\Api\Admin\LoginController::class)->name('login')->withoutMiddleware('api:admin');

		// K A T E G O R I
		Route::prefix('kategori')->name('kategori.')->controller(\App\Http\Controllers\Api\Admin\KategoriController::class)->group(function () {
			Route::get('', 'index')->name('index');
			Route::get('{kategori}', 'show')->name('show');
			Route::post('', 'store')->name('store');
			Route::put('{kategori}', 'update')->name('update');
			Route::delete('{kategori}', 'destroy')->name('destroy');
		});

		// P R O D U K
		Route::prefix('produk')->name('produk.')->controller(\App\Http\Controllers\Api\Admin\ProdukController::class)->group(function () {
			Route::get('', 'index')->name('index');
			Route::get('{produk}', 'show')->name('show')->missing(function () {
				return response()->json(TextData::failed(), 400);
			});
			Route::post('', 'store')->name('store');
			Route::put('{produk}', 'update')->name('update')->missing(function () {
				return response()->json(TextData::failed(), 400);
			});
			Route::delete('{produk}', 'destroy')->name('destroy')->missing(function () {
				return response()->json(TextData::failed(), 400);
			});
			Route::put('{produkTrashed}/restore', 'restore')->name('restore');
		});

		// P R O M O
		Route::prefix('promo')->name('promo.')->controller(\App\Http\Controllers\Api\Admin\PromoController::class)->group(function () {
			Route::get('', 'index')->name('index');
			Route::get('{promo}', 'show')->name('show')->missing(function () {
				return response()->json(TextData::failed(), 400);
			});
			Route::post('', 'store')->name('store');
			Route::put('{promo}', 'update')->name('update')->missing(function () {
				return response()->json(TextData::failed(), 400);
			});
			Route::delete('{promo}', 'destroy')->name('destroy')->missing(function () {
				return response()->json(TextData::failed(), 400);
			});
			Route::put('{promoTrashed}/restore', 'restore')->name('restore');
		});

		// P E S A N A N
		Route::prefix('pesanan')->name('pesanan.')->controller(App\Http\Controllers\Api\Admin\PesananController::class)->group(function () {
			Route::get('', 'index')->name('index');
			Route::put('/{pesanan}', 'update')->name('update')->missing(function () {
				return response()->json(TextData::failed(), 400);
			});
			Route::get('/{pesanan}', 'show')->name('show')->missing(function () {
				return response()->json(TextData::failed(), 400);
			});
		});

		// P E L A N G G A N
		Route::prefix('pelanggan')->name('pelanggan.')->controller(App\Http\Controllers\Api\Admin\PelangganController::class)->group(function () {
			Route::get('', 'index')->name('index');
			Route::put('{pelanggan}/ban', 'ban')->name('ban')->missing(function () {
				return response()->json(TextData::failed(), 400);
			});
			Route::put('{pelangganTrashed}/unban', 'unban')->name('unban')->missing(function () {
				return response()->json(TextData::failed(), 400);
			});
		});

		// R E P O R T
		Route::prefix('laporan')->name('laporan.')->controller(App\Http\Controllers\Api\Admin\ReportController::class)->group(function () {
			Route::get('', 'index')->name('index');
		});
	});

	// W E B S I T E
	Route::prefix('web')->name('web.')->group(function () {
		Route::prefix('produk')->name('produk.')->controller(App\Http\Controllers\Api\Web\ProdukController::class)->group(function () {
			Route::get('', 'index')->name('index');
			Route::get('rekomendasi', 'rekomendasi')->name('rekomendasi');
		});
		Route::post('register', App\Http\Controllers\Api\Web\RegisterController::class)->name('register');
		Route::post('verifikasi', App\Http\Controllers\Api\Web\VerifiedController::class)->name('verifikasi');
		Route::post('resend-verified', App\Http\Controllers\Api\Web\ResendController::class)->name('resend');
		Route::post('login', App\Http\Controllers\Api\Web\LoginController::class)->name('login');
		Route::prefix('password')->name('password.')->controller(App\Http\Controllers\Api\Web\PasswordController::class)->group(function () {
			Route::post('', 'store')->name('store');
			Route::put('', 'update')->name('update');
		});
		Route::prefix('promo')->name('promo.')->controller(App\Http\Controllers\Api\Web\PromoController::class)->group(function () {
			Route::get('', 'index')->name('index');
		});
	});

	// U S E R
	Route::prefix('user')->name('user.')->middleware('api:user')->group(function () {
		Route::prefix('keranjang')->name('keranjang.')->controller(App\Http\Controllers\Api\User\KeranjangController::class)->group(function () {
			Route::post('', 'store')->name('store');
			Route::put('tambah', 'tambah')->name('tambah');
			Route::put('set', 'set')->name('set');
			Route::put('kurang', 'kurang')->name('kurang');
			Route::post('checkout', 'checkout')->name('checkout');
			Route::get('', 'index')->name('index');
			Route::delete('', 'destroy')->name('destroy');
		});
		Route::prefix('wishlist')->name('wishlist.')->controller(App\Http\Controllers\Api\User\WishlistController::class)->group(function () {
			Route::post('', 'store')->name('store');
			Route::get('', 'index')->name('index');
			Route::delete('', 'destroy')->name('destroy');
		});
		Route::prefix('pesanan')->name('pesanan.')->controller(App\Http\Controllers\Api\User\PesananController::class)->group(function () {
			Route::post('', 'store')->name('store');
			Route::get('', 'index')->name('index');
			Route::get('{pesanan}', 'show')->name('show')->missing(function () {
				return response()->json(TextData::failed(), 400);
			});
			Route::get('{pesanan}/produk', 'produk')->name('produk')->missing(function () {
				return response()->json(TextData::failed(), 400);
			});
		});
		Route::prefix('checkout')->name('checkout.')->controller(App\Http\Controllers\Api\User\CheckoutController::class)->group(function () {
			Route::post('', 'store')->name('store');
			Route::post('tunggu', 'tunggu')->name('tunggu');
			Route::get('promo', 'promo')->name('promo');
			Route::get('', 'index')->name('index');
		});
		Route::prefix('profil')->name('profil.')->controller(App\Http\Controllers\Api\User\ProfilController::class)->group(function () {
			Route::get('{user}', 'show')->name('show')->missing(function () {
				return response()->json(TextData::failed(), 400);
			});
			Route::put('{user}', 'update')->name('update')->missing(function () {
				return response()->json(TextData::failed(), 400);
			});
			Route::put('{user}/password', 'password')->name('password')->missing(function () {
				return response()->json(TextData::failed(), 400);
			});
		});
		Route::prefix('rajaongkir')->name('rajaongkir.')->controller(App\Http\Controllers\Api\User\RajaOngkirController::class)->group(function () {
			Route::get('provinsi', 'provinsi')->name('provinsi');
			Route::get('kota', 'kota')->name('kota');
			Route::get('cost', 'cost')->name('cost');
		});
	});
});
