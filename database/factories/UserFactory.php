<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition()
	{
		return [
			// 'id_user' => Str::random(3),
			// 'user' => $this->faker->firstName(),
			// 'email' => $this->faker->unique()->email(),
			// 'password' => bcrypt('user12345'),
			// 'verified_at' => Carbon::now(),
			// 'kode' => Str::random(8),
		];
	}
}
