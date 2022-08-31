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
		Schema::create('wishlist', function (Blueprint $table) {
			$table->char('id_user', 3)->collation('utf8mb4_bin');
			$table->char('id_produk', 3)->collation('utf8mb4_bin');
			$table->primary(['id_user', 'id_produk']);
			$table->foreign('id_user')->references('id_user')->on('user')->onUpdate('cascade');
			$table->foreign('id_produk')->references('id_produk')->on('produk')->onUpdate('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('whislist');
	}
};
