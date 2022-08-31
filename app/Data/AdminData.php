<?php

namespace App\Data;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class AdminData
{
	private static int $tokenLength = 20;

	static function getTokenLength(): int
	{
		return self::$tokenLength;
	}
}
