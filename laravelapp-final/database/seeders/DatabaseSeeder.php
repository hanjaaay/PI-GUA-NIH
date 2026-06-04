<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents; // Bisa tetap ada atau dihapus
use App\Models\User;
use Illuminate\Database\Seeder; // Tetap ada karena Anda membuat user admin
// Hapus imports untuk TouristAttraction, Ticket, Booking jika tidak digunakan lagi
// use App\Models\TouristAttraction;
// use App\Models\Ticket;
// use App\Models\Booking;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'admin@festigo.com'],
            [
                'name' => 'Admin Festigo',
                'password' => 'password',
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        $this->call([
            TouristAttractionSeeder::class,
        ]);
    }
}
