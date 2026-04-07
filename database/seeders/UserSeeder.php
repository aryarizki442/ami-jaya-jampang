<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'phone' => '081123456789',
            'password' => 'password123',
            'role' => 'admin',
        ]);

        // Customer
        User::create([
            'name' => 'Customer',
            'email' => 'customer@gmail.com',
            'phone' => '080123456789',
            'password' => 'password123',
            'role' => 'customer',
        ]);
    }
}
