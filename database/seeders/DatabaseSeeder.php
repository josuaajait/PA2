<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            UserSeeder::class,        // 1. Users (mandiri)
            MenuSeeder::class,        // 2. Menus (mandiri)
            PromoSeeder::class,       // 3. Promos (mandiri) - Event butuh ini
            EventSeeder::class,       // 4. Events (tergantung promos)
            TestimonialSeeder::class, // 5. Testimonials (mandiri)
            GallerySeeder::class,     // 6. Galleries (tergantung semua)
        ]);
    }
}