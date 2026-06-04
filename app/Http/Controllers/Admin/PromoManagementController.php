<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promo;
use App\Services\PromoServiceClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PromoManagementController extends Controller
{
    protected $promoService;

    public function __construct(PromoServiceClient $promoService)
    {
        $this->promoService = $promoService;
    }

    public function index(Request $request)
    {
        try {
            $response = $this->promoService->getAll($request->all());
            
            if (isset($response['data'])) {
                $promos = $response['data'];
            } else {
                $promos = $response;
            }
            
            $promos = collect($promos);
            
        } catch (\Exception $e) {
            Log::error('Microservice error on index', [
                'message' => $e->getMessage()
            ]);
            
            return redirect()->back()->with('error', 'Maaf, layanan promo sedang bermasalah. Silakan coba lagi nanti.');
        }
        
        return view('admin.promos.index', compact('promos'));
    }

    public function create()
    {
        return view('admin.promos.create');
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'discount_type' => 'required|in:percentage,nominal',
                'discount_value' => 'required|numeric|min:0',
                'promo_code' => 'required|string',
                'promo_type' => 'required|in:menu,ticket,reservation,event',
                'min_purchase' => 'nullable|numeric|min:0',
                'max_discount' => 'nullable|numeric|min:0',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'max_usage' => 'nullable|integer|min:1',
                'is_active' => 'boolean',
                // 🔥 HAPUS validasi banner_image
            ]);

            $validated['slug'] = Str::slug($validated['title']);
            
            // Konversi timezone
            $localTimezone = 'Asia/Jakarta';
            $startDateLocal = \Carbon\Carbon::parse($validated['start_date'], $localTimezone);
            $endDateLocal = \Carbon\Carbon::parse($validated['end_date'], $localTimezone);
            
            $validated['start_date'] = $startDateLocal->setTimezone('UTC');
            $validated['end_date'] = $endDateLocal->setTimezone('UTC');

            $validated['is_active'] = $request->has('is_active');
            $validated['used_count'] = 0;
            // 🔥 HAPUS proses upload gambar
            // 🔥 HAPUS field banner_image dari array

            // 🔥 SIMPAN KE MICROSERVICE
            $result = $this->promoService->create($validated);
            
            if (!$result) {
                return back()->withInput()->with('error', 'Gagal menambahkan promo.');
            }
            
            return redirect()->route('admin.promos.index')->with('success', 'Promo berhasil ditambahkan');
            
        } catch (\Exception $e) {
            Log::error('Create promo error', [
                'message' => $e->getMessage()
            ]);
            
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $promo = $this->promoService->getById($id);
            
            if (!$promo) {
                return redirect()->route('admin.promos.index')->with('error', 'Promo tidak ditemukan');
            }
            
            $promo = (object) $promo;
            
        } catch (\Exception $e) {
            Log::error('Microservice error on edit', [
                'message' => $e->getMessage()
            ]);
            
            return redirect()->route('admin.promos.index')->with('error', 'Layanan promo sedang bermasalah.');
        }
        
        return view('admin.promos.edit', compact('promo'));
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'discount_type' => 'required|in:percentage,nominal',
                'discount_value' => 'required|numeric|min:0',
                'promo_code' => 'required|string',
                'promo_type' => 'required|in:menu,ticket,reservation,event',
                'min_purchase' => 'nullable|numeric|min:0',
                'max_discount' => 'nullable|numeric|min:0',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'max_usage' => 'nullable|integer|min:1',
                'is_active' => 'boolean',
                // 🔥 HAPUS validasi banner_image
            ]);

            // Konversi timezone
            $localTimezone = 'Asia/Jakarta';
            $startDateLocal = \Carbon\Carbon::parse($validated['start_date'], $localTimezone);
            $endDateLocal = \Carbon\Carbon::parse($validated['end_date'], $localTimezone);
            
            $validated['start_date'] = $startDateLocal->setTimezone('UTC');
            $validated['end_date'] = $endDateLocal->setTimezone('UTC');

            $validated['is_active'] = $request->has('is_active');
            // 🔥 HAPUS proses upload gambar

            // 🔥 UPDATE KE MICROSERVICE
            $result = $this->promoService->update($id, $validated);
            
            if (!$result) {
                return back()->withInput()->with('error', 'Gagal mengupdate promo.');
            }
            
            return redirect()->route('admin.promos.index')->with('success', 'Promo berhasil diupdate');
            
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $result = $this->promoService->delete($id);
            
            if (!$result) {
                return back()->with('error', 'Gagal menghapus promo.');
            }
            
            return redirect()->route('admin.promos.index')->with('success', 'Promo berhasil dihapus');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}