<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Admin>
 */
class AdminFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition()
	{
		return [
			'id_admin' => Str::random(3),
			'admin' => $this->faker->unique()->firstName(),
			'email' => $this->faker->unique()->email(),
			'password' => bcrypt('admin12345'),
		];
	}
}
