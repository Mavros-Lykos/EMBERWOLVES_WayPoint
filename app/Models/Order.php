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

    public function trip()
    {
        return $this->belongsTo(Trip::class, 'trip_id', 'trip_id');
    }
}
