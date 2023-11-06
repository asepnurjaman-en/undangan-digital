<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Info extends Model
{
    use LogsActivity, HasFactory, SoftDeletes;

    protected $fillable = [
		'type', 'title', 'content', 'file', 'file_type',
		'user_id', 'ip_addr' // harus selalu ada
	];
    protected $dates = ['deleted_at'];

	public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['type', 'title', 'content', 'file', 'file_type']);
    }
}
