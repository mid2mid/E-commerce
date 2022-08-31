<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;

class Admin extends Model
{
	use HasFactory;
	use SoftDeletes;

	protected $table = 'admin';
	protected $primaryKey = 'id_admin';
	public $fillable = ['id_admin', 'admin', 'password', 'email'];
	public $incrementing = false;
	protected $keyType = 'string';
	protected $casts = [];
	protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
}
