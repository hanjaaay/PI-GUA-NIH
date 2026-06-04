<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Ticket;
use App\Models\TouristAttraction;
use Illuminate\Database\Seeder;

class TouristAttractionSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::firstOrCreate(['name' => 'Konser'], ['slug' => 'konser']);

        // Event 1: Jakarta Music Festival 2026
        $event1 = TouristAttraction::create([
            'name' => 'Jakarta Music Festival 2026',
            'description' => 'Festival musik terbesar di Jakarta menghadirkan artis lokal dan internasional terkemuka dengan panggung berkualitas dunia.',
            'venue_name' => 'Gelora Bung Karno',
            'organizer' => 'Festigo Events',
            'location' => 'Jakarta',
            'city' => 'Jakarta',
            'province' => 'DKI Jakarta',
            'country' => 'Indonesia',
            'address' => 'Jalan Gerbang Pemuda, Jakarta Pusat',
            'postal_code' => '10270',
            'phone' => '021-5731000',
            'email' => 'info@festigo.com',
            'website' => 'https://festigo.com',
            'start_date' => now()->addDays(30),
            'end_date' => now()->addDays(30),
            'price' => 150000,
            'is_active' => true,
            'is_featured' => true,
            'category_id' => $category->id,
            'max_capacity' => 50000,
            'current_visitors' => 0,
            'status' => 'active',
        ]);

        Ticket::create([
            'tourist_attraction_id' => $event1->id,
            'name' => 'Tiket Reguler',
            'type' => 'regular',
            'ticket_type' => 'day_pass',
            'price' => 150000,
            'quota' => 30000,
            'available_quantity' => 30000,
            'valid_date' => now()->addDays(30)->toDateString(),
            'valid_from' => now()->addDays(30),
            'valid_until' => now()->addDays(31),
            'is_active' => true,
        ]);

        Ticket::create([
            'tourist_attraction_id' => $event1->id,
            'name' => 'Tiket VIP',
            'type' => 'vip',
            'ticket_type' => 'day_pass',
            'price' => 350000,
            'quota' => 10000,
            'available_quantity' => 10000,
            'valid_date' => now()->addDays(30)->toDateString(),
            'valid_from' => now()->addDays(30),
            'valid_until' => now()->addDays(31),
            'is_active' => true,
        ]);

        Ticket::create([
            'tourist_attraction_id' => $event1->id,
            'name' => 'Tiket VIP Platinum',
            'type' => 'platinum',
            'ticket_type' => 'day_pass',
            'price' => 750000,
            'quota' => 2000,
            'available_quantity' => 2000,
            'valid_date' => now()->addDays(30)->toDateString(),
            'valid_from' => now()->addDays(30),
            'valid_until' => now()->addDays(31),
            'is_active' => true,
        ]);

        // Event 2: Bandung Jazz Festival
        $event2 = TouristAttraction::create([
            'name' => 'Bandung Jazz Festival 2026',
            'description' => 'Perayaan musik jazz dengan musisi-musisi terbaik dari seluruh dunia di kota kembang.',
            'venue_name' => 'Bandung Convention Center',
            'organizer' => 'Festigo Events',
            'location' => 'Bandung',
            'city' => 'Bandung',
            'province' => 'Jawa Barat',
            'country' => 'Indonesia',
            'address' => 'Jalan Ahmad Yani 599, Bandung',
            'postal_code' => '40173',
            'phone' => '022-7306555',
            'email' => 'jazz@festigo.com',
            'website' => 'https://festigo.com/jazz',
            'start_date' => now()->addDays(45),
            'end_date' => now()->addDays(45),
            'price' => 120000,
            'is_active' => true,
            'is_featured' => false,
            'category_id' => $category->id,
            'max_capacity' => 10000,
            'current_visitors' => 0,
            'status' => 'active',
        ]);

        Ticket::create([
            'tourist_attraction_id' => $event2->id,
            'name' => 'Tiket Reguler',
            'type' => 'regular',
            'ticket_type' => 'day_pass',
            'price' => 120000,
            'quota' => 7000,
            'available_quantity' => 7000,
            'valid_date' => now()->addDays(45)->toDateString(),
            'valid_from' => now()->addDays(45),
            'valid_until' => now()->addDays(46),
            'is_active' => true,
        ]);

        Ticket::create([
            'tourist_attraction_id' => $event2->id,
            'name' => 'Tiket VIP',
            'type' => 'vip',
            'ticket_type' => 'day_pass',
            'price' => 250000,
            'quota' => 2000,
            'available_quantity' => 2000,
            'valid_date' => now()->addDays(45)->toDateString(),
            'valid_from' => now()->addDays(45),
            'valid_until' => now()->addDays(46),
            'is_active' => true,
        ]);

        // Event 3: Surabaya Electronic Music Festival
        $event3 = TouristAttraction::create([
            'name' => 'Surabaya Electronic Music Festival 2026',
            'description' => 'Festival musik elektronik paling meriah dengan DJ terkenal dari berbagai belahan dunia.',
            'venue_name' => 'Surabaya Expo',
            'organizer' => 'Festigo Events',
            'location' => 'Surabaya',
            'city' => 'Surabaya',
            'province' => 'Jawa Timur',
            'country' => 'Indonesia',
            'address' => 'Jalan Mayjend Sungkono, Surabaya',
            'postal_code' => '60188',
            'phone' => '031-8004333',
            'email' => 'edm@festigo.com',
            'website' => 'https://festigo.com/edm',
            'start_date' => now()->addDays(60),
            'end_date' => now()->addDays(60),
            'price' => 100000,
            'is_active' => true,
            'is_featured' => true,
            'category_id' => $category->id,
            'max_capacity' => 20000,
            'current_visitors' => 0,
            'status' => 'active',
        ]);

        Ticket::create([
            'tourist_attraction_id' => $event3->id,
            'name' => 'Tiket Early Bird',
            'type' => 'early_bird',
            'ticket_type' => 'day_pass',
            'price' => 100000,
            'quota' => 15000,
            'available_quantity' => 15000,
            'valid_date' => now()->addDays(60)->toDateString(),
            'valid_from' => now()->addDays(60),
            'valid_until' => now()->addDays(61),
            'is_active' => true,
        ]);

        Ticket::create([
            'tourist_attraction_id' => $event3->id,
            'name' => 'Tiket VIP Meet & Greet',
            'type' => 'vip',
            'ticket_type' => 'day_pass',
            'price' => 400000,
            'quota' => 500,
            'available_quantity' => 500,
            'valid_date' => now()->addDays(60)->toDateString(),
            'valid_from' => now()->addDays(60),
            'valid_until' => now()->addDays(61),
            'is_active' => true,
        ]);
    }
}

