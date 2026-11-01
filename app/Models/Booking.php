<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id','vehicle_id','start_at','end_at','total_price','status','is_overdue','reminder_sent_at'];

    protected $casts = ['start_at' => 'datetime', 'end_at' => 'datetime', 'is_overdue' => 'boolean'];
public function customer() { return $this->belongsTo(User::class, 'customer_id'); }
public function vehicle()  { return $this->belongsTo(Vehicle::class); }

}