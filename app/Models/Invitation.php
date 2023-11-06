<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invitation extends Model
{
    use LogsActivity, HasFactory, SoftDeletes;

    protected $fillable = [
		'title', 'slug', 'preset', 'file', 'file_type', 'file_preview', 'publish',
		'template_id',
		'user_id', 'ip_addr' // harus selalu ada
	];
	protected $dates = ['deleted_at'];

	public function temp(): BelongsTo
	{
		return $this->belongsTo(Template::class, 'template_id');
	}

	public function event(): HasMany
	{
		return $this->hasMany(InvitationEvent::class, 'invitation_id', 'id');
	}

	public function photo(): HasOne
	{
		return $this->hasOne(InvitationGallery::class, 'invitation_id', 'id')->whereType('photo');
	}

	public function video(): HasOne
	{
		return $this->hasOne(InvitationGallery::class, 'invitation_id', 'id')->whereType('video');
	}

	public function story(): HasMany
	{
		return $this->hasMany(InvitationStory::class, 'invitation_id', 'id');
	}

	public function guest(): HasMany
	{
		return $this->hasMany(InvitationGuest::class, 'invitation_id', 'id');
	}

	public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['title', 'file', 'publish', 'template_id']);
    }

	public function scopePublish($query)
	{
		return $query->where('publish', 'publish');
	}
}
