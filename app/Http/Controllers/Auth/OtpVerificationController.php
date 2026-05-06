<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\EmailOtpVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OtpVerificationController extends Controller
{
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

        EmailOtpVerification::where('user_id', $user->id)->delete();

        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        EmailOtpVerification::create([
            'user_id'    => $user->id,
            'otp'        => $otp,
            'expires_at' => now()->addMinutes(10),
        ]);

        Mail::raw(
            "Halo {$user->name},\n\nKode OTP verifikasi email Anda: {$otp}\n\nBerlaku 10 menit.",
            function ($message) use ($user) {
                $message->to((string) $user->email)
                        ->subject('Kode OTP Verifikasi - Caldera Resto & Pool');
            }
        );

        return back()->with('success', 'Kode OTP telah dikirim ke ' . $user->email);
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

        $otpRecord = EmailOtpVerification::where('user_id', $user->id)
            ->where('otp', $request->otp)
            ->where('is_used', false)
            ->latest()
            ->first();

        if (!$otpRecord) {
            return back()->withErrors(['otp' => 'Kode OTP tidak valid.']);
        }

        if ($otpRecord->isExpired()) {
            return back()->withErrors(['otp' => 'Kode OTP sudah kadaluarsa. Minta kode baru.']);
        }

        $otpRecord->update(['is_used' => true]);

        $user->markEmailAsVerified();

        return redirect()->route('branding.home')
                         ->with('success', 'Email berhasil diverifikasi!');
    }
}