<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    // 1. Kirim OTP Lupa Password
    public function sendResetOtp(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Email tidak ditemukan.'], 404);
        }

        // Generate 6 digit OTP
        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Hapus token lama jika ada, lalu simpan yang baru
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email,
            'token' => $otp, // Kita simpan OTP di kolom token
            'created_at' => now()
        ]);

        try {
            Mail::raw(
                "Halo {$user->name},\n\nAnda meminta reset password. Masukkan kode OTP berikut di aplikasi:\n\n{$otp}\n\nKode ini berlaku 15 menit.",
                function ($message) use ($user) {
                    $message->to((string) $user->email)->subject('Kode OTP Reset Password - Caldera Resto & Pool');
                }
            );
        } catch (\Exception $e) {

            \Illuminate\Support\Facades\Log::error('Reset Password Mail Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal mengirim email.'], 500);
        }

        return response()->json(['success' => true, 'message' => 'Kode OTP reset password telah dikirim ke email Anda.']);
    }

    // 2. Simpan Password Baru
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'otp' => 'required|digits:6',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Validasi gagal.', 'errors' => $validator->errors()], 422);
        }

        // Cek kecocokan OTP dan Email
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->otp)
            ->first();

        if (!$resetRecord) {
            return response()->json(['success' => false, 'message' => 'Kode OTP tidak valid atau sudah kadaluarsa.'], 400);
        }

        // Update Password
        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Hapus token agar tidak bisa dipakai lagi
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return response()->json(['success' => true, 'message' => 'Password berhasil direset! Silakan login kembali.']);
    }
}