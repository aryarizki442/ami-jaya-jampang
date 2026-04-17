<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminOrderController extends Controller
{
    // Konstanta ongkir (sama dengan OrderController)
    const ONGKIR_PER_SACK  = 2000;
    const MIN_QTY_DELIVERY = 15;

    public function __construct()
    {
        \Midtrans\Config::$serverKey    = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized  = true;
        \Midtrans\Config::$is3ds        = true;
    }

    // ──────────────────────────────────────────────────────────────
    // GET /api/admin/orders
    // Filter: status, search, date_from, date_to, per_page
    // ──────────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = Order::with(['user:id,name,email,phone', 'payment.paymentMethod'])
            ->withCount('items');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', fn($u) =>
                        $u->where('name',  'like', '%' . $request->search . '%')
                          ->orWhere('email', 'like', '%' . $request->search . '%')
                          ->orWhere('phone', 'like', '%' . $request->search . '%')
                  );
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->latest()->paginate($request->get('per_page', 15));

        // Tambah status_label dan total_format di list
        $orders->getCollection()->transform(function ($order) {
            $order->status_label  = $this->statusLabel($order->status);
            $order->total_format  = 'Rp.' . number_format($order->total, 0, ',', '.');
            return $order;
        });

        return response()->json(['success' => true, 'data' => $orders]);
    }

    // ──────────────────────────────────────────────────────────────
    // GET /api/admin/orders/{order}
    // Detail order lengkap + breakdown ongkir
    // ──────────────────────────────────────────────────────────────
    public function show(Order $order)
    {
        $order->load([
            'user:id,name,email,phone',
            'address',
            'items.product',
            'payment.paymentMethod',
        ]);

        $totalQty = $order->items->sum('quantity');

        return response()->json([
            'success' => true,
            'data'    => [
                'id'                => $order->id,
                'order_number'      => $order->order_number,
                'status'            => $order->status,
                'status_label'      => $this->statusLabel($order->status),
                'delivery_method'   => $order->delivery_method,
                'delivery_label'    => $order->delivery_method === 'delivery' ? 'Pengiriman' : 'Ambil Sendiri',
                'estimated_arrival' => $order->estimated_arrival,
                'note'              => $order->note,
                'created_at'        => $order->created_at->format('d M Y H:i'),
                'updated_at'        => $order->updated_at->format('d M Y H:i'),

                // ── Customer ──────────────────────────────────────
                'customer' => [
                    'id'    => $order->user->id,
                    'name'  => $order->user->name,
                    'email' => $order->user->email,
                    'phone' => $order->user->phone,
                ],

                // ── Alamat pengiriman ─────────────────────────────
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

                // ── Item produk ───────────────────────────────────
                'items' => $order->items->map(fn($item) => [
                    'id'                => $item->id,
                    'product_id'        => $item->product_id,
                    'product_name'      => $item->product_name,
                    'product_image'     => $item->product_image,
                    'product_unit'      => $item->product_unit,
                    'quantity'          => $item->quantity,
                    'unit_price'        => $item->unit_price,
                    'unit_price_format' => 'Rp.' . number_format($item->unit_price, 0, ',', '.'),
                    'subtotal'          => $item->subtotal,
                    'subtotal_format'   => 'Rp.' . number_format($item->subtotal, 0, ',', '.'),
                    'is_reviewed'       => $item->is_reviewed,
                    'current_stock'     => $item->product?->stock,
                ]),

                // ── Ringkasan harga ───────────────────────────────
                'summary' => [
                    'total_quantity'       => $totalQty,
                    'subtotal'             => $order->subtotal,
                    'subtotal_format'      => 'Rp.' . number_format($order->subtotal, 0, ',', '.'),
                    'shipping_cost'        => $order->shipping_cost,
                    'shipping_cost_format' => 'Rp.' . number_format($order->shipping_cost, 0, ',', '.'),
                    // ── Breakdown ongkir ─────────────────────────
                    'shipping_breakdown'   => $this->ongkirBreakdown($order, $totalQty),
                    'other_fee'            => $order->other_fee,
                    'other_fee_format'     => 'Rp.' . number_format($order->other_fee, 0, ',', '.'),
                    'total'                => $order->total,
                    'total_format'         => 'Rp.' . number_format($order->total, 0, ',', '.'),
                ],

                // ── Pembayaran ────────────────────────────────────
                'payment' => $order->payment ? [
                    'id'                     => $order->payment->id,
                    'status'                 => $order->payment->status,
                    'status_label'           => $this->paymentStatusLabel($order->payment->status),
                    'method'                 => $order->payment->paymentMethod?->name,
                    'payment_type'           => $order->payment->payment_type,
                    'transaction_id'         => $order->payment->transaction_id,
                    'virtual_account_number' => $order->payment->virtual_account_number,
                    'amount'                 => $order->payment->amount,
                    'amount_format'          => 'Rp.' . number_format($order->payment->amount, 0, ',', '.'),
                    'refunded_amount'        => $order->payment->refunded_amount,
                    'refunded_amount_format' => 'Rp.' . number_format($order->payment->refunded_amount ?? 0, 0, ',', '.'),
                    'refund_reason'          => $order->payment->refund_reason,
                    'payment_proof'          => $order->payment->payment_proof
                                                    ? \Storage::url($order->payment->payment_proof)
                                                    : null,
                    'expired_at'             => $order->payment->expired_at,
                    'paid_at'                => $order->payment->paid_at,
                    'refunded_at'            => $order->payment->refunded_at,
                ] : null,

                // ── Aksi yang tersedia untuk admin ────────────────
                'available_actions' => $this->availableActions($order),
            ],
        ]);
    }

    // ──────────────────────────────────────────────────────────────
    // GET /api/admin/orders/export
    // Export data order ke CSV
    // Query: status, date_from, date_to
    // ──────────────────────────────────────────────────────────────
    public function export(Request $request)
    {
        $query = Order::with([
            'user:id,name,email,phone',
            'items',
            'payment.paymentMethod',
            'address',
        ]);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->latest()->get();

        // ── Generate CSV ──────────────────────────────────────────
        $filename = 'orders-' . now()->format('Ymd-His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename={$filename}",
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        $callback = function () use ($orders) {
            $file = fopen('php://output', 'w');

            // BOM untuk Excel agar bisa baca karakter Indonesia
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header kolom
            fputcsv($file, [
                'No. Order',
                'Tanggal',
                'Nama Customer',
                'Email',
                'No. HP',
                'Kota Tujuan',
                'Metode Kirim',
                'Total Qty (Sack)',
                'Subtotal',
                'Ongkir',
                'Breakdown Ongkir',
                'Total',
                'Status Order',
                'Metode Bayar',
                'Status Bayar',
                'Tanggal Bayar',
                'Catatan',
            ]);

            foreach ($orders as $order) {
                $totalQty        = $order->items->sum('quantity');
                $ongkirBreakdown = $this->ongkirBreakdown($order, $totalQty);

                fputcsv($file, [
                    $order->order_number,
                    $order->created_at->format('d/m/Y H:i'),
                    $order->user?->name,
                    $order->user?->email,
                    $order->user?->phone,
                    $order->address?->city,
                    $order->delivery_method === 'delivery' ? 'Pengiriman' : 'Ambil Sendiri',
                    $totalQty,
                    $order->subtotal,
                    $order->shipping_cost,
                    $ongkirBreakdown['formula'],   // contoh: "18 sack × Rp2.000 = Rp36.000"
                    $order->total,
                    $this->statusLabel($order->status),
                    $order->payment?->paymentMethod?->name,
                    $this->paymentStatusLabel($order->payment?->status ?? '-'),
                    $order->payment?->paid_at?->format('d/m/Y H:i') ?? '-',
                    $order->note ?? '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // ──────────────────────────────────────────────────────────────
    // PATCH /api/admin/orders/{order}/status
    // ──────────────────────────────────────────────────────────────
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:awaiting_payment,paid,shipped,completed,cancelled',
        ], [
            'status.required' => 'Status wajib diisi',
            'status.in'       => 'Status tidak valid',
        ]);

        $allowed = [
            'awaiting_payment' => ['paid', 'cancelled'],
            'paid'             => ['shipped', 'cancelled'],
            'shipped'          => ['completed'],
            'completed'        => [],
            'cancelled'        => [],
            'refunded'         => [],
        ];

        if (! in_array($request->status, $allowed[$order->status] ?? [])) {
            return response()->json([
                'success' => false,
                'message' => "Tidak bisa ubah status dari '{$order->status}' ke '{$request->status}'",
            ], 422);
        }

        DB::transaction(function () use ($request, $order) {
            $order->update(['status' => $request->status]);

            if ($request->status === 'cancelled') {
                $order->payment?->update(['status' => 'failed']);
                foreach ($order->items as $item) {
                    $item->product->increment('stock', $item->quantity);
                    $item->product->decrement('total_sold', $item->quantity);
                }
                $order->user->notifications()->create([
                    'type'     => 'order',
                    'title'    => 'Order Dibatalkan',
                    'message'  => "Order {$order->order_number} telah dibatalkan oleh admin.",
                    'ref_type' => 'order',
                    'ref_id'   => $order->id,
                ]);
            }

            if ($request->status === 'paid') {
                $order->payment?->update(['status' => 'paid', 'paid_at' => now()]);
                $order->user->notifications()->create([
                    'type'     => 'payment',
                    'title'    => 'Pembayaran Dikonfirmasi ✅',
                    'message'  => "Pembayaran order {$order->order_number} telah dikonfirmasi oleh admin.",
                    'ref_type' => 'order',
                    'ref_id'   => $order->id,
                ]);
            }

            if ($request->status === 'shipped') {
                $order->user->notifications()->create([
                    'type'     => 'order',
                    'title'    => 'Pesanan Sedang Dikirim 🚚',
                    'message'  => "Order {$order->order_number} sedang dalam pengiriman. Estimasi: {$order->estimated_arrival}.",
                    'ref_type' => 'order',
                    'ref_id'   => $order->id,
                ]);
            }

            if ($request->status === 'completed') {
                $order->user->notifications()->create([
                    'type'     => 'order',
                    'title'    => 'Pesanan Selesai ✅',
                    'message'  => "Order {$order->order_number} telah selesai. Terima kasih sudah berbelanja!",
                    'ref_type' => 'order',
                    'ref_id'   => $order->id,
                ]);
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Status order berhasil diperbarui',
            'data'    => $order->fresh(['payment']),
        ]);
    }

    // ──────────────────────────────────────────────────────────────
    // POST /api/admin/orders/{order}/refund
    // ──────────────────────────────────────────────────────────────
    public function refund(Request $request, Order $order)
    {
        $request->validate([
            'amount' => 'nullable|numeric|min:1000',
            'reason' => 'required|string|max:255',
        ], [
            'amount.min'      => 'Nominal refund minimal Rp1.000',
            'reason.required' => 'Alasan refund wajib diisi',
        ]);

        $payment = $order->payment;

        if (! $payment) {
            return response()->json(['success' => false, 'message' => 'Data pembayaran tidak ditemukan'], 404);
        }

        if ($payment->status !== 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'Refund hanya bisa untuk pembayaran yang sudah lunas. Status: ' . $payment->status,
            ], 422);
        }

        if ($order->status === 'refunded') {
            return response()->json(['success' => false, 'message' => 'Order ini sudah pernah di-refund'], 422);
        }

        $nonRefundable = ['bank_transfer', 'bca_va', 'bni_va', 'bri_va', 'permata_va', 'mandiri_va', 'echannel'];
        if (in_array($payment->payment_type, $nonRefundable)) {
            return response()->json([
                'success' => false,
                'message' => strtoupper($payment->payment_type) . ' tidak support refund otomatis. Gunakan refund manual.',
                'manual'  => true,
            ], 422);
        }

        $refundAmount      = $request->filled('amount') ? (float) $request->amount : $payment->amount;
        $remainingRefundable = $payment->amount - $payment->refunded_amount;

        if ($refundAmount > $remainingRefundable) {
            return response()->json([
                'success' => false,
                'message' => "Nominal refund melebihi sisa yang bisa di-refund (Rp" . number_format($remainingRefundable, 0, ',', '.') . ")",
            ], 422);
        }

        try {
            $refundKey = 'REFUND-' . $order->order_number . '-' . time();

            $refundResult = \Midtrans\Transaction::refund($order->order_number, [
                'refund_key' => $refundKey,
                'amount'     => (int) $refundAmount,
                'reason'     => $request->reason,
            ]);

            $totalRefunded   = $payment->refunded_amount + $refundAmount;
            $isFullyRefunded = $totalRefunded >= $payment->amount;

            DB::transaction(function () use ($order, $payment, $refundAmount, $totalRefunded, $isFullyRefunded, $refundKey, $request) {
                $payment->update([
                    'status'          => $isFullyRefunded ? 'refunded' : 'partially_refunded',
                    'refunded_amount' => $totalRefunded,
                    'refund_key'      => $refundKey,
                    'refund_reason'   => $request->reason,
                    'refunded_at'     => now(),
                ]);

                if ($isFullyRefunded) {
                    $order->update(['status' => 'refunded']);
                    foreach ($order->items as $item) {
                        $item->product->increment('stock', $item->quantity);
                        $item->product->decrement('total_sold', $item->quantity);
                    }
                }

                $order->user->notifications()->create([
                    'type'     => 'payment',
                    'title'    => 'Refund Berhasil 💰',
                    'message'  => "Refund Rp" . number_format($refundAmount, 0, ',', '.') . " untuk order {$order->order_number} sedang diproses. Alasan: {$request->reason}",
                    'ref_type' => 'order',
                    'ref_id'   => $order->id,
                ]);
            });

            Log::info('Midtrans: refund berhasil', ['order' => $order->order_number, 'amount' => $refundAmount]);

            return response()->json([
                'success' => true,
                'message' => 'Refund berhasil diproses',
                'data'    => [
                    'order_number'         => $order->order_number,
                    'refund_amount'        => $refundAmount,
                    'refund_amount_format' => 'Rp.' . number_format($refundAmount, 0, ',', '.'),
                    'total_refunded'       => $totalRefunded,
                    'is_full_refund'       => $isFullyRefunded,
                    'refund_key'           => $refundKey,
                    'refunded_at'          => now(),
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Midtrans: refund gagal', ['order' => $order->order_number, 'error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Refund gagal. ' . (config('app.debug') ? $e->getMessage() : 'Silakan coba lagi.'),
            ], 500);
        }
    }

    // ──────────────────────────────────────────────────────────────
    // POST /api/admin/orders/{order}/refund-manual
    // ──────────────────────────────────────────────────────────────
    public function refundManual(Request $request, Order $order)
    {
        $request->validate([
            'amount'         => 'required|numeric|min:1000',
            'reason'         => 'required|string|max:255',
            'transfer_proof' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $payment = $order->payment;

        if (! $payment || $payment->status !== 'paid') {
            return response()->json(['success' => false, 'message' => 'Pembayaran tidak ditemukan atau belum lunas'], 422);
        }

        $refundAmount    = (float) $request->amount;
        $totalRefunded   = $payment->refunded_amount + $refundAmount;
        $isFullyRefunded = $totalRefunded >= $payment->amount;

        DB::transaction(function () use ($request, $order, $payment, $refundAmount, $totalRefunded, $isFullyRefunded) {
            $updateData = [
                'status'          => $isFullyRefunded ? 'refunded' : 'partially_refunded',
                'refunded_amount' => $totalRefunded,
                'refund_key'      => 'MANUAL-' . $order->order_number . '-' . time(),
                'refund_reason'   => $request->reason,
                'refunded_at'     => now(),
            ];

            if ($request->hasFile('transfer_proof')) {
                $updateData['payment_proof'] = $request->file('transfer_proof')->store('refund-proofs', 'public');
            }

            $payment->update($updateData);

            if ($isFullyRefunded) {
                $order->update(['status' => 'refunded']);
                foreach ($order->items as $item) {
                    $item->product->increment('stock', $item->quantity);
                    $item->product->decrement('total_sold', $item->quantity);
                }
            }

            $order->user->notifications()->create([
                'type'     => 'payment',
                'title'    => 'Refund Manual Diproses 💰',
                'message'  => "Refund manual Rp" . number_format($refundAmount, 0, ',', '.') . " untuk order {$order->order_number} telah dicatat.",
                'ref_type' => 'order',
                'ref_id'   => $order->id,
            ]);
        });

        return response()->json([
            'success' => true,
            'message' => 'Refund manual berhasil dicatat',
            'data'    => [
                'order_number'   => $order->order_number,
                'refund_amount'  => $refundAmount,
                'total_refunded' => $totalRefunded,
                'is_full_refund' => $isFullyRefunded,
            ],
        ]);
    }

    // ──────────────────────────────────────────────────────────────
    // GET /api/admin/orders/stats
    // ──────────────────────────────────────────────────────────────
    public function stats()
    {
        $stats = [
            'total_orders'       => Order::count(),
            'awaiting_payment'   => Order::where('status', 'awaiting_payment')->count(),
            'paid'               => Order::where('status', 'paid')->count(),
            'shipped'            => Order::where('status', 'shipped')->count(),
            'completed'          => Order::where('status', 'completed')->count(),
            'cancelled'          => Order::where('status', 'cancelled')->count(),
            'refunded'           => Order::where('status', 'refunded')->count(),
            'revenue_today'      => Order::where('status', 'completed')->whereDate('created_at', today())->sum('total'),
            'revenue_this_month' => Order::where('status', 'completed')->whereMonth('created_at', now()->month)->sum('total'),
        ];

        return response()->json(['success' => true, 'data' => $stats]);
    }

    // ──────────────────────────────────────────────────────────────
    // PRIVATE HELPERS
    // ──────────────────────────────────────────────────────────────

    /**
     * Breakdown perhitungan ongkir
     * Return: formula teks + detail angka
     */
    private function ongkirBreakdown(Order $order, int $totalQty): array
    {
        if ($order->delivery_method === 'pickup') {
            return [
                'method'        => 'pickup',
                'formula'       => 'Ambil Sendiri (Gratis)',
                'total_qty'     => $totalQty,
                'ongkir_per_sack' => 0,
                'shipping_cost' => 0,
            ];
        }

        if ($totalQty < self::MIN_QTY_DELIVERY) {
            return [
                'method'           => 'delivery',
                'formula'          => "Dibawah minimum ({$totalQty} dari " . self::MIN_QTY_DELIVERY . " sack)",
                'total_qty'        => $totalQty,
                'min_qty'          => self::MIN_QTY_DELIVERY,
                'ongkir_per_sack'  => self::ONGKIR_PER_SACK,
                'shipping_cost'    => 0,
                'note'             => 'Ongkir tidak dikenakan karena di bawah minimum',
            ];
        }

        $shippingCost = $totalQty * self::ONGKIR_PER_SACK;

        return [
            'method'          => 'delivery',
            'formula'         => "{$totalQty} sack × Rp" . number_format(self::ONGKIR_PER_SACK, 0, ',', '.') . " = Rp" . number_format($shippingCost, 0, ',', '.'),
            'total_qty'       => $totalQty,
            'ongkir_per_sack' => self::ONGKIR_PER_SACK,
            'shipping_cost'   => $shippingCost,
            'min_qty'         => self::MIN_QTY_DELIVERY,
        ];
    }

    /** Aksi yang tersedia untuk admin berdasarkan status order */
    private function availableActions(Order $order): array
    {
        $actions = [];

        $allowed = [
            'awaiting_payment' => ['paid', 'cancelled'],
            'paid'             => ['shipped', 'cancelled'],
            'shipped'          => ['completed'],
            'completed'        => [],
            'cancelled'        => [],
            'refunded'         => [],
        ];

        foreach ($allowed[$order->status] ?? [] as $status) {
            $actions[] = [
                'action' => 'update_status',
                'value'  => $status,
                'label'  => match($status) {
                    'paid'      => 'Konfirmasi Pembayaran',
                    'shipped'   => 'Tandai Sedang Dikirim',
                    'completed' => 'Tandai Selesai',
                    'cancelled' => 'Batalkan Order',
                    default     => ucfirst($status),
                },
            ];
        }

        // Tombol refund hanya muncul jika sudah paid
        if ($order->payment?->status === 'paid') {
            $actions[] = [
                'action' => 'refund',
                'label'  => 'Refund Pembayaran',
            ];
        }

        return $actions;
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