<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Public API Routes (Tanpa Auth)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/menus', [App\Http\Controllers\Api\MenuController::class, 'index']);
Route::get('/menus/{id}', [App\Http\Controllers\Api\MenuController::class, 'show']);
Route::get('/promos', [App\Http\Controllers\Api\PromoController::class, 'index']);
Route::get('/gallery', [App\Http\Controllers\Api\GalleryController::class, 'index']);
Route::get('/testimonials', [App\Http\Controllers\Api\TestimonialController::class, 'index']);

// Protected API Routes (Perlu Auth Token)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [App\Http\Controllers\Api\ProfileController::class, 'show']);
    Route::put('/profile', [App\Http\Controllers\Api\ProfileController::class, 'update']);
    
    Route::post('/reservations', [App\Http\Controllers\Api\ReservationController::class, 'store']);
    Route::get('/reservations', [App\Http\Controllers\Api\ReservationController::class, 'index']);
    Route::get('/reservations/{id}', [App\Http\Controllers\Api\ReservationController::class, 'show']);
    Route::post('/reservations/{id}/cancel', [App\Http\Controllers\Api\ReservationController::class, 'cancel']);
    
    Route::post('/tickets', [App\Http\Controllers\Api\TicketController::class, 'store']);
    Route::get('/tickets', [App\Http\Controllers\Api\TicketController::class, 'index']);
    Route::get('/tickets/{id}', [App\Http\Controllers\Api\TicketController::class, 'show']);
    
    Route::post('/testimonials', [App\Http\Controllers\Api\TestimonialController::class, 'store']);
});