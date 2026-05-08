<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::with('products')->get();

        $products = Product::latest()->get();

        $recommendedProducts = Product::where('is_active', 1)
            ->where('is_recommended', 1)
            ->latest()
            ->get();

        return view('frontend.pages.home', compact(
            'categories',
            'products',
            'recommendedProducts'
        ));
    }
    public function detail($slug)
{
    $product = Product::with('category')
        ->where('slug', $slug)
        ->firstOrFail();

    $products = Product::where('is_active', 1)
        ->latest()
        ->get();

    $recommendedProducts = Product::where('is_active', 1)
    ->where('is_recommended', 1)
    ->where('id', '!=', $product->id)
    ->latest()
    ->get();

    return view('frontend.pages.detail-product', compact('product', 'products', 'recommendedProducts'));
}

    public function productAll()
    {
        $categories = Category::with('products')->get();

        $products = Product::latest()->get();

        $recommendedProducts = Product::where('is_active', 1)
            ->where('is_recommended', 1)
            ->latest()
            ->get();

        return view('frontend.pages.all-product', compact(
            'categories',
            'products',
            'recommendedProducts'
        ));
    }
}
