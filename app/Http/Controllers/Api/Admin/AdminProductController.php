<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminProductController extends Controller
{

   public function index(Request $request)
{
    $query = Product::with(['primaryImage', 'category']);

    // Search nama produk
    if ($request->filled('search')) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }

    // Filter kategori
    if ($request->filled('category_id')) {
        $query->where('category_id', $request->category_id);
    }

    // Filter status aktif
    if ($request->filled('is_active')) {
        $query->where('is_active', $request->boolean('is_active'));
    }

    $products = $query->latest()->paginate(10);

    $products->getCollection()->transform(function ($product) {
        return [
            'id'           => $product->id,
            'name'         => $product->name,
            'slug'         => $product->slug,
            'category'     => [
                'id'   => $product->category?->id,
                'name' => $product->category?->name,
            ],
            'price'        => $product->price,
            'price_format' => 'Rp.' . number_format($product->price, 0, ',', '.'),
            'stock'        => $product->stock,
            'stock_label'  => $product->stock . ' Karung ' . ($product->stock > 0 ? 'Tersedia' : 'Habis'),
            'unit'         => $product->unit,
            'is_active'    => $product->is_active,
            'avg_rating'   => $product->avg_rating,
            'total_sold'   => $product->total_sold,
            'image'        => $product->primaryImage?->image_url,
        ];
    });

    return response()->json([
        'success' => true,
        'data'    => $products,
    ]);
}


    public function show(Product $product)
    {
        $product->load(['images', 'category']);

        return response()->json([
            'success' => true,
            'data'    => [
                'id'          => $product->id,
                'name'        => $product->name,
                'slug'        => $product->slug,
                'description' => $product->description,
                'price'       => $product->price,
                'weight_kg'   => $product->weight_kg,
                'unit'        => $product->unit,
                'stock'       => $product->stock,
                'min_order'   => $product->min_order,
                'max_order'   => $product->max_order,
                'is_active'   => $product->is_active,
                'category'    => $product->category,
                'images'      => $product->images->map(fn($img) => [
                    'id'         => $img->id,
                    'image_url'  => Storage::url($img->image_url),
                    'is_primary' => $img->is_primary,
                    'sort_order' => $img->sort_order,
                ]),
            ],
        ]);
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:200|unique:products,name',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'weight_kg'   => 'nullable|numeric|min:0',
            'unit'        => 'nullable|in:kg,sack,liter',
            'stock'       => 'nullable|integer|min:0',
            'min_order'   => 'nullable|integer|min:1',
            'max_order'   => 'nullable|integer|min:1',
            'is_active'   => 'boolean',
            // Gambar produk (bisa multiple)
            'images'      => 'nullable|array|max:5',
            'images.*'    => 'image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'name.required'        => 'Nama produk wajib diisi',
            'name.unique'          => 'Nama produk sudah digunakan',
            'price.required'       => 'Harga produk wajib diisi',
            'price.min'            => 'Harga tidak boleh minus',
            'category_id.required' => 'Kategori produk wajib dipilih',
            'category_id.exists'   => 'Kategori tidak ditemukan',
            'images.*.image'       => 'File harus berupa gambar',
            'images.*.mimes'       => 'Format gambar harus JPG atau PNG',
            'images.*.max'         => 'Ukuran gambar maksimal 2MB',
        ]);

        DB::beginTransaction();
        try {
            $product = Product::create([
                'category_id' => $request->category_id,
                'name'        => $request->name,
                'slug'        => $this->generateUniqueSlug($request->name),
                'description' => $request->description,
                'price'       => $request->price,
                'weight_kg'   => $request->weight_kg,
                'unit'        => $request->unit ?? 'sack',
                'stock'       => $request->stock ?? 0,
                'min_order'   => $request->min_order ?? 1,
                'max_order'   => $request->max_order,
                'is_active'   => $request->boolean('is_active', true),
            ]);

            // Upload gambar jika ada
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('products', 'public');

                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_url'  => $path,
                        'is_primary' => $index === 0 ? 1 : 0, // Gambar pertama jadi primary
                        'sort_order' => $index,
                    ]);
                }
            }

            DB::commit();

            $product->load(['images', 'category']);

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan',
                'data'    => $product,
            ], 201);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Admin: Gagal tambah produk', ['error' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan produk',
            ], 500);
        }
    }

    
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'        => 'sometimes|string|max:200|unique:products,name,' . $product->id,
            'description' => 'nullable|string',
            'price'       => 'sometimes|numeric|min:0',
            'category_id' => 'sometimes|exists:categories,id',
            'weight_kg'   => 'nullable|numeric|min:0',
            'unit'        => 'nullable|in:kg,sack,liter',
            'stock'       => 'nullable|integer|min:0',
            'min_order'   => 'nullable|integer|min:1',
            'max_order'   => 'nullable|integer|min:1',
            'is_active'   => 'boolean',
            'images'      => 'nullable|array|max:5',
            'images.*'    => 'image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'name.unique'    => 'Nama produk sudah digunakan',
            'price.min'      => 'Harga tidak boleh minus',
            'images.*.image' => 'File harus berupa gambar',
            'images.*.mimes' => 'Format gambar harus JPG atau PNG',
            'images.*.max'   => 'Ukuran gambar maksimal 2MB',
        ]);

        DB::beginTransaction();
        try {
            // Update slug jika nama berubah
            $data = $request->only([
                'name', 'description', 'price', 'category_id',
                'weight_kg', 'unit', 'stock', 'min_order', 'max_order',
            ]);

            if ($request->filled('name') && $request->name !== $product->name) {
                $data['slug'] = $this->generateUniqueSlug($request->name, $product->id);
            }

            if ($request->has('is_active')) {
                $data['is_active'] = $request->boolean('is_active');
            }

            $product->update($data);

            // Upload gambar baru jika ada
            if ($request->hasFile('images')) {
                $currentCount = $product->images()->count();
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('products', 'public');

                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_url'  => $path,
                        'is_primary' => $currentCount === 0 && $index === 0 ? 1 : 0,
                        'sort_order' => $currentCount + $index,
                    ]);
                }
            }

            DB::commit();

            $product->load(['images', 'category']);

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil diperbarui',
                'data'    => $product,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Admin: Gagal update produk', ['error' => $e->getMessage(), 'product_id' => $product->id]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui produk',
            ], 500);
        }
    }

    // ──────────────────────────────────────────────────────────────
    // PATCH /api/admin/products/{product}/toggle-active
    // Toggle Tampilkan Produk (switch di form)
    // ──────────────────────────────────────────────────────────────
    public function toggleActive(Product $product)
    {
        $product->update(['is_active' => ! $product->is_active]);

        return response()->json([
            'success'   => true,
            'message'   => $product->is_active ? 'Produk ditampilkan' : 'Produk disembunyikan',
            'is_active' => $product->is_active,
        ]);
    }

    // ──────────────────────────────────────────────────────────────
    // DELETE /api/admin/products/{product}
    // Hapus 1 produk (ikon tempat sampah per baris)
    // ──────────────────────────────────────────────────────────────
    public function destroy(Product $product)
    {
        DB::transaction(function () use ($product) {
            // Hapus semua gambar dari storage
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->image_url);
            }

            $product->images()->delete();
            $product->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil dihapus',
        ]);
    }

    // ──────────────────────────────────────────────────────────────
    // DELETE /api/admin/products/bulk-delete
    // Hapus banyak produk sekaligus (checkbox di tabel)
    // Body: { ids: [1, 2, 3] }
    // ──────────────────────────────────────────────────────────────
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array|min:1',
            'ids.*' => 'exists:products,id',
        ], [
            'ids.required' => 'Pilih minimal 1 produk untuk dihapus',
            'ids.min'      => 'Pilih minimal 1 produk untuk dihapus',
        ]);

        $products = Product::whereIn('id', $request->ids)->with('images')->get();

        DB::transaction(function () use ($products) {
            foreach ($products as $product) {
                // Hapus gambar dari storage
                foreach ($product->images as $image) {
                    Storage::disk('public')->delete($image->image_url);
                }
                $product->images()->delete();
                $product->delete();
            }
        });

        return response()->json([
            'success' => true,
            'message' => count($request->ids) . ' produk berhasil dihapus',
        ]);
    }

    // ──────────────────────────────────────────────────────────────
    // POST /api/admin/products/{product}/images
    // Upload tambahan gambar produk
    // ──────────────────────────────────────────────────────────────
    public function uploadImages(Request $request, Product $product)
    {
        $request->validate([
            'images'   => 'required|array|min:1|max:5',
            'images.*' => 'image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'images.required' => 'Gambar wajib diupload',
            'images.*.image'  => 'File harus berupa gambar',
            'images.*.mimes'  => 'Format gambar harus JPG atau PNG',
            'images.*.max'    => 'Ukuran gambar maksimal 2MB',
        ]);

        $currentCount = $product->images()->count();
        $uploaded     = [];

        foreach ($request->file('images') as $index => $image) {
            $path = $image->store('products', 'public');

            $img = ProductImage::create([
                'product_id' => $product->id,
                'image_url'  => $path,
                'is_primary' => $currentCount === 0 && $index === 0 ? 1 : 0,
                'sort_order' => $currentCount + $index,
            ]);

            $uploaded[] = [
                'id'        => $img->id,
                'image_url' => Storage::url($path),
            ];
        }

        return response()->json([
            'success' => true,
            'message' => count($uploaded) . ' gambar berhasil diupload',
            'data'    => $uploaded,
        ], 201);
    }

    // ──────────────────────────────────────────────────────────────
    // DELETE /api/admin/products/{product}/images/{image}
    // Hapus 1 gambar produk
    // ──────────────────────────────────────────────────────────────
    public function deleteImage(Product $product, ProductImage $image)
    {
        // Pastikan gambar milik produk ini
        abort_if($image->product_id !== $product->id, 404, 'Gambar tidak ditemukan');

        Storage::disk('public')->delete($image->image_url);
        $wasPrimary = $image->is_primary;
        $image->delete();

        // Jika gambar yang dihapus adalah primary, set gambar pertama yang tersisa jadi primary
        if ($wasPrimary) {
            $product->images()->orderBy('sort_order')->first()?->update(['is_primary' => 1]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Gambar berhasil dihapus',
        ]);
    }

    // ──────────────────────────────────────────────────────────────
    // PATCH /api/admin/products/{product}/images/{image}/set-primary
    // Ganti gambar utama produk
    // ──────────────────────────────────────────────────────────────
    public function setPrimaryImage(Product $product, ProductImage $image)
    {
        abort_if($image->product_id !== $product->id, 404, 'Gambar tidak ditemukan');

        // Unset semua primary dulu
        $product->images()->update(['is_primary' => 0]);

        // Set gambar ini jadi primary
        $image->update(['is_primary' => 1]);

        return response()->json([
            'success' => true,
            'message' => 'Gambar utama berhasil diubah',
        ]);
    }

    // ──────────────────────────────────────────────────────────────
    // Helper: generate slug unik
    // ──────────────────────────────────────────────────────────────
    private function generateUniqueSlug(string $name, ?int $excludeId = null): string
    {
        $slug  = Str::slug($name);
        $query = Product::where('slug', $slug);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        if ($query->exists()) {
            $slug = $slug . '-' . time();
        }

        return $slug;
    }
}