<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
  public function run(): void
{
    DB::table('products')->insert([
        [
            'name'           => 'Beras Putih Premium Pandan Wangi',
            'slug'           => Str::slug('Beras Putih Premium Pandan Wangi') . '-' . uniqid(),
            'category_id'    => 1, // Premium
            'description'    => 'Beras premium berkualitas tinggi dengan tekstur pulen dan aroma wangi alami.',
            'price'          => 180000,
            'weight_kg'      => 10,
            'stock'          => 120,
            'min_order'      => 1,
            'max_order'      => 20,
            'total_sold'     => 50,
            'avg_rating'     => 4.8,
            'is_active'      => 1,
            'is_recommended' => 1,
            'image' => $this->getImage('beras-putih.png'),
            'created_at'     => now(),
            'updated_at'     => now(),
        ],

        [
            'name'           => 'Beras Putih Medium Rojo Lele',
            'slug'           => Str::slug('Beras Putih Medium Rojo Lele') . '-' . uniqid(),
            'category_id'    => 2, // Medium
            'description'    => 'Beras medium dengan kualitas baik, cocok untuk konsumsi harian keluarga.',
            'price'          => 140000,
            'weight_kg'      => 10,
            'stock'          => 150,
            'min_order'      => 1,
            'max_order'      => 25,
            'total_sold'     => 30,
            'avg_rating'     => 4.5,
            'is_active'      => 1,
            'is_recommended' => 1,
            'image' => $this->getImage('beras-medium.png'),
            'created_at'     => now(),
            'updated_at'     => now(),
        ],

        [
            'name'           => 'Beras Ketan Pandan Wangi',
            'slug'           => Str::slug('Beras Ketan Pandan Wangi') . '-' . uniqid(),
            'category_id'    => 3, // Ketan
            'description'    => 'Beras ketan putih berkualitas, cocok untuk makanan tradisional dan kue.',
            'price'          => 160000,
            'weight_kg'      => 5,
            'stock'          => 80,
            'min_order'      => 1,
            'max_order'      => 15,
            'total_sold'     => 20,
            'avg_rating'     => 4.7,
            'is_active'      => 1,
            'is_recommended' => 1,
              'image' => $this->getImage('beras-ketan.png'),
            'created_at'     => now(),
            'updated_at'     => now(),
        ],
    ]);
}
 private function getImage(string $filename): string
    {
        return 'products/' . $filename;
    }
}
