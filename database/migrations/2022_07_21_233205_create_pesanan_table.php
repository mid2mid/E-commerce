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
		Schema::create('pesanan', function (Blueprint $table) {
			$table->char('id_pesanan', 15)->unique()->collation('utf8mb4_bin');
			$table->char('id_user', 3)->collation('utf8mb4_bin');
			$table->json('produk');
			$table->integer('promo')->nullable();
			$table->json('penerima');
			$table->json('ongkir');
			$table->json('midtrans');
			$table->string('metode', 50);
			$table->string('resi', 30)->nullable();
			$table->string('status', 20);
			$table->string('payment_code', 30)->nullable();
			$table->integer("gross_amount", false, true);
			$table->timestamp("settlement_time")->nullable();
			$table->timestamps();
			$table->primary('id_pesanan');
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
		Schema::dropIfExists('pesanan');
	}
};
