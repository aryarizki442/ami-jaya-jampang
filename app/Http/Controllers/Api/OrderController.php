<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class OrderController extends Controller
{
    // Konstanta ongkir
    const ONGKIR_PER_SACK       = 2000;  // Rp2.000 per sack
    const MIN_QTY_DELIVERY      = 15;    // minimal 15 sack untuk bisa delivery

    private function user() { return JWTAuth::parseToken()->authenticate(); }

    // ──────────────────────────────────────────────────────────────
    // GET /api/orders
    // ──────────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $orders = Order::with(['items', 'payment.paymentMethod'])
            ->where('user_id', $this->user()->id)
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate($request->get('per_page', 10));

        $orders->getCollection()->transform(fn($order) => $this->formatOrder($order));

        return response()->json([
            'success' => true,
            'data'    => $orders,
        ]);
    }

    // ──────────────────────────────────────────────────────────────
    // GET /api/orders/{order}
    // ──────────────────────────────────────────────────────────────
    public function show(Order $order)
    {
        abort_if($order->user_id !== $this->user()->id, 403, 'Akses ditolak');

        $order->load([
            'items.product.primaryImage',
            'address',
            'payment.paymentMethod',
        ]);

        return response()->json([
            'success' => true,
            'data'    => $this->formatOrderDetail($order),
        ]);
    }

    // ──────────────────────────────────────────────────────────────
    // GET /api/orders/shipping-calculate
    // Hitung estimasi ongkir sebelum checkout
    // Query: item_ids[] atau pakai semua yang is_selected
    // ──────────────────────────────────────────────────────────────
    public function calculateShipping(Request $request)
    {
        $user = $this->user();
        $cart = $user->cart()->with('items.product')->first();

        if (! $cart) {
            return response()->json(['success' => false, 'message' => 'Keranjang kosong'], 404);
        }

        $itemsQuery = $cart->items()->with('product');
        if ($request->filled('item_ids')) {
            $itemsQuery->whereIn('id', $request->item_ids);
        } else {
            $itemsQuery->where('is_selected', 1);
        }
        $cartItems = $itemsQuery->get();

        $totalQty = $cartItems->sum('quantity');
        $result   = $this->hitungOngkir($totalQty);

        return response()->json([
            'success' => true,
            'data'    => [
                'total_quantity'    => $totalQty,
                'min_qty_delivery'  => self::MIN_QTY_DELIVERY,
                'ongkir_per_sack'   => self::ONGKIR_PER_SACK,
                'can_delivery'      => $result['can_delivery'],
                'shipping_cost'     => $result['shipping_cost'],
                'shipping_cost_format' => 'Rp.' . number_format($result['shipping_cost'], 0, ',', '.'),
                'info'              => $result['info'],
            ],
        ]);
    }

    // ──────────────────────────────────────────────────────────────
    // POST /api/orders
    // Buat order baru dari keranjang
    // Body: {
    //   address_id*        → id alamat pengiriman
    //   payment_method_id* → id metode pembayaran
    //   delivery_method*   → delivery / pickup
    //   note               → catatan (opsional)
    //   item_ids           → id cart_items (opsional, default semua is_selected)
    // }
    // ──────────────────────────────────────────────────────────────
    public function store(Request $request)
    {
        $request->validate([
            'address_id'        => 'required|exists:user_addresses,id',
            'payment_method_id' => 'required|exists:payment_methods,id',
            'delivery_method'   => 'required|in:delivery,pickup',
            'note'              => 'nullable|string|max:500',
            'item_ids'          => 'nullable|array',
            'item_ids.*'        => 'exists:cart_items,id',
        ], [
            'address_id.required'        => 'Alamat pengiriman wajib dipilih',
            'address_id.exists'          => 'Alamat tidak ditemukan',
            'payment_method_id.required' => 'Metode pembayaran wajib dipilih',
            'payment_method_id.exists'   => 'Metode pembayaran tidak ditemukan',
            'delivery_method.required'   => 'Metode pengiriman wajib dipilih',
            'delivery_method.in'         => 'Metode pengiriman tidak valid. Pilih: delivery atau pickup',
        ]);

        $user = $this->user();

        // Pastikan alamat milik user ini
        $address = UserAddress::where('id', $request->address_id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $cart = $user->cart()->with('items.product.primaryImage')->first();

        if (! $cart) {
            return response()->json([
                'success' => false,
                'message' => 'Keranjang belanja tidak ditemukan',
            ], 404);
        }

        // Ambil item yang akan dicheckout
        $itemsQuery = $cart->items()->with('product.primaryImage');
        if (! empty($request->item_ids)) {
            $itemsQuery->whereIn('id', $request->item_ids);
        } else {
            $itemsQuery->where('is_selected', 1);
        }
        $cartItems = $itemsQuery->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada item yang dipilih. Pilih produk di keranjang terlebih dahulu.',
            ], 422);
        }

        // Validasi produk & stok
        foreach ($cartItems as $ci) {
            if (! $ci->product->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => "Produk {$ci->product->name} sudah tidak tersedia",
                ], 422);
            }

            if ($ci->product->stock < $ci->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => "Stok {$ci->product->name} tidak mencukupi. Tersisa: {$ci->product->stock} {$ci->product->unit}",
                ], 422);
            }

            if ($ci->quantity < $ci->product->min_order) {
                return response()->json([
                    'success' => false,
                    'message' => "Minimal pembelian {$ci->product->name} adalah {$ci->product->min_order} {$ci->product->unit}",
                ], 422);
            }
        }

        // ── Hitung ongkir berdasarkan total quantity ──────────────
        $totalQty = $cartItems->sum('quantity');
        $ongkir   = $this->hitungOngkir($totalQty);

        // Validasi: jika pilih delivery tapi quantity kurang dari minimal
        if ($request->delivery_method === 'delivery' && ! $ongkir['can_delivery']) {
            return response()->json([
                'success' => false,
                'message' => "Minimal pembelian untuk pengiriman adalah " . self::MIN_QTY_DELIVERY . " sack. " .
                             "Total sack kamu saat ini: {$totalQty} sack. " .
                             "Tambah produk atau pilih metode 'Ambil Sendiri (Pickup)'.",
            ], 422);
        }

        DB::beginTransaction();
        try {
            $subtotal     = $cartItems->sum(fn($ci) => $ci->product->price * $ci->quantity);
            $shippingCost = $request->delivery_method === 'delivery' ? $ongkir['shipping_cost'] : 0;

            // Buat order
            $order = Order::create([
                'order_number'      => Order::generateOrderNumber(),
                'user_id'           => $user->id,
                'address_id'        => $address->id,
                'status'            => 'awaiting_payment',
                'delivery_method'   => $request->delivery_method,
                'subtotal'          => $subtotal,
                'shipping_cost'     => $shippingCost,
                'other_fee'         => 0,
                'total'             => $subtotal + $shippingCost,
                'note'              => $request->note,
                'estimated_arrival' => $request->delivery_method === 'delivery' ? 'Hari ini - Besok' : null,
            ]);

            // Buat order items + kurangi stok
            foreach ($cartItems as $ci) {
                $order->items()->create([
                    'product_id'    => $ci->product_id,
                    'product_name'  => $ci->product->name,
                    'product_image' => $ci->product->primaryImage?->image_url,
                    'product_unit'  => $ci->product->unit,
                    'quantity'      => $ci->quantity,
                    'unit_price'    => $ci->product->price,
                    'subtotal'      => $ci->product->price * $ci->quantity,
                ]);

                $ci->product->decrement('stock', $ci->quantity);
                $ci->product->increment('total_sold', $ci->quantity);
            }

            // Buat record payment
            Payment::create([
                'order_id'          => $order->id,
                'payment_method_id' => $request->payment_method_id,
                'status'            => 'pending',
                'amount'            => $order->total,
                'expired_at'        => now()->addHours(24),
            ]);

            // Hapus item dari keranjang
            $cartItems->each->delete();

            // Notifikasi ke user
            // $user->notifications()->create([
            //     'type'     => 'order',
            //     'title'    => 'Order Berhasil Dibuat 🎉',
            //     'message'  => "Order {$order->order_number} berhasil dibuat. Segera bayar sebelum " .
            //                   now()->addHours(24)->format('d M Y H:i') . ".",
            //     'ref_type' => 'order',
            //     'ref_id'   => $order->id,
            // ]);

            DB::commit();

            $order->load(['items', 'payment.paymentMethod', 'address']);

            return response()->json([
                'success' => true,
                'message' => 'Order berhasil dibuat! Silakan lakukan pembayaran.',
                'data'    => $this->formatOrderDetail($order),
            ], 201);

        } catch (\Throwable $e) {
    DB::rollBack();

    return response()->json([
        'success' => false,
        'message' => 'Gagal membuat order',
        'error'   => $e->getMessage(), // 🔥 ini penting
    ], 500);
        }
    }

    // ──────────────────────────────────────────────────────────────
    // POST /api/orders/{order}/cancel
    // ──────────────────────────────────────────────────────────────
    public function cancel(Order $order)
    {
        abort_if($order->user_id !== $this->user()->id, 403, 'Akses ditolak');

        if ($order->status !== 'awaiting_payment') {
            return response()->json([
                'success' => false,
                'message' => "Order tidak dapat dibatalkan. Status: " . $this->statusLabel($order->status),
            ], 422);
        }

        DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                $item->product->increment('stock', $item->quantity);
                $item->product->decrement('total_sold', $item->quantity);
            }

            $order->update(['status' => 'cancelled']);
            $order->payment?->update(['status' => 'failed']);

            $order->user->notifications()->create([
                'type'     => 'order',
                'title'    => 'Order Dibatalkan',
                'message'  => "Order {$order->order_number} berhasil dibatalkan.",
                'ref_type' => 'order',
                'ref_id'   => $order->id,
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Order berhasil dibatalkan',
        ]);
    }

    // ──────────────────────────────────────────────────────────────
    // POST /api/orders/{order}/reorder
    // ──────────────────────────────────────────────────────────────
    public function reorder(Order $order)
    {
        $user = $this->user();
        abort_if($order->user_id !== $user->id, 403, 'Akses ditolak');

        $cart       = $user->cart()->firstOrCreate(['user_id' => $user->id]);
        $added      = [];
        $outOfStock = [];

        foreach ($order->items()->with('product')->get() as $item) {
            if (! $item->product || ! $item->product->is_active) {
                $outOfStock[] = $item->product_name . ' (tidak tersedia)';
                continue;
            }

            if ($item->product->stock <= 0) {
                $outOfStock[] = $item->product_name . ' (stok habis)';
                continue;
            }

            $qty      = min($item->quantity, $item->product->stock);
            $existing = $cart->items()->where('product_id', $item->product_id)->first();

            if ($existing) {
                $existing->update(['quantity' => min($existing->quantity + $qty, $item->product->stock), 'is_selected' => 1]);
            } else {
                $cart->items()->create(['product_id' => $item->product_id, 'quantity' => $qty, 'is_selected' => 1]);
            }

            $added[] = $item->product_name;
        }

        $message = count($added) . ' produk ditambahkan ke keranjang.';
        if (! empty($outOfStock)) {
            $message .= ' Tidak tersedia: ' . implode(', ', $outOfStock);
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'data'    => ['added' => $added, 'out_of_stock' => $outOfStock],
        ]);
    }

    // ──────────────────────────────────────────────────────────────
    // GET /api/orders/{order}/invoice
    // ──────────────────────────────────────────────────────────────
    public function invoice(Order $order)
    {
        $user = $this->user();
        abort_if($order->user_id !== $user->id, 403, 'Akses ditolak');

        $order->load(['items', 'address', 'payment.paymentMethod', 'user']);

        return response()->json([
            'success' => true,
            'data'    => [
                'invoice_number' => 'INV-' . $order->order_number,
                'order_number'   => $order->order_number,
                'order_date'     => $order->created_at->format('d M Y H:i'),
                'status'         => $this->statusLabel($order->status),
                'customer' => [
                    'name'  => $order->user->name,
                    'email' => $order->user->email,
                    'phone' => $order->user->phone,
                ],
                'shipping_address' => [
                    'recipient' => $order->address->recipient_name,
                    'phone'     => $order->address->phone,
                    'address'   => $order->address->detail,
                    'district'  => $order->address->district,
                    'city'      => $order->address->city,
                    'province'  => $order->address->province,
                    'postal'    => $order->address->postal_code,
                ],
                'items' => $order->items->map(fn($item) => [
                    'name'             => $item->product_name,
                    'unit'             => $item->product_unit,
                    'quantity'         => $item->quantity,
                    'unit_price'       => $item->unit_price,
                    'unit_price_format'=> 'Rp.' . number_format($item->unit_price, 0, ',', '.'),
                    'subtotal'         => $item->subtotal,
                    'subtotal_format'  => 'Rp.' . number_format($item->subtotal, 0, ',', '.'),
                ]),
                'summary' => [
                    'subtotal'             => $order->subtotal,
                    'subtotal_format'      => 'Rp.' . number_format($order->subtotal, 0, ',', '.'),
                    'shipping_cost'        => $order->shipping_cost,
                    'shipping_cost_format' => 'Rp.' . number_format($order->shipping_cost, 0, ',', '.'),
                    'other_fee'            => $order->other_fee,
                    'other_fee_format'     => 'Rp.' . number_format($order->other_fee, 0, ',', '.'),
                    'total'                => $order->total,
                    'total_format'         => 'Rp.' . number_format($order->total, 0, ',', '.'),
                ],
                'payment' => [
                    'method'  => $order->payment?->paymentMethod?->name,
                    'type'    => $order->payment?->payment_type,
                    'status'  => $order->payment?->status,
                    'paid_at' => $order->payment?->paid_at?->format('d M Y H:i'),
                ],
                'delivery' => [
                    'method'    => $order->delivery_method === 'delivery' ? 'Pengiriman' : 'Ambil Sendiri',
                    'estimated' => $order->estimated_arrival,
                ],
                'note'              => $order->note,
                'ongkir_info'       => [
                    'total_quantity'   => $order->items->sum('quantity'),
                    'ongkir_per_sack'  => self::ONGKIR_PER_SACK,
                    'min_qty_delivery' => self::MIN_QTY_DELIVERY,
                ],
            ],
        ]);
    }

    // ──────────────────────────────────────────────────────────────
    // PRIVATE — Logika hitung ongkir
    // ──────────────────────────────────────────────────────────────

    /**
     * Hitung ongkir berdasarkan total quantity
     *
     * Aturan:
     * - Minimal 15 sack untuk bisa delivery
     * - Ongkir = quantity × Rp2.000
     * - Di bawah 15 sack → tidak bisa delivery (harus pickup)
     *
     * Contoh:
     * - 18 sack → 18 × 2.000 = Rp36.000
     * - 15 sack → 15 × 2.000 = Rp30.000
     * - 14 sack → tidak bisa delivery
     */
    private function hitungOngkir(int $totalQty): array
    {
        if ($totalQty < self::MIN_QTY_DELIVERY) {
            return [
                'can_delivery'  => false,
                'shipping_cost' => 0,
                'info'          => "Minimal pembelian untuk pengiriman adalah " . self::MIN_QTY_DELIVERY .
                                   " sack. Saat ini: {$totalQty} sack. Kurang " .
                                   (self::MIN_QTY_DELIVERY - $totalQty) . " sack lagi.",
            ];
        }

        $shippingCost = $totalQty * self::ONGKIR_PER_SACK;

        return [
            'can_delivery'  => true,
            'shipping_cost' => $shippingCost,
            'info'          => "{$totalQty} sack × Rp" . number_format(self::ONGKIR_PER_SACK, 0, ',', '.') .
                               " = Rp" . number_format($shippingCost, 0, ',', '.'),
        ];
    }

    // ── Format helpers ────────────────────────────────────────────

    private function formatOrder(Order $order): array
    {
        return [
            'id'             => $order->id,
            'order_number'   => $order->order_number,
            'status'         => $order->status,
            'status_label'   => $this->statusLabel($order->status),
            'delivery_method'=> $order->delivery_method,
            'total'          => $order->total,
            'total_format'   => 'Rp.' . number_format($order->total, 0, ',', '.'),
            'items_count'    => $order->items->count(),
            'first_item'     => $order->items->first() ? [
                'name'  => $order->items->first()->product_name,
                'image' => $order->items->first()->product_image,
            ] : null,
            'payment_status' => $order->payment?->status,
            'payment_method' => $order->payment?->paymentMethod?->name,
            'created_at'     => $order->created_at->format('d M Y H:i'),
        ];
    }

    private function formatOrderDetail(Order $order): array
    {
        $totalQty = $order->items->sum('quantity');

        return [
            'id'               => $order->id,
            'order_number'     => $order->order_number,
            'status'           => $order->status,
            'status_label'     => $this->statusLabel($order->status),
            'delivery_method'  => $order->delivery_method,
            'delivery_label'   => $order->delivery_method === 'delivery' ? 'Pengiriman' : 'Ambil Sendiri',
            'estimated_arrival'=> $order->estimated_arrival,
            'note'             => $order->note,

            'address' => $order->address ? [
                'recipient' => $order->address->recipient_name,
                'phone'     => $order->address->phone,
                'label'     => $order->address->label,
                'address'   => $order->address->detail,
                'district'  => $order->address->district,
                'city'      => $order->address->city,
                'province'  => $order->address->province,
                'postal'    => $order->address->postal_code,
            ] : null,

            'items' => $order->items->map(fn($item) => [
                'id'                => $item->id,
                'product_id'        => $item->product_id,
                'name'              => $item->product_name,
                'image'             => $item->product_image,
                'unit'              => $item->product_unit,
                'quantity'          => $item->quantity,
                'unit_price'        => $item->unit_price,
                'unit_price_format' => 'Rp.' . number_format($item->unit_price, 0, ',', '.'),
                'subtotal'          => $item->subtotal,
                'subtotal_format'   => 'Rp.' . number_format($item->subtotal, 0, ',', '.'),
                'is_reviewed'       => $item->is_reviewed,
            ]),

            'summary' => [
                'total_quantity'       => $totalQty,
                'subtotal'             => $order->subtotal,
                'subtotal_format'      => 'Rp.' . number_format($order->subtotal, 0, ',', '.'),
                'shipping_cost'        => $order->shipping_cost,
                'shipping_cost_format' => 'Rp.' . number_format($order->shipping_cost, 0, ',', '.'),
                'shipping_info'        => $order->delivery_method === 'delivery'
                                            ? "{$totalQty} sack × Rp2.000 = Rp" . number_format($order->shipping_cost, 0, ',', '.')
                                            : 'Ambil Sendiri (Gratis)',
                'other_fee'            => $order->other_fee,
                'other_fee_format'     => 'Rp.' . number_format($order->other_fee, 0, ',', '.'),
                'total'                => $order->total,
                'total_format'         => 'Rp.' . number_format($order->total, 0, ',', '.'),
            ],

            'payment' => $order->payment ? [
                'status'                 => $order->payment->status,
                'status_label'           => $this->paymentStatusLabel($order->payment->status),
                'method'                 => $order->payment->paymentMethod?->name,
                'payment_type'           => $order->payment->payment_type,
                'virtual_account_number' => $order->payment->virtual_account_number,
                'snap_token'             => $order->payment->snap_token,
                'amount'                 => $order->payment->amount,
                'amount_format'          => 'Rp.' . number_format($order->payment->amount, 0, ',', '.'),
                'expired_at'             => $order->payment->expired_at,
                'paid_at'                => $order->payment->paid_at,
            ] : null,

            'can_cancel'  => $order->status === 'awaiting_payment',
            'can_review'  => $order->status === 'completed',
            'can_reorder' => in_array($order->status, ['completed', 'cancelled']),

            'created_at'  => $order->created_at->format('d M Y H:i'),
            'updated_at'  => $order->updated_at->format('d M Y H:i'),
        ];
    }

    private function statusLabel(string $status): string
    {
        return match($status) {
            'awaiting_payment' => 'Menunggu Pembayaran',
            'paid'             => 'Sudah Dibayar',
            'shipped'          => 'Sedang Dikirim',
            'completed'        => 'Selesai',
            'cancelled'        => 'Dibatalkan',
            'refunded'         => 'Dikembalikan',
            default            => ucfirst($status),
        };
    }

    private function paymentStatusLabel(string $status): string
    {
        return match($status) {
            'pending'            => 'Menunggu Pembayaran',
            'paid'               => 'Lunas',
            'failed'             => 'Gagal',
            'expired'            => 'Kedaluwarsa',
            'refunded'           => 'Dikembalikan',
            'partially_refunded' => 'Dikembalikan Sebagian',
            default              => ucfirst($status),
        };
    }
}