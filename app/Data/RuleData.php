<?php

namespace App\Data;

use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;

class RuleData
{
	private array $rules = [];

	public function __construct($rules = [])
	{
		$this->rules = $rules;
	}

	function get()
	{
		return $this->rules;
	}

	static function of()
	{
		return new RuleData();
	}

	function push(string $value)
	{
		array_push($this->rules, $value);
		return $this->rules;
	}

	function unset(string $value)
	{
		unset($this->rules[array_search($value, $this->rules)]);
		return $this->rules;
	}

	function unique(string $table, string $coloumn)
	{
		return new static($this->push("unique:$table,$coloumn"));
	}

	function withoutRequired()
	{
		return new static($this->unset('required'));
	}

	function same(string $field)
	{
		return new static($this->push("same:$field"));
	}

	function nullable()
	{
		return new static($this->push("nullable"));
	}

	// MASTER
	function masterId()
	{
		return new static([
			'required',
			"email",
			'min:3',
			'max:3',
		]);
	}
	function masterPassword()
	{
		return new static([
			'required',
			// "password",
			'min:8',
			'max:20',
		]);
	}
	function masterEmail()
	{
		return new static([
			'required',
			'email',
			'max:30',
			'min:3',
		]);
	}

	// ADMIN
	function adminAdmin()
	{
		return new static([
			'required',
			'regex:/^[a-zA-Z0-9\s]+$/i',
			'min:3',
			'max:30',
		]);
	}

	function adminId()
	{
		return new static([
			'required',
			'regex:/^[a-zA-Z0-9]+$/i',
			'max:3',
			'min:3',
		]);
	}

	function adminEmail($dns = null)
	{
		return new static([
			'required',
			'max:30',
			'min:3',
			'email' . $dns ?? '',
		]);
	}

	function adminPassword()
	{
		return new static([
			'required',
			// "password",
			'min:8',
			'max:20',
		]);
	}





	function userId()
	{
		return new static([
			'required',
			"regex:/^[a-zA-Z0-9']+$/i",
			'min:3',
			'max:3',
		]);
	}
	function userUser()
	{
		$this->rules = [
			'required',
			'regex:/^[a-zA-Z0-9\s]+$/i',
			'max:50',
			'min:3',
		];
		return new static($this->rules);
	}
	function userEmail($dns = null)
	{
		return new static([
			'required',
			'email' . $dns ?? '',
			'max:100',
			'min:3',
		]);
	}
	function userPassword()
	{
		return new static([
			'required',
			// 'password',
			'max:20',
			'min:8',
		]);
	}
	function userLahir()
	{
		return new static([
			'required',
			'date',
		]);
	}
	function userGambar()
	{
		return new static([
			'required',
			'image',
			'file',
			'max:500',
		]);
	}
	function userJenisKelamin()
	{
		return new static([
			'required',
			Rule::in(['pria', 'wanita'])
		]);
	}
	function userKode()
	{
		return new static([
			'required',
			'regex:/^[a-zA-Z0-9\s]+$/i',
			'min:8',
			'max:8',
		]);
	}

	// KATEGORI
	function KategoriKategori()
	{
		return new static([
			'required',
			'regex:/^[a-zA-Z0-9\s]+$/i',
			'max:20',
			'min:3',
		]);
	}
	function KategoriDeskripsi()
	{
		return new static([
			'required',
			'regex:/^[a-zA-Z0-9\s]+$/i',
			'max:100',
			'min:3',
		]);
	}

	// PRODUK
	function produkId()
	{
		return new static([
			'required',
			"regex:/^[a-zA-Z0-9']+$/i",
			'min:3',
			'max:3',
		]);
	}
	function produkProduk()
	{
		return new static([
			'required',
			"regex:/^[a-zA-Z0-9\s.,:']+$/i",
			'min:3',
			'max:200',
		]);
	}
	function produkDeskripsi()
	{
		return new static([
			'required',
			"string",
			'min:3',
		]);
	}
	function produkHarga()
	{
		return new static([
			'required',
			'numeric',
			'min:1',
		]);
	}
	function produkBerat()
	{
		return new static([
			'required',
			'numeric',
			'min:1',
		]);
	}
	function produkJumlah()
	{
		return new static([
			'required',
			'numeric',
			'min:1',
		]);
	}
	function produkPublish()
	{
		return new static([
			'required',
			'date',
			'after_or_equal:' . Carbon::today(),
		]);
	}
	function produkKategori()
	{
		return new static([
			'required',
			'numeric',
			'min:1',
		]);
	}
	function produkKategoriArray()
	{
		return new static([
			'required',
			'array',
			'min:1',
		]);
	}
	function produkCover()
	{
		return new static([
			'required',
			'image',
			'file',
			'max:500',
		]);
	}

	// PROMO
	function promoPromo()
	{
		return new static([
			'required',
			'regex:/^[a-zA-Z0-9\s]+$/i',
			'max:100',
			'min:3',
		]);
	}
	function promoDeskripsi()
	{
		return new static([
			'required',
			'regex:/^[a-zA-Z0-9\s]+$/i',
			'max:250',
			'min:3',
		]);
	}
	function promoKode()
	{
		return new static([
			'required',
			'alpha_num',
			'min:8',
			'max:8',
		]);
	}
	function promoPersen()
	{
		return new static([
			'required',
			'min:1',
			'max:100',
			'numeric',
		]);
	}
	function promoMax()
	{
		return new static([
			'required',
			'min:1',
			'numeric',
		]);
	}
	function promoPublishStart($date)
	{
		return new static([
			'required',
			'date',
			'after_or_equal:' . $date,
		]);
	}
	function promoPublishEnd()
	{
		return new static([
			'required',
			'date',
			'after_or_equal:publish_start',
		]);
	}
}
