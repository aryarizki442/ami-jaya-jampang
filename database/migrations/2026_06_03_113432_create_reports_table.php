<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();

            // Periode laporan
            $table->unsignedSmallInteger('year');   // 2026
            $table->unsignedTinyInteger('month');   // 3 = Maret

            // Data snapshot bulanan
            $table->integer('total_sold')->default(0);      // total sack terjual
            $table->decimal('total_revenue', 14, 2)->default(0); // total pendapatan

            $table->timestamps();

            // 1 produk hanya punya 1 record per bulan
            $table->unique(['product_id', 'year', 'month']);

            $table->index(['year', 'month']);
            $table->index('product_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};