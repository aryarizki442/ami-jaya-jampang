<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';

    // ✅ WAJIB biar tidak error 500 (mass assignment)
    protected $fillable = [
        'name',
        'description',
        'image',
        'is_active'
    ];

  
    protected $casts = [
        'is_active' => 'boolean',
    ];

    // 🔗 Relasi ke Product
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // 🔗 Accessor untuk URL gambar (biar langsung bisa dipakai di frontend)
    public function getImageAttribute($value)
{
    if (!$value) {
        return null;
    }

    return asset('storage/' . $value);
}
}
