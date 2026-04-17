<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Accessor dengan asset() - lebih simpel dan pasti jalan
    public function getImageAttribute($value)
    {
        if (!$value) {
            return null;
        }
        
        // Cek apakah sudah URL penuh
        if (filter_var($value, FILTER_VALIDATE_URL)) {
            return $value;
        }
        
        // Gunakan asset() helper Laravel
        return asset('storage/' . $value);
    }
}