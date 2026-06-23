<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login');
        }
        
        // 🔥 AMBIL NOTIFIKASI DENGAN FILTER ROLE
        $notifications = DB::table('notifications')
            ->where('notifiable_type', 'App\Models\User')
            ->where('notifiable_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->filter(function($notif) use ($user) {
                $data = json_decode($notif->data, true);
                $type = $data['type'] ?? 'general';
                
                // Admin-only notification types
                $adminOnlyTypes = [
                    'admin_payment_uploaded',
                    'admin_new_ticket', 
                    'admin_new_reservation',
                    'admin_ticket_payment',
                    'admin_reservation_payment',
                    'AdminNewTicketNotification',
                    'AdminNewReservationNotification',
                    'AdminPaymentNotification',
                    'admin_payment'
                ];
                
                // Jika user bukan admin, filter notifikasi admin
                if ($user->role !== 'admin' && in_array($type, $adminOnlyTypes)) {
                    return false;
                }
                
                return true;
            });
        
        // Pagination manual
        $perPage = 10;
        $currentPage = request()->get('page', 1);
        $currentItems = $notifications->slice(($currentPage - 1) * $perPage, $perPage);
        
        $notifications = new \Illuminate\Pagination\LengthAwarePaginator(
            $currentItems,
            $notifications->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        
        foreach ($notifications as $notif) {
            $data = json_decode($notif->data, true);
            $notif->data = $data;
            $notif->type = $data['type'] ?? 'general';
            $notif->title = $data['title'] ?? 'Notifikasi';
            $notif->body = $data['body'] ?? '';
            $notif->icon = $data['icon'] ?? 'fa-bell';
            $notif->color = $data['color'] ?? 'primary';
            $notif->booking_code = $data['booking_code'] ?? null;
            $notif->ticket_code = $data['ticket_code'] ?? null;
            $notif->url = $data['url'] ?? null;
            $notif->created_at = Carbon::parse($notif->created_at);
        }
        
        $unreadCount = $notifications->whereNull('read_at')->count();
        
        return view('customer.notifications', compact('notifications', 'unreadCount'));
    }

    /**
     * 👇 FUNGSI GETALL UNTUK API HP BARU DITAMBAHKAN DI SINI 👇
     */
    public function getAll()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json(['success' => true, 'count' => 0, 'notifications' => []]);
            }
            
            // Ambil SEMUA notifikasi milik user ini
            $notifications = DB::table('notifications')
                ->where('notifiable_type', 'App\Models\User')
                ->where('notifiable_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->get();
            
            $formatted = [];
            $seenIds = [];
            
            foreach ($notifications as $notif) {
                $data = json_decode($notif->data, true);
                $type = $data['type'] ?? 'general';
                
                // Hindari duplikat ID
                if (in_array($notif->id, $seenIds)) {
                    continue;
                }
                $seenIds[] = $notif->id;
                
                // Filter notifikasi admin
                $adminOnlyTypes = [
                    'admin_payment_uploaded',
                    'admin_new_ticket', 
                    'admin_new_reservation',
                    'admin_ticket_payment',
                    'admin_reservation_payment',
                    'AdminNewTicketNotification',
                    'AdminNewReservationNotification',
                    'AdminPaymentNotification',
                    'admin_payment'
                ];
                
                if ($user->role !== 'admin' && in_array($type, $adminOnlyTypes)) {
                    continue;
                }
                
                $formatted[] = [
                    'id' => $notif->id,
                    'type' => $type,
                    'title' => $data['title'] ?? 'Notifikasi',
                    'body' => $data['body'] ?? '',
                    'icon' => $data['icon'] ?? 'fa-bell',
                    'color' => $data['color'] ?? 'primary',
                    'booking_code' => $data['booking_code'] ?? null,
                    'ticket_code' => $data['ticket_code'] ?? null,
                    'url' => $data['url'] ?? null,
                    'read_at' => $notif->read_at,
                    'created_at' => $this->getTimeAgo($notif->created_at),
                ];
            }
            
            $unreadCount = collect($formatted)->whereNull('read_at')->count();
            
            return response()->json([
                'success' => true,
                'count' => $unreadCount,
                'notifications' => $formatted
            ]);
            
        } catch (\Exception $e) {
            Log::error('Get all notifications API error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'count' => 0,
                'notifications' => []
            ]);
        }
    }
    
    public function markAsRead($id)
    {
        try {
            $user = Auth::user();
            
            DB::table('notifications')
                ->where('id', $id)
                ->where('notifiable_type', 'App\Models\User')
                ->where('notifiable_id', $user->id)
                ->update(['read_at' => now()]);
            
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Notifikasi ditandai dibaca']);
            }
            
            return redirect()->back()->with('success', 'Notifikasi ditandai dibaca');
            
        } catch (\Exception $e) {
            Log::error('Mark as read error: ' . $e->getMessage());
            
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Gagal menandai notifikasi']);
            }
            
            return redirect()->back()->with('error', 'Gagal menandai notifikasi');
        }
    }
    
    public function markAllAsRead()
    {
        try {
            $user = Auth::user();
            
            DB::table('notifications')
                ->where('notifiable_type', 'App\Models\User')
                ->where('notifiable_id', $user->id)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
            
            return response()->json(['success' => true, 'message' => 'Semua notifikasi ditandai dibaca']);
            
        } catch (\Exception $e) {
            Log::error('Mark all as read error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function apiMarkAsRead($id)
    {
        try {
            $user = Auth::user();

            \Illuminate\Support\Facades\DB::table('notifications')
                ->where('id', $id)
                ->where('notifiable_type', 'App\Models\User')
                ->where('notifiable_id', $user->id)
                ->update(['read_at' => now()]);
                

            return response()->json(['success' => true, 'message' => 'Notifikasi ditandai dibaca']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    public function getLatest()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json(['success' => true, 'count' => 0, 'notifications' => []]);
            }
            
            // 🔥 AMBIL NOTIFIKASI UNTUK USER INI
            $notifications = DB::table('notifications')
                ->where('notifiable_type', 'App\Models\User')
                ->where('notifiable_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();
            
            $formatted = [];
            $seenIds = [];
            
            foreach ($notifications as $notif) {
                $data = json_decode($notif->data, true);
                $type = $data['type'] ?? 'general';
                
                // 🔥 HINDARI DUPLIKAT ID
                if (in_array($notif->id, $seenIds)) {
                    continue;
                }
                $seenIds[] = $notif->id;
                
                // 🔥 FILTER ADMIN NOTIFICATIONS UNTUK CUSTOMER
                $adminOnlyTypes = [
                    'admin_payment_uploaded',
                    'admin_new_ticket', 
                    'admin_new_reservation',
                    'admin_ticket_payment',
                    'admin_reservation_payment',
                    'AdminNewTicketNotification',
                    'AdminNewReservationNotification',
                    'AdminPaymentNotification',
                    'admin_payment'
                ];
                
                // Jika user BUKAN admin, SKIP notifikasi admin
                if ($user->role !== 'admin' && in_array($type, $adminOnlyTypes)) {
                    continue;
                }
                
                // 🔥 SEMUA NOTIFIKASI LAINNYA DITAMPILKAN (termasuk purchased, created, dll)
                $formatted[] = [
                    'id' => $notif->id,
                    'type' => $type,
                    'title' => $data['title'] ?? 'Notifikasi',
                    'body' => $data['body'] ?? '',
                    'icon' => $data['icon'] ?? 'fa-bell',
                    'color' => $data['color'] ?? 'primary',
                    'booking_code' => $data['booking_code'] ?? null,
                    'ticket_code' => $data['ticket_code'] ?? null,
                    'url' => $data['url'] ?? null,
                    'read_at' => $notif->read_at,
                    'created_at' => $this->getTimeAgo($notif->created_at),
                ];
            }
            
            $unreadCount = collect($formatted)->whereNull('read_at')->count();
            
            return response()->json([
                'success' => true,
                'count' => $unreadCount,
                'notifications' => $formatted
            ]);
            
        } catch (\Exception $e) {
            Log::error('Get latest notifications error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'count' => 0,
                'notifications' => []
            ]);
        }
    }
    
    private function getTimeAgo($timestamp)
    {
        $diff = now()->diffInMinutes($timestamp);
        if ($diff < 1) return 'Baru saja';
        if ($diff < 60) return $diff . ' menit lalu';
        if ($diff < 1440) return floor($diff / 60) . ' jam lalu';
        return floor($diff / 1440) . ' hari lalu';
    }
}