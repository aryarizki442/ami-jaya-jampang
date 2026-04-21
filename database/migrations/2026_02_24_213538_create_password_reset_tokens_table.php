<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->id();

            // Nullable: belum ada user saat register / forgot password
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained()
                  ->cascadeOnDelete();

            // Menyimpan email sementara untuk flow tanpa user_id
            $table->string('token_key')->nullable()->index();

            // OTP 6 digit (di-hash) atau token panjang (register_token / reset_token / update_token)
            $table->string('token', 100);

            $table->enum('type', [
                // OTP flow
                'otp_register',
                'otp_forgot_password',
                'otp_update_email',
                'otp_update_phone',
                // Token sementara setelah OTP verified
                'register_verified',
                'password_reset_verified',
                'update_email_verified',
                'update_phone_verified',
            ]);

            $table->dateTime('expired_at');
            $table->dateTime('used_at')->nullable();
            $table->dateTime('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('password_reset_tokens');
    }
};