<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
	use HasFactory;

	protected $table = 'kategori';
	protected $primaryKey = 'id_kategori';
	public $fillable = ['kategori', 'deskripsi'];
	protected $hidden = ['created_at', 'updated_at', 'laravel_through_key'];
	protected $casts = [];
}
