<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\VehicleDeletionRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VehicleDeletionRequestController extends Controller
{
    // Provider submits a vehicle deletion request
    public function store(Request $request, Vehicle $vehicle)
    {
        $user = $request->user();

        if (
            !$user ||
            $user->role !== 'provider' ||
            $user->status !== 'active'
        ) {
            return response()->json([
                'message' => 'Only active providers can request vehicle deletion.',
            ], 403);
        }

        if ((int) $vehicle->provider_id !== (int) $user->id) {
            return response()->json([
                'message' => 'You can only request deletion of your own vehicles.',
            ], 403);
        }

        $validated = $request->validate([
            'provider_reason' => 'nullable|string|max:1000',
        ]);

        return DB::transaction(function () use ($vehicle, $user, $validated) {

            $lockedVehicle = Vehicle::whereKey($vehicle->id)
                ->lockForUpdate()
                ->firstOrFail();

            if (in_array(
                $lockedVehicle->deletion_status,
                ['pending', 'approved']
            )) {
                return response()->json([
                    'message' => 'A deletion request already exists for this vehicle.',
                ], 422);
            }

            $deletionRequest = VehicleDeletionRequest::create([
                'vehicle_id' => $lockedVehicle->id,
                'provider_id' => $user->id,
                'status' => 'pending',
                'provider_reason' => $validated['provider_reason'] ?? null,
            ]);

            $lockedVehicle->update([
                'deletion_status' => 'pending',
            ]);

            return response()->json([
                'message' => 'Vehicle deletion request submitted successfully.',
                'request' => $deletionRequest->load('vehicle'),
            ], 201);
        });
    }

    // Admin views pending deletion requests
    public function index(Request $request)
    {
        $user = $request->user();

        if (
            !$user ||
            $user->role !== 'admin' ||
            $user->status !== 'active'
        ) {
            return response()->json([
                'message' => 'Only active admins can view deletion requests.',
            ], 403);
        }

        $requests = VehicleDeletionRequest::with([
            'vehicle',
            'provider',
        ])
        ->where('status', 'pending')
        ->latest()
        ->get();

        return response()->json($requests);
    }

    // Admin approves a deletion request
    public function approve(Request $request, VehicleDeletionRequest $deletionRequest)
    {
        $user = $request->user();

        if (
            !$user ||
            $user->role !== 'admin' ||
            $user->status !== 'active'
        ) {
            return response()->json([
                'message' => 'Only active admins can approve deletion requests.',
            ], 403);
        }

        return DB::transaction(function () use ($user, $deletionRequest) {

            $lockedRequest = VehicleDeletionRequest::whereKey($deletionRequest->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedRequest->status !== 'pending') {
                return response()->json([
                    'message' => 'This deletion request has already been reviewed.',
                ], 422);
            }

            $vehicle = Vehicle::whereKey($lockedRequest->vehicle_id)
                ->lockForUpdate()
                ->firstOrFail();

            $lockedRequest->update([
                'status' => 'approved',
                'reviewed_by' => $user->id,
                'reviewed_at' => now(),
            ]);

            // Stop accepting new bookings
            $vehicle->update([
                'status' => 'inactive',
                'deletion_status' => 'approved',
            ]);

            // Check whether open bookings still exist
            $hasOpenBookings = $vehicle->bookings()
                ->whereIn('status', [
                    'pending',
                    'confirmed',
                    'active',
                ])
                ->exists();

            if (!$hasOpenBookings) {
                $vehicle->delete();

                $message = 'Request approved. Vehicle has been deleted.';
            } else {
                $message = 'Request approved. Vehicle will be deleted after all open bookings finish.';
            }

            return response()->json([
                'message' => $message,
                'request' => $lockedRequest->load('vehicle', 'provider', 'reviewer'),
            ]);
        });
    }

    // Admin rejects a deletion request
    public function reject(Request $request, VehicleDeletionRequest $deletionRequest)
    {
        $user = $request->user();

        if (
            !$user ||
            $user->role !== 'admin' ||
            $user->status !== 'active'
        ) {
            return response()->json([
                'message' => 'Only active admins can reject deletion requests.',
            ], 403);
        }

        $validated = $request->validate([
            'admin_reason' => 'nullable|string|max:1000',
        ]);

        return DB::transaction(function () use (
            $user,
            $deletionRequest,
            $validated
        ) {

            $lockedRequest = VehicleDeletionRequest::whereKey($deletionRequest->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($lockedRequest->status !== 'pending') {
                return response()->json([
                    'message' => 'This deletion request has already been reviewed.',
                ], 422);
            }

            $vehicle = Vehicle::whereKey($lockedRequest->vehicle_id)
                ->lockForUpdate()
                ->firstOrFail();

            $lockedRequest->update([
                'status' => 'rejected',
                'reviewed_by' => $user->id,
                'admin_reason' => $validated['admin_reason'] ?? null,
                'reviewed_at' => now(),
            ]);

            $vehicle->update([
                'deletion_status' => 'rejected',
            ]);

            return response()->json([
                'message' => 'Vehicle deletion request rejected.',
                'request' => $lockedRequest->load('vehicle', 'provider', 'reviewer'),
            ]);
        });
    }
}