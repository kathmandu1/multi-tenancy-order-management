<?php

namespace Modules\OrderManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\OrderManagement\Database\Factories\CustomerShippingAddressFactory;

class CustomerShippingAddress extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $guarded = [];

    protected static function newFactory(): CustomerShippingAddressFactory
    {
        return CustomerShippingAddressFactory::new();
    }
}
