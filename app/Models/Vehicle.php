<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehicle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'provider_id',
        'type',
        'name',
        'description',
        'image',
        'price_per_hour',
        'pickup_location',
        'status',
        'rejection_reason',
        'deletion_status',
    ];

    public function provider()
    {
        return $this->belongsTo(User::class, 'provider_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function availabilities()
    {
        return $this->hasMany(VehicleAvailability::class);
    }
    public function deletionRequests()
{
    return $this->hasMany(VehicleDeletionRequest::class);
}
}
