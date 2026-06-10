<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\EmailOtpVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail; // <-- WAJIB DITAMBAHKAN
use Illuminate\Support\Facades\Log;  // <-- WAJIB DITAMBAHKAN

class OtpController extends Controller
{
    public function send(Request $request)
    {
        /** @var User|null $user */
        $user = auth('api')->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        if ($user->isOtpVerified()) {
            return response()->json([
                'success' => true,
                'message' => 'OTP sudah diverifikasi sebelumnya.',
            ]);
        }

        if (!$user->canRequestOtp()) {
            return response()->json([
                'success' => false,
                'message' => 'Terlalu banyak permintaan OTP. Coba lagi dalam '
                    . $user->otpSendCooldownMinutes() . ' menit.',
                'code'    => 'OTP_RATE_LIMITED',
                'data'    => [
                    'cooldown_minutes' => $user->otpSendCooldownMinutes(),
                ],
            ], 429);
        }

        // UBAH 'used' menjadi 'is_used'
        EmailOtpVerification::where('user_id', $user->id)
            ->where('is_used', false)
            ->delete();

        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // UBAH 'used' menjadi 'is_used'
        EmailOtpVerification::create([
            'user_id'    => $user->id,
            'otp'        => $otp,
            'expires_at' => now()->addMinutes(10),
            'is_used'    => false,
        ]);

        $user->recordOtpSend();

        // 👇 TAMBAHKAN KODE KIRIM EMAIL DI SINI 👇
        try {
            Mail::raw(
                "Halo {$user->name},\n\nKode OTP verifikasi email Anda: {$otp}\n\nBerlaku 10 menit.\nJangan berikan kode ini kepada siapapun.",
                function ($message) use ($user) {
                    $message->to((string) $user->email)
                            ->subject('Kode OTP Verifikasi - Caldera Resto & Pool');
                }
            );
        } catch (\Exception $e) {
            Log::error("Gagal kirim OTP ulang: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim email OTP: ' . $e->getMessage(),
            ], 500);
        }
        // 👆 SAMPAI SINI 👆

        return response()->json([
            'success' => true,
            'message' => 'OTP berhasil dikirim ke ' . $user->email,
            'data'    => [
                'expires_in_minutes' => 10,
            ],
        ]);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        /** @var User|null $user */
        $user = auth('api')->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
            ], 401);
        }

        if ($user->isOtpVerified()) {
            return response()->json([
                'success' => true,
                'message' => 'OTP sudah diverifikasi sebelumnya.',
            ]);
        }

        // UBAH 'used' menjadi 'is_used'
        $record = EmailOtpVerification::where('user_id', $user->id)
            ->where('otp', $request->otp)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'OTP tidak valid atau sudah kadaluarsa.',
                'code'    => 'OTP_INVALID',
            ], 422);
        }

        // UBAH 'used' menjadi 'is_used'
        $record->update(['is_used' => true]);

        // 👇 UBAH KODE UPDATE MENJADI PENETAPAN LANGSUNG & SAVE AGAR MENEMBUS MASS-ASSIGNMENT PROTECTION 👇
        $user->otp_verified = true;
        if (!$user->email_verified_at) {
            $user->email_verified_at = now();
        }
        $user->save(); // Bypasses fillable security for direct attributes

        return response()->json([
            'success' => true,
            'message' => 'OTP berhasil diverifikasi.',
            'data'    => [
                'otp_verified' => true,
            ],
        ]);
    }
}