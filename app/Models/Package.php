<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Package extends Model
{
    use LogsActivity, HasFactory, SoftDeletes;

    protected $fillable = [
		'title', 'slug', 'content', 'price', 'grade', 'file', 'publish',
		'user_id', 'ip_addr' // harus selalu ada
	];
	protected $dates = ['deleted_at'];

	public function getActivitylogOptions(): LogOptions
	{
		return LogOptions::defaults()->logOnly(['title', 'price', 'grade', 'file', 'publish']);
	}

	public function scopePublish($query)
	{
		return $query->where('publish', 'publish');
	}
}
