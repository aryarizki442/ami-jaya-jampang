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
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */

    /**
     * Register a new user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'name.required' => 'Nama wajib diisi',
            'name.max' => 'Nama maksimal 255 karakter',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'phone.required' => 'Nomor telepon wajib diisi',
            'phone.unique' => 'Nomor telepon sudah terdaftar',
            'password.required' => 'Kata sandi wajib diisi',
            'password.min' => 'Kata sandi minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
            ]);

            // Generate JWT token
            $token = JWTAuth::fromUser($user);

            return response()->json([
                'success' => true,
                'message' => 'Registrasi berhasil',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone' => $user->phone,
                    ],
                ]
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Registrasi gagal',
                'error' => $e->getMessage()
            ], 500);
        }
    }

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
            'password' => 'required|string',
        ], [
            'email_or_phone.required' => 'Email atau nomor telepon wajib diisi',
            'password.required' => 'Kata sandi wajib diisi',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
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
            // Menggunakan JWTAuth langsung
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
                'error' => $e->getMessage()
            ], 500);
        }

        // Dapatkan user dari token
        $user = JWTAuth::user();

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                ],
                'access_token' => $token,
                'token_type' => 'Bearer',
                'expires_in' => config('jwt.ttl') * 60 // in seconds
            ]
        ], 200);
    }
  

    /**
     * Get authenticated user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        try {
            // Dapatkan user dari token JWT
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak ditemukan',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'phone' => $user->phone,
                        'created_at' => $user->created_at,
                    ]
                ]
            ], 200);
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak valid atau expired',
                'error' => $e->getMessage()
            ], 401);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Logout user (Invalidate token)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        try {
            // Invalidate token JWT
           JWTAuth::parseToken()->invalidate();

            return response()->json([
                'success' => true,
                'message' => 'Logout berhasil'
            ], 200);
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Logout gagal',
                'error' => $e->getMessage()
            ], 500);
        }
    }


public function update(Request $request)
{
    try {
        $user = JWTAuth::parseToken()->authenticate();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'gender' => 'nullable|in:male,female',
            'birth_date' => 'nullable|date|before:today',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $user->update($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui',
            'data' => ['user' => $user->fresh()]
        ], 200);

    } catch (\Exception $e) {
        // PASTIKAN INI MENGGUNAKAN LOG YANG BENAR
        \Illuminate\Support\Facades\Log::error('Update profile error', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ], 500);
    }
}

     public function requestUpdateEmail(Request $request)
{
    try {
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        }

        // Validasi: harus memasukkan email lama yang sesuai
        $errors = [];

        if (!$request->has('current_email') || empty($request->current_email)) {
            $errors['current_email'][] = 'Email saat ini wajib diisi';
        } elseif (!filter_var($request->current_email, FILTER_VALIDATE_EMAIL)) {
            $errors['current_email'][] = 'Format email tidak valid';
        } elseif ($request->current_email !== $user->email) {
            $errors['current_email'][] = 'Email tidak sesuai dengan akun Anda';
        }

        if (!empty($errors)) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $errors
            ], 422);
        }

        // Hapus OTP lama yang belum dipakai
        PasswordResetToken::where('user_id', $user->id)
            ->where('type', 'otp_update_email')
            ->whereNull('used_at')
            ->delete();

        // Generate OTP 6 digit
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        PasswordResetToken::create([
            'user_id'    => $user->id,
            'token'      => $otp,
            'type'       => 'otp_update_email',
            'expired_at' => now()->addMinutes(5),
        ]);

        // TODO: Kirim OTP ke email LAMA user
        // Mail::to($user->email)->send(new OtpMail($otp));

        return response()->json([
            'success' => true,
            'message' => "Kode OTP telah dikirim ke email Anda ({$user->email}). Berlaku 5 menit.",
            'data'    => [
                'expires_in' => 300
            ],
            '_debug_otp' => config('app.debug') ? $otp : null,
        ], 200);

    } catch (\Exception $e) {
        Log::error('Request Update Email Error: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan',
            'error'   => config('app.debug') ? $e->getMessage() : 'Internal Server Error'
        ], 500);
    }
}


   
    
  public function verifyUpdateEmail(Request $request)
{
    try {
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        }

        // Validasi input: otp + new_email
        $errors = [];

        if (!$request->has('otp') || empty($request->otp)) {
            $errors['otp'][] = 'Kode OTP wajib diisi';
        } elseif (strlen($request->otp) !== 6) {
            $errors['otp'][] = 'Kode OTP harus 6 digit';
        }

        if (!$request->has('new_email') || empty($request->new_email)) {
            $errors['new_email'][] = 'Email baru wajib diisi';
        } elseif (!filter_var($request->new_email, FILTER_VALIDATE_EMAIL)) {
            $errors['new_email'][] = 'Format email baru tidak valid';
        } else {
            $emailExists = User::where('email', $request->new_email)
                ->where('id', '!=', $user->id)
                ->exists();

            if ($emailExists) {
                $errors['new_email'][] = 'Email baru sudah digunakan akun lain';
            }

            if ($request->new_email === $user->email) {
                $errors['new_email'][] = 'Email baru tidak boleh sama dengan email saat ini';
            }
        }

        if (!empty($errors)) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $errors
            ], 422);
        }

        // Cari OTP yang valid
        $otpRecord = PasswordResetToken::where('user_id', $user->id)
            ->where('token', $request->otp)
            ->where('type', 'otp_update_email')
            ->whereNull('used_at')
            ->where('expired_at', '>', now())
            ->first();

        if (!$otpRecord) {
            return response()->json([
                'success' => false,
                'message' => 'Kode OTP tidak valid atau sudah kadaluarsa',
            ], 422);
        }

        // Update email user
        $user->update([
            'email'             => $request->new_email,
            'email_verified_at' => now(),
        ]);

        // Tandai OTP sudah digunakan & bersihkan OTP lama
        $otpRecord->update(['used_at' => now()]);
        PasswordResetToken::where('user_id', $user->id)
            ->where('type', 'otp_update_email')
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Email berhasil diperbarui',
            'data'    => [
                'email'       => $user->email,
                'verified_at' => $user->email_verified_at,
            ],
        ], 200);

    } catch (\Exception $e) {
        Log::error('Verify Update Email Error: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan',
            'error'   => config('app.debug') ? $e->getMessage() : null
        ], 500);
    }
}




  
  public function requestUpdatePhone(Request $request)
{
    try {
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        }

        // Validasi: harus memasukkan email yang sesuai dengan akun
        $errors = [];

        if (!$request->has('current_email') || empty($request->current_email)) {
            $errors['current_email'][] = 'Email saat ini wajib diisi';
        } elseif (!filter_var($request->current_email, FILTER_VALIDATE_EMAIL)) {
            $errors['current_email'][] = 'Format email tidak valid';
        } elseif ($request->current_email !== $user->email) {
            $errors['current_email'][] = 'Email tidak sesuai dengan akun Anda';
        }

        if (!empty($errors)) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $errors
            ], 422);
        }

        // Hapus OTP lama yang belum dipakai
        PasswordResetToken::where('user_id', $user->id)
            ->where('type', 'otp_update_phone')
            ->whereNull('used_at')
            ->delete();

        // Generate OTP 6 digit
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        PasswordResetToken::create([
            'user_id'    => $user->id,
            'token'      => $otp,
            'type'       => 'otp_update_phone',
            'expired_at' => now()->addMinutes(5),
        ]);

        // TODO: Kirim OTP ke email user
        // Mail::to($user->email)->send(new OtpMail($otp));

        return response()->json([
            'success' => true,
            'message' => "Kode OTP telah dikirim ke email Anda ({$user->email}). Berlaku 5 menit.",
            'data'    => [
                'expires_in' => 300
            ],
            '_debug_otp' => config('app.debug') ? $otp : null,
        ], 200);

    } catch (\Exception $e) {
        Log::error('Request Update Phone Error: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan',
            'error'   => config('app.debug') ? $e->getMessage() : 'Internal Server Error'
        ], 500);
    }
}

    public function verifyUpdatePhone(Request $request)
  {
    try {
        $user = JWTAuth::parseToken()->authenticate();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        }

        // Validasi input: otp + new_phone
        $errors = [];

        if (!$request->has('otp') || empty($request->otp)) {
            $errors['otp'][] = 'Kode OTP wajib diisi';
        } elseif (strlen($request->otp) !== 6) {
            $errors['otp'][] = 'Kode OTP harus 6 digit';
        }

        if (!$request->has('new_phone') || empty($request->new_phone)) {
            $errors['new_phone'][] = 'Nomor telepon baru wajib diisi';
        } else {
            $phoneExists = User::where('phone', $request->new_phone)
                ->where('id', '!=', $user->id)
                ->exists();

            if ($phoneExists) {
                $errors['new_phone'][] = 'Nomor telepon sudah digunakan akun lain';
            }

            if ($request->new_phone === $user->phone) {
                $errors['new_phone'][] = 'Nomor telepon baru tidak boleh sama dengan nomor saat ini';
            }
        }

        if (!empty($errors)) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors'  => $errors
            ], 422);
        }

        // Cari OTP yang valid
        $otpRecord = PasswordResetToken::where('user_id', $user->id)
            ->where('token', $request->otp)
            ->where('type', 'otp_update_phone')
            ->whereNull('used_at')
            ->where('expired_at', '>', now())
            ->first();

        if (!$otpRecord) {
            return response()->json([
                'success' => false,
                'message' => 'Kode OTP tidak valid atau sudah kadaluarsa',
            ], 422);
        }

        // Update nomor telepon
        $user->update(['phone' => $request->new_phone]);

        // Tandai OTP sudah digunakan & bersihkan OTP lama
        $otpRecord->update(['used_at' => now()]);
        PasswordResetToken::where('user_id', $user->id)
            ->where('type', 'otp_update_phone')
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'No. Telepon berhasil diperbarui',
            'data'    => ['phone' => $user->phone],
        ], 200);

    } catch (\Exception $e) {
        Log::error('Verify Update Phone Error: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan',
            'error'   => config('app.debug') ? $e->getMessage() : null
        ], 500);
    }
  }

    public function uploadAvatar(Request $request)
   {
    try {
        // Validasi
        $validator = Validator::make($request->all(), [
            'avatar' => 'required|image|mimes:jpeg,jpg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        // Ambil user dari token
        $user = JWTAuth::parseToken()->authenticate();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 404);
        }

        // Hapus foto lama jika ada
        if ($user->avatar) {
            $oldPath = str_replace('/storage/', '', $user->avatar);
            $oldPath = str_replace('storage/', '', $oldPath);
            
            if (Storage::disk('public')->exists($oldPath)) {
                Storage::disk('public')->delete($oldPath);
            }
        }

        // Upload file
        $file = $request->file('avatar');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('avatars', $filename, 'public');

        // Update user
        $user->update(['avatar' => $path]);

        // Log sukses (sudah pakai import)
        Log::info('Avatar uploaded', ['user_id' => $user->id]);

        return response()->json([
            'success' => true,
            'message' => 'Foto profil berhasil diperbarui',
            'data' => [
                'avatar' => Storage::url($path),
                'filename' => $filename
            ]
        ], 200);

    } catch (\Exception $e) {
        // PASTIKAN INI PAKAI LOG DENGAN BENAR
        Log::error('Upload error: ' . $e->getMessage(), [
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'Gagal upload: ' . $e->getMessage()
        ], 500);
    }
}

    public function deleteAvatar(Request $request)
    {
        $user = $request->user();

        if (! $user->avatar) {
            return response()->json(['message' => 'Tidak ada foto profil'], 422);
        }

        Storage::disk('public')->delete($user->avatar);
        $user->update(['avatar' => null]);

        return response()->json(['message' => 'Foto profil dihapus']);
    }

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

        if (! Hash::check($request->current_password, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['Password lama tidak sesuai.'],
            ]);
        }

        $user->update(['password' => Hash::make($request->password)]);

        // Invalidate token saat ini → user harus login ulang
        JWTAuth::parseToken()->invalidate();

        return response()->json([
            'success' => true,
            'message' => 'Password berhasil diubah. Silakan login kembali.',
        ]);
    }


     private function formatUser(User $user): array
    {
        return [
            'id'         => $user->id,
            'name'       => $user->name,
            'email'      => $user->email,
            'phone'      => $user->phone,
            'gender'     => $user->gender,
            'birth_date' => $user->birth_date?->format('Y-m-d'),
            'avatar'     => $user->avatar
                                ? Storage::url($user->avatar)
                                : null,
            'created_at' => $user->created_at->format('Y-m-d H:i:s'),
        ];
    }


    /**
     * Refresh a token (OPTIONAL - bisa dihapus jika tidak ingin ada refresh token)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    /*
    public function refresh()
    {
        try {
            $token = JWTAuth::refresh();

            return response()->json([
                'success' => true,
                'message' => 'Token berhasil di-refresh',
                'data' => [
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    'expires_in' => config('jwt.ttl') * 60
                ]
            ], 200);
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak dapat di-refresh',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    */
}
