<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;

class AdminVehicleController extends Controller
{
    // Check if the authenticated user is an active admin
    private function isActiveAdmin(Request $request)
    {
        $user = $request->user();

        return $user
            && $user->role === 'admin'
            && $user->status === 'active';
    }

    // Approve a pending vehicle
    public function approve(Request $request, Vehicle $vehicle)
    {
        if (!$this->isActiveAdmin($request)) {
            return response()->json([
                'message' => 'Unauthorized. Only active admins can approve vehicles.',
            ], 403);
        }

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
        if (!$this->isActiveAdmin($request)) {
            return response()->json([
                'message' => 'Unauthorized. Only active admins can reject vehicles.',
            ], 403);
        }

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
    public function pending(Request $request)
    {
        if (!$this->isActiveAdmin($request)) {
            return response()->json([
                'message' => 'Unauthorized. Only active admins can view pending vehicles.',
            ], 403);
        }

        $vehicles = Vehicle::where('status', 'pending_approval')->get();

        return response()->json($vehicles);
    }
}
