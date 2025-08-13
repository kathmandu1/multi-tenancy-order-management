<?php

namespace Modules\OrderManagement\DataBuilder;

use Illuminate\Http\Request;
use Modules\OrderManagement\DTO\CustomerDTO;
use Modules\OrderManagement\DTO\OrderDTO;
use Modules\OrderManagement\DTO\OrderItemDTO;

final class OrderItemDataBuilder
{
    public static function getDtoData(Request $request): OrderItemDTO
    {
        return new OrderItemDTO(
            product_id: $request->product_id,
            quantity: $request->quantity,
            order_id: $request->order_id,
        );
    }
}
