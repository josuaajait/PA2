<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\EmailOtpVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
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

        $user = User::create([
            'name'         => $request->name,
            'email'        => $request->email,
            'password'     => $request->password,
            'phone'        => $request->phone,
            'role'         => 'customer',
            'is_active'    => true,
            'otp_verified' => false,
        ]);

        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        EmailOtpVerification::create([
            'user_id'    => $user->id,
            'otp'        => $otp,
            'expires_at' => now()->addMinutes(10),
            'is_used'    => false, 
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
            Log::error("Gagal kirim OTP saat register: " . $e->getMessage());
        }

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
            return response()->json(['success' => false, 'message' => 'Validasi gagal.', 'errors' => $validator->errors()], 422);
        }

        try {
            if (!$token = JWTAuth::attempt($request->only('email', 'password'))) {
                return response()->json(['success' => false, 'message' => 'Email atau password salah.'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['success' => false, 'message' => 'Gagal membuat token.'], 500);
        }

        /** @var User|null $user */
        $user = JWTAuth::user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);
        }

        if (!$user->is_active) {
            JWTAuth::invalidate($token);
            return response()->json(['success' => false, 'message' => 'Akun tidak aktif. Hubungi admin.'], 403);
        }

        // 👇 BYPASS TOTAL: CEK DATABASE MENTAH UNTUK MENGHINDARI BUG POSTGRESQL BOOLEAN 👇
        $dbUser = DB::table('users')->where('id', $user->id)->first();
        $isVerified = false;

        if ($dbUser) {
            // Cek berbagai kemungkinan nilai true dari PostgreSQL (1, 't', true, dll)
            if ($dbUser->otp_verified == 1 || $dbUser->otp_verified === true || $dbUser->otp_verified === 't') {
                $isVerified = true;
            }
            // Jadikan email_verified_at sebagai patokan utama ganda
            if ($dbUser->email_verified_at !== null) {
                $isVerified = true;
            }
        }

        // Jika mentahnya terdeteksi belum verifikasi
        if (!$isVerified) { 
            return response()->json([
                'success' => false,
                'needs_verification' => true, 
                'message' => 'Akun Anda belum diverifikasi. Silakan masukkan kode OTP.',
                'data' => [
                    'token' => $token, 
                    'user'  => $this->userResource($user),
                ]
            ], 200);
        }
        // 👆 SAMPAI SINI 👆
        
        return response()->json([
            'success' => true,
            'message' => 'Login berhasil.',
            'data'    => [
                'user'         => $this->userResource($user),
                'token'        => $token,
                'token_type'   => 'Bearer',
                'expires_in'   => config('jwt.ttl') * 60,
                'otp_verified' => true, // Paksa return true karena sudah lolos bypass
            ],
        ]);
    }

    public function refresh()
    {
        try {
            $newToken = JWTAuth::parseToken()->refresh();
            return response()->json(['success' => true, 'message' => 'Token diperbarui.', 'data' => ['token' => $newToken, 'token_type' => 'Bearer', 'expires_in' => config('jwt.ttl') * 60]]);
        } catch (JWTException $e) {
            return response()->json(['success' => false, 'message' => 'Token tidak dapat diperbarui. Silakan login ulang.', 'code' => 'REFRESH_FAILED'], 401);
        }
    }

    public function logout()
    {
        try {
            JWTAuth::parseToken()->invalidate();
            return response()->json(['success' => true, 'message' => 'Logout berhasil.']);
        } catch (JWTException $e) {
            return response()->json(['success' => false, 'message' => 'Gagal logout.'], 500);
        }
    }

    public function me()
    {
        /** @var User|null $user */
        $user = auth('api')->user();
        if (!$user) return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);
        return response()->json(['success' => true, 'data' => ['user' => $this->userResource($user)]]);
    }

    private function userResource(User $user): array
    {
        return [
            'id'           => $user->id,
            'name'         => $user->name,
            'email'        => $user->email,
            'phone'        => $user->phone,
            'role'         => $user->role,
            'avatar'       => $user->avatar ? asset('storage/' . $user->avatar) : null,
            'otp_verified' => true,
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