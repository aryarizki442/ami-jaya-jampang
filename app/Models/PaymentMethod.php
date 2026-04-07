<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $table = 'payment_methods';

    protected $fillable = [
        'name',
        'code',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ─────────────────────────────────────────────
    // RELATION
    // ─────────────────────────────────────────────

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // ─────────────────────────────────────────────
    // SCOPE (biar clean query)
    // ─────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // ─────────────────────────────────────────────
    // HELPER (optional tapi powerful 🔥)
    // ─────────────────────────────────────────────

    public static function findByCode(string $code)
    {
        return self::where('code', $code)->first();
    }
}
