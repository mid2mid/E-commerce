<?php

namespace App\Data;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class MasterData
{
	private static string $email = 'master@gmail.com';
	private static string $username = 'master';
	private static string $password = '$2y$10$bbwcbQ82Ff6A44Us9Ikmz.wRzL4U2fp0RpT2mzIHR24WhuIXa9EAy'; //master12345
	private static int $tokenLength = 40;

	static function getTokenLength(): int
	{
		return self::$tokenLength;
	}
	static function getEmail(): string
	{
		return env('MASTER_EMAIL', self::$email);
	}

	static function getUsername(): string
	{
		return env('MASTER_USERNAME', self::$username);
	}

	static function getPassword(): string
	{
		return env('MASTER_PASSWORD', self::$password);
	}

	static function check(string $email, string $password): bool
	{
		return Hash::check($password, self::getPassword()) && Str::squish($email) === self::getEmail();
	}
}
