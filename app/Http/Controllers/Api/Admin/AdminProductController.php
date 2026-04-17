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
    Log::info($request->all());

    $validated = $request->validate([
        'name'        => 'required|string|max:200|unique:products,name',
        'description' => 'nullable|string',
        'price'       => 'required|numeric|min:0',
        'category_id' => 'required|exists:categories,id',
        'weight_kg'   => 'nullable|numeric|min:0',
        'stock'       => 'nullable|integer|min:0',
        'min_order'   => 'nullable|integer|min:1',
        'max_order'   => 'nullable|integer|gte:min_order',
        'is_active'   => 'nullable|boolean',
        'images'      => 'nullable|array|max:5',
        'images.*'    => 'image|mimes:jpg,jpeg,png|max:2048',
    ]);

    DB::beginTransaction();

    try {
        $product = Product::create([
            'category_id' => $validated['category_id'],
            'name'        => $validated['name'],
            'slug'        => Str::slug($validated['name']) . '-' . time(),
            'description' => $validated['description'] ?? null,
            'price'       => $validated['price'],
            'weight_kg'   => $validated['weight_kg'] ?? 0,
            'stock'       => $validated['stock'] ?? 0,
            'min_order'   => $validated['min_order'] ?? 1,
            'max_order'   => $validated['max_order'] ?? null,
            'is_active'   => filter_var($request->input('is_active', true), FILTER_VALIDATE_BOOLEAN),
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                if (!$image->isValid()) {
                    throw new \Exception('File image tidak valid pada index ' . $index);
                }

                $path = $image->store('products', 'public');

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_url'  => $path,
                    'is_primary' => $index === 0,
                    'sort_order' => $index,
                ]);
            }
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan',
            'data'    => $product->load(['images', 'category']),
        ], 201);

    } catch (\Throwable $e) {
        DB::rollBack();

        Log::error('ERROR STORE PRODUCT', [
            'error' => $e->getMessage(),
            'line'  => $e->getLine(),
            'file'  => $e->getFile(),
            'trace' => $e->getTraceAsString(),
        ]);

        return response()->json([
            'success' => false,
            'error'   => $e->getMessage(),
            'line'    => $e->getLine(),
        ], 500);
    }
}
    
public function update(Request $request, Product $product)
{
    // Method spoofing akan otomatis dihandle Laravel
    // $request->method() akan mengembalikan 'PUT'
    
    Log::info('UPDATE PRODUCT', [
        'method' => $request->method(), // Akan jadi 'PUT'
        'product_id' => $product->id,
        'data' => $request->all()
    ]);

    // Validasi tetap sama
    $validated = $request->validate([
        'name'        => 'sometimes|required|string|max:200|unique:products,name,' . $product->id,
        'description' => 'nullable|string',
        'price'       => 'sometimes|required|numeric|min:0',
        'category_id' => 'sometimes|required|exists:categories,id',
        'weight_kg'   => 'nullable|numeric|min:0',
        'unit'        => 'nullable|string|max:50',
        'stock'       => 'nullable|integer|min:0',
        'min_order'   => 'nullable|integer|min:1',
        'max_order'   => 'nullable|integer|gte:min_order',
        'is_active'   => 'nullable|boolean',
        'images'      => 'nullable|array|max:5',
        'images.*'    => 'image|mimes:jpg,jpeg,png|max:2048',
        'delete_images' => 'nullable|array',
        'delete_images.*' => 'integer|exists:product_images,id'
    ]);

    DB::beginTransaction();

    try {
        // Update product data
        $updateData = [];
        
        if ($request->has('name')) {
            $updateData['name'] = $validated['name'];
            $updateData['slug'] = Str::slug($validated['name']) . '-' . time();
        }
        if ($request->has('description')) $updateData['description'] = $validated['description'];
        if ($request->has('price')) $updateData['price'] = $validated['price'];
        if ($request->has('category_id')) $updateData['category_id'] = $validated['category_id'];
        if ($request->has('weight_kg')) $updateData['weight_kg'] = $validated['weight_kg'];
        if ($request->has('unit')) $updateData['unit'] = $validated['unit'];
        if ($request->has('stock')) $updateData['stock'] = $validated['stock'];
        if ($request->has('min_order')) $updateData['min_order'] = $validated['min_order'];
        if ($request->has('max_order')) $updateData['max_order'] = $validated['max_order'];
        if ($request->has('is_active')) {
            $updateData['is_active'] = filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN);
        }
        
        $product->update($updateData);

        // Handle delete images
        if ($request->has('delete_images')) {
            $deleteIds = is_array($request->delete_images) 
                ? $request->delete_images 
                : explode(',', $request->delete_images);
            
            $imagesToDelete = ProductImage::where('product_id', $product->id)
                ->whereIn('id', $deleteIds)
                ->get();
            
            foreach ($imagesToDelete as $image) {
                Storage::disk('public')->delete($image->image_url);
                $image->delete();
            }
        }

        // Handle upload new images
        if ($request->hasFile('images')) {
            $maxSortOrder = ProductImage::where('product_id', $product->id)
                ->max('sort_order') ?? -1;
            
            foreach ($request->file('images') as $index => $image) {
                if (!$image->isValid()) {
                    throw new \Exception('File image tidak valid pada index ' . $index);
                }

                $path = $image->store('products', 'public');
                
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_url'  => $path,
                    'is_primary' => false,
                    'sort_order' => $maxSortOrder + $index + 1,
                ]);
            }
        }

        // Set primary image if none exists
        $hasPrimary = ProductImage::where('product_id', $product->id)
            ->where('is_primary', true)
            ->exists();
            
        if (!$hasPrimary) {
            $firstImage = ProductImage::where('product_id', $product->id)
                ->orderBy('sort_order')
                ->first();
                
            if ($firstImage) {
                $firstImage->update(['is_primary' => true]);
            }
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil diperbarui',
            'data'    => $product->load(['images', 'category']),
        ], 200);

    } catch (\Throwable $e) {
        DB::rollBack();

        Log::error('ERROR UPDATE PRODUCT', [
            'product_id' => $product->id,
            'error' => $e->getMessage(),
            'line'  => $e->getLine(),
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Gagal memperbarui produk: ' . $e->getMessage(),
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