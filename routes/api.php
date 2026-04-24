<?php
// routes/api.php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MenuController;
use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\Api\TicketController;
use App\Http\Controllers\Api\PromoController;
use App\Http\Controllers\Api\GalleryController;
use App\Http\Controllers\Api\PoolController;
use App\Http\Controllers\Api\TestimonialController;
use App\Http\Controllers\Api\ProfileController;

/*
|--------------------------------------------------------------------------
| Public API Routes (Tanpa Auth)
|--------------------------------------------------------------------------
*/
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/menus', [MenuController::class, 'index']);
Route::get('/menus/{id}', [MenuController::class, 'show']);
Route::get('/promos', [PromoController::class, 'index']);
Route::get('/promos/{slug}', [PromoController::class, 'show']);
Route::get('/gallery', [GalleryController::class, 'index']);
Route::get('/pool/info', [PoolController::class, 'info']);
Route::get('/testimonials', [TestimonialController::class, 'index']);

/*
|--------------------------------------------------------------------------
| Protected API Routes (Perlu Auth Token)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    
    // Reservations
    Route::post('/reservations', [ReservationController::class, 'store']);
    Route::get('/reservations', [ReservationController::class, 'index']);
    Route::get('/reservations/{id}', [ReservationController::class, 'show']);
    Route::post('/reservations/{id}/cancel', [ReservationController::class, 'cancel']);
    
    // Tickets
    Route::post('/tickets', [TicketController::class, 'store']);
    Route::get('/tickets', [TicketController::class, 'index']);
    Route::get('/tickets/{id}', [TicketController::class, 'show']);
    
    // Testimonials
    Route::post('/testimonials', [TestimonialController::class, 'store']);
});