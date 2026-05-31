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
        // Create super admin
        \App\User::updateOrCreate(
            ['email' => 'admin@pageturn.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password123'),
                'role' => 'super_admin',
                'is_active' => true,
            ]
        );

        // Create Finance Admin
        \App\User::updateOrCreate(
            ['email' => 'finance@pageturn.com'],
            [
                'name' => 'Finance Admin',
                'password' => Hash::make('password123'),
                'role' => 'finance_admin',
                'is_active' => true,
            ]
        );

        // Create Inventory Admin
        \App\User::updateOrCreate(
            ['email' => 'inventory@pageturn.com'],
            [
                'name' => 'Inventory Admin',
                'password' => Hash::make('password123'),
                'role' => 'inventory_admin',
                'is_active' => true,
            ]
        );

        // Create Catalog Admin
        \App\User::updateOrCreate(
            ['email' => 'catalog@pageturn.com'],
            [
                'name' => 'Catalog Admin',
                'password' => Hash::make('password123'),
                'role' => 'catalog_admin',
                'is_active' => true,
            ]
        );

        // Create Orders Admin
        \App\User::updateOrCreate(
            ['email' => 'orders@pageturn.com'],
            [
                'name' => 'Orders Admin',
                'password' => Hash::make('password123'),
                'role' => 'orders_admin',
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
