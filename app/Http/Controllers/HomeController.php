<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $stats = [
            ['id' => 'counter1', 'value' => 1500, 'label' => 'Happy Clients', 'icon' => 'fa-smile', 'color' => 'primary'],
            ['id' => 'counter2', 'value' => 2500, 'label' => 'Projects Completed', 'icon' => 'fa-check-circle', 'color' => 'success'],
            ['id' => 'counter3', 'value' => 50, 'label' => 'Team Members', 'icon' => 'fa-users', 'color' => 'info'],
            ['id' => 'counter4', 'value' => 25, 'label' => 'Awards Won', 'icon' => 'fa-trophy', 'color' => 'warning'],
        ];

        $features = [
            [
                'icon' => 'fa-paint-brush',
                'gradient' => 'primary',
                'title' => 'Modern Design',
                'description' => 'Beautiful and intuitive interface with smooth animations and interactions.',
                'feature' => 'design'
            ],
            [
                'icon' => 'fa-code',
                'gradient' => 'success',
                'title' => 'Clean Code',
                'description' => 'Well-organized and documented code for easy customization and maintenance.',
                'feature' => 'code'
            ],
            [
                'icon' => 'fa-headset',
                'gradient' => 'info',
                'title' => '24/7 Support',
                'description' => 'Dedicated support team ready to help you with any questions or issues.',
                'feature' => 'support'
            ],
        ];

        $testimonials = [
            [
                'image' => 'https://randomuser.me/api/portraits/women/68.jpg',
                'quote' => 'The support team is amazing! They helped me customize everything I needed within hours.',
                'name' => 'Emily Rodriguez',
                'position' => 'Marketing Director',
                'rating' => 5
            ],
            [
                'image' => 'https://randomuser.me/api/portraits/men/32.jpg',
                'quote' => 'Incredible attention to detail and the animations are buttery smooth. Highly recommended!',
                'name' => 'Michael Chen',
                'position' => 'Product Designer',
                'rating' => 4.5
            ],
            [
                'image' => 'https://randomuser.me/api/portraits/women/44.jpg',
                'quote' => 'This is by far the best template I\'ve ever used. The design is stunning and the code is so clean!',
                'name' => 'Sarah Johnson',
                'position' => 'CEO, TechStart',
                'rating' => 5
            ],
        ];

        return view('pages.home', compact('stats', 'features', 'testimonials'));
    }
}