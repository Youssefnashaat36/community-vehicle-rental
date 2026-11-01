<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        $provider = User::create([
            'name' => 'Provider',
            'email' => 'provider@test.com',
            'password' => Hash::make('password'),
            'role' => 'provider',
            'status' => 'active',
        ]);

        $customer = User::create([
            'name' => 'Customer',
            'email' => 'customer@test.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'status' => 'active',
        ]);

        Vehicle::create([
            'provider_id' => $provider->id,
            'type' => 'bicycle',
            'name' => 'City Bike',
            'price_per_hour' => 10,
            'pickup_location' => 'Main Gate',
            'status' => 'active',
        ]);

        Vehicle::create([
            'provider_id' => $provider->id,
            'type' => 'scooter',
            'name' => 'E-Scooter',
            'price_per_hour' => 25,
            'pickup_location' => 'Library',
            'status' => 'active',
        ]);

        Vehicle::create([
            'provider_id' => $provider->id,
            'type' => 'car',
            'name' => 'Toyota Yaris',
            'price_per_hour' => 80,
            'pickup_location' => 'Parking A',
            'status' => 'pending_approval',
        ]);
    }
}