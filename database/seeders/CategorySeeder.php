<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [      
                'name' => 'Medium',
                'slug' => 'medium',
                'description' => 'Beras kualitas medium dengan tekstur pulen',
                'is_active' => true,
            ],
            [
                'name' => 'Premium',
                'slug' => 'premium',
                'description' => 'Beras kualitas premium dengan aroma harum dan tekstur super pulen',
                'is_active' => true,
            ],
            [
                'name' => 'Ketan',
                'slug' => 'ketan',
                'description' => 'Beras ketan putih untuk berbagai olahan tradisional',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}