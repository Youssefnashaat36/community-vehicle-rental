<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AdminVehicleController;

// Authentication Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    // Authentication
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);

});

// Vehicle Routes
Route::get('/vehicles', [VehicleController::class, 'index']);
Route::get('/vehicles/{vehicle}', [VehicleController::class, 'show']);
Route::post('/vehicles', [VehicleController::class, 'store'])
    ->middleware('auth:sanctum');

// Booking Routes
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/bookings', [BookingController::class, 'index']);
    Route::post('/bookings', [BookingController::class, 'store']);
    Route::get('/bookings/{booking}', [BookingController::class, 'show']);
    Route::put('/bookings/{booking}', [BookingController::class, 'update']);
    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy']);

});

// Admin Vehicle Routes
Route::middleware('auth:sanctum')->group(function () {

    Route::patch(
        '/admin/vehicles/{vehicle}/approve',
        [AdminVehicleController::class, 'approve']
    );

    Route::patch(
        '/admin/vehicles/{vehicle}/reject',
        [AdminVehicleController::class, 'reject']
    );

    Route::get(
        '/admin/vehicles/pending',
        [AdminVehicleController::class, 'pending']
    );

});
