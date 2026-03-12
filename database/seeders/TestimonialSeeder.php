<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Testimonial;
use Carbon\Carbon;

class TestimonialSeeder extends Seeder
{
    public function run()
    {
        $testimonials = [
            [
                'customer_name' => 'Budi Santoso',
                'customer_email' => 'budi@example.com',
                'rating' => 5,
                'comment' => 'Makanannya enak, pelayanannya ramah. Kolamnya juga bersih!',
                'service_type' => 'restaurant',
                'visit_date' => Carbon::now()->subDays(5),
                'is_approved' => true,
                'is_featured' => true,
            ],
            [
                'customer_name' => 'Siti Aminah',
                'customer_email' => 'siti@example.com',
                'rating' => 4,
                'comment' => 'Suasananya nyaman, cocok untuk keluarga. Harga terjangkau.',
                'service_type' => 'pool',
                'visit_date' => Carbon::now()->subDays(3),
                'is_approved' => true,
                'is_featured' => false,
            ],
            [
                'customer_name' => 'Ahmad Rizki',
                'customer_email' => 'ahmad@example.com',
                'rating' => 5,
                'comment' => 'Live musicnya bagus, makanannya enak. Will come again!',
                'service_type' => 'event',
                'visit_date' => Carbon::now()->subDays(7),
                'is_approved' => true,
                'is_featured' => true,
            ],
            [
                'customer_name' => 'Dewi Lestari',
                'customer_email' => 'dewi@example.com',
                'rating' => 5,
                'comment' => 'Pelayanan cepat, tempat bersih, harga bersahabat',
                'service_type' => 'restaurant',
                'visit_date' => Carbon::now()->subDays(2),
                'is_approved' => true,
                'is_featured' => false,
            ],
            [
                'customer_name' => 'Rudi Hermawan',
                'customer_email' => 'rudi@example.com',
                'rating' => 4,
                'comment' => 'Tiket kolam online memudahkan, tidak perlu antri',
                'service_type' => 'pool',
                'visit_date' => Carbon::now()->subDays(1),
                'is_approved' => true,
                'is_featured' => false,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::create($testimonial);
        }
    }
}