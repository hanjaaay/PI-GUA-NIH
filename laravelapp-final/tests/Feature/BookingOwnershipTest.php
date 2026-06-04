<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class BookingOwnershipTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_download_another_users_ticket(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $booking = Booking::factory()->create([
            'user_id' => $owner->id,
            'status' => 'paid',
            'payment_status' => 'paid',
        ]);

        $this->actingAs($otherUser)
            ->get(route('bookings.ticket', $booking))
            ->assertForbidden();
    }

    public function test_api_user_cannot_view_another_users_booking(): void
    {
        $owner = User::factory()->create();
        $otherUser = User::factory()->create();
        $booking = Booking::factory()->create([
            'user_id' => $owner->id,
        ]);

        Sanctum::actingAs($otherUser);

        $this->getJson('/api/bookings/'.$booking->id)
            ->assertForbidden();
    }
}
