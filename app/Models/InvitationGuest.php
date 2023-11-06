<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvitationGuest extends Model
{
    use LogsActivity, HasFactory, SoftDeletes;

    protected $fillable = [
		'type', 'name', 'slug', 'message',
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
        return LogOptions::defaults()->logOnly(['name', 'message', 'invitation_id']);
    }
}
