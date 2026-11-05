<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    // Display all active vehicles
    public function index()
    {
        $vehicles = Vehicle::where('status', 'active')->get();

        return response()->json($vehicles);
    }

    // Display a single active vehicle
    public function show(Vehicle $vehicle)
    {
        if ($vehicle->status !== 'active') {
            return response()->json([
                'message' => 'Vehicle is not available for browsing.',
            ], 404);
        }

        return response()->json($vehicle);
    }

    // Add a new vehicle
    public function store(Request $request)
    {
        $user = $request->user();

        if ($user->role !== 'provider' || $user->status !== 'active') {
            return response()->json([
                'message' => 'Only active providers can add vehicles.',
            ], 403);
        }

        $validated = $request->validate([
            'type' => 'required|in:bicycle,scooter,motorbike,car',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:255',
            'price_per_hour' => 'required|numeric|min:0',
            'pickup_location' => 'required|string|max:255',
        ]);

        $vehicle = $user->vehicles()->create([
            'type' => $validated['type'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'image' => $validated['image'] ?? null,
            'price_per_hour' => $validated['price_per_hour'],
            'pickup_location' => $validated['pickup_location'],
            'status' => 'pending_approval',
        ]);

        return response()->json([
            'message' => 'Vehicle submitted successfully and is waiting for admin approval.',
            'vehicle' => $vehicle,
        ], 201);
    }
}
