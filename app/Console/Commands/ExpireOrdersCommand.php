<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExpireOrdersCommand extends Command
{
    protected $signature   = 'orders:expire';
    protected $description = 'Expire order yang lewat batas waktu pembayaran & kembalikan stok';

    public function handle(): int
    {
        $orders = Order::with(['items.product', 'payment', 'user'])
            ->where('status', 'awaiting_payment')
            ->whereHas('payment', function ($q) {
                $q->where('status', 'pending')
                  ->whereNotNull('expired_at')
                  ->where('expired_at', '<', now());
            })
            ->get();

        if ($orders->isEmpty()) {
            $this->info('Tidak ada order expired.');
            return self::SUCCESS;
        }

        $count = 0;

        foreach ($orders as $order) {
            try {
                DB::transaction(function () use ($order) {
                    // Lock order untuk cegah race condition dgn webhook Midtrans
                    $fresh = Order::where('id', $order->id)
                        ->lockForUpdate()
                        ->first();

                    // Guard 1: status sudah diubah pihak lain
                    if (! $fresh || $fresh->status !== 'awaiting_payment') {
                        return;
                    }

                    // Guard 2: payment sudah paid
                    $payment = $fresh->payment()->lockForUpdate()->first();
                    if (! $payment || $payment->status === 'paid') {
                        return;
                    }

                    // Guard 3: cek expiry di payment (double-check)
                    if (! $payment->expired_at || $payment->expired_at->isFuture()) {
                        return;
                    }

                    // Restore stok
                    foreach ($fresh->items as $item) {
                        if ($item->product) {
                            $item->product->increment('stock', $item->quantity);
                            $item->product->decrement('total_sold', $item->quantity);
                        }
                    }

                    $fresh->update(['status' => 'cancelled']);
                    $payment->update(['status' => 'expired']);

                    $fresh->user?->notifications()->create([
                        'type'     => 'payment',
                        'title'    => 'Pembayaran Kedaluwarsa',
                        'message'  => "Pembayaran order {$fresh->order_number} kedaluwarsa. Order dibatalkan otomatis & stok dikembalikan.",
                        'ref_type' => 'order',
                        'ref_id'   => $fresh->id,
                    ]);

                    Log::info('Auto-expire order', [
                        'order_number' => $fresh->order_number,
                    ]);
                });

                $count++;
            } catch (\Throwable $e) {
                Log::error('Expire order gagal', [
                    'order_number' => $order->order_number,
                    'error'        => $e->getMessage(),
                ]);
            }
        }

        $this->info("Berhasil expire {$count} order.");
        return self::SUCCESS;
    }
}