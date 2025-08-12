<?php
//create Databuilder using OrderTrackingDTO
namespace Modules\OrderManagement\DataBuilder;

use Illuminate\Http\Request;
use Modules\OrderManagement\DTO\OrderTrackingDTO;

class OrderTrackingDataBuilder
{
    public static function getDtoData(Request $request): OrderTrackingDTO
    {
        return new OrderTrackingDTO(
            order_id: $request->order_id,
            order_action_by: $request->order_action_by,
            date: $request->date,
            order_status: $request->order_status,
            remarks: $request->remarks
        );
    }
}
