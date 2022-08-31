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
		Schema::create('pesanan_tunggu', function (Blueprint $table) {
			$table->string('id_pesanan_tunggu', 15)->unique()->collation('utf8mb4_bin');
			$table->char('id_user', 3)->collation('utf8mb4_bin');
			$table->json('produk');
			$table->integer('promo')->nullable();
			$table->json('penerima')->nullable();
			$table->json('ongkir')->nullable();
			$table->primary('id_pesanan_tunggu');
			$table->foreign('id_user')->references('id_user')->on('user')->onUpdate('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('pesanan_tunggu');
	}
};
