<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\AdminLogin;
use App\Models\Kategori;
use App\Models\KategoriList;
use App\Models\Keranjang;
use App\Models\Penjualan;
use App\Models\Pesanan;
use App\Models\PesananTunggu;
use App\Models\Promo;
use App\Models\Produk;
use App\Models\User;
use App\Models\UserLogin;
use App\Models\UserVerified;
use App\Models\Whislist;
use App\Models\Wishlist;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 *
	 * @return void
	 */
	public function run()
	{
		Admin::factory()->create([
			'id_admin' => Str::random(3),
			'admin' => 'susKNTL',
			'email' => 'susuKNTL@gmail.com',
			'password' => bcrypt('admin12345'),
		]);

		Kategori::factory(10)->create();

		$produk = [];
		$faker = Faker::create('id_ID');
		for ($i = 0; $i < rand(50, 100); $i++) {
			array_push($produk, [
				"id_produk" => Str::random(3),
				"produk" => $faker->word(),
				"deskripsi" => $faker->sentence(rand(20, 40)),
				"jumlah" => rand(4, 20),
				"harga" => rand(5000, 100000),
				"berat" => rand(10, 100),
				"cover" => "cover.jpg",
				'published_at' => Carbon::now()->getTimestamp(),
			]);
		}
		foreach ($produk as $v) {
			Produk::factory()->create($v);
		}

		$a = [];
		foreach (Kategori::all(['id_kategori'])->toArray() as $v) {
			array_push($a, $v['id_kategori']);
		}

		foreach ($produk as $v) {
			$kat = array_rand($a, rand(3, 7));
			for ($i = 0; $i < count($kat); $i++) {
				KategoriList::factory()->create([
					'id_kategori' => $a[$kat[$i]],
					'id_produk' => $v['id_produk'],
				]);
			}
		}

		Promo::factory(1)->create();
		try {
			Storage::deleteDirectory('image');
			Storage::makeDirectory('image');
			foreach ($produk as $v) {
				Storage::disk('local')->copy('/migrateImage/cover.jpg', "/public/image/produk/" . $v['id_produk'] . "/cover.jpg");
			}
		} catch (\Throwable $th) {
			Storage::deleteDirectory('image');
			Storage::makeDirectory('image');
		}
	}
}
