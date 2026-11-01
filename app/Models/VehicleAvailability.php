<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleAvailability extends Model
{
    protected $fillable = ['vehicle_id','day_of_week','start_time','end_time'];

    //
}
