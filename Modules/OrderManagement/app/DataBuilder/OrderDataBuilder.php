<?php

namespace Modules\OrderManagement\DataBuilder;

use Illuminate\Http\Request;
use Modules\OrderManagement\DTO\CustomerDTO;
use Modules\OrderManagement\DTO\OrderDTO;

final class OrderDataBuilder
{
    public static function getDtoData(Request $request): OrderDTO
    {
        return new OrderDTO(
            customer_id: $request->customer_id,
            order_code: $request->total_order_amount,
            total_order_amount: $request->total_order_amount,
            total_discount_amount: $request->total_discount_amount,
            actual_amount: $request->actual_amount,
            status: $request->status,
            remark: $request->remark,
            order_date: $request->order_date,
            delivery_date: $request->delivery_date,
            shipping_address_id: $request->shipping_address_id,
            order_items: $request->order_items,
        );
    }
}
