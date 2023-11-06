<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvitationEvent extends Model
{
    use LogsActivity, HasFactory, SoftDeletes;

    protected $fillable = [
		'title', 'slug', 'content', 'file_preview', 'publish',
		'invitation_id',
		'user_id', 'ip_addr' // harus selalu ada
	];
	protected $dates = ['deleted_at'];

	public function inv(): BelongsTo
	{
		return $this->belongsTo(Invitation::class, 'invitation_id');
	}

	public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['title', 'content', 'publish', 'invitation_id']);
    }

    public function scopePublish($query)
	{
		return $query->where('publish', 'publish');
	}
}
