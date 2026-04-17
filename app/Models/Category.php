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
        'slug',
        'description',
        'image',
        'is_active'
    ];

    // ✅ Default value (optional tapi bagus)
    protected $casts = [
        'is_active' => 'boolean',
    ];

    // 🔗 Relasi ke Product
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // 🔗 Accessor untuk URL gambar (biar langsung bisa dipakai di frontend)
    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }

        return asset('storage/' . $this->image);
    }
}
