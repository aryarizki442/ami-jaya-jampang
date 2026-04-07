<?php

namespace App\Http\Controllers\Api;

use App\Models\Cart;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{

    private function user(): User
    {
        return Auth::user();
    }

    private function getCart(): Cart
    {
        return Cart::firstOrCreate([
            'user_id' => $this->user()->id
        ]);
    }


    public function index()
    {
        $cart = $this->getCart();

        $cart->load([
            'items.product.primaryImage'
        ]);

        $selectedTotal = $cart->items
            ->where('is_selected', true)
            ->sum(fn($item) => $item->price * $item->quantity);

        return response()->json([
            'success' => true,
            'data' => [

               
                'items' => $cart->items->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'product_id' => $item->product_id,
                        'product_name' => $item->product->name,
                        'image' => $item->product->primaryImage?->url,
                        'price' => $item->price,
                        'quantity' => $item->quantity,
                        'subtotal' => $item->price * $item->quantity, // ⭐ TAMBAHAN
                        'is_selected' => $item->is_selected
                    ];
                }),

                
                'summary' => [
                    'total_items' => $cart->items->count(), // ⭐ TAMBAHAN
                    'total_quantity' => $cart->items->sum('quantity'), // ⭐ TAMBAHAN
                    'selected_total' => $selectedTotal
                ]
            ]
        ]);
    }



    public function addItem(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);

        if (!$product->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak tersedia'
            ], 422);
        }

  
        if ($product->stock < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Stok tidak mencukupi'
            ], 422);
        }

        return DB::transaction(function () use ($request, $product) {

            $cart = $this->getCart();

            $item = $cart->items()
                ->where('product_id', $product->id)
                ->first();

            if ($item) {

                $newQty = $item->quantity + $request->quantity;

             
                if ($product->stock < $newQty) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Stok tidak mencukupi'
                    ], 422);
                }

                $item->update([
                    'quantity' => $newQty
                ]);

            } else {

                $item = $cart->items()->create([
                    'product_id' => $product->id,
                    'price' => $product->price, 
                    'quantity' => $request->quantity,
                    'is_selected' => true
                ]);
            }

            $item->load('product.primaryImage');

            return response()->json([
                'success' => true,
                'message' => 'Produk ditambahkan ke cart',
                'data' => $item
            ], 201);

        });
    }



    public function updateItem(Request $request, CartItem $item)
    {
        abort_if($item->cart->user_id !== $this->user()->id, 403);

        $request->validate([
            'quantity' => 'sometimes|integer|min:1',
            'is_selected' => 'sometimes|boolean'
        ]);

        // ⭐ TAMBAHAN: cek stok jika quantity diubah
        if ($request->has('quantity')) {

            if ($item->product->stock < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok tidak mencukupi'
                ], 422);
            }
        }

        $item->update(
            $request->only('quantity', 'is_selected')
        );

        return response()->json([
            'success' => true,
            'message' => 'Cart diperbarui',
            'data' => $item->fresh('product.primaryImage')
        ]);
    }



    public function removeItem(CartItem $item)
    {
        abort_if($item->cart->user_id !== $this->user()->id, 403);

        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item dihapus dari cart'
        ]);
    }



    public function selectAll(Request $request)
    {
        $request->validate([
            'selected' => 'required|boolean'
        ]);

        $this->getCart()
            ->items()
            ->update([
                'is_selected' => $request->boolean('selected')
            ]);

        return response()->json([
            'success' => true,
            'message' => 'Semua item diperbarui'
        ]);
    }



    public function clear()
    {
        $this->getCart()->items()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cart dikosongkan'
        ]);
    }
}
