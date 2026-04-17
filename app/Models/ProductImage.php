<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductImage extends Model
{
    protected $table = 'product_images';

    // ✅ WAJIB biar bisa create()
    protected $fillable = [
        'product_id',
        'image_url',
        'is_primary',
        'sort_order'
    ];

    // biar boolean bener di API
    protected $casts = [
        'is_primary' => 'boolean'
    ];

    // 🔗 relasi ke product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // 🔥 penting buat controller kamu
    public function getImageUrlAttribute($value)
    {
        return asset('storage/' . $value);
    }
}
