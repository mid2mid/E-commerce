<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PesananTunggu extends Model
{
	use HasFactory;

	protected $table = 'pesanan_tunggu';
	protected $primaryKey = 'id_pesanan_tunggu';
	public $fillable = ['id_pesanan_tunggu', 'produk', 'id_user', 'promo', 'penerima', 'ongkir'];
	// public $fillable = ['id_pesanan_tunggu', 'produk', 'id_user', 'kupon', 'alamat', 'penerima', 'hp'];
	public $incrementing = false;
	protected $keyType = 'string';
	public $timestamps = false;
	protected $casts = [
		'produk' => 'array',
		'penerima' => 'array',
		'ongkir' => 'array',
		// 'promo' => 'array',
	];

	// public function getPenerimaAttribute()
	// {
	// return Carbon::create($this->attributes['publish_start'])->toDateString();
	// return (array)json_decode($this->attributes['penerima']);
	// }
}
