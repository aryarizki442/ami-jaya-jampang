<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAddress extends Model
{
    use HasFactory;

    protected $table = 'user_addresses';

    protected $fillable = [
        'user_id',
        'label',
        'recipient_name',
        'phone',
        'province',
        'city',
        'district',
        'village',
        'postal_code',
        'detail',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    /**
     * Relasi ke User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accessor alamat lengkap (optional tapi berguna)
     */
    public function getFullAddressAttribute()
    {
        return implode(', ', array_filter([
            $this->detail,
            $this->village,
            $this->district,
            $this->city,
            $this->province,
            $this->postal_code,
        ]));
    }
}