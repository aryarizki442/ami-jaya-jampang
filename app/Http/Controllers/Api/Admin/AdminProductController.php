<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;



class AdminProductController extends Controller
{

    public function index(Request $request)
{
    $query = Product::with('category');

    // Search dan filter...
    if ($request->filled('search')) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }
    if ($request->filled('category_id')) {
        $query->where('category_id', $request->category_id);
    }
    if ($request->filled('is_active')) {
        $query->where('is_active', $request->boolean('is_active'));
    }
    if ($request->filled('is_recommended')) {
        $query->where('is_recommended', $request->boolean('is_recommended'));
    }

    $perPage = $request->input('per_page', 10);
    $products = $query->latest()->paginate($perPage);

    // Transformasi data - LANGSUNG PAKAI ASSET
    $products->getCollection()->transform(function ($product) {
        return [
            'id'             => $product->id,
            'name'           => $product->name,
            'slug'           => $product->slug,
            'category'       => [
                'id'   => $product->category?->id,
                'name' => $product->category?->name,
            ],
            'price'          => $product->price,
            'price_format'   => 'Rp.' . number_format($product->price, 0, ',', '.'),
            'stock'          => $product->stock,
            'stock_label'    => $product->stock . ' Karung ' . ($product->stock > 0 ? 'Tersedia' : 'Habis'),
            'is_active'      => $product->is_active,
            'is_recommended' => $product->is_recommended,
            'avg_rating'     => $product->avg_rating,
            'total_sold'     => $product->total_sold,
            'image'          => $product->image ? asset('storage/' . $product->image) : null, 
        ];
    });

