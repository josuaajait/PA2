<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Promo; // 👈 Panggil Model Promo Database Utama
use Illuminate\Support\Facades\Log;

class PromoController extends Controller
{
    public function index()
    {
        try {
            // 👇 AMBIL DATA LANGSUNG DARI DATABASE LOKAL 👇
            $promos = Promo::where('is_active', true)
                           ->orderBy('created_at', 'desc')
                           ->get();

            return response()->json([
                'success' => true,
                'data' => $promos
            ]);

        } catch (\Exception $e) {
            Log::error('Gagal mengambil data promo lokal: ' . $e->getMessage());
            return response()->json([
                'success' => true,
                'data' => [] 
            ]);
        }
    }

    public function show($slug)
    {
        try {
            // 👇 AMBIL DETAIL LANGSUNG DARI DATABASE LOKAL 👇
            $promo = Promo::where('slug', $slug)->first();

            if ($promo) {
                return response()->json([
                    'success' => true,
                    'data' => $promo
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Promo tidak ditemukan'
            ], 404);

        } catch (\Exception $e) {
            Log::error('Gagal mengambil detail promo lokal: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Layanan sedang tidak tersedia'
            ], 500);
        }
    }
}