<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $query = Booking::with(['customer', 'vehicle']);

        if ($user->role === 'customer') {
            if ($user->status !== 'active') {
                abort(403, 'Your account is not active.');
            }

            $query->where('customer_id', $user->id);

        } elseif ($user->role === 'provider') {
            if ($user->status !== 'active') {
                abort(403, 'Your provider account is not active.');
            }

            $query->whereHas('vehicle', function ($vehicleQuery) use ($user) {
                $vehicleQuery->where('provider_id', $user->id);
            });

        } elseif ($user->role === 'admin') {
            if ($user->status !== 'active') {
                abort(403, 'Your admin account is not active.');
            }

        } else {
            abort(403, 'You are not authorized to view bookings.');
        }

        if ($request->filled('vehicle_id')) {
            $query->where('vehicle_id', $request->vehicle_id);
        }

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $user = $request->user();

        if ($user->role !== 'customer' || $user->status !== 'active') {
            abort(403, 'Only active customers can create bookings.');
        }

        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'start_at' => 'required|date|after:now',
            'end_at' => 'required|date|after:start_at',
        ]);

        $booking = DB::transaction(function () use ($validated, $user) {

            $customer = User::whereKey($user->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($customer->role !== 'customer' || $customer->status !== 'active') {
                abort(403, 'Only active customers can create bookings.');
            }

            // BR-05: Maximum 2 open bookings.
            $openBookingsCount = Booking::where('customer_id', $customer->id)
                ->whereIn('status', ['pending', 'confirmed', 'active'])
                ->count();

            if ($openBookingsCount >= 2) {
                abort(422, 'You can have a maximum of 2 open bookings at a time.');
            }

            $vehicle = Vehicle::whereKey($validated['vehicle_id'])
                ->lockForUpdate()
                ->firstOrFail();

            if ($vehicle->status !== 'active') {
                abort(422, 'Vehicle is not available for booking.');
            }

            $start = Carbon::parse($validated['start_at']);
            $end = Carbon::parse($validated['end_at']);

            // BR-07: Booking must start within the next 14 days.
            if ($start->gt(now()->addDays(14))) {
                abort(422, 'Booking must start within the next 14 days.');
            }

            // BR-03: Minimum rental period is 1 hour.
            $durationInMinutes = $start->diffInMinutes($end);

            if ($durationInMinutes < 60) {
                abort(422, 'Minimum rental period is 1 hour.');
            }

            // BR-04: Maximum rental period depends on vehicle type.
            $maxDurationInHours = $vehicle->type === 'car' ? 72 : 24;

            $durationInHours = $start->diffInMinutes($end) / 60;

            if ($durationInHours > $maxDurationInHours) {
                abort(
                    422,
                    "Maximum rental period for {$vehicle->type} is {$maxDurationInHours} hours."
                );
            }

            // BR-06: Prevent overlapping bookings by the same customer.
            $customerHasConflict = Booking::where('customer_id', $customer->id)
                ->whereIn('status', ['pending', 'confirmed', 'active'])
                ->where('start_at', '<', $end)
                ->where('end_at', '>', $start)
                ->exists();

            if ($customerHasConflict) {
                abort(422, 'You already have a booking during this period.');
            }

            // BR-01: Prevent overlapping vehicle bookings.
            $hasConflict = Booking::where('vehicle_id', $vehicle->id)
                ->whereIn('status', ['pending', 'confirmed', 'active'])
                ->where('start_at', '<', $end)
                ->where('end_at', '>', $start)
                ->exists();

            if ($hasConflict) {
                abort(422, 'Vehicle is already booked during this period.');
            }

            // Check vehicle availability for every day in the period.
            $currentDay = $start->copy()->startOfDay();

            while ($currentDay->lt($end)) {
                $dayStart = $currentDay->copy()->max($start);
                $dayEnd = $currentDay->copy()->addDay()->min($end);
                $dayOfWeek = $currentDay->dayOfWeek;

                $availability = $vehicle->availabilities()
                    ->where('day_of_week', $dayOfWeek)
                    ->where('start_time', '<=', $dayStart->format('H:i:s'))
                    ->where('end_time', '>=', $dayEnd->format('H:i:s'))
                    ->exists();

                if (!$availability) {
                    abort(422, 'Vehicle is not available during the requested time.');
                }

                $currentDay->addDay();
            }

            // Calculate booking price.
            $hours = $start->diffInMinutes($end) / 60;
            $totalPrice = round($vehicle->price_per_hour * $hours, 2);

            return Booking::create([
                'customer_id' => $customer->id,
                'vehicle_id' => $vehicle->id,
                'start_at' => $start,
                'end_at' => $end,
                'total_price' => $totalPrice,
                'status' => 'pending',
            ]);
        });

        return response()->json(
            $booking->load(['customer', 'vehicle']),
            201
        );
    }

    public function show(Request $request, Booking $booking)
    {
        $user = $request->user();

        if ($user->status !== 'active') {
            abort(403, 'Your account is not active.');
        }

        if ($user->role === 'customer') {
            if ((int) $booking->customer_id !== (int) $user->id) {
                abort(403, 'You are not authorized to view this booking.');
            }

        } elseif ($user->role === 'provider') {
            if ((int) $booking->vehicle->provider_id !== (int) $user->id) {
                abort(403, 'You are not authorized to view this booking.');
            }

        } elseif ($user->role !== 'admin') {
            abort(403, 'You are not authorized to view this booking.');
        }

        return response()->json($booking->load(['customer', 'vehicle']));
    }

    public function update(Request $request, Booking $booking)
    {
        $user = $request->user();

        if ($user->status !== 'active') {
            abort(403, 'Your account is not active.');
        }

        $validated = $request->validate([
            'status' => 'required|in:pending,confirmed,active,completed,cancelled,expired',
        ]);

        $newStatus = $validated['status'];

        $updatedBooking = DB::transaction(function () use (
            $booking,
            $user,
            $newStatus
        ) {
            // Lock vehicle first to coordinate with new booking requests.
            $vehicle = Vehicle::whereKey($booking->vehicle_id)
                ->lockForUpdate()
                ->firstOrFail();

            $lockedBooking = Booking::whereKey($booking->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($user->role === 'provider') {

                if ((int) $vehicle->provider_id !== (int) $user->id) {
                    abort(403, 'You are not authorized to update this booking.');
                }

                $allowedTransitions = [
                    'pending' => ['confirmed'],
                    'confirmed' => ['active'],
                    'active' => ['completed'],
                ];

                if (!in_array(
                    $newStatus,
                    $allowedTransitions[$lockedBooking->status] ?? []
                )) {
                    abort(422, 'This booking status transition is not allowed.');
                }

            } elseif ($user->role !== 'admin') {
                abort(403, 'You are not authorized to update bookings.');
            }

            $lockedBooking->update([
                'status' => $newStatus,
            ]);

            // If the booking is now closed, check whether the vehicle
            // can be soft deleted.
            if (in_array($newStatus, ['completed', 'cancelled', 'expired'])) {
                $this->deleteVehicleIfNoOpenBookings($vehicle);
            }

            return $lockedBooking;
        });

        $updatedBooking->refresh()->load('customer');

        // Include the vehicle in the response even if it was soft deleted.
        $updatedBooking->setRelation(
            'vehicle',
            Vehicle::withTrashed()->find($updatedBooking->vehicle_id)
        );

        return response()->json($updatedBooking);
    }

    // BR-08: Customer can cancel Pending or Confirmed bookings
    // until 2 hours before the booking start time.
    public function destroy(Request $request, Booking $booking)
    {
        $user = $request->user();

        if ($user->role !== 'customer' || $user->status !== 'active') {
            abort(403, 'Only active customers can cancel bookings.');
        }

        $cancelledBooking = DB::transaction(function () use ($booking, $user) {

            // Lock vehicle first to coordinate with new booking requests.
            $vehicle = Vehicle::whereKey($booking->vehicle_id)
                ->lockForUpdate()
                ->firstOrFail();

            $lockedBooking = Booking::whereKey($booking->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ((int) $lockedBooking->customer_id !== (int) $user->id) {
                abort(403, 'You can only cancel your own bookings.');
            }

            if (!in_array($lockedBooking->status, ['pending', 'confirmed'])) {
                abort(422, 'Only pending or confirmed bookings can be cancelled.');
            }

            $cancellationDeadline = $lockedBooking->start_at->copy()->subHours(2);

            if (now()->gt($cancellationDeadline)) {
                abort(422, 'Bookings can only be cancelled at least 2 hours before start time.');
            }

            $lockedBooking->update([
                'status' => 'cancelled',
            ]);

            // Check whether this was the last open booking.
            $this->deleteVehicleIfNoOpenBookings($vehicle);

            return $lockedBooking;
        });

        $cancelledBooking->refresh()->load('customer');

        // Include the vehicle even if it was soft deleted.
        $cancelledBooking->setRelation(
            'vehicle',
            Vehicle::withTrashed()->find($cancelledBooking->vehicle_id)
        );

        return response()->json([
            'message' => 'Booking cancelled successfully.',
            'booking' => $cancelledBooking,
        ]);
    }

    /**
     * Soft delete the vehicle after its last open booking is closed,
     * but only if an admin has approved its deletion request.
     */
    private function deleteVehicleIfNoOpenBookings(Vehicle $vehicle)
    {
        if ($vehicle->deletion_status !== 'approved') {
            return;
        }

        $hasOpenBookings = Booking::where('vehicle_id', $vehicle->id)
            ->whereIn('status', ['pending', 'confirmed', 'active'])
            ->exists();

        if (!$hasOpenBookings) {
            $vehicle->delete();
        }
    }
}
