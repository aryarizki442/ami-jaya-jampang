<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [];

        for ($i = 1; $i <= 110; $i++) {
            $products[] = [
                'name' => 'Beras Sample ' . $i,
                'category_id' => ($i % 3) + 1, // 1,2,3 berulang
                'price' => rand(90000, 150000),
                'stock' => rand(10, 100),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('products')->insert($products);
    }
}
