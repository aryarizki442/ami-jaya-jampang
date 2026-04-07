<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminCategoryController extends Controller
{
    // ──────────────────────────────────────────────────────────────
    // GET /api/admin/categories
    // ──────────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $categories = Category::withCount('products')
            ->when($request->filled('search'), fn($q) => $q->where('name', 'like', '%' . $request->search . '%'))
            ->when($request->filled('is_active'), fn($q) => $q->where('is_active', $request->boolean('is_active')))
            ->get();

        return response()->json(['success' => true, 'data' => $categories]);
    }

    // ──────────────────────────────────────────────────────────────
    // POST /api/admin/categories
    // ──────────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:100|unique:categories,name',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
            'is_active'   => 'boolean',
        ], [
            'name.required' => 'Nama kategori wajib diisi',
            'name.unique'   => 'Nama kategori sudah ada',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('categories', 'public');
        }

        $category = Category::create([
            'name'        => $request->name,
            'slug'        => $this->generateUniqueSlug($request->name),
            'description' => $request->description,
            'image'       => $imagePath,
            'is_active'   => $request->boolean('is_active', true),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil ditambahkan',
            'data'    => $category,
        ], 201);
    }

    // ──────────────────────────────────────────────────────────────
    // PUT /api/admin/categories/{category}
    // ──────────────────────────────────────────────────────────────
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name'        => 'sometimes|string|max:100|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
            'is_active'   => 'boolean',
        ]);

        $data = $request->only('name', 'description');

        if ($request->filled('name') && $request->name !== $category->name) {
            $data['slug'] = $this->generateUniqueSlug($request->name, $category->id);
        }

        if ($request->has('is_active')) {
            $data['is_active'] = $request->boolean('is_active');
        }

        if ($request->hasFile('image')) {
            // Hapus gambar lama
            if ($category->image) {
                Storage::disk('public')->delete($category->image);
            }
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        $category->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil diperbarui',
            'data'    => $category->fresh(),
        ]);
    }

    // ──────────────────────────────────────────────────────────────
    // DELETE /api/admin/categories/{category}
    // ──────────────────────────────────────────────────────────────
    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori tidak bisa dihapus karena masih memiliki produk',
            ], 422);
        }

        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return response()->json(['success' => true, 'message' => 'Kategori berhasil dihapus']);
    }

    // ──────────────────────────────────────────────────────────────
    // PATCH /api/admin/categories/{category}/toggle-active
    // ──────────────────────────────────────────────────────────────
    public function toggleActive(Category $category)
    {
        $category->update(['is_active' => ! $category->is_active]);

        return response()->json([
            'success'   => true,
            'message'   => $category->is_active ? 'Kategori ditampilkan' : 'Kategori disembunyikan',
            'is_active' => $category->is_active,
        ]);
    }

    private function generateUniqueSlug(string $name, ?int $excludeId = null): string
    {
        $slug  = Str::slug($name);
        $query = Category::where('slug', $slug);
        if ($excludeId) $query->where('id', '!=', $excludeId);
        return $query->exists() ? $slug . '-' . time() : $slug;
    }
}