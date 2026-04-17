<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
  
    public function index(Request $request)
    {
        $query = Product::with(['primaryImage', 'category'])
            ->where('is_active', 1);

        // Filter pencarian nama
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter kategori
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter harga
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filter stok tersedia
        if ($request->boolean('in_stock')) {
            $query->where('stock', '>', 0);
        }

        $allowedSorts = ['price', 'avg_rating', 'total_sold', 'created_at'];
        $sortBy  = in_array($request->sort_by, $allowedSorts) ? $request->sort_by : 'created_at';
        $sortDir = $request->sort_dir === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortBy, $sortDir);

        $products = $query->latest()->paginate(10);

        return response()->json([
            'success' => true,
            'data'    => $products,
        ]);
    }

  
    public function show(Product $product)
    {
        abort_if(! $product->is_active, 404, 'Produk tidak ditemukan');

        $product->load([
            'images',
            'category'
            //'reviews' => fn($q) => $q->with('user:id,name,avatar')->latest('created_at')->take(5),
        ]);

        return response()->json([
            'success' => true,
            'data'    => $product,
        ]);
    }

  
    // GET /api/products/slug/{slug}
    
    public function showBySlug(string $slug)
    {
        $product = Product::where('slug', $slug)->where('is_active', 1)->firstOrFail();
        return $this->show($product);
    }

   
    // GET /api/products/{product}/reviews
    // Query params: rating, per_page
  
    // public function reviews(Product $product, Request $request)
    // {
    //     $reviews = $product->reviews()
    //         ->with('user:id,name,avatar')
    //         ->when($request->filled('rating'), fn($q) => $q->where('rating', $request->rating))
    //         ->latest('created_at')
    //         ->paginate(10);

    //     $summary = [
    //         'avg_rating'    => $product->avg_rating,
    //         'total_reviews' => $product->reviews()->count(),
    //         'rating_counts' => $product->reviews()
    //             ->selectRaw('rating, count(*) as count')
    //             ->groupBy('rating')
    //             ->orderByDesc('rating')
    //             ->pluck('count', 'rating'),
    //     ];

    //     return response()->json([
    //         'success' => true,
    //         'data'    => [
    //             'summary' => $summary,
    //             'reviews' => $reviews,
    //         ],
    //     ]);
    // }
}