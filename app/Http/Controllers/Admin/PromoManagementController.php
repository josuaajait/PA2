<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promo;
use App\Services\PromoServiceClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
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
            // 🔥 PANGGIL MICROSERVICE
            $response = $this->promoService->getAll($request->all());
            
            // Response dari microservice berupa array
            if (isset($response['data'])) {
                $promos = $response['data'];
            } else {
                $promos = $response;
            }
            
            // Konversi ke collection untuk view
            $promos = collect($promos);
            
        } catch (\Exception $e) {
            // 🔥 MICROSERVICE ERROR - TAMPILKAN ERROR KE USER
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
                'banner_image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
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
            ]);

            $validated['slug'] = Str::slug($validated['title']);
            
            // Konversi timezone
            $localTimezone = 'Asia/Jakarta';
            $startDateLocal = \Carbon\Carbon::parse($validated['start_date'], $localTimezone);
            $endDateLocal = \Carbon\Carbon::parse($validated['end_date'], $localTimezone);
            
            $validated['start_date'] = $startDateLocal->setTimezone('UTC');
            $validated['end_date'] = $endDateLocal->setTimezone('UTC');
            
            // Upload image ke storage lokal (tetap di monolith)
            if ($request->hasFile('banner_image')) {
                $path = $request->file('banner_image')->store('promos', 'public');
                $validated['banner_image'] = $path;
            }

            $validated['is_active'] = $request->has('is_active');
            $validated['used_count'] = 0;

            // 🔥 SIMPAN KE MICROSERVICE
            $result = $this->promoService->create($validated);
            
            if (!$result) {
                return back()->withInput()->with('error', 'Gagal menambahkan promo. Layanan promo sedang bermasalah.');
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
            // 🔥 AMBIL DARI MICROSERVICE
            $promo = $this->promoService->getById($id);
            
            if (!$promo) {
                return redirect()->route('admin.promos.index')->with('error', 'Promo tidak ditemukan');
            }
            
            // Konversi array ke object untuk view
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
                'banner_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
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
            ]);

            // Konversi timezone
            $localTimezone = 'Asia/Jakarta';
            $startDateLocal = \Carbon\Carbon::parse($validated['start_date'], $localTimezone);
            $endDateLocal = \Carbon\Carbon::parse($validated['end_date'], $localTimezone);
            
            $validated['start_date'] = $startDateLocal->setTimezone('UTC');
            $validated['end_date'] = $endDateLocal->setTimezone('UTC');

            if ($request->hasFile('banner_image')) {
                $path = $request->file('banner_image')->store('promos', 'public');
                $validated['banner_image'] = $path;
            }

            $validated['is_active'] = $request->has('is_active');

            // 🔥 UPDATE KE MICROSERVICE
            $result = $this->promoService->update($id, $validated);
            
            if (!$result) {
                return back()->withInput()->with('error', 'Gagal mengupdate promo. Layanan promo sedang bermasalah.');
            }
            
            return redirect()->route('admin.promos.index')->with('success', 'Promo berhasil diupdate');
            
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            // 🔥 HAPUS DARI MICROSERVICE
            $result = $this->promoService->delete($id);
            
            if (!$result) {
                return back()->with('error', 'Gagal menghapus promo. Layanan promo sedang bermasalah.');
            }
            
            return redirect()->route('admin.promos.index')->with('success', 'Promo berhasil dihapus');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}