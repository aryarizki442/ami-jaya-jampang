<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [];

        for ($i = 1; $i <= 50; $i++) {
            $name = 'Beras Premium ' . $i;
            
            $products[] = [
                'name'           => $name,
                'slug'           => Str::slug($name) . '-' . uniqid(),
                'category_id'    => rand(1, 3),
                'description'    => 'Beras berkualitas tinggi dengan tekstur pulen dan aroma wangi alami.',
                'price'          => rand(100000, 200000),
                'weight_kg'      => rand(5, 25),
                'stock'          => rand(20, 200),
                'min_order'      => 1,
                'max_order'      => rand(10, 50),
                'total_sold'     => rand(0, 500),
                'avg_rating'     => rand(35, 50) / 10,
                'is_active'      => 1,
                'is_recommended' => $i <= 15 ? 1 : 0, 
                'image'          => 'products/beras-putih.png',
                'created_at'     => now(),
                'updated_at'     => now(),
            ];
        }

        DB::table('products')->insert($products);
    }
}