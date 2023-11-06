<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Template extends Model
{
    use LogsActivity, HasFactory, SoftDeletes;

    protected $fillable = [
		'title', 'slug', 'file', 'file_type', 'preset', 'url', 'grade', 'publish',
		'user_id', 'ip_addr' // harus selalu ada
	];
	protected $dates = ['deleted_at'];

	public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['title', 'file', 'preset', 'url', 'grade', 'publish']);
    }

    public function scopePublish($query)
	{
		return $query->where('publish', 'publish');
	}
}
