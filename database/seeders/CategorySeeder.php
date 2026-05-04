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
                'image' => $this->getImage('beras-putih.png'),
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Medium',
                'image' => $this->getImage('beras-medium.png'),
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Ketan',
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
