<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::where('status', 'active')->get();

        return response()->json($vehicles);
    }

    public function show(Vehicle $vehicle)
    {
        if ($vehicle->status !== 'active') {
            return response()->json([
                'message' => 'Vehicle is not available for browsing.',
            ], 404);
        }

        return response()->json($vehicle);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'provider_id' => 'required|exists:users,id',
            'type' => 'required|in:bicycle,scooter,motorbike,car',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string|max:255',
            'price_per_hour' => 'required|numeric|min:0',
            'pickup_location' => 'required|string|max:255',
        ]);

        $vehicle = Vehicle::create([
            'provider_id' => $validated['provider_id'],
            'type' => $validated['type'],
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'image' => $validated['image'] ?? null,
            'price_per_hour' => $validated['price_per_hour'],
            'pickup_location' => $validated['pickup_location'],
            'status' => 'pending_approval',
        ]);

        return response()->json($vehicle, 201);
    }
}