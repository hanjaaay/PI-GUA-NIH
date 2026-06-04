<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Services\BookingService;
use App\Services\PaymentService;
use App\Support\BookingStatuses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function __construct(
        private readonly PaymentService $paymentService,
        private readonly BookingService $bookingService
    ) {
        $this->middleware('auth');
    }

    public function showPaymentForm(Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            return redirect()->route('bookings.index')->with('error', 'Unauthorized access');
        }

        $booking = $this->bookingService->expireIfDue($booking);

        if ($booking->status !== BookingStatuses::PENDING) {
            return redirect()->route('bookings.show', $booking)->with('error', 'Booking is not ready for payment');
        }

        $midtransSnapUrl = $this->paymentService->snapScriptUrl();

        return view('bookings.payment', compact('booking', 'midtransSnapUrl'));
    }

    public function create(Request $request, Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            return redirect()->route('bookings.index')->with('error', 'Unauthorized access');
        }

        $validator = Validator::make($request->all(), [
            'payment_method' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $booking = $this->bookingService->expireIfDue($booking);

        if ($booking->status !== BookingStatuses::PENDING) {
            return redirect()->route('bookings.show', $booking)
                ->with('error', 'Booking is not ready for payment');
        }

        try {
            $snapToken = $this->paymentService->createSnapToken(
                $booking->loadMissing('user')
            );

            $midtransSnapUrl = $this->paymentService->snapScriptUrl();

            return view('payments.show', compact('snapToken', 'booking', 'midtransSnapUrl'));

        } catch (\Exception $e) {
            Log::error('Payment creation with Midtrans failed: '.$e->getMessage());

            return back()->with('error', 'Payment creation failed. Please try again.');
        }
    }

    public function uploadProof(Request $request, Payment $payment)
    {
        if ($payment->booking->user_id !== auth()->id()) {
            return redirect()->route('bookings.index')->with('error', 'Unauthorized access');
        }

        if (! $payment->isPending()) {
            return redirect()->route('bookings.show', $payment->booking)->with('error', 'Payment is not pending');
        }

        $validator = Validator::make($request->all(), [
            'payment_proof' => 'required|image|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        try {
            $path = $request->file('payment_proof')->store('payment-proofs', 'public');

            $payment->update([
                'payment_proof' => $path,
            ]);

            return redirect()->route('bookings.show', $payment->booking)
                ->with('success', 'Payment proof uploaded successfully');

        } catch (\Exception $e) {
            Log::error('Payment proof upload failed: '.$e->getMessage());

            return back()->with('error', 'Payment proof upload failed');
        }
    }

    public function verify(Request $request, Payment $payment)
    {
        if (! auth()->user()->isAdmin()) {
            return redirect()->route('home')->with('error', 'Unauthorized access');
        }

        return redirect()
            ->route('bookings.show', $payment->booking)
            ->with('error', 'Manual payment verification is disabled. Use Midtrans webhook confirmation.');
    }

    public function cancel(Request $request, Payment $payment)
    {
        if ($payment->booking->user_id !== auth()->id()) {
            return redirect()->route('bookings.index')->with('error', 'Unauthorized access');
        }

        if (! $payment->isPending()) {
            return redirect()->route('bookings.show', $payment->booking)->with('error', 'Payment cannot be cancelled');
        }

        try {
            $this->bookingService->markFailed($payment->booking);
            $payment->markAsFailed();

            return redirect()->route('bookings.index')
                ->with('success', 'Payment cancelled successfully');

        } catch (\Exception $e) {
            Log::error('Payment cancellation failed: '.$e->getMessage());

            return back()->with('error', 'Payment cancellation failed');
        }
    }

    public function show(Payment $payment)
    {
        if ($payment->booking->user_id !== auth()->id() && ! auth()->user()->isAdmin()) {
            return redirect()->route('bookings.index')->with('error', 'Unauthorized access');
        }

        $booking = $payment->booking;
        $snapToken = data_get($payment->payment_details, 'snap_token');
        $midtransSnapUrl = $this->paymentService->snapScriptUrl();

        return view('payments.show', compact(
            'payment',
            'booking',
            'snapToken',
            'midtransSnapUrl'
        ));
    }
}
