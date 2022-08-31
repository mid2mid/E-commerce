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
		Schema::create('produk', function (Blueprint $table) {
			$table->char('id_produk', 3)->unique()->collation('utf8mb4_bin');
			$table->string('produk', 200);
			$table->longText('deskripsi');
			$table->integer('jumlah')->unsigned();
			$table->integer('harga')->unsigned();
			$table->integer('berat')->unsigned();
			$table->string('cover', 100);
			$table->timestamp('published_at');
			$table->timestamps();
			$table->softDeletes();
			$table->primary('id_produk');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('produk');
	}
};
