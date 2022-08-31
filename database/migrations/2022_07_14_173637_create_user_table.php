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
		Schema::create('user', function (Blueprint $table) {
			$table->char('id_user', 3)->unique()->collation('utf8mb4_bin');
			// $table->string('username', 20)->unique()->collation('utf8mb4_bin');
			$table->string('user', 50);
			$table->string('email', 30)->unique();
			$table->text('password')->collation('utf8mb4_bin');
			$table->timestamp('lahir')->nullable();
			$table->string('gambar', 100)->nullable();
			$table->enum('jk', ['pria', 'wanita'])->nullable();
			$table->string('kode', 8)->nullable();
			$table->string('token', 20)->nullable();
			$table->timestamp('verified_at')->nullable();
			$table->timestamps();
			$table->softDeletes();
			$table->primary('id_user');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('user');
	}
};
