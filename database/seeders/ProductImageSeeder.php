<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductImageSeeder extends Seeder
{
    public function run(): void
    {
        $products = DB::table('products')->pluck('id')->toArray();

        $images = [];

        foreach ($products as $index => $productId) {
            $images[] = [
                'product_id' => $productId,
                'image_url' => 'products/beras-putih.png', // ✅ FIX INI
                'is_primary' => 1,
                'sort_order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('product_images')->insert($images);
    }
}
