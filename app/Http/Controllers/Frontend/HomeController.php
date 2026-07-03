<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\UserAddress;
use App\Models\Cart;
use App\Models\CartItem;
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

public function productAll(Request $request)
{
    $query = Product::with('category')
        ->where('is_active', 1);


    // SEARCH DARI NAVBAR
    if ($request->filled('search')) {
        $query->where(
            'name',
            'like',
            '%' . $request->search . '%'
        );
    }


    // FILTER CATEGORY (opsional)
    if ($request->filled('category_id')) {
        $query->where(
            'category_id',
            $request->category_id
        );
    }


    $products = $query->get();


    $categories = Category::all();


    return view('frontend.pages.all-product', compact(
    'products',
    'categories'
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

// BTN BELI dan KERANJANG di kirim ke Checkout
public function checkout(Request $request)
{
    $itemIds = json_decode($request->query('items', '[]'), true);

    if (empty($itemIds) || !is_array($itemIds)) {
        return redirect()->route('cart')->with('error', 'Pilih produk terlebih dahulu');
    }

    $items = CartItem::with('product')
        ->whereIn('id', $itemIds)
        ->get();

    if ($items->isEmpty()) {
        return redirect()->route('cart')->with('error', 'Item tidak valid');
    }

    return view('frontend.pages.checkout', compact('items'));
}
public function orderCustomerAll(Request $request)
    {
        $orders = Order::with('orderItems.product')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('frontend.pages.profile.order-all', compact('orders'));
    }
}
