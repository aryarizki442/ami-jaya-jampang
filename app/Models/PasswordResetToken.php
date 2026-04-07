<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Mime\Email;

class PasswordResetToken extends Model
{
    use HasFactory;

    protected $table = 'password_reset_tokens';
    
    protected $fillable = [
        'user_id',
        'token',
       'new_email',
        'type',
        'expired_at',
        'used_at'
    ];

    protected $casts = [
        'expired_at' => 'datetime',
        'used_at' => 'datetime',
        'created_at' => 'datetime'
    ];

    public $timestamps = false; 

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Cek apakah token masih valid
    public function isValid()
    {
        return !$this->used_at && $this->expired_at->isFuture();
    }

    // Cek apakah token sudah dipakai
    public function isUsed()
    {
        return !is_null($this->used_at);
    }
}