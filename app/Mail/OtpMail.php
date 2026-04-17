<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $otp,
        public string $type = ''
    ) {}

    public function envelope(): Envelope
    {
        $subject = match($this->type) {
            'otp_register'         => 'Kode OTP Registrasi',
            'otp_forgot_password'  => 'Kode OTP Reset Password',
            'otp_update_email'     => 'Kode OTP Ganti Email',
            'otp_update_phone'     => 'Kode OTP Ganti Nomor Telepon',
            default                => 'Kode OTP',
        };

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(view: 'emails.otp');
    }
}