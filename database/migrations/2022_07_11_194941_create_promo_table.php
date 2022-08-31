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
		Schema::create('promo', function (Blueprint $table) {
			$table->id('id_promo');
			$table->string('promo', 50);
			$table->string('deskripsi', 200);
			$table->char('kode', 8)->unique()->collation('utf8mb4_bin');
			$table->integer('max', false, true);
			$table->integer('persen', false, true);
			$table->timestamp('publish_start')->nullable();
			$table->timestamp('publish_end')->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('promo');
	}
};