    return response()->json([
        'success' => true,
        'data'    => $products,
    ]);
}

    public function show(Product $product)
    {
        return response()->json([
            'success' => true,
            'data'    => [
                'id'             => $product->id,
                'name'           => $product->name,
                'slug'           => $product->slug,
                'description'    => $product->description,
                'price'          => $product->price,
                'weight_kg'      => $product->weight_kg,
                'stock'          => $product->stock,
                'min_order'      => $product->min_order,
                'max_order'      => $product->max_order,
                'is_active'      => $product->is_active,
                'is_recommended' => $product->is_recommended,
                'category'       => $product->category,
                'image'          => $product->image_url, 
            ],
        ]);
    }

    public function store(Request $request)
    {
        Log::info('STORE PRODUCT', $request->all());

        $validated = $request->validate([
            'name'           => 'required|string|max:200|unique:products,name',
            'description'    => 'nullable|string',
            'price'          => 'required|numeric|min:0',
            'category_id'    => 'required|exists:categories,id',
            'weight_kg'      => 'nullable|numeric|min:0',
            'stock'          => 'nullable|integer|min:0',
            'min_order'      => 'nullable|integer|min:1',
            'max_order'      => 'nullable|integer|gte:min_order',
            'is_active'      => 'nullable|boolean',
            'is_recommended' => 'nullable|boolean',
            'image'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        DB::beginTransaction();

        try {
            $productData = [
                'category_id'    => $validated['category_id'],
                'name'           => $validated['name'],
                'slug'           => Str::slug($validated['name']) . '-' . time(),
                'description'    => $validated['description'] ?? null,
                'price'          => $validated['price'],
                'weight_kg'      => $validated['weight_kg'] ?? 0,
                'stock'          => $validated['stock'] ?? 0,
                'min_order'      => $validated['min_order'] ?? 1,
                'max_order'      => $validated['max_order'] ?? null,
                'is_active'      => filter_var($request->input('is_active', true), FILTER_VALIDATE_BOOLEAN),
                'is_recommended' => filter_var($request->input('is_recommended', false), FILTER_VALIDATE_BOOLEAN),
            ];

            // Upload image jika ada
            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('products', 'public');
                $productData['image'] = $path;
                
                Log::info('Image uploaded', ['path' => $path]);
            }

            $product = Product::create($productData);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil ditambahkan',
                'data'    => $product->load('category'),
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('ERROR STORE PRODUCT', [
                'error' => $e->getMessage(),
                'line'  => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan produk: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, Product $product)
    {
        Log::info('UPDATE PRODUCT', [
            'product_id' => $product->id,
            'data'       => $request->all()
        ]);

        $validated = $request->validate([
            'name'           => 'sometimes|required|string|max:200|unique:products,name,' . $product->id,
            'description'    => 'nullable|string',
            'price'          => 'sometimes|required|numeric|min:0',
            'category_id'    => 'sometimes|required|exists:categories,id',
            'weight_kg'      => 'nullable|numeric|min:0',
            'stock'          => 'nullable|integer|min:0',
            'min_order'      => 'nullable|integer|min:1',
            'max_order'      => 'nullable|integer|gte:min_order',
            'is_active'      => 'nullable|boolean',
            'is_recommended' => 'nullable|boolean',
            'image'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        DB::beginTransaction();

        try {
            $updateData = [];

            if ($request->has('name')) {
                $updateData['name'] = $validated['name'];
                $updateData['slug'] = Str::slug($validated['name']) . '-' . time();
            }
            if ($request->has('description')) $updateData['description'] = $validated['description'];
            if ($request->has('price')) $updateData['price'] = $validated['price'];
            if ($request->has('category_id')) $updateData['category_id'] = $validated['category_id'];
            if ($request->has('weight_kg')) $updateData['weight_kg'] = $validated['weight_kg'];
            if ($request->has('stock')) $updateData['stock'] = $validated['stock'];
            if ($request->has('min_order')) $updateData['min_order'] = $validated['min_order'];
            if ($request->has('max_order')) $updateData['max_order'] = $validated['max_order'];
            if ($request->has('is_active')) {
                $updateData['is_active'] = filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN);
            }
            if ($request->has('is_recommended')) {
                $updateData['is_recommended'] = filter_var($request->is_recommended, FILTER_VALIDATE_BOOLEAN);
            }

            // Handle single image upload (replace gambar lama)
            if ($request->hasFile('image')) {
                // Hapus gambar lama jika ada
                if ($product->image && Storage::disk('public')->exists($product->image)) {
                    Storage::disk('public')->delete($product->image);
                    Log::info('Old image deleted', ['path' => $product->image]);
                }

                // Upload gambar baru
                $path = $request->file('image')->store('products', 'public');
                $updateData['image'] = $path;
                
                Log::info('New image uploaded', ['path' => $path]);
            }

            $product->update($updateData);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil diperbarui',
                'data'    => $product->load('category'),
            ], 200);

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('ERROR UPDATE PRODUCT', [
                'product_id' => $product->id,
                'error'      => $e->getMessage(),
                'line'       => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui produk: ' . $e->getMessage(),
            ], 500);
        }
    }

    // Toggle status aktif
    public function toggleActive(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);

        return response()->json([
            'success'   => true,
            'message'   => $product->is_active ? 'Produk ditampilkan' : 'Produk disembunyikan',
            'is_active' => $product->is_active,
        ]);
    }

    // Toggle status rekomendasi
    public function toggleRecommended(Product $product)
    {
        $product->update(['is_recommended' => !$product->is_recommended]);

        return response()->json([
            'success'        => true,
            'message'        => $product->is_recommended ? 'Produk ditambahkan ke rekomendasi' : 'Produk dihapus dari rekomendasi',
            'is_recommended' => $product->is_recommended,
        ]);
    }

    // Hapus 1 produk
    public function destroy(Product $product)
    {
        DB::transaction(function () use ($product) {
            // Hapus gambar dari storage
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            $product->delete();
        });

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil dihapus',
        ]);
    }

    // Hapus banyak produk sekaligus
    public function bulkDelete(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array|min:1',
            'ids.*' => 'exists:products,id',
        ], [
            'ids.required' => 'Pilih minimal 1 produk untuk dihapus',
            'ids.min'      => 'Pilih minimal 1 produk untuk dihapus',
        ]);

        $products = Product::whereIn('id', $request->ids)->get();

        DB::transaction(function () use ($products) {
            foreach ($products as $product) {
                // Hapus gambar dari storage
                if ($product->image && Storage::disk('public')->exists($product->image)) {
                    Storage::disk('public')->delete($product->image);
                }
                $product->delete();
            }
        });

        return response()->json([
            'success' => true,
            'message' => count($request->ids) . ' produk berhasil dihapus',
        ]);
    }
}
