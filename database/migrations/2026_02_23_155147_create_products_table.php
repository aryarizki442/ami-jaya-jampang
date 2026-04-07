<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->string('name', 200);
            $table->string('slug', 200)->unique();
            $table->string('sku')->nullable()->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 12, 2);
            $table->decimal('weight_kg', 6, 2)->nullable();
            $table->integer('stock')->default(0);
            $table->integer('min_order')->default(1);
            $table->integer('max_order')->nullable();
            $table->integer('total_sold')->default(0);
            $table->decimal('avg_rating', 3, 2)->default(0.00);
            $table->tinyInteger('is_active')->default(1);
            $table->timestamps();

            $table->index('category_id');
            $table->index('is_active');
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
