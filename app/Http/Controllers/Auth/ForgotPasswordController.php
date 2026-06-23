<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    const OTP_EXPIRY_MINUTES = 10;
    const RESEND_COOLDOWN_SECONDS = 60;

    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'Email tidak terdaftar.',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user->is_active) {
            return back()->withErrors(['email' => 'Akun tidak aktif. Hubungi admin.']);
        }

        $existing = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if ($existing && Carbon::parse($existing->created_at)->diffInSeconds(now()) < self::RESEND_COOLDOWN_SECONDS) {
            $wait = self::RESEND_COOLDOWN_SECONDS - Carbon::parse($existing->created_at)->diffInSeconds(now());
            return back()->withErrors(['email' => "Tunggu {$wait} detik sebelum minta OTP baru."]);
        }

        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            [
                'token'      => Hash::make($otp), // di-hash, bukan plain text
                'created_at' => Carbon::now(),
            ]
        );

        Mail::raw(
            "Halo {$user->name},\n\nKode OTP reset password Anda: {$otp}\n\nBerlaku selama 10 menit.\n\nJika Anda tidak meminta reset password, abaikan email ini.",
            function ($message) use ($request) {
                $message->to($request->email)
                        ->subject('Kode OTP Reset Password - Caldera Resto & Pool');
            }
        );

        return redirect()->route('password.otp.form', ['email' => $request->email])
                         ->with('success', 'Kode OTP telah dikirim ke ' . $request->email);
    }

    public function showOtpForm(Request $request)
    {
        return view('auth.reset-password-otp', [
            'email' => $request->email ?? $request->query('email'),
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email'                 => 'required|email|exists:users,email',
            'otp'                   => 'required|digits:6',
            'password'              => 'required|min:8|confirmed',
            'password_confirmation' => 'required',
        ], [
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min'       => 'Password minimal 8 karakter.',
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        if (! $record || ! Hash::check($request->otp, $record->token)) {
            return back()->withErrors(['otp' => 'Kode OTP tidak valid.'])->withInput();
        }

        if (Carbon::parse($record->created_at)->addMinutes(self::OTP_EXPIRY_MINUTES)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
            return back()->withErrors(['otp' => 'Kode OTP sudah kadaluarsa. Minta kode baru.'])->withInput();
        }

        User::where('email', $request->email)->update([
            'password' => Hash::make($request->password),
        ]);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('login')
                         ->with('success', 'Password berhasil direset. Silakan login.');
    }
}