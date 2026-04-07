<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    
    public function index()
    {
        $categories = Category::where('is_active', 1)
            ->withCount('activeProducts')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $categories,
        ]);
    }


    public function show(Category $category)
    {
        $category->load([
            'activeProducts' => function ($q) {
                $q->with('primaryImage')
                  ->orderByDesc('total_sold')
                  ->take(20);
            }
        ]);

        return response()->json([
            'success' => true,
            'data'    => $category,
        ]);
    }
}