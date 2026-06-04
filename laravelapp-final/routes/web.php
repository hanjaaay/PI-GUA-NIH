<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\CheckinController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TouristAttractionController;
use Illuminate\Support\Facades\Route;

Route::get('/',
    [TouristAttractionController::class, 'index'])
    ->name('home');

Route::get('/attractions',
    [TouristAttractionController::class, 'index'])
    ->name('attractions.index');

Route::get('/attractions/{attraction}',
    [TouristAttractionController::class, 'show'])
    ->name('attractions.show');

Route::post('/midtrans-callback',
    [BookingController::class, 'midtransCallback'])
    ->name('midtrans.callback');

Route::middleware(['auth'])->group(function () {

    Route::get('/profile',
        [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile',
        [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile',
        [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    Route::get('/dashboard',
        [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/bookings',
        [BookingController::class, 'index'])
        ->name('bookings.index');

    Route::get('/attractions/{attraction}/book',
        [BookingController::class, 'create'])
        ->name('bookings.create');

    Route::post('/attractions/{attraction}/book',
        [BookingController::class, 'store'])
        ->name('bookings.store');

    Route::get('/bookings/{booking}',
        [BookingController::class, 'show'])
        ->name('bookings.show');

    Route::delete('/bookings/{booking}',
        [BookingController::class, 'destroy'])
        ->name('bookings.destroy');

    Route::get('/bookings/{booking}/ticket',
        [BookingController::class, 'downloadTicket'])
        ->name('bookings.ticket');

    Route::post('/bookings/{booking}/mark-paid',
        [BookingController::class, 'markPaid'])
        ->name('bookings.markPaid');

    Route::get('/bookings/{booking}/status',
        [BookingController::class, 'status'])
        ->name('bookings.status');
});

Route::middleware([
    'auth',
    'operator',
])->group(function () {

    Route::view(
        '/scanner',
        'checkin.scanner'
    )->name('scanner');

    Route::get(
        '/checkin/{ticketCode}',
        [CheckinController::class, 'show']
    )->name('checkin');

    Route::post(
        '/checkin/{ticketCode}',
        [CheckinController::class, 'checkin']
    )->name('checkin.consume');
});

require __DIR__.'/auth.php';


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

Route::get('/upload-test', function () {
    return '
    <form method="POST" enctype="multipart/form-data">
        '.csrf_field().'
        <input type="file" name="image">
        <button type="submit">Upload</button>
    </form>
    ';
});

Route::post('/upload-test', function (Request $request) {

    $path = $request->file('image')->store('testing', 'public');

    return $path;
});
