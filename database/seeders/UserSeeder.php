<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Admin Caldera',
            'email' => 'admin@caldera.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_active' => true
        ]);

        User::create([
            'name' => 'Staff Caldera',
            'email' => 'staff@caldera.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
            'is_active' => true
        ]);

        User::create([
            'name' => 'Customer Demo',
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'is_active' => true
        ]);
    }
}