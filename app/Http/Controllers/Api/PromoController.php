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
            // Meminta data ke Microservice Promo (Menggunakan IP Gateway Docker Anda yang sukses)
            $response = Http::get('http://172.17.0.1:8083/api/promo');

            if ($response->successful()) {
                $json = $response->json();
                
                // 👇 PERBAIKAN UTAMA: BONGKAR BUNGKUS PAGINATION MICROSERVICE 👇
                // Jika ada data di dalam data (paginated), ambil list dalamnya saja.
                $promos = isset($json['data']['data']) ? $json['data']['data'] : ($json['data'] ?? []);

                return response()->json([
                    'success' => true,
                    'data' => $promos // Kirim list bersih ke HP
                ]);
            }

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
            $response = Http::get("http://172.17.0.1:8083/api/promo/{$slug}");

            if ($response->successful()) {
                $json = $response->json();
                
                // Bongkar bungkus jika ada key 'data'
                $promo = isset($json['data']) ? $json['data'] : $json;

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
            Log::error('Gagal mengambil detail promo dari Microservice: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Layanan sedang tidak tersedia'
            ], 500);
        }
    }
}