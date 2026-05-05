<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'weight_kg',
        'stock',
        'min_order',
        'max_order',
        'is_active',
        'is_recommended',
        'image',
    ];

    protected $casts = [
        'price'     => 'decimal:2',
        'weight_kg' => 'decimal:2',
        'stock'     => 'integer',
        'min_order' => 'integer',
        'max_order' => 'integer',
        'is_active' => 'boolean',
        'is_recommended' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
     public function getImageUrlAttribute(): ?string
{
    if (!$this->image) {
        return null;
    }
    
    // Jika sudah full URL, return langsung
    if (filter_var($this->image, FILTER_VALIDATE_URL)) {
        return $this->image;
    }
    
    // Jika hanya path, tambahkan asset
    return asset('storage/' . $this->image);
}

}
