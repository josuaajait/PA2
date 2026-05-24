<?php
// app/Http/Controllers/Branding/PromoController.php

namespace App\Http\Controllers\Branding;

use App\Http\Controllers\Controller;
use App\Services\PromoServiceClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PromoController extends Controller
{
    protected $promoService;

    public function __construct(PromoServiceClient $promoService)
    {
        $this->promoService = $promoService;
    }

    public function index(Request $request)
    {
        try {
            // 🔥 AMBIL DATA PROMO DARI MICROSERVICE
            $response = $this->promoService->getAll($request->all());

            // DEBUG: Lihat data mentah
            Log::info('Microservice response', ['data' => $response]);
            
            // Response dari microservice berupa array
            if (isset($response['data'])) {
                $promos = collect($response['data']);
            } else {
                $promos = collect($response);
            }
            
            // Filter hanya promo yang aktif dan masih dalam periode
            $now = now('Asia/Jakarta');
            $promos = $promos->filter(function ($promo) use ($now) {
                $promo = (object) $promo;
                $startDate = \Carbon\Carbon::parse($promo->start_date);
                $endDate = \Carbon\Carbon::parse($promo->end_date);
                
                return $promo->is_active && $now->between($startDate, $endDate);
            });
            
            // Pagination manual (opsional, karena data dari microservice)
            $perPage = 9;
            $currentPage = request()->get('page', 1);
            $currentItems = $promos->slice(($currentPage - 1) * $perPage, $perPage);
            
            $promos = new \Illuminate\Pagination\LengthAwarePaginator(
                $currentItems,
                $promos->count(),
                $perPage,
                $currentPage,
                ['path' => request()->url(), 'query' => request()->query()]
            );
            
        } catch (\Exception $e) {
            Log::error('Microservice error on customer promo page', [
                'message' => $e->getMessage()
            ]);
            
            // Jika microservice mati, tampilkan halaman kosong dengan pesan error
            $promos = collect([]);
            return view('pages.promos', compact('promos'))
                ->with('error', 'Maaf, layanan promo sedang tidak tersedia. Silakan coba lagi nanti.');
        }
        
        return view('pages.promos', compact('promos'));
    }

    public function detail($slug)
    {
        try {
            // 🔥 AMBIL DETAIL PROMO DARI MICROSERVICE
            // Karena microservice tidak memiliki endpoint get by slug, kita ambil semua lalu filter
            $response = $this->promoService->getAll();
            
            if (isset($response['data'])) {
                $promos = collect($response['data']);
            } else {
                $promos = collect($response);
            }
            
            $promo = $promos->firstWhere('slug', $slug);
            
            if (!$promo) {
                abort(404, 'Promo not found');
            }
            
            $promo = (object) $promo;
            
        } catch (\Exception $e) {
            Log::error('Microservice error on promo detail', [
                'message' => $e->getMessage()
            ]);
            abort(503, 'Layanan promo sedang bermasalah');
        }
        
        return view('pages.promo-detail', compact('promo'));
    }
}