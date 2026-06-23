<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PoolTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

// FIREBASE SDK IMPORTS
use Kreait\Laravel\Firebase\Facades\Firebase;
use Kreait\Firebase\Messaging\CloudMessage;

class TicketManagementController extends Controller
{
    /**
     * Menampilkan daftar semua tiket (admin)
     */
    public function index(Request $request)
    {
        $query = PoolTicket::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('visit_date', $request->date);
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $tickets = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.tickets.index', compact('tickets'));
    }

    /**
     * Menampilkan detail satu tiket (admin)
     */
    public function show($id)
    {
        $ticket = PoolTicket::with('user')->findOrFail($id);

        // Method helper untuk cek bukti pembayaran (gunakan accessor)
        $hasPaymentProof = !is_null($ticket->payment_proof) && $ticket->payment_proof !== '';
        $paymentProofUrl = null;

        if ($hasPaymentProof) {
            if (str_starts_with($ticket->payment_proof, 'http')) {
                $paymentProofUrl = $ticket->payment_proof;
            } else {
                $paymentProofUrl = asset('storage/' . $ticket->payment_proof);
            }
        }

        return view('admin.tickets.show', compact('ticket', 'hasPaymentProof', 'paymentProofUrl'));
    }

    /**
     * Verifikasi pembayaran tiket (via AJAX - simple)
     * 🔥 HANYA untuk tiket dengan payment_status = 'payment_verified'
     */
    public function verify(PoolTicket $ticket)
    {
        try {
            // HANYA bisa verifikasi jika payment_status = 'payment_verified'
            if ($ticket->payment_status !== 'payment_verified') {
                return response()->json([
                    'success' => false,
                    'message' => 'Tiket tidak dapat diverifikasi. Status saat ini: ' . $ticket->payment_status
                ]);
            }

            $ticket->update([
                'payment_status' => 'paid',
                'status'         => 'active',
                'verified_at'    => now(),
                'verified_by'    => Auth::id()
            ]);

            // 👇 PROSES KIRIM NOTIFIKASI OTOMATIS (FCM & DATABASE) 👇

            // Cari customer berdasarkan user_id atau customer_email
            $customer = null;
            if ($ticket->user_id) {
                $customer = \App\Models\User::find($ticket->user_id);
            } elseif ($ticket->customer_email) {
                $customer = \App\Models\User::where('email', $ticket->customer_email)->first();
            }

            if ($customer) {
                $judul = 'Pembayaran Tiket Diverifikasi! ✅';
                $pesanBody = "Pembayaran tiket kolam Anda dengan kode {$ticket->ticket_code} telah diverifikasi oleh admin. Tiket kini Aktif.";

                // 1. Simpan ke database notifications (Agar muncul di lonceng aplikasi)
                DB::table('notifications')->insert([
                    'id'              => Str::uuid(),
                    'type'            => 'App\Notifications\SystemNotification',
                    'notifiable_type' => 'App\Models\User',
                    'notifiable_id'   => $customer->id,
                    'data'            => json_encode([
                        'title' => $judul,
                        'body'  => $pesanBody,
                    ]),
                    'read_at'         => null,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ]);

                // 2. Tembak ke Firebase (Agar HP bergetar di luar aplikasi)
                if ($customer->fcm_token) {
                    $pesanFcm = CloudMessage::fromArray([
                        'token' => $customer->fcm_token,
                        'notification' => [
                            'title' => $judul,
                            'body'  => $pesanBody
                        ],
                    ]);

                    try {
                        Firebase::messaging()->send($pesanFcm);
                    } catch (\Exception $e) {
                        Log::error('FCM Ticket Verify Error: ' . $e->getMessage());
                    }
                }
            }
            // 👆 SAMPAI SINI 👆

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran tiket berhasil diverifikasi'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Verifikasi pembayaran tiket (via form - lengkap dengan tolak/terima)
     * 🔥 Method ini untuk form admin yang lebih kompleks
     */
    public function adminVerifyPayment(Request $request, $id)
    {
        $ticket = PoolTicket::findOrFail($id);

        $request->validate([
            'action' => 'required|in:verify,reject',
            'notes'  => 'nullable|string|max:500'
        ]);

        if ($request->action === 'verify') {
            $ticket->update([
                'payment_status' => 'paid',
                'status'         => 'active',
                'verified_at'    => now(),
                'verified_by'    => Auth::id(),
                'rejection_reason' => null // Hapus alasan tolak jika ada
            ]);
            $message = '✅ Pembayaran tiket ' . $ticket->ticket_code . ' telah diverifikasi. Tiket aktif.';
        } else {
            $ticket->update([
                'payment_status' => 'unpaid',
                'status'         => 'pending',
                'rejection_reason' => $request->notes,
                'verified_at'    => null,
                'verified_by'    => null
            ]);
            $message = '❌ Pembayaran tiket ' . $ticket->ticket_code . ' ditolak. ' . ($request->notes ? 'Alasan: ' . $request->notes : '');
        }

        // TODO: Kirim notifikasi WhatsApp ke customer (implementasikan nanti)
        // WhatsappNotification::send($ticket->customer_phone, $message);

        return redirect()->route('admin.tickets.show', $ticket->id)
            ->with('success', $message);
    }

    /**
     * Cek kapasitas kolam untuk tanggal tertentu
     */
    public function checkCapacity(Request $request)
    {
        $date = $request->date ?? now()->format('Y-m-d');
        $capacity = PoolTicket::checkCapacity($date);

        return response()->json($capacity);
    }
}