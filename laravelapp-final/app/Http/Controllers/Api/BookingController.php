<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Ticket;
use App\Models\TouristAttraction;
use App\Services\BookingService;
use App\Support\BookingStatuses;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function __construct(
        private readonly BookingService $bookingService
    ) {}

    public function index(Request $request)
    {
        $bookings = $request->user()
            ->bookings()
            ->with(['touristAttraction'])
            ->latest()
            ->paginate(10);

        return response()->json([
            'bookings' => $bookings,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tourist_attraction_id' => 'required|exists:tourist_attractions,id',
            'visit_date' => 'nullable|date|after:today',
            'ticket_id' => 'nullable|exists:tickets,id',
            'quantity' => 'nullable|integer|min:1|max:10|required_without:number_of_tickets',
            'number_of_tickets' => 'nullable|integer|min:1|max:10|required_without:quantity',
        ]);

        $attraction = TouristAttraction::findOrFail($request->tourist_attraction_id);
        $quantity = (int) ($request->quantity ?? $request->number_of_tickets);
        $ticketId = $request->ticket_id;

        if (! $ticketId) {
            $ticketId = Ticket::where('tourist_attraction_id', $attraction->id)
                ->where('is_active', true)
                ->value('id');
        }

        if (! $ticketId) {
            return response()->json([
                'message' => 'No active ticket is available for this attraction',
            ], 422);
        }

        try {
            $booking = $this->bookingService->createBooking(
                $request->user(),
                $attraction,
                (int) $ticketId,
                $quantity
            );

            return response()->json([
                'message' => 'Booking created successfully',
                'booking' => $booking->load(['touristAttraction', 'ticket']),
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function show(Booking $booking)
    {
        // Check if the booking belongs to the authenticated user
        if ($booking->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 403);
        }

        return response()->json([
            'booking' => $booking->load('touristAttraction'),
        ]);
    }

    public function cancel(Booking $booking)
    {
        // Check if the booking belongs to the authenticated user
        if ($booking->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 403);
        }

        // Check if the booking can be cancelled
        if ($booking->status !== BookingStatuses::PENDING) {
            return response()->json([
                'message' => 'This booking cannot be cancelled',
            ], 422);
        }

        $booking = $this->bookingService->cancel($booking);

        return response()->json([
            'message' => 'Booking cancelled successfully',
            'booking' => $booking,
        ]);
    }

    public function upcoming(Request $request)
    {
        $upcomingBookings = $request->user()
            ->bookings()
            ->with('touristAttraction')
            ->where('visit_date', '>=', now())
            ->where('status', '!=', 'cancelled')
            ->orderBy('visit_date')
            ->get();

        return response()->json([
            'bookings' => $upcomingBookings,
        ]);
    }

    public function history(Request $request)
    {
        $historyBookings = $request->user()
            ->bookings()
            ->with('touristAttraction')
            ->where('visit_date', '<', now())
            ->orWhere(function ($query) use ($request) {
                $query->where('user_id', $request->user()->id)
                    ->where('status', BookingStatuses::CANCELLED);
            })
            ->orderBy('visit_date', 'desc')
            ->paginate(10);

        return response()->json([
            'bookings' => $historyBookings,
        ]);
    }
}
