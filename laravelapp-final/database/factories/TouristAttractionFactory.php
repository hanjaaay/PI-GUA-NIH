<?php

namespace Database\Factories;

use App\Models\TouristAttraction;
use Illuminate\Database\Eloquent\Factories\Factory;

class TouristAttractionFactory extends Factory
{
    protected $model = TouristAttraction::class;

    public function definition()
    {
        return [
            'name' => $this->faker->company.' Attraction',
            'type' => $this->faker->randomElement(['attraction', 'concert', 'event']),
            'description' => $this->faker->paragraph,
            'location' => $this->faker->city,
            'price' => $this->faker->numberBetween(10000, 500000),
            'start_date' => $this->faker->dateTimeBetween('-1 month', '+1 month'),
            'end_date' => $this->faker->dateTimeBetween('+2 months', '+6 months'),
            'is_active' => true,
            'image' => 'default.jpg',
            'featured_image' => 'featured.jpg',
            'facilities' => ['VIP', 'Regular'],
            'opening_hours' => '08:00',
            'closing_hours' => '17:00',
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'contact' => $this->faker->phoneNumber,
            'city' => $this->faker->city,
            'province' => $this->faker->state,
        ];
    }
}
