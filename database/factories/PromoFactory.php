<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Promo>
 */
class PromoFactory extends Factory
{
	/**
	 * Define the model's default state.
	 *
	 * @return array<string, mixed>
	 */
	public function definition()
	{
		return [
			'promo' => $this->faker->firstName(),
			'deskripsi' => 'lorem ipsum dolor',
			'kode' => 'AAAAAAAA',
			'max' => Arr::random([40000, 50000, 20000, 10000]),
			'persen' => Arr::random([50, 60, 70, 80]),
			'publish_start' => Carbon::now(),
			'publish_end' => Carbon::now()->addDays(rand(2, 5)),
		];
	}
}
