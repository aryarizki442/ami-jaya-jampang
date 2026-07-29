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

    public function methods()
    {
        $methods = PaymentMethod::where('is_active', 1)->get();

        return response()->json([
            'success' => true,
            'data'    => $methods,
        ]);
    }

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

        $order->load(['items', 'address', 'user', 'payment.paymentMethod']);

        $payment = $order->payment;

        if (! $payment) {
            return response()->json(['success' => false, 'message' => 'Data pembayaran tidak ditemukan'], 404);
        }

        $methodCode = $payment->paymentMethod?->code;

        // Log untuk QRIS
        if ($methodCode === 'qris') {
            Log::info('QRIS payment initiated', [
                'order_number' => $order->order_number,
                'amount' => $order->total
            ]);
        }

        // Cek apakah snap_token masih valid
        if ($payment->snap_token && $payment->expired_at && now()->isBefore($payment->expired_at)) {
            return response()->json([
                'success' => true,
                'message' => 'Token masih aktif',
                'data'    => $this->snapTokenResponse($order, $payment),
            ]);
        }

        try {
            // QRIS expired 10 menit, VA 30 menit
            $expiredAt = $methodCode === 'qris' 
                ? now()->addMinutes(10) 
                : now()->addMinutes(30);
            
            $snapParams = $this->buildSnapParams($order, $user, $expiredAt);

            $snapToken = \Midtrans\Snap::getSnapToken($snapParams);

            // Simpan order_id yang digunakan di Midtrans ke transaction_id
            $midtransOrderId = $snapParams['transaction_details']['order_id'];

            $payment->update([
                'snap_token' => $snapToken,
                'expired_at' => $expiredAt,
                'transaction_id' => $midtransOrderId, // Simpan order_id Midtrans di transaction_id
            ]);

            Log::info('Midtrans: snap token dibuat', [
                'order_number' => $order->order_number,
                'midtrans_order_id' => $midtransOrderId,
                'method_code'  => $methodCode,
                'amount'       => $order->total,
                'expired_at'   => $expiredAt,
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

    public function notification(Request $request)
    {
        try {
            Log::info('WEBHOOK MIDTRANS DITERIMA', [
                'headers' => $request->headers->all(),
                'raw'     => $request->getContent(),
            ]);

            $payload = json_decode($request->getContent(), true);

            if (empty($payload)) {
                $payload = $request->all();
            }

            $orderId           = $payload['order_id'] ?? null;
            $transactionStatus = $payload['transaction_status'] ?? null;
            $fraudStatus       = $payload['fraud_status'] ?? null;
            $paymentType       = $payload['payment_type'] ?? null;
            $grossAmount       = $payload['gross_amount'] ?? null;
            $transactionId     = $payload['transaction_id'] ?? null;
            $statusCode        = $payload['status_code'] ?? null;
            $signatureKey      = $payload['signature_key'] ?? null;
            $settlementTime    = $payload['settlement_time'] ?? null;

            if (! $orderId || ! $transactionStatus || ! $grossAmount || ! $statusCode) {
                Log::warning('Midtrans: payload tidak lengkap', ['payload' => $payload]);
                return response()->json(['message' => 'OK - test notification received'], 200);
            }

            // Validasi signature (kecuali local)
            if (! app()->environment('local')) {
                $expectedSignature = hash(
                    'sha512',
                    $orderId . $statusCode . $grossAmount . config('midtrans.server_key')
                );

                if ($signatureKey !== $expectedSignature) {
                    Log::warning('Midtrans: signature tidak valid', [
                        'order_id' => $orderId,
                    ]);

                    return response()->json(['message' => 'Invalid signature'], 403);
                }
            }

            // Cari order berdasarkan berbagai kemungkinan
            $order = $this->findOrderByMidtransOrderId($orderId);

            if (! $order) {
                Log::warning('Midtrans: order tidak ditemukan', [
                    'order_id' => $orderId,
                    'payload' => $payload
                ]);
                return response()->json(['message' => 'Order not found'], 404);
            }

            $payment = $order->payment;

            if (! $payment) {
                Log::warning('Midtrans: payment tidak ditemukan', [
                    'order_number' => $order->order_number,
                ]);

                return response()->json(['message' => 'Payment not found'], 404);
            }

            // Cegah duplikasi proses
            if ($payment->status === 'paid' && $transactionStatus !== 'refund') {
                return response()->json(['message' => 'Already processed'], 200);
            }

            // Tentukan status payment
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
                $settlementTime,
                $payload,
                $orderId
            ) {
                $virtualAccountNumber = $this->extractVirtualAccountNumber($payload);

                $paymentMethodId = $this->resolvePaymentMethodId(
                    $paymentType,
                    $payment->payment_method_id
                );

                $paymentUpdateData = [
                    'status'            => $paymentStatus,
                    'payment_type'      => $paymentType,
                    'payment_method_id' => $paymentMethodId,
                ];

                // Update transaction_id dengan yang asli dari Midtrans
                if ($transactionId) {
                    $paymentUpdateData['transaction_id'] = $transactionId;
                }

                if ($virtualAccountNumber) {
                    $paymentUpdateData['virtual_account_number'] = $virtualAccountNumber;
                }

                if ($paymentStatus === 'paid') {
                    $paymentUpdateData['paid_at'] = $settlementTime
                        ? \Carbon\Carbon::parse($settlementTime)
                        : now();
                }

                $payment->update($paymentUpdateData);

                if ($paymentStatus === 'paid') {
                    $this->handlePaymentSuccess($order);
                } elseif (in_array($paymentStatus, ['failed', 'expired'])) {
                    $this->handlePaymentFailed($order, $paymentStatus);
                }
            });

            return response()->json(['message' => 'OK'], 200);
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

    /**
     * Cari order berdasarkan midtrans order_id dengan berbagai metode
     */
    private function findOrderByMidtransOrderId(string $orderId): ?Order
    {
        // 1. Cari berdasarkan order_number langsung
        $order = Order::where('order_number', $orderId)
            ->with(['items.product', 'user', 'payment.paymentMethod'])
            ->first();

        if ($order) {
            return $order;
        }

        // 2. Cari berdasarkan transaction_id di payment (menyimpan midtrans_order_id)
        $payment = Payment::where('transaction_id', $orderId)->first();
        if ($payment) {
            return Order::with(['items.product', 'user', 'payment.paymentMethod'])
                ->find($payment->order_id);
        }

        // 3. Parse order_id format: {order_number}-{timestamp}-{random}
        if (str_contains($orderId, '-')) {
            $parts = explode('-', $orderId);
            $orderNumberParts = [];
            
            for ($i = 0; $i < count($parts); $i++) {
                $orderNumberParts[] = $parts[$i];
                $testOrderNumber = implode('-', $orderNumberParts);
                
                $order = Order::where('order_number', $testOrderNumber)
                    ->with(['items.product', 'user', 'payment.paymentMethod'])
                    ->first();
                
                if ($order) {
                    return $order;
                }
            }
        }

        return null;
    }

    public function uploadProof(Request $request, Order $order)
    {
        $user = JWTAuth::parseToken()->authenticate();

        if ($order->user_id !== $user->id) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
        }

        $payment = $order->payment;

        if (! $payment) {
            return response()->json(['success' => false, 'message' => 'Data pembayaran tidak ditemukan'], 404);
        }

        // QRIS tidak memerlukan upload bukti
        if ($payment->paymentMethod?->code === 'qris') {
            return response()->json([
                'success' => false,
                'message' => 'Metode QRIS tidak memerlukan upload bukti pembayaran',
            ], 422);
        }

        $request->validate([
            'payment_proof' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($payment->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Pembayaran sudah diproses, tidak bisa upload bukti baru',
            ], 422);
        }

        if ($payment->payment_proof) {
            Storage::disk('public')->delete($payment->payment_proof);
        }

        $path = $request->file('payment_proof')->store('payment-proofs', 'public');

        $payment->update([
            'payment_proof' => $path,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Bukti pembayaran berhasil diupload. Menunggu konfirmasi admin.',
            'data'    => [
                'payment_proof_url' => Storage::url($path),
            ],
        ]);
    }

    private function buildSnapParams(Order $order, $user, \Carbon\Carbon $expiredAt): array
    {
        $itemDetails = $order->items->map(fn ($item) => [
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

        $methodCode = $order->payment?->paymentMethod?->code;

        // Generate unique order_id untuk Midtrans
        $uniqueOrderId = $order->order_number . '-' . time() . '-' . rand(1000, 9999);

        $params = [
            'transaction_details' => [
                'order_id' => $uniqueOrderId,
                'gross_amount' => (int) $order->total,
            ],

            'item_details' => $itemDetails,

            'customer_details' => [
                'first_name' => $user->name,
                'email'      => $user->email,
                'phone'      => $user->phone,

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
                'unit'       => 'minute',
                'duration'   => 10,
            ],

            'callbacks' => [
                'finish' => config('app.url') . '/payment/finish',
            ],
        ];

        // Konfigurasi khusus untuk QRIS
        if ($methodCode === 'qris') {
            $params['enabled_payments'] = ['gopay', 'shopeepay'];
            $params['qris'] = [
                'acquirer' => 'gopay'
            ];
        } else {
            $params['enabled_payments'] = $this->enabledPayments($order);
        }

        return $params;
    }

    private function enabledPayments(Order $order): array
    {
        $methodCode = $order->payment?->paymentMethod?->code;

        return match ($methodCode) {
            'bca_va'     => ['bca_va'],
            'bni_va'     => ['bni_va'],
            'bri_va'     => ['bri_va'],
            'mandiri_va' => ['echannel'],
            'qris'       => ['gopay', 'shopeepay'],
            default => [
                'bca_va',
                'bni_va',
                'bri_va',
                'echannel',
                'gopay',
                'shopeepay'
            ],
        };
    }

    private function extractVirtualAccountNumber(array $payload): ?string
    {
        if (! empty($payload['va_numbers'][0]['va_number'])) {
            $bank = strtoupper($payload['va_numbers'][0]['bank'] ?? '');
            $vaNumber = $payload['va_numbers'][0]['va_number'];

            return $bank ? "{$bank}: {$vaNumber}" : $vaNumber;
        }

        if (! empty($payload['permata_va_number'])) {
            return 'PERMATA: ' . $payload['permata_va_number'];
        }

        if (! empty($payload['bill_key'])) {
            $billerCode = $payload['biller_code'] ?? '';
            return 'MANDIRI: ' . $billerCode . $payload['bill_key'];
        }

        // Untuk QRIS
        if (isset($payload['payment_type']) && in_array($payload['payment_type'], ['gopay', 'shopeepay', 'qris'])) {
            return 'QRIS Payment';
        }

        return null;
    }

    private function resolvePaymentMethodId(?string $paymentType, ?int $currentMethodId): ?int
    {
        if (! $paymentType) {
            return $currentMethodId;
        }

        $codeMap = [
            'bca_va'    => 'bca_va',
            'bni_va'    => 'bni_va',
            'bri_va'    => 'bri_va',
            'echannel'  => 'mandiri_va',
            'gopay'     => 'qris',
            'shopeepay' => 'qris',
            'qris'      => 'qris',
        ];

        $methodCode = $codeMap[$paymentType] ?? null;

        if (! $methodCode) {
            Log::info('Midtrans: payment_type tidak ada di map', [
                'payment_type' => $paymentType,
            ]);

            return $currentMethodId;
        }

        $method = PaymentMethod::where('code', $methodCode)
            ->where('is_active', 1)
            ->first();

        return $method?->id ?? $currentMethodId;
    }

    private function snapTokenResponse(Order $order, Payment $payment): array
    {
        return [
            'snap_token'    => $payment->snap_token,
            'client_key'    => config('midtrans.client_key'),
            'snap_url'      => config('midtrans.snap_url'),
            'order_number'  => $order->order_number,
            'amount'        => $order->total,
            'amount_format' => 'Rp.' . number_format($order->total, 0, ',', '.'),
            'expired_at'    => $payment->expired_at,
            'payment_method' => $payment->paymentMethod?->code,
            'is_qris'       => $payment->paymentMethod?->code === 'qris',
        ];
    }

    private function formatPayment(Order $order, Payment $payment): array
    {
        $isQris = $payment->paymentMethod?->code === 'qris';
        
        return [
            'order_number'           => $order->order_number,
            'amount'                 => $payment->amount,
            'amount_format'          => 'Rp.' . number_format($payment->amount, 0, ',', '.'),
            'status'                 => $payment->status,
            'status_label'           => $this->statusLabel($payment->status),
            'payment_method'         => $payment->paymentMethod?->name,
            'payment_method_code'    => $payment->paymentMethod?->code,
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
            'is_qris'                => $isQris,
            'qris_info'              => $isQris ? [
                'acquirer' => 'Gopay / ShopeePay',
                'how_to_pay' => 'Scan QR code menggunakan aplikasi Gopay atau ShopeePay'
            ] : null,
        ];
    }

    private function handlePaymentSuccess(Order $order): void
    {
        $order->update([
            'status' => 'paid',
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

    private function handlePaymentFailed(Order $order, string $paymentStatus): void
    {
        $order->update([
            'status' => 'cancelled',
        ]);

        foreach ($order->items as $item) {
            $item->product->increment('stock', $item->quantity);
            $item->product->decrement('total_sold', $item->quantity);
        }

        $label = $paymentStatus === 'expired' ? 'kedaluwarsa' : 'gagal';

        if ($order->user) {
            $order->user->notifications()->create([
                'type'     => 'payment',
                'title'    => 'Pembayaran ' . ucfirst($label),
                'message'  => "Pembayaran order {$order->order_number} {$label}. Order dibatalkan otomatis.",
                'ref_type' => 'order',
                'ref_id'   => $order->id,
            ]);
        }
    }

    private function statusLabel(string $status): string
    {
        return match ($status) {
            'pending'            => 'Menunggu Pembayaran',
            'paid'               => 'Lunas',
            'failed'             => 'Gagal',
            'expired'            => 'Kedaluwarsa',
            'refunded'           => 'Dikembalikan',
            'partially_refunded' => 'Dikembalikan Sebagian',
            default              => ucfirst($status),
        };
    }

    public function index(Request $request)
    {
        $query = Payment::with([
            'order.user',
            'order',
            'paymentMethod'
        ])->latest();

        $allowed = [
            'pending',
            'paid',
            'failed',
            'expired',
            'refunded',
            'partially_refunded'
        ];

        if ($request->filled('status') && $request->status !== 'all' && in_array($request->status, $allowed)) {
            $query->where('status', $request->status);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereDate('created_at', '>=', $request->start_date)
                  ->whereDate('created_at', '<=', $request->end_date);
        }

        $payments = $query->paginate(10);

        $payments->getCollection()->transform(function ($payment) {
            return [
                'id' => $payment->id,
                'order_number' => $payment->order?->order_number ?? '-',
                'customer_name' => $payment->order?->user?->name ?? '-',
                'payment' => [
                    'method' => $payment->paymentMethod?->name ?? '-',
                    'method_code' => $payment->paymentMethod?->code ?? null,
                    'virtual_account_number' => $payment->virtual_account_number ?? null,
                    'expired_at' => $payment->expired_at,
                    'status' => $payment->status,
                    'status_label' => $this->statusLabel($payment->status),
                    'is_qris' => $payment->paymentMethod?->code === 'qris',
                ],
                'amount' => $payment->amount,
                'amount_format' => 'Rp ' . number_format($payment->amount, 0, ',', '.'),
                'created_at' => $payment->created_at
                    ? $payment->created_at->translatedFormat('d M Y, H:i')
                    : '-',
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $payments
        ]);
    }

    public function adminShow($id)
    {
        $payment = Payment::with([
            'order.user',
            'order.items.product',
            'paymentMethod'
        ])->find($id);

        if (! $payment) {
            return response()->json([
                'success' => false,
                'message' => 'Payment tidak ditemukan'
            ], 404);
        }

        $isQris = $payment->paymentMethod?->code === 'qris';

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $payment->id,
                'order_number' => $payment->order?->order_number ?? '-',
                'customer_name' => $payment->order?->user?->name ?? '-',
                'customer_email' => $payment->order?->user?->email ?? '-',
                'payment_method' => $payment->paymentMethod?->name ?? '-',
                'payment_method_code' => $payment->paymentMethod?->code ?? null,
                'payment_type' => $payment->payment_type ?? '-',
                'status' => $payment->status,
                'status_label' => $this->statusLabel($payment->status),
                'virtual_account_number' => $payment->virtual_account_number ?? null,
                'expired_at' => $payment->expired_at,
                'paid_at' => $payment->paid_at,
                'amount' => $payment->amount,
                'amount_format' => 'Rp ' . number_format($payment->amount, 0, ',', '.'),
                'transaction_id' => $payment->transaction_id ?? '-',
                'is_qris' => $isQris,
                'qris_info' => $isQris ? [
                    'acquirer' => 'Gopay / ShopeePay',
                    'payment_type' => 'QRIS'
                ] : null,
                'items' => $payment->order?->items->map(function ($item) {
                    return [
                        'name' => $item->product_name ?? $item->product?->name,
                        'qty' => $item->quantity,
                        'price' => $item->unit_price,
                        'subtotal' => $item->subtotal,
                    ];
                }) ?? [],
                'created_at' => $payment->created_at
                    ? $payment->created_at->translatedFormat('d F Y H:i')
                    : '-',
                'paid_at' => $payment->paid_at
                    ? $payment->paid_at->translatedFormat('d F Y H:i')
                    : '-',
            ]
        ]);
    }
}