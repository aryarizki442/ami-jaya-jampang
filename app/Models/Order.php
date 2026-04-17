<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'user_id',
        'address_id',
        'status',
        'delivery_method',
        'subtotal',
        'shipping_cost',
        'other_fee',
        'total',
        'note',
        'estimated_arrival',
    ];

    protected $casts = [
        'subtotal'      => 'float',
        'shipping_cost' => 'float',
        'other_fee'     => 'float',
        'total'         => 'float',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];

    // ── Relasi ───────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->belongsTo(UserAddress::class, 'address_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    // ── Static helper (tetap di model karena butuh query ke DB) ──

    /**
     * Generate nomor order unik
     * Format: AJJ-YYYYMMDD-0001
     */
    public static function generateOrderNumber(): string
    {
        $prefix = 'AJJ-' . now()->format('Ymd') . '-';
        $last   = static::where('order_number', 'like', $prefix . '%')
                        ->orderByDesc('order_number')
                        ->value('order_number');
        $seq    = $last ? ((int) substr($last, -4)) + 1 : 1;

        return $prefix . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }
}