<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = ['provider_id','type','name','description','image','price_per_hour','pickup_location','status','rejection_reason'];

    public function provider() { return $this->belongsTo(User::class, 'provider_id'); }
    public function bookings() { return $this->hasMany(Booking::class); }
    public function availabilities() { return $this->hasMany(VehicleAvailability::class); }

}
