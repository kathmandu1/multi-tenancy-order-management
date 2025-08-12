<?php
//create Databuilder using OrderTrackingDTO
namespace Modules\OrderManagement\DataBuilder;

use Illuminate\Http\Request;
use Modules\OrderManagement\DTO\CustomerShippingDTO;

class CustomerShippingDataBuilder
{
    public static function getDtoData(Request $request): CustomerShippingDTO
    {
        return new CustomerShippingDTO(
            customer_id: $request->customer_id,
            recipient_name: $request->recipient_name,
            phone: $request->phone,
            address: $request->address,
            city: $request->city,
            state: $request->state,
            postal_code: $request->postal_code,
            country: $request->country,
            longitude: $request->longitude,
            latitude: $request->latitude,
            status: $request->status
        );
    }
}
