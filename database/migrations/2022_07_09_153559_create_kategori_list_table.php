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
		Schema::create('kategori_list', function (Blueprint $table) {
			// $table->id('id_kategori_list');
			$table->bigInteger('id_kategori')->unsigned();
			$table->char('id_produk', 3)->collation('utf8mb4_bin');
			$table->primary(['id_kategori', 'id_produk']);
			$table->foreign('id_kategori', 'fk_kategori')->references('id_kategori')->on('kategori')->onUpdate('cascade')->onDelete('cascade');
			$table->foreign('id_produk', 'fk_produk')->references('id_produk')->on('produk')->onUpdate('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('kategori_list');
	}
};
