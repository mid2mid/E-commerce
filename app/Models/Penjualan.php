<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
	use HasFactory;
	protected $table = 'penjualan';
	protected $primaryKey = 'id_penjualan';
	public $fillable = ['id_user', 'id_produk', 'jumlah', "id_pesanan", "created_at"];
	public $timestamps = false;
}
