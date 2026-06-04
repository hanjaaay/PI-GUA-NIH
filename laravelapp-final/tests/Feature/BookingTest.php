<?php

namespace Tests\Feature;

use App\Models\Ticket;
use App\Models\TouristAttraction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_booking()
    {
        $user = User::factory()->create();
        $attraction = TouristAttraction::factory()->create();
        $ticket = Ticket::factory()->create(['tourist_attraction_id' => $attraction->id]);

        $this->actingAs($user);

        $response = $this->post(route('bookings.store', $attraction), [
            'ticket_id' => $ticket->id,
            'quantity' => 2,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('bookings', [
            'user_id' => $user->id,
            'tourist_attraction_id' => $attraction->id,
            'ticket_id' => $ticket->id,
            'quantity' => 2,
            'status' => 'pending',
            'payment_status' => 'pending',
        ]);

        $this->assertEquals(
            $ticket->available_quantity - 2,
            $ticket->fresh()->available_quantity
        );
    }

    public function test_booking_requires_authentication()
    {
        $attraction = TouristAttraction::factory()->create();
        $response = $this->post(route('bookings.store', $attraction), [
            'ticket_id' => Ticket::factory()->create([
                'tourist_attraction_id' => $attraction->id,
            ])->id,
            'quantity' => 1,
        ]);
        $response->assertRedirect('/login');
    }
}
