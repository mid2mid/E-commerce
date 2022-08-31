<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
	use HasFactory;
	use SoftDeletes;

	protected $table = 'user';
	protected $primaryKey = 'id_user';
	public $fillable = ['id_user', 'user', 'email', 'password', 'lahir', 'gambar', 'jk', 'verified_at'];
	public $incrementing = false;
	protected $keyType = 'string';
	protected $casts = [];
	protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
	protected $appends = ['status'];

	function wishlist()
	{
		return $this->hasMany(Wishlist::class, 'id_user', 'id_user');
	}

	function scopeVerified($query)
	{
		return $query->whereNotNull('verified_at');
	}

	function keranjang()
	{
		return $this->hasMany(Keranjang::class, 'id_user', 'id_user');
	}

	function keranjangProduk()
	{
		return $this->hasManyThrough(Produk::class, Keranjang::class, 'id_user', 'id_produk', 'id_user', 'id_produk');
	}

	public function getStatusAttribute()
	{
		return $this->attributes['deleted_at'] ? true : false;
	}
}
