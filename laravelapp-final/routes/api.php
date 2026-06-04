<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\TouristAttractionController;
use Illuminate\Support\Facades\Route;



// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Tourist attractions
Route::get('/attractions', [TouristAttractionController::class, 'index']);
Route::get('/attractions/popular', [TouristAttractionController::class, 'popular']);
Route::get('/attractions/nearby', [TouristAttractionController::class, 'nearby']);
Route::get('/attractions/{touristAttraction}', [TouristAttractionController::class, 'show']);
Route::get('/attractions/{touristAttraction}/reviews', [TouristAttractionController::class, 'reviews']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::put('/profile', [AuthController::class, 'updateProfile']);
    Route::put('/change-password', [AuthController::class, 'changePassword']);

    // Bookings
    Route::get('/bookings', [BookingController::class, 'index']);
    Route::post('/bookings', [BookingController::class, 'store']);
    Route::get('/bookings/upcoming', [BookingController::class, 'upcoming']);
    Route::get('/bookings/history', [BookingController::class, 'history']);
    Route::get('/bookings/{booking}', [BookingController::class, 'show']);
    Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel']);

    // Reviews
    Route::get('/reviews', [ReviewController::class, 'index']);
    Route::post('/reviews', [ReviewController::class, 'store']);
    Route::get('/reviews/{review}', [ReviewController::class, 'show']);
    Route::put('/reviews/{review}', [ReviewController::class, 'update']);
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy']);
    Route::get('/user/reviews', [ReviewController::class, 'userReviews']);
});
