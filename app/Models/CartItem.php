<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'product_id',
        'price',
        'quantity',
        'is_selected'
    ];

    protected $casts = [
        'is_selected' => 'boolean',
    ];

    /**
     * Cart pemilik item
     */
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * Product dari item
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * subtotal item
     */
    public function getSubtotalAttribute()
    {
        return $this->price * $this->quantity;
    }
}