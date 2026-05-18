<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\EmailOtpVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class OtpVerificationController extends Controller
{
    // Maksimal percobaan OTP yang salah
    const MAX_ATTEMPTS = 5;
    // Lockout duration dalam menit
    const LOCKOUT_MINUTES = 15;
    // Maksimal kirim ulang OTP per jam
    const MAX_RESEND = 3;

    public function showVerifyForm()
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('branding.home');
        }

        return view('auth.otp-verification');
    }

    public function sendOtp(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('branding.home');
        }

        // Cek batas kirim ulang OTP (max 3x per jam)
        $resendKey = 'otp_resend_' . $user->id;
        $resendCount = Cache::get($resendKey, 0);

        if ($resendCount >= self::MAX_RESEND) {
            return back()->withErrors([
                'otp' => 'Anda telah mencapai batas pengiriman OTP. Coba lagi dalam 1 jam.'
            ]);
        }

        // Hapus OTP lama
        EmailOtpVerification::where('user_id', $user->id)->delete();

        // Reset attempt counter
        Cache::forget('otp_attempts_' . $user->id);

        // Generate OTP 6 digit
        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        EmailOtpVerification::create([
            'user_id'    => $user->id,
            'otp'        => $otp,
            'expires_at' => now()->addMinutes(10),
        ]);

        // Increment resend counter (reset setiap 1 jam)
        Cache::put($resendKey, $resendCount + 1, now()->addHour());

        Mail::raw(
            "Halo {$user->name},\n\nKode OTP verifikasi email Anda: {$otp}\n\nBerlaku 10 menit.\nJangan berikan kode ini kepada siapapun.",
            function ($message) use ($user) {
                $message->to((string) $user->email)
                        ->subject('Kode OTP Verifikasi - Caldera Resto & Pool');
            }
        );

        $remaining = self::MAX_RESEND - ($resendCount + 1);

        return back()->with('success', "Kode OTP telah dikirim ke {$user->email}. Sisa percobaan kirim ulang: {$remaining}x");
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        /** @var User $user */
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        $attemptKey = 'otp_attempts_' . $user->id;
        $lockKey    = 'otp_locked_' . $user->id;

        // Cek apakah user sedang di-lock
        if (Cache::has($lockKey)) {
            $remainingSeconds = Cache::get($lockKey) - time();
            $remainingMinutes = ceil($remainingSeconds / 60);
            return back()->withErrors([
                'otp' => "Terlalu banyak percobaan gagal. Akun dikunci selama {$remainingMinutes} menit lagi."
            ]);
        }

        $attempts = Cache::get($attemptKey, 0);

        $otpRecord = EmailOtpVerification::where('user_id', $user->id)
            ->where('otp', $request->otp)
            ->where('is_used', false)
            ->latest()
            ->first();

        if (!$otpRecord) {
            // Increment attempt
            $attempts++;
            Cache::put($attemptKey, $attempts, now()->addHour());

            $remaining = self::MAX_ATTEMPTS - $attempts;

            if ($attempts >= self::MAX_ATTEMPTS) {
                // Lock akun
                $lockUntil = time() + (self::LOCKOUT_MINUTES * 60);
                Cache::put($lockKey, $lockUntil, now()->addMinutes(self::LOCKOUT_MINUTES));
                Cache::forget($attemptKey);

                return back()->withErrors([
                    'otp' => 'Terlalu banyak percobaan gagal. Akun dikunci selama ' . self::LOCKOUT_MINUTES . ' menit.'
                ]);
            }

            return back()->withErrors([
                'otp' => "Kode OTP tidak valid. Sisa percobaan: {$remaining}x"
            ]);
        }

        if ($otpRecord->isExpired()) {
            return back()->withErrors([
                'otp' => 'Kode OTP sudah kadaluarsa. Minta kode baru.'
            ]);
        }

        // OTP valid — reset semua counter
        Cache::forget($attemptKey);
        Cache::forget($lockKey);
        Cache::forget('otp_resend_' . $user->id);

        $otpRecord->update(['is_used' => true]);
        $user->markEmailAsVerified();

        return redirect()->route('branding.home')
            ->with('success', 'Email berhasil diverifikasi! Selamat datang di Caldera.');
    }
}