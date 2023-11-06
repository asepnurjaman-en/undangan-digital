<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Account extends Model
{
    use LogsActivity, HasFactory, SoftDeletes;

    protected $fillable = [
        'content', 'file', 'actived',
        'package_id', 'invitation_id', 'guestbook',
        'user_id', 'ip_addr'
    ];
    protected $dates = ['deleted_at'];

    protected $casts = [
        'id' => 'string'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['content', 'file', 'actived', 'package_id', 'invitation_id', 'guestbook']);
    }

    // public function pack(): BelongsTo
    // {
    //     return $this->belongsTo(Package::class, 'package_id');
    // }

    public function inv(): BelongsTo
    {
        return $this->belongsTo(Invitation::class, 'invitation_id');
    }
}
