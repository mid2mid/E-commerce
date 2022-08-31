<?php

namespace App\Data;

use Illuminate\Support\Arr;

class TextData
{

	static function ok(array $result = [], string $pesan = 'ok', array $option = [])
	{
		$data = [
			'status' => true,
			'message' => $pesan,
		];
		if (!empty($result)) $data += ['data' => $result];
		if (!empty($option)) $data += $option;
		return $data;
	}

	static function failed(string $pesan = 'failed', array $error = [],  array $option = [])
	{
		$data = [
			'status' => false,
			'message' => $pesan,
		];
		if (!empty($error)) {
			$data += ['field' => []];
			foreach ($error as $i => $v) $data['field'] += [$i => Arr::join($v, ' ')];
		}
		if (!empty($option)) $data += $option;
		return $data;
	}
}
