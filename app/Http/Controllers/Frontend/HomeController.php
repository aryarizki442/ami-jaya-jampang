<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\UserAddress;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
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

    // default kosong
    $cartItems = collect();

    // hanya ambil cart jika login
    if (auth()->check()) {

        $cartItems = Cart::with('product')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();
    }

    return view('frontend.pages.home', compact(
        'categories',
        'products',
        'recommendedProducts',
        'cartItems'
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
    public function cart()
{
    $recommendedProducts = Product::where('is_active', 1)
        ->where('is_recommended', 1)
        ->latest()
        ->get();

    return view('frontend.pages.cart', compact('recommendedProducts'));
}

public function checkout(Request $request)
{
    $user = Auth::user();

    if (!$user) {
        return redirect()->route('login');
    }

    $cart = Cart::where('user_id', $user->id)
        ->with(['items.product'])
        ->first();

    if (!$cart) {
        return view('checkout', [
            'items' => collect()
        ]);
    }

    $items = $cart->items()->where('is_selected', 1)->get();

    return view('frontend.pages.checkout', compact('items'));
}

}
