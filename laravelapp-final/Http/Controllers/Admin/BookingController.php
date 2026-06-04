<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Notifications\BookingStatusUpdated;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'touristAttraction'])
            ->when($request->start_date, function ($q) use ($request) {
                return $q->whereDate('created_at', '>=', $request->start_date);
            })
            ->when($request->end_date, function ($q) use ($request) {
                return $q->whereDate('created_at', '<=', $request->end_date);
            });

        $bookings = $query->latest()->paginate(10);

        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'touristAttraction']);

        return view('admin.bookings.show', compact('booking'));
    }

    public function edit(Booking $booking)
    {
        return view('admin.bookings.edit', compact('booking'));
    }

    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,cancelled,expired,failed,completed',
            'visit_date' => 'required|date',
            'quantity' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
        ]);

        $oldStatus = $booking->status;
        $booking->update($request->only([
            'status',
            'visit_date',
            'quantity',
            'total_price',
        ]));

        if ($oldStatus !== $booking->status) {
            $booking->user->notify(new BookingStatusUpdated($booking));
        }

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Booking updated successfully');
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();

        return redirect()->route('admin.bookings.index')
            ->with('success', 'Booking deleted successfully.');
    }
}
