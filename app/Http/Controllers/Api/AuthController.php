<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PasswordResetToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    // =========================================================================
    // REGISTER FLOW
    // Step 1 → Step 2 → Step 3
    // =========================================================================

    /**
     * Register a new user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function registerRequestOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
        ], [
            'email.required' => 'Email wajib diisi',
            'email.email'    => 'Format email tidak valid',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors(),
            ], 422);
        }

        // Cek apakah email sudah terdaftar
        if (User::where('email', $request->email)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => ['email' => ['Email sudah terdaftar']],
            ], 422);
        }

        // Hapus OTP lama untuk email ini (jika ada)
        PasswordResetToken::where('token_key', $request->email)
            ->where('type', 'otp_register')
            ->delete();

        // Generate OTP 6 digit
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        PasswordResetToken::create([
            'token_key'  => $request->email, // simpan email sebagai key
            'token'      => Hash::make($otp), // hash OTP untuk keamanan
            'type'       => 'otp_register',
            'expired_at' => now()->addMinutes(5),
        ]);

        // TODO: Kirim OTP ke email
        // Mail::to($request->email)->send(new OtpRegisterMail($otp));

        Log::info('OTP Register dikirim', ['email' => $request->email]);

        return response()->json([
            'success' => true,
            'message' => "Kode OTP telah dikirim ke {$request->email}. Berlaku 5 menit.",
            'data'    => ['expires_in' => 300],
            '_debug_otp' => config('app.debug') ? $otp : null,
        ], 200);
    }

    /**
     * REGISTER - Step 2
     * User memasukkan OTP yang diterima di email.
     * Jika OTP valid → return token sementara untuk lanjut ke step 3.
     *
     * POST /api/auth/register/verify-otp
     * Body: { email, otp }
     */
    public function registerVerifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'otp'   => 'required|string|size:6',
        ], [
            'email.required' => 'Email wajib diisi',
            'email.email'    => 'Format email tidak valid',
            'otp.required'   => 'Kode OTP wajib diisi',
            'otp.size'       => 'Kode OTP harus 6 digit',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors(),
            ], 422);
        }

        // Double check: email tidak boleh sudah terdaftar
        if (User::where('email', $request->email)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Email sudah terdaftar',
            ], 422);
        }

        // Cari OTP yang valid (belum expired, belum dipakai)
        $otpRecord = PasswordResetToken::where('token_key', $request->email)
            ->where('type', 'otp_register')
            ->whereNull('used_at')
            ->where('expired_at', '>', now())
            ->first();

        if (!$otpRecord || !Hash::check($request->otp, $otpRecord->token)) {
            return response()->json([
                'success' => false,
                'message' => 'Kode OTP tidak valid atau sudah kadaluarsa',
            ], 422);
        }

        // Tandai OTP sudah diverifikasi (gunakan used_at sebagai flag)
        $otpRecord->update(['used_at' => now()]);

        // Buat register_token sementara agar step 3 bisa berjalan
        // Token ini disimpan di DB dengan type berbeda
        $registerToken = bin2hex(random_bytes(32));

        PasswordResetToken::create([
            'token_key'  => $request->email,
            'token'      => Hash::make($registerToken),
            'type'       => 'register_verified',
            'expired_at' => now()->addMinutes(30), // 30 menit untuk isi form
        ]);

        return response()->json([
            'success' => true,
            'message' => 'OTP valid. Silakan lengkapi data registrasi.',
            'data'    => [
                'email'          => $request->email,
                'register_token' => $registerToken,
                'expires_in'     => 1800,
            ],
        ], 200);
    }

    /**
     * REGISTER - Step 3
     * User mengisi nama, nomor telepon, password.
     * Email diambil dari register_token yang sudah diverifikasi.
     *
     * POST /api/auth/register/complete
     * Body: { email, register_token, name, phone, password, password_confirmation }
     */
    public function registerComplete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'          => 'required|string|email',
            'register_token' => 'required|string',
            'name'           => 'required|string|max:255',
            'phone'          => 'required|string|max:20|unique:users',
            'password'       => 'required|string|min:8|confirmed',
        ], [
            'email.required'          => 'Email wajib diisi',
            'register_token.required' => 'Token registrasi wajib diisi',
            'name.required'           => 'Nama wajib diisi',
            'name.max'                => 'Nama maksimal 255 karakter',
            'phone.required'          => 'Nomor telepon wajib diisi',
            'phone.unique'            => 'Nomor telepon sudah terdaftar',
            'password.required'       => 'Kata sandi wajib diisi',
            'password.min'            => 'Kata sandi minimal 8 karakter',
            'password.confirmed'      => 'Konfirmasi kata sandi tidak cocok',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors(),
            ], 422);
        }

        // Verifikasi register_token
        $tokenRecord = PasswordResetToken::where('token_key', $request->email)
            ->where('type', 'register_verified')
            ->whereNull('used_at')
            ->where('expired_at', '>', now())
            ->first();

        if (!$tokenRecord || !Hash::check($request->register_token, $tokenRecord->token)) {
            return response()->json([
                'success' => false,
                'message' => 'Token registrasi tidak valid atau sudah kadaluarsa. Ulangi dari awal.',
            ], 422);
        }

        // Double check email belum terdaftar
        if (User::where('email', $request->email)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Email sudah terdaftar',
            ], 422);
        }

        try {
            $user = User::create([
                'name'              => $request->name,
                'email'             => $request->email,
                'phone'             => $request->phone,
                'password'          => Hash::make($request->password),
                'email_verified_at' => now(), // email sudah terverifikasi via OTP
            ]);

            // Hapus token registrasi
            $tokenRecord->delete();
            PasswordResetToken::where('token_key', $request->email)
                ->where('type', 'otp_register')
                ->delete();

            // Generate JWT
            $token = JWTAuth::fromUser($user);

            return response()->json([
                'success' => true,
                'message' => 'Registrasi berhasil',
                'data'    => [
                    'user'         => $this->formatUser($user),
                    'access_token' => $token,
                    'token_type'   => 'Bearer',
                    'expires_in'   => config('jwt.ttl') * 60,
                ],
            ], 201);

        } catch (\Exception $e) {
            Log::error('Register Complete Error', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Registrasi gagal',
                'error'   => config('app.debug') ? $e->getMessage() : 'Internal Server Error',
            ], 500);
        }
    }

    // =========================================================================
    // LOGIN
    // =========================================================================

    /**
     * Login user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email_or_phone' => 'required|string',
            'password'       => 'required|string',
        ], [
            'email_or_phone.required' => 'Email atau nomor telepon wajib diisi',
            'password.required'       => 'Kata sandi wajib diisi',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors(),
            ], 422);
        }

        // Check if input is email or phone
        $loginField = filter_var($request->email_or_phone, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'phone';

             $user = \App\Models\User::where($loginField, $request->email_or_phone)->first();

    if (!$user) {
        // Email/Phone tidak ditemukan
        return response()->json([
            'success' => false,
            'message' => 'Email/No. Telepon Anda salah',
            'errors' => [
                'email_or_phone' => ['Email/No. Telepon Anda salah']
            ]
        ], 401);
    }

    // cek password
    if (!\Illuminate\Support\Facades\Hash::check($request->password, $user->password)) {
        return response()->json([
            'success' => false,
            'message' => 'Kata Sandi Anda Salah',
            'errors' => [
                'password' => ['Kata Sandi Anda Salah']
            ]
        ], 401);
    }

        $credentials = [
            $loginField => $request->email_or_phone,
            'password' => $request->password
        ];

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email/Nomor telepon atau kata sandi salah',
                ], 401);
            }
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak dapat membuat token',
                'error'   => $e->getMessage(),
            ], 500);
        }

        $user = JWTAuth::user();

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'data'    => [
                'user'         => $this->formatUser($user),
                'access_token' => $token,
                'token_type'   => 'Bearer',
                'expires_in'   => config('jwt.ttl') * 60,
            ],
        ], 200);
    }

    // =========================================================================
    // ME, LOGOUT, UPDATE PROFIL DASAR
    // =========================================================================

    /**
     * Get authenticated user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json(['success' => false, 'message' => 'User tidak ditemukan'], 404);
            }

            return response()->json([
                'success' => true,
                'data'    => ['user' => $this->formatUser($user)],
            ], 200);

        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid atau expired',
            ], 401);
        }
    }

    /**
     * POST /api/auth/logout
     */
    public function logout()
    {
        try {
            JWTAuth::parseToken()->invalidate();

            return response()->json(['success' => true, 'message' => 'Logout berhasil'], 200);
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Logout gagal',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Logout user (Invalidate token)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json(['success' => false, 'message' => 'User tidak ditemukan'], 404);
            }

            $validator = Validator::make($request->all(), [
                'name'       => 'sometimes|string|max:255',
                'gender'     => 'nullable|in:male,female',
                'birth_date' => 'nullable|date|before:today',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors'  => $validator->errors(),
                ], 422);
            }

            $user->update($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diperbarui',
                'data'    => ['user' => $this->formatUser($user->fresh())],
            ], 200);

        } catch (\Exception $e) {
            Log::error('Update profile error', ['message' => $e->getMessage()]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan',
                'error'   => config('app.debug') ? $e->getMessage() : 'Internal Server Error',
            ], 500);
        }
    }

    // =========================================================================
    // GANTI EMAIL
    // Step 1: request OTP → Step 2: verify OTP + isi email baru
    // =========================================================================

    /**
     * GANTI EMAIL - Step 1
     * Validasi email lama → kirim OTP ke email lama.
     *
     * POST /api/auth/update-email/request-otp
     * Body: { current_email }
     */
    public function requestUpdateEmail(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) return $this->userNotFound();

            $errors = [];

            if (empty($request->current_email)) {
                $errors['current_email'][] = 'Email saat ini wajib diisi';
            } elseif (!filter_var($request->current_email, FILTER_VALIDATE_EMAIL)) {
                $errors['current_email'][] = 'Format email tidak valid';
            } elseif ($request->current_email !== $user->email) {
                $errors['current_email'][] = 'Email tidak sesuai dengan akun Anda';
            }

            if (!empty($errors)) {
                return response()->json(['success' => false, 'message' => 'Validasi gagal', 'errors' => $errors], 422);
            }

            $this->sendOtp($user->id, $user->email, 'otp_update_email');

            return response()->json([
                'success' => true,
                'message' => "Kode OTP telah dikirim ke {$user->email}. Berlaku 5 menit.",
                'data'    => ['expires_in' => 300],
                '_debug_otp' => config('app.debug') ? $this->lastOtp : null,
            ], 200);

        } catch (\Exception $e) {
            Log::error('Request Update Email Error: ' . $e->getMessage());
            return $this->serverError($e);
        }
    }

    /**
     * GANTI EMAIL - Step 2
     * Verifikasi OTP + masukkan email baru.
     *
     * POST /api/auth/update-email/verify-otp
     * Body: { otp, new_email }
     */
    public function verifyUpdateEmail(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) return $this->userNotFound();

            $errors = $this->validateOtpInput($request, 'otp');

            if (empty($request->new_email)) {
                $errors['new_email'][] = 'Email baru wajib diisi';
            } elseif (!filter_var($request->new_email, FILTER_VALIDATE_EMAIL)) {
                $errors['new_email'][] = 'Format email baru tidak valid';
            } else {
                if (User::where('email', $request->new_email)->where('id', '!=', $user->id)->exists()) {
                    $errors['new_email'][] = 'Email baru sudah digunakan akun lain';
                }
                if ($request->new_email === $user->email) {
                    $errors['new_email'][] = 'Email baru tidak boleh sama dengan email saat ini';
                }
            }

            if (!empty($errors)) {
                return response()->json(['success' => false, 'message' => 'Validasi gagal', 'errors' => $errors], 422);
            }

            $otpRecord = $this->findValidOtp($user->id, $request->otp, 'otp_update_email');

            if (!$otpRecord) {
                return response()->json(['success' => false, 'message' => 'Kode OTP tidak valid atau sudah kadaluarsa'], 422);
            }

            $user->update(['email' => $request->new_email, 'email_verified_at' => now()]);
            $this->consumeOtp($otpRecord, $user->id, 'otp_update_email');

            return response()->json([
                'success' => true,
                'message' => 'Email berhasil diperbarui',
                'data'    => ['email' => $user->fresh()->email],
            ], 200);

        } catch (\Exception $e) {
            Log::error('Verify Update Email Error: ' . $e->getMessage());
            return $this->serverError($e);
        }
    }

    // =========================================================================
    // GANTI NOMOR TELEPON
    // Step 1: request OTP (via email) → Step 2: verify OTP + isi nomor baru
    // =========================================================================

    /**
     * GANTI NO. TELEPON - Step 1
     * Validasi email akun → kirim OTP ke email.
     *
     * POST /api/auth/update-phone/request-otp
     * Body: { current_email }
     */
    public function requestUpdatePhone(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) return $this->userNotFound();

            $errors = [];

            if (empty($request->current_email)) {
                $errors['current_email'][] = 'Email saat ini wajib diisi';
            } elseif (!filter_var($request->current_email, FILTER_VALIDATE_EMAIL)) {
                $errors['current_email'][] = 'Format email tidak valid';
            } elseif ($request->current_email !== $user->email) {
                $errors['current_email'][] = 'Email tidak sesuai dengan akun Anda';
            }

            if (!empty($errors)) {
                return response()->json(['success' => false, 'message' => 'Validasi gagal', 'errors' => $errors], 422);
            }

            $this->sendOtp($user->id, $user->email, 'otp_update_phone');

            return response()->json([
                'success' => true,
                'message' => "Kode OTP telah dikirim ke {$user->email}. Berlaku 5 menit.",
                'data'    => ['expires_in' => 300],
                '_debug_otp' => config('app.debug') ? $this->lastOtp : null,
            ], 200);

        } catch (\Exception $e) {
            Log::error('Request Update Phone Error: ' . $e->getMessage());
            return $this->serverError($e);
        }
    }

    /**
     * GANTI NO. TELEPON - Step 2
     * Verifikasi OTP + masukkan nomor baru.
     *
     * POST /api/auth/update-phone/verify-otp
     * Body: { otp, new_phone }
     */
    public function verifyUpdatePhone(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) return $this->userNotFound();

            $errors = $this->validateOtpInput($request, 'otp');

            if (empty($request->new_phone)) {
                $errors['new_phone'][] = 'Nomor telepon baru wajib diisi';
            } else {
                if (User::where('phone', $request->new_phone)->where('id', '!=', $user->id)->exists()) {
                    $errors['new_phone'][] = 'Nomor telepon sudah digunakan akun lain';
                }
                if ($request->new_phone === $user->phone) {
                    $errors['new_phone'][] = 'Nomor telepon baru tidak boleh sama dengan nomor saat ini';
                }
            }

            if (!empty($errors)) {
                return response()->json(['success' => false, 'message' => 'Validasi gagal', 'errors' => $errors], 422);
            }

            $otpRecord = $this->findValidOtp($user->id, $request->otp, 'otp_update_phone');

            if (!$otpRecord) {
                return response()->json(['success' => false, 'message' => 'Kode OTP tidak valid atau sudah kadaluarsa'], 422);
            }

            $user->update(['phone' => $request->new_phone]);
            $this->consumeOtp($otpRecord, $user->id, 'otp_update_phone');

            return response()->json([
                'success' => true,
                'message' => 'Nomor telepon berhasil diperbarui',
                'data'    => ['phone' => $user->fresh()->phone],
            ], 200);

        } catch (\Exception $e) {
            Log::error('Verify Update Phone Error: ' . $e->getMessage());
            return $this->serverError($e);
        }
    }

    // =========================================================================
    // GANTI PASSWORD (user sudah login)
    // Tidak perlu OTP — cukup verifikasi password lama
    // =========================================================================

    /**
     * POST /api/auth/change-password
     * Body: { current_password, password, password_confirmation }
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password'         => 'required|string|min:8|confirmed',
        ], [
            'current_password.required' => 'Password lama wajib diisi',
            'password.required'         => 'Password baru wajib diisi',
            'password.min'              => 'Password baru minimal 8 karakter',
            'password.confirmed'        => 'Konfirmasi password tidak cocok',
        ]);

        $user = JWTAuth::parseToken()->authenticate();

        if (!Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Password lama tidak sesuai.'],
            ]);
        }

        $user->update(['password' => Hash::make($request->password)]);
        JWTAuth::parseToken()->invalidate();

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diubah. Silakan login kembali.',
        ], 200);
    }

    // =========================================================================
    // LUPA PASSWORD (user belum login)
    // Step 1: request OTP via email → Step 2: verify OTP → Step 3: reset password
    // =========================================================================

    /**
     * LUPA PASSWORD - Step 1
     * Masukkan email → kirim OTP jika email terdaftar.
     *
     * POST /api/auth/forgot-password/request-otp
     * Body: { email }
     */
    public function forgotPasswordRequestOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
        ], [
            'email.required' => 'Email wajib diisi',
            'email.email'    => 'Format email tidak valid',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors(),
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

        $this->sendOtp($user->id, $user->email, 'otp_forgot_password');

        return response()->json([
            'success' => true,
            'message' => "Kode OTP telah dikirim ke {$user->email}. Berlaku 5 menit.",
            'data'    => ['expires_in' => 300],
            '_debug_otp' => config('app.debug') ? $this->lastOtp : null,
        ], 200);
    }

    /**
     * LUPA PASSWORD - Step 2
     * Verifikasi OTP → return reset_token untuk step 3.
     *
     * POST /api/auth/forgot-password/verify-otp
     * Body: { email, otp }
     */
    public function forgotPasswordVerifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'otp'   => 'required|string|size:6',
        ], [
            'email.required' => 'Email wajib diisi',
            'otp.required'   => 'Kode OTP wajib diisi',
            'otp.size'       => 'Kode OTP harus 6 digit',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Email tidak terdaftar'], 422);
        }

        $otpRecord = $this->findValidOtp($user->id, $request->otp, 'otp_forgot_password');

        if (!$otpRecord) {
            return response()->json(['success' => false, 'message' => 'Kode OTP tidak valid atau sudah kadaluarsa'], 422);
        }

        // Tandai OTP sudah terpakai
        $otpRecord->update(['used_at' => now()]);

        // Buat reset_token sementara (berlaku 15 menit)
        $resetToken = bin2hex(random_bytes(32));

        PasswordResetToken::create([
            'token_key'  => $request->email,
            'token'      => Hash::make($resetToken),
            'type'       => 'password_reset_verified',
            'expired_at' => now()->addMinutes(15),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'OTP valid. Silakan buat password baru.',
            'data'    => [
                'email'       => $request->email,
                'reset_token' => $resetToken,
                'expires_in'  => 900,
            ],
        ], 200);
    }

    /**
     * LUPA PASSWORD - Step 3
     * Reset password menggunakan reset_token dari step 2.
     *
     * POST /api/auth/forgot-password/reset
     * Body: { email, reset_token, password, password_confirmation }
     */
    public function forgotPasswordReset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'       => 'required|string|email',
            'reset_token' => 'required|string',
            'password'    => 'required|string|min:8|confirmed',
        ], [
            'email.required'       => 'Email wajib diisi',
            'reset_token.required' => 'Token reset wajib diisi',
            'password.required'    => 'Kata sandi baru wajib diisi',
            'password.min'         => 'Kata sandi minimal 8 karakter',
            'password.confirmed'   => 'Konfirmasi kata sandi tidak cocok',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Email tidak terdaftar'], 422);
        }

        // Verifikasi reset_token
        $tokenRecord = PasswordResetToken::where('token_key', $request->email)
            ->where('type', 'password_reset_verified')
            ->whereNull('used_at')
            ->where('expired_at', '>', now())
            ->first();

        if (!$tokenRecord || !Hash::check($request->reset_token, $tokenRecord->token)) {
            return response()->json([
                'success' => false,
                'message' => 'Token reset tidak valid atau sudah kadaluarsa. Ulangi dari awal.',
            ], 422);
        }

        $user->update(['password' => Hash::make($request->password)]);

        // Bersihkan semua token terkait
        PasswordResetToken::where('token_key', $request->email)
            ->whereIn('type', ['otp_forgot_password', 'password_reset_verified'])
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil direset. Silakan login dengan password baru.',
        ], 200);
    }

    // =========================================================================
    // AVATAR
    // =========================================================================

    /**
     * POST /api/auth/avatar
     */
    public function uploadAvatar(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'avatar' => 'required|image|mimes:jpeg,jpg,png|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors'  => $validator->errors(),
                ], 422);
            }

            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) return $this->userNotFound();

            // Hapus avatar lama
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }

            $file     = $request->file('avatar');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path     = $file->storeAs('avatars', $filename, 'public');

            $user->update(['avatar' => $path]);

            return response()->json([
                'success' => true,
                'message' => 'Foto profil berhasil diperbarui',
                'data'    => ['avatar' => Storage::url($path)],
            ], 200);

        } catch (\Exception $e) {
            Log::error('Upload Avatar Error: ' . $e->getMessage());
            return $this->serverError($e);
        }
    }

    /**
     * DELETE /api/auth/avatar
     */
    public function deleteAvatar(Request $request)
    {
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user->avatar) {
            return response()->json(['success' => false, 'message' => 'Tidak ada foto profil'], 422);
        }

        if (Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->update(['avatar' => null]);

        return response()->json(['success' => true, 'message' => 'Foto profil dihapus'], 200);
    }

    // =========================================================================
    // PRIVATE HELPERS
    // =========================================================================

    /** OTP terakhir yang di-generate (untuk debug mode) */
    private string $lastOtp = '';

    /**
     * Generate & simpan OTP ke DB, kirim ke email.
     */
    private function sendOtp(int $userId, string $email, string $type): void
    {
        // Hapus OTP lama yang belum dipakai
        PasswordResetToken::where('user_id', $userId)
            ->where('type', $type)
            ->whereNull('used_at')
            ->delete();

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $this->lastOtp = $otp;

        PasswordResetToken::create([
            'user_id'    => $userId,
            'token'      => Hash::make($otp),
            'type'       => $type,
            'expired_at' => now()->addMinutes(5),
        ]);

        // TODO: Kirim OTP ke email
        // Mail::to($email)->send(new OtpMail($otp, $type));

        Log::info("OTP [{$type}] dikirim ke {$email}");
    }

    /**
     * Validasi field OTP dari request.
     */
    private function validateOtpInput(Request $request, string $field = 'otp'): array
    {
        $errors = [];

        if (empty($request->$field)) {
            $errors[$field][] = 'Kode OTP wajib diisi';
        } elseif (strlen($request->$field) !== 6) {
            $errors[$field][] = 'Kode OTP harus 6 digit';
        }

        return $errors;
    }

    /**
     * Cari OTP yang valid (belum expired, belum dipakai, hash cocok).
     */
    private function findValidOtp(int $userId, string $otp, string $type): ?PasswordResetToken
    {
        $record = PasswordResetToken::where('user_id', $userId)
            ->where('type', $type)
            ->whereNull('used_at')
            ->where('expired_at', '>', now())
            ->latest()
            ->first();

        if (!$record || !Hash::check($otp, $record->token)) {
            return null;
        }

        return $record;
    }

    /**
     * Tandai OTP sudah dipakai & hapus semua OTP lama.
     */
    private function consumeOtp(PasswordResetToken $otpRecord, int $userId, string $type): void
    {
        $otpRecord->update(['used_at' => now()]);

        PasswordResetToken::where('user_id', $userId)
            ->where('type', $type)
            ->delete();
    }

    /**
     * Format data user untuk response.
     */
    private function formatUser(User $user): array
    {
        return [
            'id'         => $user->id,
            'name'       => $user->name,
            'email'      => $user->email,
            'phone'      => $user->phone,
            'gender'     => $user->gender,
            'birth_date' => $user->birth_date?->format('Y-m-d'),
            'avatar'     => $user->avatar ? Storage::url($user->avatar) : null,
            'created_at' => $user->created_at->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Response user tidak ditemukan.
     */
    private function userNotFound()
    {
        return response()->json(['success' => false, 'message' => 'User tidak ditemukan'], 404);
    }

    /**
     * Refresh a token (OPTIONAL - bisa dihapus jika tidak ingin ada refresh token)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    private function serverError(\Exception $e)
    {
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan',
            'error'   => config('app.debug') ? $e->getMessage() : 'Internal Server Error',
        ], 500);
    }
}
