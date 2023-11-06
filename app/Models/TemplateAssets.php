<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TemplateAssets extends Model
{
    use LogsActivity, HasFactory, SoftDeletes;

    protected $fillable = [
		'type', 'title', 'content', 'publish',
		'user_id', 'ip_addr' // harus selalu ada
	];
	protected $dates = ['deleted_at'];

	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

	public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['type', 'title', 'content', 'publish']);
    }

    public function scopePublish($query)
	{
		return $query->where('publish', 'publish');
	}
}
