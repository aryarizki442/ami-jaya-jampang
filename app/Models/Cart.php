<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id'
    ];

    /**
     * Cart milik user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    

    /**
     * Semua item di cart
     */
    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Item yang dipilih saja (untuk checkout)
     */
    public function selectedItems()
    {
        return $this->hasMany(CartItem::class)->where('is_selected', true);
    }

    /**
     * Hitung total harga item yang dipilih
     */
    public function selectedTotal()
    {
        return $this->selectedItems->sum(fn ($item) => $item->price * $item->quantity);
    }
}