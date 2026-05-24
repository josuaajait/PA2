<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PromoServiceClient;
use Illuminate\Http\Request;

class PromoCheckController extends Controller
{
    public function check(Request $request)
    {
        $request->validate([
            'promo_code' => 'required|string',
            'total_amount' => 'required|numeric|min:0'
        ]);

        $promoService = app(PromoServiceClient::class);
        $result = $promoService->validatePromo($request->promo_code, $request->total_amount);

        if ($result && $result['valid']) {
            return response()->json([
                'valid' => true,
                'discount_amount' => $result['discount_amount']
            ]);
        }

        return response()->json([
            'valid' => false,
            'message' => $result['message'] ?? 'Kode promo tidak valid'
        ]);
    }
}