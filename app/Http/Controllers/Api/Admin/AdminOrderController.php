<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminOrderController extends Controller
{
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

        return response()->json(['success' => true, 'data' => $orders]);
    }

    // ──────────────────────────────────────────────────────────────
    // GET /api/admin/orders/{order}
    // ──────────────────────────────────────────────────────────────
    public function show(Order $order)
    {
        $order->load([
            'user:id,name,email,phone',
            'address',
            'items.product',
            'payment.paymentMethod',
        ]);

        return response()->json(['success' => true, 'data' => $order]);
    }

    // ──────────────────────────────────────────────────────────────
    // PATCH /api/admin/orders/{order}/status
    // Update status order manual oleh admin
    // Body: { status }
    // ──────────────────────────────────────────────────────────────
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:awaiting_payment,paid,shipped,completed,cancelled',
        ], [
            'status.required' => 'Status wajib diisi',
            'status.in'       => 'Status tidak valid',
        ]);

        // Aturan transisi status yang diperbolehkan
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
                // Notifikasi ke user
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
            }

            if ($request->status === 'shipped') {
                $order->user->notifications()->create([
                    'type'     => 'order',
                    'title'    => 'Pesanan Sedang Dikirim',
                    'message'  => "Order {$order->order_number} sedang dalam pengiriman.",
                    'ref_type' => 'order',
                    'ref_id'   => $order->id,
                ]);
            }

            if ($request->status === 'completed') {
                $order->user->notifications()->create([
                    'type'     => 'order',
                    'title'    => 'Pesanan Selesai',
                    'message'  => "Order {$order->order_number} telah selesai. Terima kasih!",
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
    // Refund pembayaran via Midtrans API
    // Body: { amount?, reason }
    //   - amount  → opsional, jika kosong = full refund
    //   - reason  → wajib, alasan refund
    //
    // Support refund: GoPay, ShopeePay, QRIS, Kartu Kredit
    // Tidak support: Virtual Account (harus manual)
    // ──────────────────────────────────────────────────────────────
    public function refund(Request $request, Order $order)
    {
        $request->validate([
            'amount' => 'nullable|numeric|min:1000',
            'reason' => 'required|string|max:255',
        ], [
            'amount.numeric' => 'Nominal refund harus berupa angka',
            'amount.min'     => 'Nominal refund minimal Rp1.000',
            'reason.required'=> 'Alasan refund wajib diisi',
        ]);

        $payment = $order->payment;

        // ── Validasi kondisi order ────────────────────────────────
        if (! $payment) {
            return response()->json([
                'success' => false,
                'message' => 'Data pembayaran tidak ditemukan',
            ], 404);
        }

        if ($payment->status !== 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'Refund hanya bisa dilakukan untuk pembayaran yang sudah lunas. Status saat ini: ' . $payment->status,
            ], 422);
        }

        if (in_array($order->status, ['refunded'])) {
            return response()->json([
                'success' => false,
                'message' => 'Order ini sudah pernah di-refund',
            ], 422);
        }

        // ── Validasi metode pembayaran ────────────────────────────
        $nonRefundableTypes = ['bank_transfer', 'bca_va', 'bni_va', 'bri_va', 'permata_va', 'mandiri_va', 'echannel'];

        if (in_array($payment->payment_type, $nonRefundableTypes)) {
            return response()->json([
                'success'  => false,
                'message'  => 'Metode pembayaran ' . strtoupper($payment->payment_type) . ' tidak support refund otomatis. Lakukan refund manual ke rekening customer.',
                'manual'   => true,
            ], 422);
        }

        // ── Tentukan nominal refund ───────────────────────────────
        $refundAmount = $request->filled('amount')
            ? (float) $request->amount
            : $payment->amount; // default full refund

        // Pastikan tidak melebihi amount yang sudah dibayar
        $remainingRefundable = $payment->amount - $payment->refunded_amount;

        if ($refundAmount > $remainingRefundable) {
            return response()->json([
                'success' => false,
                'message' => "Nominal refund (Rp" . number_format($refundAmount, 0, ',', '.') . ") melebihi sisa yang bisa di-refund (Rp" . number_format($remainingRefundable, 0, ',', '.') . ")",
            ], 422);
        }

        // ── Hit Midtrans Refund API ───────────────────────────────
        try {
            $refundKey = 'REFUND-' . $order->order_number . '-' . time();

            $refundResult = \Midtrans\Transaction::refund($order->order_number, [
                'refund_key' => $refundKey,
                'amount'     => (int) $refundAmount,
                'reason'     => $request->reason,
            ]);

            // Hitung total refunded setelah ini
            $totalRefunded  = $payment->refunded_amount + $refundAmount;
            $isFullyRefunded = $totalRefunded >= $payment->amount;

            DB::transaction(function () use ($order, $payment, $refundAmount, $totalRefunded, $isFullyRefunded, $refundKey, $request) {

                // Update tabel payments
                $payment->update([
                    'status'          => $isFullyRefunded ? 'refunded' : 'partially_refunded',
                    'refunded_amount' => $totalRefunded,
                    'refund_key'      => $refundKey,
                    'refund_reason'   => $request->reason,
                    'refunded_at'     => now(),
                ]);

                // Update status order jika full refund
                if ($isFullyRefunded) {
                    $order->update(['status' => 'refunded']);

                    // Restore stok produk
                    foreach ($order->items as $item) {
                        $item->product->increment('stock', $item->quantity);
                        $item->product->decrement('total_sold', $item->quantity);
                    }
                }

                // Kirim notifikasi ke customer
                $amountFormat = 'Rp.' . number_format($refundAmount, 0, ',', '.');
                $order->user->notifications()->create([
                    'type'     => 'payment',
                    'title'    => 'Refund Berhasil',
                    'message'  => "Refund sebesar {$amountFormat} untuk order {$order->order_number} sedang diproses. Alasan: {$request->reason}",
                    'ref_type' => 'order',
                    'ref_id'   => $order->id,
                ]);
            });

            Log::info('Midtrans: Refund berhasil', [
                'order_number'  => $order->order_number,
                'refund_amount' => $refundAmount,
                'reason'        => $request->reason,
                'is_full'       => $isFullyRefunded,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Refund berhasil diproses',
                'data'    => [
                    'order_number'    => $order->order_number,
                    'refund_amount'   => $refundAmount,
                    'refund_amount_format' => 'Rp.' . number_format($refundAmount, 0, ',', '.'),
                    'total_refunded'  => $totalRefunded,
                    'is_full_refund'  => $isFullyRefunded,
                    'refund_key'      => $refundKey,
                    'refunded_at'     => now(),
                    'midtrans_response' => $refundResult,
                ],
            ]);
        } catch (\Exception $e) {
            Log::error('Midtrans: Refund gagal', [
                'order_number' => $order->order_number,
                'error'        => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Refund gagal: ' . $e->getMessage(),
                'error'   => config('app.debug') ? $e->getMessage() : 'Silakan coba lagi atau hubungi Midtrans support',
            ], 500);
        }
    }

    // ──────────────────────────────────────────────────────────────
    // POST /api/admin/orders/{order}/refund-manual
    // Catat refund manual (untuk VA yang tidak support refund API)
    // Body: { amount, reason, transfer_proof? }
    // ──────────────────────────────────────────────────────────────
    public function refundManual(Request $request, Order $order)
    {
        $request->validate([
            'amount'         => 'required|numeric|min:1000',
            'reason'         => 'required|string|max:255',
            'transfer_proof' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'amount.required' => 'Nominal refund wajib diisi',
            'reason.required' => 'Alasan refund wajib diisi',
        ]);

        $payment = $order->payment;

        if (! $payment || $payment->status !== 'paid') {
            return response()->json([
                'success' => false,
                'message' => 'Pembayaran tidak ditemukan atau belum lunas',
            ], 422);
        }

        $refundAmount   = (float) $request->amount;
        $totalRefunded  = $payment->refunded_amount + $refundAmount;
        $isFullyRefunded = $totalRefunded >= $payment->amount;

        DB::transaction(function () use ($request, $order, $payment, $refundAmount, $totalRefunded, $isFullyRefunded) {

            $refundKey = 'MANUAL-' . $order->order_number . '-' . time();

            $updateData = [
                'status'          => $isFullyRefunded ? 'refunded' : 'partially_refunded',
                'refunded_amount' => $totalRefunded,
                'refund_key'      => $refundKey,
                'refund_reason'   => $request->reason,
                'refunded_at'     => now(),
            ];

            // Upload bukti transfer refund jika ada
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

            // Notifikasi ke customer
            $amountFormat = 'Rp.' . number_format($refundAmount, 0, ',', '.');
            $order->user->notifications()->create([
                'type'     => 'payment',
                'title'    => 'Refund Manual Diproses',
                'message'  => "Refund manual sebesar {$amountFormat} untuk order {$order->order_number} telah dicatat. Alasan: {$request->reason}",
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
}