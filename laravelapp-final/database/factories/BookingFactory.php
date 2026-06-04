<?php

namespace Database\Factories;

use App\Models\Booking;
use App\Models\Ticket;
use App\Models\TouristAttraction;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'tourist_attraction_id' => TouristAttraction::factory(),
            'ticket_id' => Ticket::factory(),
            'visit_date' => $this->faker->dateTimeBetween('+1 day', '+1 month'),
            'quantity' => $this->faker->numberBetween(1, 5),
            'total_price' => $this->faker->numberBetween(10000, 500000),
            'status' => $this->faker->randomElement(['pending', 'paid', 'cancelled']),
            'payment_status' => $this->faker->randomElement(['pending', 'paid', 'failed']),
            'payment_method' => $this->faker->randomElement(['bank_transfer', 'credit_card', 'cash']),
            'payment_proof' => 'proof.jpg',
            'notes' => $this->faker->sentence,
        ];
    }
}
