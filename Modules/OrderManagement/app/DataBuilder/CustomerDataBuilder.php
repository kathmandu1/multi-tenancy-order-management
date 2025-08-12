<?php

namespace Modules\OrderManagement\DataBuilder;

use Illuminate\Http\Request;
use Modules\OrderManagement\DTO\CustomerDTO;

final class CustomerDataBuilder
{
    public static function getDtoData(Request $request): CustomerDTO
    {
        return new CustomerDTO(
            name: $request->name,
            address: $request->address,
            phone: $request->phone,
            email: $request->email,
            price_type: $request->price_type,
        );
    }
}

