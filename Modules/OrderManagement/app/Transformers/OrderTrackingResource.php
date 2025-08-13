<?php

namespace Modules\OrderManagement\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderTrackingResource extends JsonResource
{
    /**
     * @OA\Schema(
     * schema="orderTrackingResourceSchema",
     * title="Order Tracking Resource Schema",
     * description="A schema for a single instance of an order tracking resource.",
     * @OA\Property(
     *  property="order_id",
     *  type="integer",
     *  format="int64",
     *  description="The ID of the associated order.",
     *  example=1
     * ),
     * @OA\Property(
     *  property="order_action_by",
     *  type="string",
     *  description="The user who performed the action.",
     *  example="admin"
     * ),
     * @OA\Property(
     *  property="date",
     *  type="string",
     *  format="date-time",
     *  description="The date and time when the action was performed.",
     *  example="2023-01-01T12:00:00Z"
     * ),
     * @OA\Property(
     *  property="order_status",
     *  type="string",
     *  description="The current status of the order.",
     *  example="shipped"
     * ),
     * @OA\Property(
     *  property="remarks",
     *  type="string",
     *  description="Any additional remarks about the order action.",
     *  example="Delivered successfully"
     * )
     * )
     */
    public function toArray(Request $request): array
    {
        return [
            'order_id' => $this->order_id,
            'order_action_by' => $this->order_action_by,
            'date' => $this->date,
            'order_status' => $this->order_status,
            'remarks' => $this->remarks,
        ];
    }
}
