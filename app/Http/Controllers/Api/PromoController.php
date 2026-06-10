<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Promo;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PromoController extends Controller
{
    public function index()
    {
        try {
            // Main API meminta data ke Microservice Promo
            // Sesuaikan URL ini dengan host/port microservice Anda di server
            $response = Http::get('http://127.0.0.1:8083/api/promo');

            if ($response->successful()) {
                // Langsung return response dari microservice ke Mobile App
                return response()->json($response->json());
            }

            // Jika microservice error, return empty array
            return response()->json([
                'success' => true,
                'data' => []
            ]);

        } catch (\Exception $e) {
            Log::error('Gagal mengambil data dari Microservice Promo: ' . $e->getMessage());
            return response()->json([
                'success' => true,
                'data' => [] 
            ]);
        }
    }

    public function show($slug)
    {
        try {
            // Minta detail promo ke Microservice
            $response = Http::get("http://127.0.0.1:8083/api/promo/{$slug}");

            if ($response->successful()) {
                // Langsung teruskan response dari microservice ke Mobile App
                return response()->json($response->json());
            }

            // Jika tidak ketemu di microservice
            return response()->json([
                'success' => false,
                'message' => 'Promo tidak ditemukan'
            ], 404);

        } catch (\Exception $e) {
            Log::error('Gagal mengambil detail promo dari Microservice: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Layanan sedang tidak tersedia'
            ], 500);
        }
    }
}