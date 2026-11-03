<?php

use App\Http\Controllers\VehicleController;
use App\Http\Controllers\BookingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

//Vehicle routes
Route::get('/vehicles', [VehicleController::class, 'index']);
Route::get('/vehicles/{vehicle}', [VehicleController::class, 'show']);
Route::post('/vehicles', [VehicleController::class, 'store']);

// Booking routes
Route::get('/bookings', [BookingController::class, 'index']);
Route::post('/bookings', [BookingController::class, 'store']);
Route::get('/bookings/{booking}', [BookingController::class, 'show']);
Route::put('/bookings/{booking}', [BookingController::class, 'update']);
Route::delete('/bookings/{booking}', [BookingController::class, 'destroy']);
use App\Http\Controllers\AdminVehicleController;
// Admin vehicle routes
Route::patch('/admin/vehicles/{vehicle}/approve', [AdminVehicleController::class, 'approve']);
Route::patch(
    '/admin/vehicles/{vehicle}/reject',
    [AdminVehicleController::class, 'reject']
);
Route::get('/admin/vehicles/pending', [AdminVehicleController::class, 'pending']);