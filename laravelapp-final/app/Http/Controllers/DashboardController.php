<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Mengambil hanya satu pemesanan terbaru
        $latestBooking = $user->bookings()->with('touristAttraction')->latest()->first();

        return view('dashboard', compact('latestBooking'));
    }
}
