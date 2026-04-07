<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number', 30)->unique();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->foreignId('address_id')->constrained('user_addresses')->restrictOnDelete();
            $table->enum('status', [
                'awaiting_payment',
                'paid',
                'shipped',
                'completed',
                'cancelled',
                'refunded',
            ])->default('awaiting_payment');
            $table->enum('delivery_method', ['delivery', 'pickup']);
            $table->decimal('subtotal', 12, 2);
            $table->decimal('shipping_cost', 10, 2)->default(0);
            $table->decimal('other_fee', 10, 2)->default(0);
            $table->decimal('total', 12, 2);
            $table->text('note')->nullable();
            $table->string('estimated_arrival', 100)->nullable();
            $table->timestamps();
            $table->index('user_id');
            $table->index('status');
            $table->index('order_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};