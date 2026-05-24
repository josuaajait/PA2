<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PromoServiceClient
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.promo.url', env('PROMO_SERVICE_URL', 'http://localhost:8083/api/promo'));
    }

    protected function getHeaders()
    {
        $request = app(Request::class);
        $token = session('jwt_token') ?? $request->bearerToken();

        if (!$token && $request->hasHeader('Authorization')) {
            $token = str_replace('Bearer ', '', $request->header('Authorization'));
        }
        
        return [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ];
    }

    public function getAll($filters = [])
    {
        $response = Http::timeout(5)->withHeaders($this->getHeaders())
            ->get($this->baseUrl, $filters);

        if ($response->failed()) {
            Log::error('Microservice GET all failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            throw new \Exception('Microservice unavailable');
        }

        return $response->json();
    }

    public function getById($id)
    {
        $response = Http::timeout(5)->withHeaders($this->getHeaders())
            ->get($this->baseUrl . '/' . $id);
        
        if ($response->failed()) {
            throw new \Exception('Microservice unavailable');
        }
        
        return $response->json();
    }

    // app/Services/PromoServiceClient.php

    public function create($data)
    {
        try {
            $payload = [
                'title' => $data['title'],
                'description' => $data['description'],
                'discount_type' => $data['discount_type'],
                'discount_value' => (float) $data['discount_value'],
                'promo_code' => $data['promo_code'],
                'promo_type' => $data['promo_type'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'min_purchase' => $data['min_purchase'] ?? null,
                'max_discount' => $data['max_discount'] ?? null,
                'max_usage' => $data['max_usage'] ?? null,
                'is_active' => (bool) ($data['is_active'] ?? true),
            ];
            
            // 🔥 KONVERSI GAMBAR KE BASE64
            if (isset($data['banner_image']) && $data['banner_image']) {
                $imagePath = storage_path('app/public/' . $data['banner_image']);
                if (file_exists($imagePath)) {
                    $imageData = base64_encode(file_get_contents($imagePath));
                    $payload['banner_image_base64'] = $imageData;
                    $payload['banner_image_extension'] = pathinfo($imagePath, PATHINFO_EXTENSION);
                }
            }
            
            $response = Http::timeout(10)->withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl, $payload);
            
            if ($response->failed()) {
                Log::error('Microservice create failed', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                throw new \Exception('Microservice unavailable: ' . $response->body());
            }
            
            return $response->json();
            
        } catch (\Exception $e) {
            Log::error('Microservice create exception', [
                'message' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    public function update($id, $data)
    {
        $response = Http::timeout(5)->withHeaders($this->getHeaders())
            ->put($this->baseUrl . '/' . $id, $data);
        
        if ($response->failed()) {
            throw new \Exception('Microservice unavailable');
        }
        
        return $response->json();
    }

    public function delete($id)
    {
        $response = Http::timeout(5)->withHeaders($this->getHeaders())
            ->delete($this->baseUrl . '/' . $id);
        
        if ($response->failed()) {
            throw new \Exception('Microservice unavailable');
        }
        
        return true;
    }

    public function validatePromo($promoCode, $totalAmount)
    {
        $response = Http::timeout(5)->post($this->baseUrl . '/validate', [
            'promo_code' => $promoCode,
            'total_amount' => $totalAmount
        ]);
        
        if ($response->failed()) {
            throw new \Exception('Microservice unavailable');
        }
        
        return $response->json();
    }
}