<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
	use HasFactory;
	protected $table = 'wishlist';
	protected $primaryKey = ['id_user', 'id_produk'];
	public $fillable = ['id_user', 'id_produk'];
	public $incrementing = false;
	protected $keyType = 'string';
	protected $casts = [];
	public $timestamps = false;


	function produk()
	{
		return $this->belongsTo(Produk::class, 'id_produk', 'id_produk');
	}
}
