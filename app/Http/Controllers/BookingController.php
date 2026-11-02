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
        $query = Booking::with(['customer', 'vehicle']);

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        if ($request->filled('vehicle_id')) {
            $query->where('vehicle_id', $request->vehicle_id);
        }

        return response()->json($query->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:users,id',
            'vehicle_id' => 'required|exists:vehicles,id',
            'start_at' => 'required|date|after:now',
            'end_at' => 'required|date|after:start_at',
        ]);

        $booking = DB::transaction(function () use ($validated) {

            // Lock customer to prevent simultaneous booking limit bypass.
            $customer = User::whereKey($validated['customer_id'])
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

    public function show(Booking $booking)
    {
        return response()->json($booking->load(['customer', 'vehicle']));
    }

    public function update(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'status' => 'sometimes|required|in:pending,confirmed,active,completed,cancelled,expired',
        ]);

        $booking->update($validated);

        return response()->json($booking->load(['customer', 'vehicle']));
    }

    // BR-08: Customer can cancel Pending or Confirmed bookings
    // until 2 hours before the booking start time.
    public function destroy(Request $request, Booking $booking)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:users,id',
        ]);

        if ((int) $validated['customer_id'] !== (int) $booking->customer_id) {
            abort(403, 'You can only cancel your own bookings.');
        }

        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            abort(422, 'Only pending or confirmed bookings can be cancelled.');
        }

        $cancellationDeadline = $booking->start_at->copy()->subHours(2);

        if (now()->gt($cancellationDeadline)) {
            abort(422, 'Bookings can only be cancelled at least 2 hours before start time.');
        }

        $booking->update([
            'status' => 'cancelled',
        ]);

        return response()->json([
            'message' => 'Booking cancelled successfully.',
            'booking' => $booking->fresh()->load(['customer', 'vehicle']),
        ]);
    }
}
