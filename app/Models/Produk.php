<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produk extends Model
{
	use HasFactory;
	use SoftDeletes;

	protected $table = 'produk';
	protected $primaryKey = 'id_produk';
	public $fillable = ['produk', 'deskripsi', 'jumlah', 'harga', 'berat', 'cover', 'publish', 'status', 'id_produk', 'published_at'];
	public $incrementing = false;
	protected $keyType = 'string';
	protected $casts = [
		// 'gambar' => 'array',
	];
	protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
	protected $dates = ['created_at', 'updated_at', 'published_at', 'deleted_at'];
	// protected $appends = ['cover'];

	function kategoriList()
	{
		return $this->hasMany(KategoriList::class, 'id_produk', 'id_produk');
	}

	function wishlist()
	{
		return $this->hasMany(Wishlist::class, 'id_produk', 'id_produk');
	}

	function kategori()
	{
		return $this->hasManyThrough(Kategori::class, KategoriList::class, 'id_produk', 'id_kategori', 'id_produk', 'id_kategori');
	}


	function scopePublish($query)
	{
		return $query->where('published_at', '<', Carbon::now());
	}

	function scopeTersedia($query)
	{
		return $query->where('jumlah', '>', 0);
	}

	// public function getCoverAttribute()
	// {
	// 	return json_decode($this->attributes['gambar'])[0];
	// }

	function visibleProdukList()
	{
		return $this->visible = ['id_produk', 'produk'];
	}
}
