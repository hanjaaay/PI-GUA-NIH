<?php

namespace Database\Factories;

use App\Models\Ticket;
use App\Models\TouristAttraction;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    public function definition()
    {
        return [
            'tourist_attraction_id' => TouristAttraction::factory(),
            'name' => $this->faker->word.' Ticket',
            'type' => $this->faker->randomElement(['regular', 'vip', 'package', 'children']),
            'price' => $this->faker->numberBetween(10000, 500000),
            'quota' => $quota = $this->faker->numberBetween(10, 100),
            'available_quantity' => $quota,
            'valid_from' => $this->faker->dateTimeBetween('-1 month', '+1 month'),
            'valid_until' => $this->faker->dateTimeBetween('+2 months', '+6 months'),
            'description' => $this->faker->sentence,
            'is_active' => true,
            'qr_code' => $this->faker->uuid,
        ];
    }
}
