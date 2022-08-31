<?php

namespace App\Data;

use Carbon\Carbon;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Str;

class JwtData
{
	private static String $algo = 'HS256';
	private static String $secret_key = 'YrRkbJm@3n&mUQjMKLUFON0lfSra1D3bpgFdMLmh@yc_7qvk!PfAgpwXkFaW'; //master12345

	static function getAlgo(): string
	{
		return env('JWT_ALGO', self::$algo);
	}

	static function getSecret(): string
	{
		return env('JWT_SECRET', self::$secret_key);
	}

	static function encode(array $payload): string
	{
		return JWT::encode($payload, self::getSecret(), self::getAlgo());
	}

	static function decode(string $jwt): array|null
	{
		try {
			return (array) JWT::decode($jwt, new Key(self::getSecret(), self::getAlgo()));
		} catch (\Throwable $th) {
			return null;
		}
	}

	static function validateMaster(string $token): array|null
	{
		try {
			$jwt = static::decode($token);
			return (Str::length($jwt['token']) === MasterData::getTokenLength() && $jwt['email'] === MasterData::getEmail() && $jwt['username'] === MasterData::getUsername() && $jwt['role'] === 'master' && Carbon::now()->getTimestamp() > $jwt['iat'] && Carbon::now()->getTimestamp() < $jwt['exp']) ? $jwt : null;
		} catch (\Throwable $th) {
			return null;
		}
	}

	static function validateAdmin(string $token): array|null
	{
		try {
			$jwt = static::decode($token);
			return (Str::length($jwt['token']) === AdminData::getTokenLength() && $jwt['role'] === 'admin' && Carbon::now()->getTimestamp() > $jwt['iat'] && Carbon::now()->getTimestamp() < $jwt['exp']) ? $jwt : null;
		} catch (\Throwable $th) {
			return null;
		}
	}

	static function validateUser(string $token): array|null
	{
		try {
			$jwt = static::decode($token);
			return (Str::length($jwt['token']) === UserData::getTokenLength() && Str::length($jwt['refresh']) === 20 && $jwt['role'] === 'user' && Carbon::now()->getTimestamp() > $jwt['iat'] && Carbon::now()->getTimestamp() < $jwt['exp']) ? $jwt : null;
		} catch (\Throwable $th) {
			return null;
		}
	}
}
