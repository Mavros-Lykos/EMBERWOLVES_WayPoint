<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    /** @use HasFactory<\Database\Factories\TripFactory> */
    use HasFactory;

    protected $primaryKey = 'trip_id';
    protected $keyType = 'string';
    public $incrementing = false;

    public function orders()
    {
        return $this->hasMany(Order::class, 'trip_id', 'trip_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id', 'vehicle_id');
    }
}
