<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Pastikan kategori sudah ada
        $mediumCategory = \App\Models\Category::where('name', 'Medium')->first();
        $premiumCategory = \App\Models\Category::where('name', 'Premium')->first();
        $ketanCategory = \App\Models\Category::where('name', 'Ketan')->first();

        $products = [
            // Produk Kategori Medium
            [
                'category_id' => $mediumCategory->id,
                'name' => 'Beras Medium 5kg',
                'description' => 'Beras kualitas medium dengan tekstur pulen, cocok untuk konsumsi sehari-hari. Dipanen dari petani lokal terbaik.',
                'price' => 65000,
                'weight_kg' => 5,
                'stock' => 100,
                'min_order' => 1,
                'max_order' => 20,
                'is_active' => true,
            ],
            [
                'category_id' => $mediumCategory->id,
                'name' => 'Beras Medium 10kg',
                'description' => 'Beras medium kemasan 10kg, lebih hemat untuk kebutuhan keluarga. Tekstur pulen dan berkualitas.',
                'price' => 125000,
                'weight_kg' => 10,
                'stock' => 50,
                'min_order' => 1,
                'max_order' => 10,
                'is_active' => true,
            ],
            [
                'category_id' => $mediumCategory->id,
                'name' => 'Beras Medium 25kg',
                'description' => 'Beras medium kemasan karung 25kg, cocok untuk kebutuhan bulanan atau usaha kuliner.',
                'price' => 300000,
                'weight_kg' => 25,
                'stock' => 30,
                'min_order' => 1,
                'max_order' => 5,
                'is_active' => true,
            ],
            
            // Produk Kategori Premium
            [
                'category_id' => $premiumCategory->id,
                'name' => 'Beras Premium 5kg',
                'description' => 'Beras premium dengan aroma pandan alami, tekstur super pulen. Cocok untuk acara spesial atau konsumsi sehari-hari yang lebih berkualitas.',
                'price' => 85000,
                'weight_kg' => 5,
                'stock' => 80,
                'min_order' => 1,
                'max_order' => 15,
                'is_active' => true,
            ],
            [
                'category_id' => $premiumCategory->id,
                'name' => 'Beras Premium 10kg',
                'description' => 'Beras premium kemasan 10kg, kualitas terbaik dengan butiran utuh dan bersih.',
                'price' => 165000,
                'weight_kg' => 10,
                'stock' => 40,
                'min_order' => 1,
                'max_order' => 10,
                'is_active' => true,
            ],
            [
                'category_id' => $premiumCategory->id,
                'name' => 'Beras Premium 25kg',
                'description' => 'Beras premium kemasan karung 25kg, cocok untuk restoran atau kebutuhan keluarga besar.',
                'price' => 400000,
                'weight_kg' => 25,
                'stock' => 20,
                'min_order' => 1,
                'max_order' => 5,
                'is_active' => true,
            ],
            
            // Produk Kategori Ketan
            [
                'category_id' => $ketanCategory->id,
                'name' => 'Beras Ketan Putih 1kg',
                'description' => 'Beras ketan putih kualitas baik, cocok untuk membuat lemper, dodol, atau jajanan tradisional lainnya.',
                'price' => 18000,
                'weight_kg' => 1,
                'stock' => 150,
                'min_order' => 1,
                'max_order' => 30,
                'is_active' => true,
            ],
            [
                'category_id' => $ketanCategory->id,
                'name' => 'Beras Ketan Putih 5kg',
                'description' => 'Beras ketan putih kemasan 5kg, cocok untuk usaha kuliner atau stok kebutuhan membuat kue tradisional.',
                'price' => 85000,
                'weight_kg' => 5,
                'stock' => 60,
                'min_order' => 1,
                'max_order' => 15,
                'is_active' => true,
            ],
            [
                'category_id' => $ketanCategory->id,
                'name' => 'Beras Ketan Hitam 1kg',
                'description' => 'Beras ketan hitam premium, kaya antioksidan, cocok untuk bubur ketan hitam dan aneka dessert.',
                'price' => 25000,
                'weight_kg' => 1,
                'stock' => 100,
                'min_order' => 1,
                'max_order' => 20,
                'is_active' => true,
            ],
        ];

        foreach ($products as $productData) {
            $product = Product::create([
                'category_id' => $productData['category_id'],
                'name' => $productData['name'],
                'slug' => Str::slug($productData['name']) . '-' . time() . '-' . rand(100, 999),
                'description' => $productData['description'],
                'price' => $productData['price'],
                'weight_kg' => $productData['weight_kg'],
                'stock' => $productData['stock'],
                'min_order' => $productData['min_order'],
                'max_order' => $productData['max_order'],
                'is_active' => $productData['is_active'],
            ]);

            // Optional: Tambahkan dummy images jika diperlukan
            // $this->addDummyImages($product);
        }
    }

    /**
     * Optional: Method untuk menambahkan dummy images
     * (Anda perlu menyiapkan file gambar contoh terlebih dahulu)
     */
    private function addDummyImages($product)
    {
        // Contoh jika ingin menambahkan gambar dummy
        // Sesuaikan dengan path gambar yang Anda miliki
        $dummyImages = [
            'products/dummy-1.jpg',
            'products/dummy-2.jpg',
            'products/dummy-3.jpg',
        ];

        foreach ($dummyImages as $index => $imagePath) {
            ProductImage::create([
                'product_id' => $product->id,
                'image_url' => $imagePath,
                'is_primary' => $index === 0,
                'sort_order' => $index,
            ]);
        }
    }
}