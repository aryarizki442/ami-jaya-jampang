<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categories')->insert([
            [
                'name' => 'Premium',
                'description' => 'Beras kualitas terbaik dengan butiran utuh, pulen, dan aroma wangi.',
                'image' => $this->getImage('beras-putih.png'),
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Medium',
                'description' => 'Beras kualitas menengah yang cocok untuk konsumsi harian dengan harga terjangkau.',
                'image' => $this->getImage('beras-medium.png'),
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ketan',
                'description' => 'Beras ketan dengan tekstur lengket, cocok untuk makanan tradisional dan kue.',
                'image' => $this->getImage('beras-ketan.png'),
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    private function getImage(string $filename): string
    {
        return 'categories/' . $filename;
    }
}
