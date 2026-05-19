<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Tymon\JWTAuth\Facades\JWTAuth;

class PaymentController extends Controller
{
    public function __construct()
    {
        \Midtrans\Config::$serverKey    = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized  = true;
        \Midtrans\Config::$is3ds        = true;
    }

    // ──────────────────────────────────────────────────────────────
    // GET /api/payment-methods
    // Daftar metode pembayaran aktif
    // ──────────────────────────────────────────────────────────────
    public function methods()
    {
        $methods = PaymentMethod::where('is_active', 1)->get();

        return response()->json([
            'success' => true,
            'data'    => $methods,
        ]);
    }

    // ──────────────────────────────────────────────────────────────
    // GET /api/orders/{order}/payment
    // Detail pembayaran order milik customer
    // ──────────────────────────────────────────────────────────────
    public function show(Order $order)
    {
        $user = JWTAuth::parseToken()->authenticate();

        if ($order->user_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
        }

        $payment = $order->payment()->with('paymentMethod')->first();

        if (! $payment) {
            return response()->json(['success' => false, 'message' => 'Data pembayaran tidak ditemukan'], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $this->formatPayment($order, $payment),
        ]);
    }

    // ──────────────────────────────────────────────────────────────
    // POST /api/orders/{order}/payment/snap-token
    // Generate Snap Token Midtrans → kirim ke frontend
    // Frontend pakai token ini untuk buka popup pembayaran Midtrans
    // ──────────────────────────────────────────────────────────────
    public function getSnapToken(Order $order)
    {
        $user = JWTAuth::parseToken()->authenticate();

        if ($order->user_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
        }

        if ($order->status !== 'awaiting_payment') {
            return response()->json([
                'success' => false,
                'message' => 'Order tidak bisa dibayar. Status: ' . $order->status,
            ], 422);
        }

        $payment = $order->payment;

        if (! $payment) {
            return response()->json(['success' => false, 'message' => 'Data pembayaran tidak ditemukan'], 404);
        }

        // Reuse token jika masih valid (belum expired)
        if ($payment->snap_token && $payment->expired_at && now()->isBefore($payment->expired_at)) {
            return response()->json([
                'success' => true,
                'message' => 'Token masih aktif',
                'data'    => $this->snapTokenResponse($order, $payment),
            ]);
        }

        try {
            $order->load(['items', 'address', 'user']);

            $snapToken = \Midtrans\Snap::getSnapToken(
                $this->buildSnapParams($order, $user)
            );

            $expiredAt = now()->addHours(24);

            $payment->update([
                'snap_token' => $snapToken,
                'expired_at' => $expiredAt,
            ]);

            Log::info('Midtrans: snap token dibuat', [
                'order_number' => $order->order_number,
                'amount'       => $order->total,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Token pembayaran berhasil dibuat',
                'data'    => $this->snapTokenResponse($order, $payment->fresh()),
            ]);
        } catch (\Exception $e) {
            Log::error('Midtrans: gagal buat snap token', [
                'order_number' => $order->order_number,
                'error'        => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat token pembayaran. Silakan coba lagi.',
                'error'   => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    // ──────────────────────────────────────────────────────────────
    // POST /api/payment/notification
    // Webhook Midtrans — otomatis dipanggil saat status transaksi berubah
    // Endpoint ini TANPA auth JWT (dipanggil server Midtrans, bukan user)
    // Daftarkan di: Midtrans Dashboard → Settings → Configuration
    //               → Payment Notification URL
    // ──────────────────────────────────────────────────────────────
  public function notification(Request $request)
{
    try {
        Log::info('WEBHOOK MIDTRANS DITERIMA', [
            'headers' => $request->headers->all(),
            'raw'     => $request->getContent(),
            'all'     => $request->all(),
        ]);

        // ✅ DIPERBAIKI: ambil payload dari raw JSON dulu
        $payload = json_decode($request->getContent(), true);

        // ✅ DIPERBAIKI: fallback kalau raw JSON kosong
        if (empty($payload)) {
            $payload = $request->all();
        }

        Log::info('MIDTRANS FINAL PAYLOAD', [
            'payload' => $payload,
        ]);

        $orderId           = $payload['order_id'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus       = $payload['fraud_status'] ?? null;
        $paymentType       = $payload['payment_type'] ?? null;
        $grossAmount       = $payload['gross_amount'] ?? null;
        $transactionId     = $payload['transaction_id'] ?? null;
        $statusCode        = $payload['status_code'] ?? null;
        $signatureKey      = $payload['signature_key'] ?? null;

        // ✅ DIPERBAIKI: validasi field wajib
        if (! $orderId || ! $transactionStatus || ! $grossAmount || ! $statusCode) {
    Log::warning('Midtrans: payload tidak lengkap', [
        'payload' => $payload,
        'raw' => $request->getContent(),
    ]);

    return response()->json([
        'message' => 'OK - test notification received'
    ], 200);
}

        // ✅ BYPASS SIGNATURE UNTUK LOCAL DEBUG
        if (app()->environment('local')) {
            Log::warning('SIGNATURE BYPASSED LOCAL DEBUG', [
                'order_id' => $orderId,
                'received_signature' => $signatureKey,
            ]);
        } else {
            // ✅ DIPERBAIKI: validasi signature untuk production
            $expectedSignature = hash(
                'sha512',
                $orderId . $statusCode . $grossAmount . config('midtrans.server_key')
            );

            if ($signatureKey !== $expectedSignature) {
                Log::warning('Midtrans: signature tidak valid', [
                    'order_id' => $orderId,
                    'expected' => $expectedSignature,
                    'received' => $signatureKey,
                ]);

                return response()->json([
                    'message' => 'Invalid signature',
                ], 403);
            }
        }

        $order = Order::where('order_number', $orderId)
            ->with(['items.product', 'user', 'payment'])
            ->first();

        if (! $order) {
            Log::warning('Midtrans: order tidak ditemukan', [
                'order_number' => $orderId,
            ]);

            return response()->json([
                'message' => 'Order not found',
            ], 404);
        }

        $payment = $order->payment;

        if (! $payment) {
            Log::warning('Midtrans: payment tidak ditemukan', [
                'order_number' => $orderId,
                'order_id'     => $order->id,
            ]);

            return response()->json([
                'message' => 'Payment not found',
            ], 404);
        }

        if ($payment->status === 'paid' && $transactionStatus !== 'refund') {
            return response()->json([
                'message' => 'Already processed',
            ], 200);
        }

        $paymentStatus = match (true) {
            $transactionStatus === 'capture' && $fraudStatus === 'accept'    => 'paid',
            $transactionStatus === 'capture' && $fraudStatus === 'challenge' => 'pending',
            $transactionStatus === 'settlement'                              => 'paid',
            in_array($transactionStatus, ['cancel', 'deny', 'failure'])      => 'failed',
            $transactionStatus === 'expire'                                  => 'expired',
            default                                                          => 'pending',
        };

        DB::transaction(function () use (
            $order,
            $payment,
            $paymentStatus,
            $paymentType,
            $transactionId,
            $payload
        ) {
            $virtualAccountNumber =
                $payload['va_numbers'][0]['va_number']
                ?? $payload['permata_va_number']
                ?? $payment->virtual_account_number;

            if ($paymentStatus === 'paid') {
    $order->update([
        'status' => 'paid',
    ]);

    Log::info('ORDER DIPAKSA UPDATE PAID', [
        'order_number' => $order->order_number,
        'order_status' => $order->fresh()->status,
        'payment_status' => $payment->fresh()->status,
    ]);
}
        });

        Log::info('Midtrans: webhook berhasil diproses', [
            'order_number'   => $orderId,
            'payment_status' => $paymentStatus,
        ]);

        return response()->json([
            'message' => 'OK',
        ], 200);

    } catch (\Exception $e) {
        Log::error('Midtrans: error notifikasi', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        return response()->json([
            'message' => 'Error',
            'error'   => config('app.debug') ? $e->getMessage() : null,
        ], 500);
    }
}
    // ──────────────────────────────────────────────────────────────
    // POST /api/orders/{order}/payment/upload-proof
    // Upload bukti transfer manual (untuk COD / transfer bank manual)
    // ──────────────────────────────────────────────────────────────
    public function uploadProof(Request $request, Order $order)
    {
        $user = JWTAuth::parseToken()->authenticate();

        if ($order->user_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
        }

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'payment_proof.required' => 'Bukti pembayaran wajib diupload',
            'payment_proof.image'    => 'File harus berupa gambar',
            'payment_proof.mimes'    => 'Format harus JPG atau PNG',
            'payment_proof.max'      => 'Ukuran maksimal 2MB',
        ]);

        $payment = $order->payment;

        if (! $payment) {
            return response()->json(['success' => false, 'message' => 'Data pembayaran tidak ditemukan'], 404);
        }

        if ($payment->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Pembayaran sudah diproses, tidak bisa upload bukti baru',
            ], 422);
        }

        // Hapus bukti lama jika ada
        if ($payment->payment_proof) {
            Storage::disk('public')->delete($payment->payment_proof);
        }

        $path = $request->file('payment_proof')->store('payment-proofs', 'public');
        $payment->update(['payment_proof' => $path]);

        return response()->json([
            'success' => true,
            'message' => 'Bukti pembayaran berhasil diupload. Menunggu konfirmasi admin.',
            'data'    => [
                'payment_proof_url' => Storage::url($path),
            ],
        ]);
    }

    // ──────────────────────────────────────────────────────────────
    // PRIVATE HELPERS
    // ──────────────────────────────────────────────────────────────

    /** Susun params untuk Midtrans Snap */
    private function buildSnapParams(Order $order, $user): array
    {
        $itemDetails = $order->items->map(fn($item) => [
            'id'       => (string) $item->product_id,
            'price'    => (int) $item->unit_price,
            'quantity' => (int) $item->quantity,
            'name'     => mb_substr($item->product_name, 0, 50),
        ])->toArray();

        if ($order->shipping_cost > 0) {
            $itemDetails[] = [
                'id'       => 'SHIPPING',
                'price'    => (int) $order->shipping_cost,
                'quantity' => 1,
                'name'     => 'Ongkos Kirim',
            ];
        }

        if ($order->other_fee > 0) {
            $itemDetails[] = [
                'id'       => 'OTHER_FEE',
                'price'    => (int) $order->other_fee,
                'quantity' => 1,
                'name'     => 'Biaya Lainnya',
            ];
        }

        return [
            'transaction_details' => [
                // 'order_id'     => $order->order_number,
                'order_id'     => $order->order_number . '-' . $order->id,
                'gross_amount' => (int) $order->total,
            ],
            'item_details'     => $itemDetails,
            'customer_details' => [
                'first_name'      => $user->name,
                'email'           => $user->email,
                'phone'           => $user->phone,
                'billing_address' => [
                    'first_name'   => $order->address->recipient_name,
                    'phone'        => $order->address->phone,
                    'address'      => $order->address->detail,
                    'city'         => $order->address->city,
                    'postal_code'  => $order->address->postal_code,
                    'country_code' => 'IDN',
                ],
            ],
            'expiry' => [
                'start_time' => now()->format('Y-m-d H:i:s O'),
                'unit'       => 'hours',
                'duration'   => 24,
            ],
            'enabled_payments' => [
                'bca_va', 'bni_va', 'bri_va', 'permata_va',
                'gopay', 'shopeepay', 'qris',
            ],
            'callbacks' => [
                'finish' => config('app.url') . '/payment/finish',
            ],
        ];
    }

    /** Format response snap token */
    private function snapTokenResponse(Order $order, Payment $payment): array
    {
        return [
            'snap_token'   => $payment->snap_token,
            'client_key'   => config('midtrans.client_key'),
            'snap_url'     => config('midtrans.snap_url'),
            'order_number' => $order->order_number,
            'amount'       => $order->total,
            'amount_format'=> 'Rp.' . number_format($order->total, 0, ',', '.'),
            'expired_at'   => $payment->expired_at,
        ];
    }

    /** Format data payment untuk response */
    private function formatPayment(Order $order, Payment $payment): array
    {
        return [
            'order_number'           => $order->order_number,
            'amount'                 => $payment->amount,
            'amount_format'          => 'Rp.' . number_format($payment->amount, 0, ',', '.'),
            'status'                 => $payment->status,
            'status_label'           => $this->statusLabel($payment->status),
            'payment_method'         => $payment->paymentMethod?->name,
            'payment_type'           => $payment->payment_type,
            'transaction_id'         => $payment->transaction_id,
            'virtual_account_number' => $payment->virtual_account_number,
            'snap_token'             => $payment->snap_token,
            'payment_proof'          => $payment->payment_proof
                                            ? Storage::url($payment->payment_proof)
                                            : null,
            'refunded_amount'        => $payment->refunded_amount,
            'refund_reason'          => $payment->refund_reason,
            'expired_at'             => $payment->expired_at,
            'paid_at'                => $payment->paid_at,
            'refunded_at'            => $payment->refunded_at,
        ];
    }

    /** Handle saat pembayaran berhasil */
  private function handlePaymentSuccess(Order $order): void
{
    Log::info('HANDLE PAYMENT SUCCESS DIPANGGIL', [
        'order_before' => $order->status,
    ]);

    $order->update([
        'status' => 'paid',
    ]);

    $order->refresh();

    Log::info('ORDER UPDATED', [
        'order_after' => $order->status,
    ]);

    if ($order->user) {
        $order->user->notifications()->create([
            'type'     => 'payment',
            'title'    => 'Pembayaran Berhasil',
            'message'  => "Pembayaran order {$order->order_number} berhasil.",
            'ref_type' => 'order',
            'ref_id'   => $order->id,
        ]);
    }
}
    /** Handle saat pembayaran gagal atau expired */
    private function handlePaymentFailed(Order $order, string $paymentStatus): void
    {
        $order->update(['status' => 'cancelled']);

        // Kembalikan stok
        foreach ($order->items as $item) {
            $item->product->increment('stock', $item->quantity);
            $item->product->decrement('total_sold', $item->quantity);
        }

        $label = $paymentStatus === 'expired' ? 'kedaluwarsa' : 'gagal';

        $order->user->notifications()->create([
            'type'     => 'payment',
            'title'    => 'Pembayaran ' . ucfirst($label),
            'message'  => "Pembayaran order {$order->order_number} {$label}. Order dibatalkan otomatis.",
            'ref_type' => 'order',
            'ref_id'   => $order->id,
        ]);

        Log::info('Midtrans: pembayaran ' . $paymentStatus, [
            'order_number' => $order->order_number,
        ]);
    }

    /** Label status pembayaran Bahasa Indonesia */
    private function statusLabel(string $status): string
    {
        return match($status) {
            'pending'             => 'Menunggu Pembayaran',
            'paid'                => 'Lunas',
            'failed'              => 'Gagal',
            'expired'             => 'Kedaluwarsa',
            'refunded'            => 'Dikembalikan',
            'partially_refunded'  => 'Dikembalikan Sebagian',
            default               => ucfirst($status),
        };
    }
}
