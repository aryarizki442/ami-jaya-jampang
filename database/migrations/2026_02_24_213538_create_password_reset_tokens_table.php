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
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->id();

            // Nullable karena register & forgot password belum punya user_id
            $table->foreignId('user_id')
                  ->nullable()
                  ->constrained()
                  ->cascadeOnDelete();

            // Untuk flow yang belum login (register, forgot password)
            // isinya = email user
            $table->string('token_key')->nullable()->index();

            // OTP 6 digit atau token panjang (register_token / reset_token)
            $table->string('token', 100);

            $table->enum('type', [
                'otp_register',            
                'register_verified',      
                'otp_forgot_password',    
                'password_reset_verified', 
                'otp_update_email',        
                'otp_update_phone',        
            ]);

            $table->dateTime('expired_at');
            $table->dateTime('used_at')->nullable();
            $table->dateTime('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('password_reset_tokens');
    }
};