<?php

namespace App\Data;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class RajaongkirData
{
	private static string $key = '';
	private static string $origin = '';
	private static array $kurir = ['jne', 'pos', 'tiki'];

	static function getKey(): string
	{
		return env('RAJAONGKIR_KEY', self::$key);
	}

	static function getOrigin(): string
	{
		return env('RAJAONGKIR_ORIGIN', self::$origin);
	}

	static function getKurir(): array
	{
		return env('RAJAONGKIR_KURIR', self::$kurir);
	}

	static function getCost(string $destination, int $weight, string $courier): array|null
	{
		$http = Http::asForm()->withHeaders([
			"key" => self::getKey(),
		])->post('https://api.rajaongkir.com/starter/cost', [
			'origin' => static::getOrigin(),
			'destination' => $destination,
			'weight' => $weight,
			'courier' => $courier,
		]);
		return (!$http->failed()) ? $http->collect('rajaongkir')->toArray() : null;
	}

	static function getProvince(string $id): array|null
	{
		$http = Http::withHeaders([
			"key" => self::getKey(),
		])->get('https://api.rajaongkir.com/starter/province' . $id);
		return (!$http->failed()) ? $http->collect('rajaongkir')->toArray() : null;
	}

	static function getCity(string|null $province, string|null $city): array|null
	{
		$http = Http::withHeaders([
			"key" => self::getKey(),
		])->get('https://api.rajaongkir.com/starter/city?' . Arr::query(['province' => $province, 'id' => $city]));
		return (!$http->failed()) ? $http->collect('rajaongkir')->toArray() : null;
	}
}
