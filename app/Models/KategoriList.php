<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KategoriList extends Model
{
	use HasFactory;
	protected $table = 'kategori_list';
	protected $primaryKey = ['id_kategori', 'id_produk'];
	public $fillable = ['id_kategori', 'id_produk'];
	public $timestamps = false;
	protected $keyType = 'string';
	public $incrementing = false;
	// protected $hidden = ['laravel_through_key', 'updated_at', 'deleted_at'];
}
