<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    public $timestamps = false;
    const CREATED_AT   = 'created_at';

    protected $fillable = [
        'product_id',
        'user_id',
        'order_item_id',
        'rating',
        'comment',
    ];

    protected $casts = [
        'rating'     => 'integer',
        'created_at' => 'datetime',
    ];

    // ── Relasi ───────────────────────────────────────────────────

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }
}
