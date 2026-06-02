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
        
        $notifications = DB::table('notifications')
            ->where('notifiable_type', 'App\Models\User')
            ->where('notifiable_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
        
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
        
        $unreadCount = DB::table('notifications')
            ->where('notifiable_type', 'App\Models\User')
            ->where('notifiable_id', $user->id)
            ->whereNull('read_at')
            ->count();
        
        return view('customer.notifications', compact('notifications', 'unreadCount'));
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
            
            return redirect()->back()->with('success', 'Notifikasi ditandai dibaca');
            
        } catch (\Exception $e) {
            Log::error('Mark as read error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menandai notifikasi');
        }
    }
    
    // app/Http/Controllers/NotificationController.php

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
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    public function getLatest()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json(['count' => 0, 'notifications' => []]);
            }
            
            $notifications = DB::table('notifications')
                ->where('notifiable_type', 'App\Models\User')
                ->where('notifiable_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();
            
            $unreadCount = DB::table('notifications')
                ->where('notifiable_type', 'App\Models\User')
                ->where('notifiable_id', $user->id)
                ->whereNull('read_at')
                ->count();
            
            $formatted = [];
            foreach ($notifications as $notif) {
                $data = json_decode($notif->data, true);
                $formatted[] = [
                    'id' => $notif->id,
                    'type' => $data['type'] ?? 'general',
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