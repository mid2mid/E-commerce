<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promo extends Model
{
	use HasFactory;

	protected $table = 'promo';
	protected $primaryKey = 'id_promo';
	public $fillable = ['promo', 'deskripsi', 'max', 'persen', 'kode', 'publish_start', 'publish_end'];
	protected $casts = [
		'publish_start' => 'datetime'
	];
	protected $hidden = ['created_at', 'updated_at', 'deleted_at'];
	protected $appends = ['status'];

	public function getStatusAttribute()
	{
		$now = Carbon::today();
		$start = Carbon::create($this->attributes['publish_start'])->toDateString();
		$end = Carbon::create($this->attributes['publish_end'])->toDateString();
		if ($now->between($start, $end)) return 'aktif';
		if ($now->lessThan($end)) return  'coming soon';
		if ($now->greaterThan($start)) return  'expired';
		return null;
	}
	public function getPublishStartAttribute()
	{
		// return Carbon::create($this->attributes['publish_start'])->toDateString();
		return strtotime($this->attributes['publish_start']);
	}
	public function getPublishEndAttribute()
	{
		// return Carbon::create($this->attributes['publish_end'])->toDateString();
		return strtotime($this->attributes['publish_end']);
	}
	public function getRouteKeyName()
	{
		return 'kode';
	}
}
