<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;

class AdminVehicleController extends Controller
{
    // Approve a pending vehicle
    public function approve(Vehicle $vehicle)
    {
        if ($vehicle->status !== 'pending_approval') {
            return response()->json([
                'message' => 'Only pending vehicles can be approved.',
            ], 422);
        }

        $vehicle->update([
            'status' => 'active',
            'rejection_reason' => null,
        ]);

        return response()->json([
            'message' => 'Vehicle approved successfully.',
            'vehicle' => $vehicle->fresh(),
        ]);
    }

    // Reject a pending vehicle
    public function reject(Request $request, Vehicle $vehicle)
    {
        if ($vehicle->status !== 'pending_approval') {
            return response()->json([
                'message' => 'Only pending vehicles can be rejected.',
            ], 422);
        }

        $validated = $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $vehicle->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        return response()->json([
            'message' => 'Vehicle rejected successfully.',
            'vehicle' => $vehicle->fresh(),
        ]);
    }

    // Get all pending vehicles
    public function pending()
    {
        $vehicles = Vehicle::where('status', 'pending_approval')->get();

        return response()->json($vehicles);
    }
}