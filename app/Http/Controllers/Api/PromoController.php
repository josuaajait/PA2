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
            // 1. Coba minta data ke Microservice Promo
            $response = Http::timeout(3)->get('http://172.17.0.1:8083/api/promo');

            if ($response->successful()) {
                $json = $response->json();
                $promos = isset($json['data']['data']) ? $json['data']['data'] : ($json['data'] ?? []);

                return response()->json([
                    'success' => true,
                    'data' => $promos 
                ]);
            }

            // Jika response dari microservice bukan 200 OK, lempar ke catch
            throw new \Exception('Microservice merespon dengan error');

        } catch (\Exception $e) {
            Log::error('Microservice Promo Down, menggunakan fallback lokal: ' . $e->getMessage());
            
            // 👇 2. FALLBACK: Ambil dari database lokal jika microservice mati 👇
            try {
                // Ambil data promo dari database lokal (tabel promos)
                $localPromos = Promo::where('is_active', true)->get();
                
                return response()->json([
                    'success' => true,
                    'data' => $localPromos
                ]);
            } catch (\Exception $ex) {
                // Jika database lokal juga gagal/kosong
                return response()->json([
                    'success' => true,
                    'data' => [] 
                ]);
            }
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

            // Jika response dari microservice bukan 200 OK, lempar ke catch
            throw new \Exception('Microservice merespon dengan error');

            } catch (\Exception $e) {
            Log::error('Microservice Promo Down, menggunakan fallback lokal: ' . $e->getMessage());

        } // 👇 2. FALLBACK: Ambil dari database lokal jika microservice mati 👇
            try {
                // Ambil data promo dari database lokal (tabel promos)
                $localPromos = Promo::where('is_active', true)->get();
                
                return response()->json([
                    'success' => true,
                    'data' => $localPromos
                ]);
            } catch (\Exception $ex) {
                // Jika database lokal juga gagal/kosong
                return response()->json([
                    'success' => true,
                    'data' => [] 
                ]);
            }
        }
}