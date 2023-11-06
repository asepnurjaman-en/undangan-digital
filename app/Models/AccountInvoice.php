<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccountInvoice extends Model
{
    use LogsActivity, HasFactory, SoftDeletes;

    protected $fillable = [
        'date', 'content', 'status', 'amount', 'payment_link', 'payment_code',
        'package_id',
        'user_id', 'ip_addr'
    ];
    protected $dates = ['deleted_at'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(['date', 'content', 'status', 'amount', 'payment_link', 'payment_code', 'package_id']);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pack(): BelongsTo
    {
        return $this->belongsTo(Package::class, 'package_id');
    }

    public function scopeCurrent($query, $id = null)
    {
        if (Auth::user()->role=='member') :
            $id = Auth::user()->id;
        else :
            $id = $id;
        endif;

        return $query->where('user_id', $id)->where('status', 'CONFIRMED')->latest();
    }
}
