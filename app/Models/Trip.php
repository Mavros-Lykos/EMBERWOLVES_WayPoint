<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Trip extends Model
{
    use HasFactory;

    protected $primaryKey = 'trip_id';
    protected $keyType    = 'string';
    public    $incrementing = false;
    public    $timestamps   = false;

    protected $fillable = [
        'trip_id', 'vehicle_id', 'trip_number', 'operation_date',
        'brand', 'district', 'status', 'total_weight_kg', 'total_volume_m3',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id', 'vehicle_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'trip_id', 'trip_id');
    }

    public function routeLegs()
    {
        return $this->hasMany(RouteLeg::class, 'trip_id', 'trip_id')->orderBy('seq');
    }
}
