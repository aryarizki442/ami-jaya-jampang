<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\OtpMail;
use App\Models\PasswordResetToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class OtpController extends Controller
{
    /**
     * Daftar purpose yang TIDAK butuh JWT (user belum login)
     */
    private const PUBLIC_PURPOSES = [
        'register',
        'forgot_password',
    ];

    /**
     * Daftar purpose yang BUTUH JWT (user sudah login)
     */
    private const AUTH_PURPOSES = [
        'update_email',
        'update_phone',
    ];

    /**
     * Mapping purpose → type di DB
     */
    private const TYPE_MAP = [
        'register'       => 'otp_register',
        'forgot_password'=> 'otp_forgot_password',
        'update_email'   => 'otp_update_email',
        'update_phone'   => 'otp_update_phone',
    ];

    // =========================================================================
    // REQUEST OTP (Universal)
    // POST /api/otp/request
    // Body:
    //   - purpose: register | forgot_password | update_email | update_phone
    //   - email  : (wajib untuk register & forgot_password)
    //              (opsional untuk update_email & update_phone, diambil dari JWT)
    // =========================================================================
    public function request(Request $request)
    {
        $purpose = $request->input('purpose');

        // Validasi purpose
        $allPurposes = array_merge(self::PUBLIC_PURPOSES, self::AUTH_PURPOSES);
        if (!$purpose || !in_array($purpose, $allPurposes)) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => [
                    'purpose' => ['Purpose tidak valid. Pilih: ' . implode(', ', $allPurposes)]
                ],
            ], 422);
        }

        // Routing berdasarkan purpose
        return match ($purpose) {
            'register'        => $this->handleRequestRegister($request),
            'forgot_password' => $this->handleRequestForgotPassword($request),
            'update_email'    => $this->handleRequestUpdateEmail($request),
            'update_phone'    => $this->handleRequestUpdatePhone($request),
        };
    }

    // =========================================================================
    // VERIFY OTP (Universal)
    // POST /api/otp/verify
    // Body:
    //   - purpose: register | forgot_password | update_email | update_phone
    //   - email  : (wajib untuk register & forgot_password)
    //   - otp    : 6 digit
    // =========================================================================
    public function verify(Request $request)
    {
        $purpose = $request->input('purpose');

        $allPurposes = array_merge(self::PUBLIC_PURPOSES, self::AUTH_PURPOSES);
        if (!$purpose || !in_array($purpose, $allPurposes)) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => [
                    'purpose' => ['Purpose tidak valid. Pilih: ' . implode(', ', $allPurposes)]
                ],
            ], 422);
        }

        // Validasi OTP wajib ada dulu
        if (empty($request->otp) || strlen($request->otp) !== 6) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => ['otp' => ['Kode OTP wajib diisi dan harus 6 digit']],
            ], 422);
        }

        return match ($purpose) {
            'register'        => $this->handleVerifyRegister($request),
            'forgot_password' => $this->handleVerifyForgotPassword($request),
            'update_email'    => $this->handleVerifyUpdateEmail($request),
            'update_phone'    => $this->handleVerifyUpdatePhone($request),
        };
    }

    // =========================================================================
    // HANDLER: REQUEST OTP
    // =========================================================================

    /**
     * Register: cek email belum terdaftar → kirim OTP
     */
    private function handleRequestRegister(Request $request)
    {
        if (empty($request->email) || !filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => ['email' => ['Email wajib diisi dan harus valid']],
            ], 422);
        }

        if (User::where('email', $request->email)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => ['email' => ['Email sudah terdaftar']],
            ], 422);
        }

        $otp = $this->generateAndSaveOtp(
            userId  : null,
            tokenKey: $request->email,
            type    : self::TYPE_MAP['register']
        );

        $this->sendOtpEmail($request->email, $otp, self::TYPE_MAP['register']);

        return $this->otpSentResponse($request->email, $otp);
    }

    /**
     * Lupa password: cek email sudah terdaftar → kirim OTP
     */
    private function handleRequestForgotPassword(Request $request)
    {
        if (empty($request->email) || !filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => ['email' => ['Email wajib diisi dan harus valid']],
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => ['email' => ['Email tidak terdaftar']],
            ], 422);
        }

        $otp = $this->generateAndSaveOtp(
            userId  : $user->id,
            tokenKey: null,
            type    : self::TYPE_MAP['forgot_password']
        );

        $this->sendOtpEmail($user->email, $otp, self::TYPE_MAP['forgot_password']);

        return $this->otpSentResponse($user->email, $otp);
    }

    /**
     * Ganti email: butuh JWT → kirim OTP ke email lama
     */
    private function handleRequestUpdateEmail(Request $request)
    {
        $user = $this->getAuthUser();
        if (!$user) return $this->unauthorizedResponse();

        $otp = $this->generateAndSaveOtp(
            userId  : $user->id,
            tokenKey: null,
            type    : self::TYPE_MAP['update_email']
        );

        $this->sendOtpEmail($user->email, $otp, self::TYPE_MAP['update_email']);

        return $this->otpSentResponse($user->email, $otp);
    }

    /**
     * Ganti no. telepon: butuh JWT → kirim OTP ke email akun
     */
    private function handleRequestUpdatePhone(Request $request)
    {
        $user = $this->getAuthUser();
        if (!$user) return $this->unauthorizedResponse();

        $otp = $this->generateAndSaveOtp(
            userId  : $user->id,
            tokenKey: null,
            type    : self::TYPE_MAP['update_phone']
        );

        $this->sendOtpEmail($user->email, $otp, self::TYPE_MAP['update_phone']);

        return $this->otpSentResponse($user->email, $otp);
    }

    // =========================================================================
    // HANDLER: VERIFY OTP
    // =========================================================================

    /**
     * Register: verifikasi OTP → return register_token untuk lanjut complete register
     */
    private function handleVerifyRegister(Request $request)
    {
        if (empty($request->email) || !filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => ['email' => ['Email wajib diisi dan harus valid']],
            ], 422);
        }

        if (User::where('email', $request->email)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Email sudah terdaftar',
            ], 422);
        }

        // Cari OTP berdasarkan token_key (email), bukan user_id
        $otpRecord = PasswordResetToken::where('token_key', $request->email)
            ->where('type', self::TYPE_MAP['register'])
            ->whereNull('used_at')
            ->where('expired_at', '>', now())
            ->latest()
            ->first();

        if (!$otpRecord || !Hash::check($request->otp, $otpRecord->token)) {
            return $this->invalidOtpResponse();
        }

        // Tandai OTP terpakai
        $otpRecord->update(['used_at' => now()]);

        // Buat register_token sementara (berlaku 30 menit)
        $registerToken = bin2hex(random_bytes(32));

        PasswordResetToken::create([
            'user_id'    => null,
            'token_key'  => $request->email,
            'token'      => Hash::make($registerToken),
            'type'       => 'register_verified',
            'expired_at' => now()->addMinutes(30),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'OTP valid. Silakan lengkapi data registrasi.',
            'data'    => [
                'purpose'        => 'register',
                'email'          => $request->email,
                'register_token' => $registerToken,
                'expires_in'     => 1800,
            ],
        ], 200);
    }

    /**
     * Lupa password: verifikasi OTP → return reset_token untuk reset password
     */
    private function handleVerifyForgotPassword(Request $request)
    {
        if (empty($request->email) || !filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => ['email' => ['Email wajib diisi dan harus valid']],
            ], 422);
        }

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Email tidak terdaftar'], 422);
        }

        $otpRecord = $this->findValidOtp($user->id, null, $request->otp, self::TYPE_MAP['forgot_password']);
        if (!$otpRecord) return $this->invalidOtpResponse();

        $otpRecord->update(['used_at' => now()]);

        // Buat reset_token sementara (berlaku 15 menit)
        $resetToken = bin2hex(random_bytes(32));

        PasswordResetToken::create([
            'user_id'    => $user->id,
            'token_key'  => $request->email,
            'token'      => Hash::make($resetToken),
            'type'       => 'password_reset_verified',
            'expired_at' => now()->addMinutes(15),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'OTP valid. Silakan buat password baru.',
            'data'    => [
                'purpose'     => 'forgot_password',
                'email'       => $request->email,
                'reset_token' => $resetToken,
                'expires_in'  => 900,
            ],
        ], 200);
    }

    /**
     * Ganti email: verifikasi OTP → return update_email_token
     */
    private function handleVerifyUpdateEmail(Request $request)
    {
        $user = $this->getAuthUser();
        if (!$user) return $this->unauthorizedResponse();

        $otpRecord = $this->findValidOtp($user->id, null, $request->otp, self::TYPE_MAP['update_email']);
        if (!$otpRecord) return $this->invalidOtpResponse();

        $otpRecord->update(['used_at' => now()]);

        // Buat token sementara untuk proses ganti email (berlaku 15 menit)
        $updateToken = bin2hex(random_bytes(32));

        PasswordResetToken::create([
            'user_id'    => $user->id,
            'token_key'  => null,
            'token'      => Hash::make($updateToken),
            'type'       => 'update_email_verified',
            'expired_at' => now()->addMinutes(15),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'OTP valid. Silakan masukkan email baru.',
            'data'    => [
                'purpose'      => 'update_email',
                'update_token' => $updateToken,
                'expires_in'   => 900,
            ],
        ], 200);
    }

    /**
     * Ganti no. telepon: verifikasi OTP → return update_phone_token
     */
    private function handleVerifyUpdatePhone(Request $request)
    {
        $user = $this->getAuthUser();
        if (!$user) return $this->unauthorizedResponse();

        $otpRecord = $this->findValidOtp($user->id, null, $request->otp, self::TYPE_MAP['update_phone']);
        if (!$otpRecord) return $this->invalidOtpResponse();

        $otpRecord->update(['used_at' => now()]);

        // Buat token sementara untuk proses ganti no. telepon (berlaku 15 menit)
        $updateToken = bin2hex(random_bytes(32));

        PasswordResetToken::create([
            'user_id'    => $user->id,
            'token_key'  => null,
            'token'      => Hash::make($updateToken),
            'type'       => 'update_phone_verified',
            'expired_at' => now()->addMinutes(15),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'OTP valid. Silakan masukkan nomor telepon baru.',
            'data'    => [
                'purpose'      => 'update_phone',
                'update_token' => $updateToken,
                'expires_in'   => 900,
            ],
        ], 200);
    }

    // =========================================================================
    // PRIVATE HELPERS
    // =========================================================================

    /**
     * Generate OTP, hash, simpan ke DB.
     * Hapus OTP lama yang belum dipakai terlebih dahulu.
     */
    private function generateAndSaveOtp(?int $userId, ?string $tokenKey, string $type): string
    {
        // Hapus OTP lama
        $query = PasswordResetToken::where('type', $type)->whereNull('used_at');

        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('token_key', $tokenKey);
        }

        $query->delete();

        // Generate OTP baru
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        PasswordResetToken::create([
            'user_id'    => $userId,
            'token_key'  => $tokenKey,
            'token'      => Hash::make($otp),
            'type'       => $type,
            'expired_at' => now()->addMinutes(5),
        ]);

        return $otp;
    }

    /**
     * Cari OTP valid di DB (belum expired, belum dipakai, hash cocok).
     */
    private function findValidOtp(?int $userId, ?string $tokenKey, string $otp, string $type): ?PasswordResetToken
    {
        $query = PasswordResetToken::where('type', $type)
            ->whereNull('used_at')
            ->where('expired_at', '>', now())
            ->latest();

        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('token_key', $tokenKey);
        }

        $record = $query->first();

        if (!$record || !Hash::check($otp, $record->token)) {
            return null;
        }

        return $record;
    }

    /**
     * Kirim OTP ke email via Mailable.
     */
    private function sendOtpEmail(string $email, string $otp, string $type): void
    {
        try {
            Mail::to($email)->send(new OtpMail($otp, $type));
            Log::info("OTP [{$type}] dikirim ke {$email}");
        } catch (\Exception $e) {
            Log::error("Gagal kirim OTP [{$type}] ke {$email}: " . $e->getMessage());
            throw $e; // lempar ke caller agar response 500 ditangani
        }
    }

    /**
     * Ambil user dari JWT token.
     * Return null jika token tidak ada / tidak valid.
     */
    private function getAuthUser(): ?User
    {
        try {
            return JWTAuth::parseToken()->authenticate() ?: null;
        } catch (JWTException) {
            return null;
        }
    }

    /**
     * Response OTP berhasil dikirim.
     */
    private function otpSentResponse(string $email, string $otp)
    {
        return response()->json([
            'success' => true,
            'message' => "Kode OTP telah dikirim ke {$email}. Berlaku 5 menit.",
            'data'    => ['expires_in' => 300],
            // Hanya tampil saat APP_DEBUG=true
            '_debug_otp' => config('app.debug') ? $otp : null,
        ], 200);
    }

    private function invalidOtpResponse()
    {
        return response()->json([
            'success' => false,
            'message' => 'Kode OTP tidak valid atau sudah kadaluarsa',
        ], 422);
    }

    private function unauthorizedResponse()
    {
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized. Harap login terlebih dahulu.',
        ], 401);
    }
}