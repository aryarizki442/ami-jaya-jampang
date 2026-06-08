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
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    // =========================================================================
    // REGISTER - Step 3 (Final)
    // Step 1 & 2 (request OTP + verify OTP) → OtpController
    // =========================================================================

    /**
     * POST /api/auth/register/complete
     * Body: { email, register_token, name, phone, password, password_confirmation }
     */
    public function registerComplete(Request $request)
    {
        // Log untuk debugging
        Log::info('========== REGISTER COMPLETE CALLED ==========');
        Log::info('Request data:', $request->all());

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

        // Cari token di database
        $tokenRecord = PasswordResetToken::where('token_key', $request->email)
            ->where('type', 'register_verified')
            ->whereNull('used_at')
            ->where('expired_at', '>', now())
            ->first();

        Log::info('Token record ditemukan: ' . ($tokenRecord ? 'YES' : 'NO'));

        if (!$tokenRecord || !Hash::check($request->register_token, $tokenRecord->token)) {
            return response()->json([
                'success' => false,
                'message' => 'Token registrasi tidak valid atau kadaluarsa. Ulangi dari awal.',
            ], 422);
        }

        if (User::where('email', $request->email)->exists()) {
            return response()->json(['success' => false, 'message' => 'Email sudah terdaftar'], 422);
        }

        try {
            $user = User::create([
                'name'              => $request->name,
                'email'             => $request->email,
                'phone'             => $request->phone,
                'password'          => Hash::make($request->password),
                'role'              => 'customer',
                'email_verified_at' => now(),
            ]);

            // Hapus token yang sudah digunakan
            PasswordResetToken::where('token_key', $request->email)
                ->whereIn('type', ['otp_register', 'register_verified'])
                ->delete();

            // Buat JWT token untuk auto login
            $token = JWTAuth::fromUser($user);

            return response()->json([
                'success' => true,
                'message' => 'Registrasi berhasil',
                'data'    => [
                    'user'         => $user,
                    'access_token' => $token,
                    'token_type'   => 'Bearer',
                    'expires_in'   => config('jwt.ttl') * 60,
                ],
            ], 201);

        } catch (\Exception $e) {
            Log::error('Register Complete Error', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat registrasi: ' . $e->getMessage(),
            ], 500);
        }
    }



    // =========================================================================
    // LOGIN
    // =========================================================================

    /**
     * POST /api/auth/login
     * Body: { email_or_phone, password }
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

        $loginField  = filter_var($request->email_or_phone, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';
        $credentials = [$loginField => $request->email_or_phone, 'password' => $request->password];

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
    // ME & LOGOUT
    // =========================================================================

    /** GET /api/auth/me */
    public function me()
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) return $this->userNotFound();

            return response()->json([
                'success' => true,
                'data'    => ['user' => $this->formatUser($user)],
            ], 200);

        } catch (JWTException $e) {
            return response()->json(['success' => false, 'message' => 'Token tidak valid atau expired'], 401);
        }
    }

    /** POST /api/auth/logout */
    public function logout()
    {
        try {
            JWTAuth::parseToken()->invalidate();
            return response()->json(['success' => true, 'message' => 'Logout berhasil'], 200);
        } catch (JWTException $e) {
            return response()->json(['success' => false, 'message' => 'Logout gagal', 'error' => $e->getMessage()], 500);
        }
    }

    // =========================================================================
    // UPDATE PROFIL
    // =========================================================================

    /**
     * Update nama, gender, birth_date.
     * PUT /api/auth/profile
     */
    public function update(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) return $this->userNotFound();

            $validator = Validator::make($request->all(), [
                'name'       => 'sometimes|string|max:255',
                'gender'     => 'nullable|in:male,female',
                'birth_date' => 'nullable|date|before:today',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => 'Validasi gagal', 'errors' => $validator->errors()], 422);
            }

            $user->update($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diperbarui',
                'data'    => ['user' => $this->formatUser($user->fresh())],
            ], 200);

        } catch (\Exception $e) {
            return $this->serverError($e);
        }
    }

    /**
     * Ganti email — butuh update_token dari OtpController.
     * POST /api/auth/update-email
     * Body: { update_token, new_email }
     */
    public function updateEmail(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) return $this->userNotFound();

            $validator = Validator::make($request->all(), [
                'update_token' => 'required|string',
                'new_email'    => 'required|email|unique:users,email,' . $user->id,
            ], [
                'update_token.required' => 'Token verifikasi wajib diisi',
                'new_email.required'    => 'Email baru wajib diisi',
                'new_email.email'       => 'Format email tidak valid',
                'new_email.unique'      => 'Email sudah digunakan akun lain',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => 'Validasi gagal', 'errors' => $validator->errors()], 422);
            }

            if ($request->new_email === $user->email) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors'  => ['new_email' => ['Email baru tidak boleh sama dengan email saat ini']],
                ], 422);
            }

           $tokenRecord = PasswordResetToken::where('user_id', $user->id)
                ->where('type', 'update_email_verified')
                ->whereNull('used_at')
                ->where('expired_at', '>', now())
                ->orderByDesc('id')
               ->latest()->first();

            if (!$tokenRecord || !Hash::check($request->update_token, $tokenRecord->token)) {
                return response()->json(['success' => false, 'message' => 'Token tidak valid atau kadaluarsa. Ulangi verifikasi OTP.'], 422);
            }

            $user->update(['email' => $request->new_email, 'email_verified_at' => now()]);
            $tokenRecord->delete();

            return response()->json([
                'success' => true,
                'message' => 'Email berhasil diperbarui',
                'data'    => ['email' => $user->fresh()->email],
            ], 200);

        } catch (\Exception $e) {
            return $this->serverError($e);
        }
    }

    /**
     * Ganti nomor telepon — butuh update_token dari OtpController.
     * POST /api/auth/update-phone
     * Body: { update_token, new_phone }
     */
    public function updatePhone(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) return $this->userNotFound();

            $validator = Validator::make($request->all(), [
                'update_token' => 'required|string',
                'new_phone'    => 'required|string|max:20|unique:users,phone,' . $user->id,
            ], [
                'update_token.required' => 'Token verifikasi wajib diisi',
                'new_phone.required'    => 'Nomor telepon baru wajib diisi',
                'new_phone.unique'      => 'Nomor telepon sudah digunakan akun lain',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => 'Validasi gagal', 'errors' => $validator->errors()], 422);
            }

            if ($request->new_phone === $user->phone) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors'  => ['new_phone' => ['Nomor baru tidak boleh sama dengan nomor saat ini']],
                ], 422);
            }

          $tokenRecord = PasswordResetToken::where('user_id', $user->id)
             ->where('type', 'update_phone_verified')
                ->whereNull('used_at')
                ->where('expired_at', '>', now())
                ->orderByDesc('id')
               ->latest()->first();


            if (!$tokenRecord || !Hash::check($request->update_token, $tokenRecord->token)) {
                return response()->json(['success' => false, 'message' => 'Token tidak valid atau kadaluarsa. Ulangi verifikasi OTP.'], 422);
            }

            $user->update(['phone' => $request->new_phone]);
            $tokenRecord->delete();

            return response()->json([
                'success' => true,
                'message' => 'Nomor telepon berhasil diperbarui',
                'data'    => ['phone' => $user->fresh()->phone],
            ], 200);

        } catch (\Exception $e) {
            return $this->serverError($e);
        }
    }

    // =========================================================================
    // PASSWORD
    // =========================================================================

    /**
     * Ganti password (sudah login).
     * POST /api/auth/change-password
     * Body: { current_password, password, password_confirmation }
     */
   public function changePassword(Request $request)
{
    try {
        // Ambil user dari JWT
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return $this->userNotFound();
        }

        // Validasi request
        $validator = Validator::make($request->all(), [
            'update_token'     => 'required|string',
            'current_password' => 'required|string',
            'password'         => 'required|string|min:8|confirmed',
        ], [
            'current_password.required' => 'Password lama wajib diisi',
            'password.required'         => 'Password baru wajib diisi',
            'password.min'              => 'Password baru minimal 8 karakter',
            'password.confirmed'        => 'Konfirmasi password tidak cocok',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $validator->errors(),
            ], 422);
        }

        // Cek password lama
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => [
                    'current_password' => [
                        'Password lama tidak sesuai'
                    ]
                ],
            ], 422);
        }

        // Cek token OTP yang sudah diverifikasi
        $tokenRecord = PasswordResetToken::where('user_id', $user->id)
            ->where('type', 'change_password_verified')
            ->whereNull('used_at')
            ->where('expired_at', '>', now())
            ->latest()
            ->first();

        if (
            !$tokenRecord ||
            !Hash::check($request->update_token, $tokenRecord->token)
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Token OTP tidak valid atau kadaluarsa',
            ], 422);
        }

        // Optional: password baru tidak boleh sama dengan password lama
        if (Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password baru tidak boleh sama dengan password lama',
            ], 422);
        }

        // Update password
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Tandai token sudah digunakan
        $tokenRecord->update([
            'used_at' => now(),
        ]);

        // Logout JWT saat ini
        JWTAuth::invalidate(JWTAuth::getToken());

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diubah. Silakan login kembali.',
        ], 200);

    } catch (\Exception $e) {
        return $this->serverError($e);
    }
}

    /**
     * Reset password (lupa password) — butuh reset_token dari OtpController.
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
            return response()->json(['success' => false, 'message' => 'Validasi gagal', 'errors' => $validator->errors()], 422);
        }

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Email tidak terdaftar'], 422);
        }

        $tokenRecord = PasswordResetToken::where('user_id', $user->id)
            ->where('type', 'password_reset_verified')
            ->whereNull('used_at')
            ->where('expired_at', '>', now())
            ->first();

        if (!$tokenRecord || !Hash::check($request->reset_token, $tokenRecord->token)) {
            return response()->json(['success' => false, 'message' => 'Token reset tidak valid atau kadaluarsa. Ulangi dari awal.'], 422);
        }

        $user->update(['password' => Hash::make($request->password)]);

        PasswordResetToken::where('user_id', $user->id)
            ->whereIn('type', ['otp_forgot_password', 'password_reset_verified'])
            ->delete();

        return response()->json(['success' => true, 'message' => 'Password berhasil direset. Silakan login dengan password baru.'], 200);
    }

    // =========================================================================
    // AVATAR
    // =========================================================================

    /** POST /api/auth/avatar */
    public function uploadAvatar(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'avatar' => 'required|image|mimes:jpeg,jpg,png|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json(['success' => false, 'message' => 'Validasi gagal', 'errors' => $validator->errors()], 422);
            }

            $user = JWTAuth::parseToken()->authenticate();
            if (!$user) return $this->userNotFound();

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
            return $this->serverError($e);
        }
    }

    /** DELETE /api/auth/avatar */
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

    private function formatUser(User $user): array
    {
        return [
            'id'         => $user->id,
            'name'       => $user->name,
            'email'      => $user->email,
            'phone'      => $user->phone,
            'role'       => $user->role,
            'gender'     => $user->gender,
            'birth_date' => $user->birth_date?->format('Y-m-d'),
            'avatar'     => $user->avatar ? Storage::url($user->avatar) : null,
            'created_at' => $user->created_at->format('Y-m-d H:i:s'),
        ];
    }

    private function userNotFound()
    {
        return response()->json(['success' => false, 'message' => 'User tidak ditemukan'], 404);
    }

    private function serverError(\Exception $e)
    {
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan',
            'error'   => config('app.debug') ? $e->getMessage() : 'Internal Server Error',
        ], 500);
    }
}
