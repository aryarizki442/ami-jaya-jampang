<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PasswordResetToken extends Model
{
    public $timestamps = false; // Kita pakai created_at manual (useCurrent)

    protected $fillable = [
        'user_id',
        'token_key',
        'token',
        'type',
        'expired_at',
        'used_at',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
        'used_at'    => 'datetime',
        'created_at' => 'datetime',
    ];

    // -------------------------------------------------------------------------
    // Relations
    // -------------------------------------------------------------------------

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // -------------------------------------------------------------------------
    // Scopes — bisa dipakai untuk query lebih bersih
    // -------------------------------------------------------------------------

    /** Hanya OTP yang belum expired */
    public function scopeValid($query)
    {
        return $query->whereNull('used_at')->where('expired_at', '>', now());
    }

    /** Filter by type */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }
}