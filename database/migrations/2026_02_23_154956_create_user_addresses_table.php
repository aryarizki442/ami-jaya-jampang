<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('label', ['home', 'office', 'other']);
            $table->string('recipient_name', 100);
            $table->string('phone', 20);
            $table->string('province', 100);
            $table->string('city', 100);
            $table->string('district', 100);
            $table->string('village', 100)->nullable();
            $table->string('postal_code', 10)->nullable();
            $table->text('detail')->nullable();
            $table->tinyInteger('is_primary')->default(0);
            $table->timestamps();

            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_addresses');
    }
};