<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Ticket;
use App\Models\TouristAttraction;
use App\Services\BookingService;
use App\Services\PaymentService;
use App\Support\BookingStatuses;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Midtrans\Transaction;

class BookingController extends Controller
{
    public function __construct(
        private readonly BookingService $bookingService,
        private readonly PaymentService $paymentService
    ) {}

    public function create($attraction)
    {
        $attraction =
            TouristAttraction::findOrFail($attraction);

        $tickets =
            Ticket::where(
                'tourist_attraction_id',
                $attraction->id
            )
                ->where('is_active', true)
                ->get();

        return view(
            'bookings.create',
            compact(
                'attraction',
                'tickets'
            )
        );
    }

    public function store(Request $request, $attraction)
    {
        $event =
            TouristAttraction::findOrFail($attraction);

        $request->validate([

            'ticket_id' => 'required|exists:tickets,id',

            'quantity' => 'required|integer|min:1|max:10',
        ]);

        try {
            $booking = $this->bookingService->createBooking(
                $request->user(),
                $event,
                (int) $request->ticket_id,
                (int) $request->quantity
            );

            return redirect()->route(
                'bookings.show',
                $booking
            );

        } catch (\Exception $e) {
            Log::error(
                'BOOKING ERROR: '.
                $e->getMessage()
            );

            return back()->with(
                'error',
                $e->getMessage()
            );
        }
    }

    public function show(Booking $booking)
    {
        if (
            $booking->user_id !==
            Auth::id()
        ) {
            abort(403);
        }

        $booking = $this->bookingService->expireIfDue($booking);

        $snapToken = null;
        $midtransSnapUrl = $this->paymentService->snapScriptUrl();

        if ($booking->isPending()) {

            try {
                $snapToken =
                    $this->paymentService->createSnapToken(
                        $booking->loadMissing('user')
                    );

            } catch (\Exception $e) {

                Log::error(
                    'MIDTRANS ERROR: '.
                    $e->getMessage()
                );
            }
        }

        return view(
            'bookings.show',
            compact(
                'booking',
                'snapToken',
                'midtransSnapUrl'
            )
        );
    }

    public function status(Booking $booking): \Illuminate\Http\JsonResponse
{
    if ($booking->user_id !== Auth::id()) {
        abort(403);
    }

    $booking = $this->bookingService->expireIfDue($booking);

    try {

        if ($booking->status === BookingStatuses::PENDING) {

            $midtransStatus =
                Transaction::status($booking->order_id);

            $transactionStatus =
                $midtransStatus->transaction_status ?? null;

            if (
                in_array(
                    $transactionStatus,
                    ['settlement', 'capture']
                )
            ) {

                $this->bookingService->confirmPaidForce(
                    $booking,
                    $midtransStatus->transaction_id ?? null
                );

                $booking->refresh();
            }
        }

    } catch (\Exception $e) {

        Log::error(
            'Midtrans sync failed: ' .
            $e->getMessage()
        );
    }

    return response()->json([
        'status'         => $booking->status,
        'payment_status' => $booking->payment_status,
        'is_paid'        => $booking->status === BookingStatuses::PAID,
        'is_expired'     => $booking->status === BookingStatuses::EXPIRED,
        'is_pending'     => $booking->isPending(),
    ]);
}

    public function index()
    {
        $bookings =
            Booking::with([

                'touristAttraction',
                'ticket',

            ])
                ->where(
                    'user_id',
                    auth()->id()
                )
                ->latest()
                ->paginate(9);

        return view(
            'bookings.index',
            compact('bookings')
        );
    }

    public function destroy(Booking $booking)
    {
        if (
            $booking->user_id !==
            Auth::id()
        ) {
            abort(403);
        }

        $booking = $this->bookingService->expireIfDue($booking);

        if ($booking->status !== BookingStatuses::PENDING) {

            return back()->with(
                'error',
                'Only pending bookings can be deleted'
            );
        }

        $this->bookingService->deletePending($booking);

        return redirect()
            ->route('bookings.index')
            ->with(
                'success',
                'Booking deleted successfully'
            );
    }

    public function downloadTicket(
        Booking $booking
    ) {
        if (
            $booking->user_id !==
            Auth::id()
        ) {
            abort(403);
        }

        if ($booking->status !== BookingStatuses::PAID)
            
            
            {

            return back()->with(
                'error',
                'Ticket only available for paid booking'
            );
        }
        
        ini_set('memory_limit', '1024M');
       $pdf = Pdf::loadView(
    'tickets.ticket-pdf',
    compact('booking')
);

$pdf->setOption([
    'isRemoteEnabled' => true,
]);

return $pdf->download(
    'ticket-'.$booking->order_id.'.pdf'
);
    }

    public function midtransCallback(
        Request $request
    ) {
        Log::info('Midtrans Callback received.', [
            'order_id' => $request->input('order_id'),
            'transaction_status' => $request->input('transaction_status'),
        ]);

        $result =
            $this->paymentService
                ->handleMidtransNotification(
                    $request->all()
                );

        return response()->json([

            'success' => $result['success'],

            'message' => $result['message'],
        ], $result['http_status']);
    }

    public function markPaid(
        Booking $booking
    ) {
        if (
            $booking->user_id !==
            Auth::id()
        ) {
            abort(403);
        }

        return response()->json([

            'success' => false,

            'message' => 'Payment confirmation is handled by verified Midtrans webhook.',
        ], 202);
    }
}
