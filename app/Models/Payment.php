<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    public $timestamps = false;
    const CREATED_AT   = 'created_at';

    protected $fillable = [
        'order_id',
        'payment_method_id',
        'status',
        'amount',
        'refunded_amount',
        'snap_token',
        'payment_type',
        'transaction_id',
        'virtual_account_number',
        'refund_key',
        'refund_reason',
        'payment_proof',
        'expired_at',
        'paid_at',
        'refunded_at',
    ];

    protected $casts = [
        'amount'          => 'float',
        'refunded_amount' => 'float',
        'expired_at'      => 'datetime',
        'paid_at'         => 'datetime',
        'refunded_at'     => 'datetime',
        'created_at'      => 'datetime',
    ];

    // ── Relasi ───────────────────────────────────────────────────

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }
}