<?php

namespace Modules\OrderManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\OrderManagement\Database\Factories\OrderTrackingFactory;

class OrderTracking extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    // protected static function newFactory(): OrderTrackingFactory
    // {
    //     // return OrderTrackingFactory::new();
    // }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
