<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Review;
use App\Models\ReviewPhoto;
use App\Models\TouristAttraction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $attractions = TouristAttraction::whereHas('tickets')->get();

        if ($users->isEmpty() || $attractions->isEmpty()) {
            $this->command->info('Please seed users and attractions with tickets first.');

            return;
        }

        Schema::disableForeignKeyConstraints();
        Review::truncate();
        ReviewPhoto::truncate();
        Booking::where('id', '>', 0)->delete();
        Schema::enableForeignKeyConstraints();

        // Create 20 sample bookings and reviews for them
        for ($i = 0; $i < 20; $i++) {
            $user = $users->random();
            $attraction = $attractions->random();
            $ticket = $attraction->tickets->random();

            // Create a new booking
            $booking = Booking::create([
                'user_id' => $user->id,
                'tourist_attraction_id' => $attraction->id,
                'ticket_id' => $ticket->id,
                'booking_code' => 'BK'.strtoupper(uniqid()),
                'quantity' => rand(1, 4),
                'total_price' => $ticket->price * rand(1, 4),
                'visit_date' => now()->addDays(rand(1, 30)),
                'status' => 'completed', // Assume these are completed bookings
                'payment_status' => 'paid',
            ]);

            // Create a review for the new booking
            $review = Review::create([
                'user_id' => $booking->user_id,
                'tourist_attraction_id' => $booking->tourist_attraction_id,
                'booking_id' => $booking->id,
                'rating' => rand(3, 5),
                'comment' => fake()->realText(200),
                'is_verified' => true,
            ]);

            // Add 0-3 photos for each review
            $photoCount = rand(0, 3);
            for ($j = 0; $j < $photoCount; $j++) {
                ReviewPhoto::create([
                    'review_id' => $review->id,
                    'photo_path' => 'review-photos/sample-'.rand(1, 5).'.jpg',
                    'caption' => fake()->sentence(),
                ]);
            }
        }
    }
}
