<?php

namespace Database\Seeders;

use App\Models\Vehicle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        Vehicle::create([
            'name' => 'Toyota Camry',
            'type' => 'car',
            'description' => 'Comfortable sedan perfect for city driving',
            'daily_rate' => 45.99,
            'available' => true,
        ]);

        Vehicle::create([
            'name' => 'Honda CBR',
            'type' => 'bike',
            'description' => 'Sporty motorcycle for adventurous rides',
            'daily_rate' => 35.50,
            'available' => true,
        ]);

        Vehicle::create([
            'name' => 'Tesla Model 3',
            'type' => 'car',
            'description' => 'Electric vehicle with autopilot features',
            'daily_rate' => 89.99,
            'available' => true,
        ]);

        Vehicle::create([
            'name' => 'Vespa Scooter',
            'type' => 'scooter',
            'description' => 'Classic Italian scooter, great for short trips',
            'daily_rate' => 25.00,
            'available' => true,
        ]);

        Vehicle::create([
            'name' => 'Ford Transit Van',
            'type' => 'van',
            'description' => 'Spacious van for group trips or moving',
            'daily_rate' => 75.25,
            'available' => false,
        ]);
    }
}
