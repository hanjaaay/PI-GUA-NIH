<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\BookingTicket;
use App\Models\Ticket;
use App\Models\TouristAttraction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckinTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_checkin_does_not_consume_ticket(): void
    {
        [$operator, $bookingTicket] = $this->paidTicketFixture();

        $this->actingAs($operator)
            ->get(route('checkin', $bookingTicket->ticket_code))
            ->assertOk();

        $this->assertFalse($bookingTicket->fresh()->is_used);
        $this->assertNull($bookingTicket->fresh()->used_at);
    }

    public function test_post_checkin_consumes_ticket_once(): void
    {
        [$operator, $bookingTicket] = $this->paidTicketFixture();

        $this->actingAs($operator)
            ->post(route('checkin.consume', $bookingTicket->ticket_code))
            ->assertOk();

        $usedAt = $bookingTicket->fresh()->used_at;

        $this->assertTrue($bookingTicket->fresh()->is_used);
        $this->assertNotNull($usedAt);

        $this->actingAs($operator)
            ->post(route('checkin.consume', $bookingTicket->ticket_code))
            ->assertOk();

        $this->assertEquals($usedAt, $bookingTicket->fresh()->used_at);
    }

    public function test_non_operator_cannot_check_in_ticket(): void
    {
        [, $bookingTicket] = $this->paidTicketFixture();
        $user = User::factory()->create(['role' => 'user']);

        $this->actingAs($user)
            ->post(route('checkin.consume', $bookingTicket->ticket_code))
            ->assertForbidden();

        $this->assertFalse($bookingTicket->fresh()->is_used);
    }

    private function paidTicketFixture(): array
    {
        $operator = User::factory()->create(['role' => 'staff']);
        $customer = User::factory()->create(['role' => 'user']);
        $attraction = TouristAttraction::factory()->create();
        $ticket = Ticket::factory()->create([
            'tourist_attraction_id' => $attraction->id,
        ]);

        $booking = Booking::factory()->create([
            'user_id' => $customer->id,
            'tourist_attraction_id' => $attraction->id,
            'ticket_id' => $ticket->id,
            'status' => 'paid',
            'payment_status' => 'paid',
            'expired_at' => now()->addMinutes(15),
        ]);

        $bookingTicket = BookingTicket::create([
            'booking_id' => $booking->id,
            'ticket_code' => 'TKT-TEST-'.$booking->id,
            'qr_code' => null,
        ]);

        return [$operator, $bookingTicket];
    }
}
