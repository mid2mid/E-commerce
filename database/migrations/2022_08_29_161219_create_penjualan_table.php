<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('penjualan', function (Blueprint $table) {
			$table->id("id_penjualan")->unsigned();
			$table->char('id_user', 3)->collation('utf8mb4_bin');
			$table->char('id_produk', 3)->collation('utf8mb4_bin');
			$table->char('id_pesanan', 15)->collation('utf8mb4_bin');
			$table->string('produk');
			$table->integer('jumlah', false, true);
			$table->timestamp("created_at")->useCurrent();
			$table->foreign('id_user')->references('id_user')->on('user')->onUpdate('cascade');
			$table->foreign('id_produk')->references('id_produk')->on('produk')->onUpdate('cascade');
			$table->foreign('id_pesanan')->references('id_pesanan')->on('pesanan')->onUpdate('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('penjualan');
	}
};
