<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
	use HasFactory;
	protected $table = 'pesanan';
	protected $primaryKey = 'id_pesanan';
	public $fillable = ['id_pesanan', 'id_user', 'produk', 'promo', 'penerima', 'ongkir', 'midtrans', 'status', 'metode', "gross_amount", "settlement_time"];
	public $incrementing = false;
	protected $keyType = 'string';
	protected $casts = [
		'produk' => 'array',
		// 'promo' => 'array',
		'penerima' => 'array',
		'ongkir' => 'array',
		"midtrans" => "array",
	];
	protected $dates = ['settlement_time'];

	// public function getCoverAttribute()
	// {
	// if()
	// return json_decode($this->attributes['gambar'])[0];
	// }
}
