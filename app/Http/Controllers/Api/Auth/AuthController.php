<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\EmailOtpVerification; // <-- Ditambahkan
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail; // <-- Ditambahkan
use Illuminate\Support\Facades\Log;  // <-- Ditambahkan
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use PHPOpenSourceSaver\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone'    => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        // 1. Buat User Baru
        $user = User::create([
            'name'         => $request->name,
            'email'        => $request->email,
            'password'     => $request->password,
            'phone'        => $request->phone,
            'role'         => 'customer',
            'is_active'    => true,
            'otp_verified' => false,
        ]);

        // 2. OTOMATIS KIRIM OTP SAAT DAFTAR
        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        EmailOtpVerification::create([
            'user_id'    => $user->id,
            'otp'        => $otp,
            'expires_at' => now()->addMinutes(10),
            'is_used'       => false,
        ]);

        try {
            Mail::raw(
                "Halo {$user->name},\n\nKode OTP verifikasi email Anda: {$otp}\n\nBerlaku 10 menit.\nJangan berikan kode ini kepada siapapun.",
                function ($message) use ($user) {
                    $message->to((string) $user->email)
                            ->subject('Kode OTP Verifikasi Pendaftaran - Caldera Resto & Pool');
                }
            );
        } catch (\Exception $e) {
            // Jika email gagal dikirim, catat di log tapi biarkan register tetap berlanjut
            Log::error("Gagal kirim OTP saat register: " . $e->getMessage());
        }

        // 3. Buat Token JWT
        $token = JWTAuth::fromUser($user);

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil. Silakan cek email Anda untuk verifikasi OTP.',
            'data'    => [
                'user'         => $this->userResource($user),
                'token'        => $token,
                'token_type'   => 'Bearer',
                'expires_in'   => config('jwt.ttl') * 60,
                'otp_verified' => false,
            ],
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        try {
            if (!$token = JWTAuth::attempt($request->only('email', 'password'))) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email atau password salah.',
                ], 401);
            }
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat token.',
            ], 500);
        }

        /** @var User|null $user */
        $user = JWTAuth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        if (!$user->is_active) {
            JWTAuth::invalidate($token);
            return response()->json([
                'success' => false, 
                'message' => 'Akun tidak aktif. Hubungi admin.',
            ], 403);
        }

        if (!$user->otp_verified) { 
            return response()->json([
                'success' => false,
                'needs_verification' => true, // Flag khusus untuk Flutter
                'message' => 'Akun Anda belum diverifikasi. Silakan masukkan kode OTP.',
                'data' => [
                    'token' => $token, // Token tetap dikirim karena API OTP butuh token (Bearer)
                    'user'  => $this->userResource($user),
                ]
            ], 200);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Login berhasil.',
            'data'    => [
                'user'         => $this->userResource($user),
                'token'        => $token,
                'token_type'   => 'Bearer',
                'expires_in'   => config('jwt.ttl') * 60,
                'otp_verified' => $user->isOtpVerified(),
            ],
        ]);
    }

    public function refresh()
    {
        try {
            $newToken = JWTAuth::parseToken()->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Token diperbarui.',
                'data'    => [
                    'token'      => $newToken,
                    'token_type' => 'Bearer',
                    'expires_in' => config('jwt.ttl') * 60,
                ],
            ]);
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token tidak dapat diperbarui. Silakan login ulang.',
                'code'    => 'REFRESH_FAILED',
            ], 401);
        }
    }

    public function logout()
    {
        try {
            JWTAuth::parseToken()->invalidate();

            return response()->json([
                'success' => true,
                'message' => 'Logout berhasil.',
            ]);
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal logout.',
            ], 500);
        }
    }

    public function me()
    {
        /** @var User|null $user */
        $user = auth('api')->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        return response()->json([
            'success' => true,
            'data'    => [
                'user' => $this->userResource($user),
            ],
        ]);
    }

    private function userResource(User $user): array
    {
        return [
            'id'           => $user->id,
            'name'         => $user->name,
            'email'        => $user->email,
            'phone'        => $user->phone,
            'role'         => $user->role,
            'avatar'       => $user->avatar
                                ? asset('storage/' . $user->avatar)
                                : null,
            'otp_verified' => $user->isOtpVerified(),
            'is_active'    => $user->is_active,
            'created_at'   => $user->created_at->toISOString(),
        ];
    }

    public function updateFcmToken(Request $request)
    {
        $request->validate(['fcm_token' => 'required|string']);
        
        /** @var \App\Models\User $user */
        $user = auth('api')->user();
        
        if ($user) {
            $user->update(['fcm_token' => $request->fcm_token]);
            return response()->json(['success' => true, 'message' => 'FCM Token berhasil disimpan']);
        }
        
        return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
    }
}