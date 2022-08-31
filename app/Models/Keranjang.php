<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keranjang extends Model
{
	use HasFactory;
	protected $table = 'keranjang';
	protected $primaryKey = ['id_user', 'id_produk'];
	public $fillable = ['id_user', 'id_produk', 'keranjang'];
	public $incrementing = false;
	protected $keyType = 'string';
	protected $casts = [];
	public $timestamps = false;


	function produk()
	{
		return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
	}

	function scopeTersedia($query)
	{
		return $query->where('keranjang', '>', 0);
	}
}
