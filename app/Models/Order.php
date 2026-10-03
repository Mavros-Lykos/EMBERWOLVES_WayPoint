<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    protected $primaryKey = 'order_ref';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'order_ref', 'outlet_id', 'order_date', 'placed_at', 'temp_requirement',
        'order_units', 'order_weight_kg', 'order_volume_m3', 'status',
        'deferral_reason', 'trip_id', 'deferred_yesterday', 'days_since_last_served',
        'urgency_flag'
    ];

    public function trip()
    {
        return $this->belongsTo(Trip::class, 'trip_id', 'trip_id');
    }

    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'outlet_id', 'outlet_id');
    }
}
