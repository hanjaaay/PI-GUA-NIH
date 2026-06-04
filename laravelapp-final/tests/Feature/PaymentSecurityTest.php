<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Ticket;
use App\Models\TouristAttraction;
use App\Models\User;
use App\Services\BookingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['midtrans.server_key' => 'test-server-key']);
    }

    public function test_client_mark_paid_route_cannot_confirm_payment(): void
    {
        $user = User::factory()->create();
        $booking = Booking::factory()->create([
            'user_id' => $user->id,
            'status' => 'pending',
            'payment_status' => 'pending',
            'expired_at' => now()->addMinutes(15),
        ]);

        $response = $this->actingAs($user)
            ->post(route('bookings.markPaid', $booking));

        $response->assertStatus(202);
        $this->assertNotSame('paid', $booking->fresh()->status);
        $this->assertDatabaseCount('booking_tickets', 0);
    }

    public function test_midtrans_callback_rejects_invalid_signature(): void
    {
        $booking = $this->createReservedBooking();

        $response = $this->postJson('/midtrans-callback', array_merge(
            $this->payloadFor($booking),
            ['signature_key' => 'bad-signature']
        ));

        $response->assertForbidden();
        $this->assertSame('pending', $booking->fresh()->status);
    }

    public function test_verified_midtrans_settlement_confirms_payment_and_issues_qr_tickets(): void
    {
        $booking = $this->createReservedBooking(quantity: 2);

        $response = $this->postJson('/midtrans-callback', $this->payloadFor($booking));

        $response->assertOk();
        $this->assertSame('paid', $booking->fresh()->status);
        $this->assertSame('paid', $booking->fresh()->payment_status);
        $this->assertDatabaseCount('booking_tickets', 2);
        $this->assertDatabaseHas('payments', [
            'payment_code' => $booking->order_id,
            'payment_status' => 'paid',
        ]);
    }

    public function test_duplicate_paid_callbacks_do_not_duplicate_qr_tickets(): void
    {
        $booking = $this->createReservedBooking(quantity: 2);
        $payload = $this->payloadFor($booking);

        $this->postJson('/midtrans-callback', $payload)->assertOk();
        $this->postJson('/midtrans-callback', $payload)->assertOk();

        $this->assertSame('paid', $booking->fresh()->status);
        $this->assertDatabaseCount('booking_tickets', 2);
    }

    public function test_expired_booking_cannot_become_paid_and_stock_restores_once(): void
    {
        [$booking, $ticket] = $this->createReservedBookingWithTicket(quantity: 2);
        $booking->update(['expired_at' => now()->subMinute()]);

        $this->assertSame(3, $ticket->fresh()->available_quantity);

        $payload = $this->payloadFor($booking->fresh());

        $this->postJson('/midtrans-callback', $payload)->assertOk();
        $this->postJson('/midtrans-callback', $payload)->assertOk();

        $this->assertSame('expired', $booking->fresh()->status);
        $this->assertSame('expired', $booking->fresh()->payment_status);
        $this->assertSame(5, $ticket->fresh()->available_quantity);
        $this->assertDatabaseCount('booking_tickets', 0);
    }

    public function test_midtrans_expire_callback_restores_stock_once(): void
    {
        [$booking, $ticket] = $this->createReservedBookingWithTicket(quantity: 2);
        $payload = $this->payloadFor($booking, [
            'transaction_status' => 'expire',
            'status_code' => '201',
        ]);

        $this->postJson('/midtrans-callback', $payload)->assertOk();
        $this->postJson('/midtrans-callback', $payload)->assertOk();

        $this->assertSame('expired', $booking->fresh()->status);
        $this->assertSame(5, $ticket->fresh()->available_quantity);
    }

    private function createReservedBooking(int $quantity = 1): Booking
    {
        return $this->createReservedBookingWithTicket($quantity)[0];
    }

    private function createReservedBookingWithTicket(int $quantity = 1): array
    {
        $user = User::factory()->create();
        $attraction = TouristAttraction::factory()->create([
            'start_date' => now()->addDay(),
        ]);
        $ticket = Ticket::factory()->create([
            'tourist_attraction_id' => $attraction->id,
            'price' => 10000,
            'quota' => 5,
            'available_quantity' => 5,
        ]);

        $booking = app(BookingService::class)->createBooking(
            $user,
            $attraction,
            $ticket->id,
            $quantity
        );

        return [$booking, $ticket];
    }

    private function payloadFor(Booking $booking, array $overrides = []): array
    {
        $orderId = $booking->order_id;
        $statusCode = (string) ($overrides['status_code'] ?? '200');
        $grossAmount = number_format((float) $booking->total_price, 2, '.', '');
        $signature = hash(
            'sha512',
            $orderId.$statusCode.$grossAmount.config('midtrans.server_key')
        );

        return array_merge([
            'order_id' => $orderId,
            'status_code' => $statusCode,
            'gross_amount' => $grossAmount,
            'signature_key' => $signature,
            'transaction_status' => 'settlement',
            'fraud_status' => 'accept',
            'transaction_id' => 'MIDTRANS-'.$booking->id,
            'payment_type' => 'bank_transfer',
        ], $overrides);
    }
}
