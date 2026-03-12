<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    public function run()
    {
        $menus = [
            [
                'name' => 'Nasi Goreng Spesial',
                'category' => 'makanan',
                'description' => 'Nasi goreng dengan topping ayam suwir, telur mata sapi, dan kerupuk',
                'price' => 35000,
                'is_available' => true,
                'is_recommended' => true,
                'sort_order' => 1
            ],
            [
                'name' => 'Mie Goreng Seafood',
                'category' => 'makanan',
                'description' => 'Mie goreng dengan udang, cumi, dan bakso ikan',
                'price' => 40000,
                'is_available' => true,
                'is_recommended' => true,
                'sort_order' => 2
            ],
            [
                'name' => 'Ayam Bakar Madu',
                'category' => 'makanan',
                'description' => 'Ayam bakar dengan bumbu madu, disajikan dengan sambal matah',
                'price' => 45000,
                'is_available' => true,
                'is_recommended' => false,
                'sort_order' => 3
            ],
            [
                'name' => 'Es Teh Manis',
                'category' => 'minuman',
                'description' => 'Teh hitam dengan gula, disajikan dingin',
                'price' => 8000,
                'is_available' => true,
                'is_recommended' => false,
                'sort_order' => 4
            ],
            [
                'name' => 'Jus Alpukat',
                'category' => 'minuman',
                'description' => 'Jus alpukat segar dengan susu coklat',
                'price' => 18000,
                'is_available' => true,
                'is_recommended' => true,
                'sort_order' => 5
            ],
            [
                'name' => 'Pisang Goreng Keju',
                'category' => 'dessert',
                'description' => 'Pisang goreng dengan topping keju dan coklat',
                'price' => 15000,
                'is_available' => true,
                'is_recommended' => true,
                'sort_order' => 6
            ],
        ];

        foreach ($menus as $menu) {
            Menu::create($menu);
        }
    }
}