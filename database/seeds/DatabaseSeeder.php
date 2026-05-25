<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Create default admin user
        \App\User::updateOrCreate(
            ['email' => 'admin@pageturn.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );

        // Create default staff user
        \App\User::updateOrCreate(
            ['email' => 'staff@pageturn.com'],
            [
                'name' => 'Staff Member',
                'password' => Hash::make('password123'),
                'role' => 'staff',
                'is_active' => true,
            ]
        );

        // Create sample customer
        \App\User::updateOrCreate(
            ['email' => 'customer@example.com'],
            [
                'name' => 'John Doe',
                'password' => Hash::make('password123'),
                'role' => 'customer',
                'is_active' => true,
                'phone' => '09123456789',
                'address' => '123 Book Street, Manila, Philippines',
            ]
        );
    }
}
