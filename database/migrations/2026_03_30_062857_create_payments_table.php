<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('payment_method_id')->constrained()->restrictOnDelete();
            $table->enum('status', [
                'pending',
                'paid',
                'failed',
                'expired',
                'refunded',
                'partially_refunded',
            ])->default('pending');
            $table->decimal('amount', 12, 2);
            $table->decimal('refunded_amount', 12, 2)->default(0);
            $table->string('snap_token')->nullable();
            $table->string('payment_type', 50)->nullable();       // gopay, qris, bca_va, dll
            $table->string('transaction_id', 100)->nullable();    // transaction ID dari Midtrans
            $table->string('virtual_account_number', 30)->nullable();
            $table->string('refund_key', 100)->nullable();
            $table->string('refund_reason', 255)->nullable();
            $table->string('payment_proof')->nullable();
            $table->dateTime('expired_at')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->dateTime('refunded_at')->nullable();
            $table->dateTime('created_at')->useCurrent();
            $table->index('order_id');
            $table->index('status');
            $table->index('transaction_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};