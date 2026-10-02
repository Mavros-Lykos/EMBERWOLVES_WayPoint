<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    /** @use HasFactory<\Database\Factories\VehicleFactory> */
    use HasFactory;

    protected $primaryKey = 'vehicle_id';
    protected $keyType = 'string';
    public $incrementing = false;

    public function trips()
    {
        return $this->hasMany(Trip::class, 'vehicle_id', 'vehicle_id');
    }
}
