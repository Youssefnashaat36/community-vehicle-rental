<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;

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
}