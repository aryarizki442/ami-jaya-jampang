<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class ReviewController extends Controller
{
    private function user() { return JWTAuth::parseToken()->authenticate(); }

    // ──────────────────────────────────────────────────────────────
    // GET /api/reviews/pending
    // List produk yang belum diulas dari order yang sudah completed
    // ──────────────────────────────────────────────────────────────
    public function pending()
    {
        $user = $this->user();

        $items = OrderItem::with(['product.primaryImage', 'order'])
            ->whereHas('order', fn($q) =>
                $q->where('user_id', $user->id)
                  ->where('status', 'completed')
            )
            ->where('is_reviewed', 0)
            ->latest('id')
            ->paginate(10);

        $items->getCollection()->transform(fn($item) => [
            'order_item_id'     => $item->id,
            'order_number'      => $item->order->order_number,
            'order_date'        => $item->order->created_at->format('d M Y'),
            'product_id'        => $item->product_id,
            'product_name'      => $item->product_name,
            'product_image'     => $item->product_image,
            'product_unit'      => $item->product_unit,
            'quantity'          => $item->quantity,
            'unit_price'        => $item->unit_price,
            'unit_price_format' => 'Rp.' . number_format($item->unit_price, 0, ',', '.'),
        ]);

        return response()->json([
            'success' => true,
            'data'    => $items,
        ]);
    }

    // ──────────────────────────────────────────────────────────────
    // GET /api/reviews/my
    // Semua ulasan yang pernah dikirim oleh user
    // ──────────────────────────────────────────────────────────────
    public function myReviews(Request $request)
    {
        $user = $this->user();

        $reviews = ProductReview::with(['product.primaryImage', 'orderItem.order'])
            ->where('user_id', $user->id)
            ->latest('created_at')
            ->paginate($request->get('per_page', 10));

        $reviews->getCollection()->transform(fn($review) => [
            'id'           => $review->id,
            'rating'       => $review->rating,
            'rating_stars' => str_repeat('⭐', $review->rating),
            'comment'      => $review->comment,
            'created_at'   => $review->created_at->format('d M Y'),
            'product' => [
                'id'    => $review->product->id,
                'name'  => $review->product->name,
                'image' => $review->product->primaryImage?->image_url,
            ],
            'order_number' => $review->orderItem?->order?->order_number,
        ]);

        return response()->json([
            'success' => true,
            'data'    => $reviews,
        ]);
    }

    // ──────────────────────────────────────────────────────────────
    // POST /api/reviews
    // Kirim ulasan baru (CREATE)
    // Body: { order_item_id, rating, comment? }
    //
    // Syarat:
    // - Order harus milik user yang login
    // - Order harus berstatus completed
    // - Item belum pernah diulas (is_reviewed = 0)
    // ──────────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $user = $this->user();

        $request->validate([
            'order_item_id' => 'required|exists:order_items,id',
            'rating'        => 'required|integer|min:1|max:5',
            'comment'       => 'nullable|string|max:1000',
        ], [
            'order_item_id.required' => 'Item pesanan wajib dipilih',
            'order_item_id.exists'   => 'Item pesanan tidak ditemukan',
            'rating.required'        => 'Rating bintang wajib diisi',
            'rating.integer'         => 'Rating harus berupa angka',
            'rating.min'             => 'Rating minimal 1 bintang',
            'rating.max'             => 'Rating maksimal 5 bintang',
            'comment.max'            => 'Komentar maksimal 1000 karakter',
        ]);

        $orderItem = OrderItem::with('order')->findOrFail($request->order_item_id);

        // Pastikan order milik user ini
        if ($orderItem->order->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak',
            ], 403);
        }

        // Order harus sudah completed
        if ($orderItem->order->status !== 'completed') {
            return response()->json([
                'success' => false,
                'message' => 'Ulasan hanya bisa diberikan setelah pesanan selesai',
            ], 422);
        }

        // Belum pernah diulas
        if ($orderItem->is_reviewed) {
            return response()->json([
                'success' => false,
                'message' => 'Produk ini sudah pernah diulas',
            ], 422);
        }

        $review = null;

        DB::transaction(function () use ($request, $user, $orderItem, &$review) {
            // Simpan ulasan
            $review = ProductReview::create([
                'product_id'    => $orderItem->product_id,
                'user_id'       => $user->id,
                'order_item_id' => $orderItem->id,
                'rating'        => $request->rating,
                'comment'       => $request->comment,
            ]);

            // Tandai item sudah diulas
            $orderItem->update(['is_reviewed' => 1]);

            // Hitung ulang avg_rating produk
            $avg = ProductReview::where('product_id', $orderItem->product_id)->avg('rating');
            $orderItem->product->update(['avg_rating' => round($avg, 2)]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Ulasan berhasil dikirim. Terima kasih! 🌟',
            'data'    => [
                'id'         => $review->id,
                'rating'     => $review->rating,
                'comment'    => $review->comment,
                'created_at' => $review->created_at->format('d M Y'),
            ],
        ], 201);
    }

    // ──────────────────────────────────────────────────────────────
    // PUT /api/reviews/{review}
    // Edit ulasan — hanya bisa dalam 24 jam setelah dibuat
    // Body: { rating, comment? }
    // ──────────────────────────────────────────────────────────────
    public function update(Request $request, ProductReview $review)
    {
        $user = $this->user();

        if ($review->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak',
            ], 403);
        }

        // Hanya bisa edit dalam 24 jam
        if ($review->created_at->diffInHours(now()) > 24) {
            return response()->json([
                'success' => false,
                'message' => 'Ulasan hanya bisa diedit dalam 24 jam setelah dikirim',
            ], 422);
        }

        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ], [
            'rating.required' => 'Rating bintang wajib diisi',
            'rating.min'      => 'Rating minimal 1 bintang',
            'rating.max'      => 'Rating maksimal 5 bintang',
        ]);

        DB::transaction(function () use ($request, $review) {
            $review->update([
                'rating'  => $request->rating,
                'comment' => $request->comment,
            ]);

            // Hitung ulang avg_rating
            $avg = ProductReview::where('product_id', $review->product_id)->avg('rating');
            $review->product->update(['avg_rating' => round($avg, 2)]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Ulasan berhasil diperbarui',
            'data'    => [
                'id'         => $review->id,
                'rating'     => $review->rating,
                'comment'    => $review->comment,
                'created_at' => $review->created_at->format('d M Y'),
            ],
        ]);
    }

    // ──────────────────────────────────────────────────────────────
    // DELETE /api/reviews/{review}
    // Hapus ulasan sendiri
    // ──────────────────────────────────────────────────────────────
    public function destroy(ProductReview $review)
    {
        $user = $this->user();

        if ($review->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak',
            ], 403);
        }

        DB::transaction(function () use ($review) {
            $productId = $review->product_id;

            // Kembalikan flag is_reviewed
            $review->orderItem?->update(['is_reviewed' => 0]);
            $review->delete();

            // Hitung ulang avg_rating
            $avg = ProductReview::where('product_id', $productId)->avg('rating');
            $review->product->update(['avg_rating' => $avg ? round($avg, 2) : 0]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Ulasan berhasil dihapus',
        ]);
    }
}