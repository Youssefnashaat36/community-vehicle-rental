<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminVehicleController;
use App\Http\Controllers\VehicleDeletionRequestController;

// ========================================
// Authentication Routes
// ========================================

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// ========================================
// Protected Routes
// ========================================

Route::middleware('auth:sanctum')->group(function () {

    // Authentication
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

    // ========================================
    // Provider Vehicle Routes
    // ========================================

    // Get all vehicles owned by the logged-in provider
    Route::get(
        '/provider/vehicles',
        [VehicleController::class, 'myVehicles']
    );

    // Submit vehicle deletion request
    Route::post(
        '/provider/vehicles/{vehicle}/deletion-request',
        [VehicleDeletionRequestController::class, 'store']
    );

    // ========================================
    // Admin Vehicle Deletion Requests
    // ========================================

    Route::get(
        '/admin/vehicle-deletion-requests',
        [VehicleDeletionRequestController::class, 'index']
    );

    Route::patch(
        '/admin/vehicle-deletion-requests/{deletionRequest}/approve',
        [VehicleDeletionRequestController::class, 'approve']
    );

    Route::patch(
        '/admin/vehicle-deletion-requests/{deletionRequest}/reject',
        [VehicleDeletionRequestController::class, 'reject']
    );

    // ========================================
    // Vehicle Management
    // ========================================

    Route::post('/vehicles', [VehicleController::class, 'store']);

    // Admin pending vehicles
    Route::get(
        '/admin/vehicles/pending',
        [AdminVehicleController::class, 'pending']
    );

    Route::patch(
        '/admin/vehicles/{vehicle}/approve',
        [AdminVehicleController::class, 'approve']
    );

    Route::patch(
        '/admin/vehicles/{vehicle}/reject',
        [AdminVehicleController::class, 'reject']
    );

    // ========================================
    // Booking Routes
    // ========================================

    Route::get('/bookings', [BookingController::class, 'index']);

    Route::post('/bookings', [BookingController::class, 'store']);

    Route::get('/bookings/{booking}', [BookingController::class, 'show']);

    Route::put('/bookings/{booking}', [BookingController::class, 'update']);

    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy']);
});

// ========================================
// Public Vehicle Routes
// ========================================

Route::get('/vehicles', [VehicleController::class, 'index']);

Route::get('/vehicles/{vehicle}', [VehicleController::class, 'show']);
