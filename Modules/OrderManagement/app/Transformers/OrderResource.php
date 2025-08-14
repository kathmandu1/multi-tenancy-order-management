<?php

namespace Modules\OrderManagement\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
     /**
     * @OA\Schema(
     * schema="orderResourceSchema",
     * title="Order Item Resource Schema",
     * description="A schema for a single instance of an order item resource.",
     * @OA\Property(
     *  property="id",
     *  type="integer",
     *  format="int64",
     *  description="The unique identifier of the order item.",
     *  example=1
     * ),
     * @OA\Property(
     *  property="order_code",
     *  type="string",
     *  description="The unique code of the order.",
     *  example="ORD123456"
     * ),
     * @OA\Property(
     *  property="delivery_date",
     *  type="string",
     *  format="date-time",
     *  description="The expected delivery date of the order.",
     *  example="2023-01-01 12:00:00"
     * ),
     * @OA\Property(
     *  property="total_order_amount",
     *  type="integer",
     *  format="int64",
     *  description="The total amount of the order.",
     *  example=1000
     * ),
     * @OA\Property(
     *  property="total_discount_amount",
     *  type="integer",
     *  format="int64",
     *  description="The total discount amount applied to the order.",
     *  example=100
     * ),
     * @OA\Property(
     *  property="actual_amount",
     *  type="integer",
     *  format="int64",
     *  description="The actual amount to be paid for the order.",
     *  example=200
     * ),
     * @OA\Property(
     *  property="status",
     *  type="string",
     *  description="The current status of the order.",
     *  example="pending"
     * ),
     * @OA\Property(
     *  property="remark",
     *  type="string",
     *  description="Any additional remarks about the order.",
     *  example="Please deliver between 5 PM and 7 PM."
     * ),
     * @OA\Property(
     *  property="customer",
     *  type="object",
     *  ref="#/components/schemas/CustomerResourceSchema"
     * ),
     * @OA\Property(
     *  property="shipping_address",
     *  type="object",
     *  ref="#/components/schemas/CustomerShippingAddressResourceSchema"
     * ),
     * @OA\Property(
     *  property="order_items",
     *  type="array",
     *  @OA\Items(ref="#/components/schemas/orderItemsResourceSchema")
     * ),
     * @OA\Property(
     *  property="order_tracking",
     *  type="array",
     *  @OA\Items(ref="#/components/schemas/orderTrackingResourceSchema")
     * )
     * )
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_code' => $this->order_code,
            'delivery_date' => $this->delivery_date,
            'total_order_amount' => $this->total_order_amount,
            'total_discount_amount' => $this->total_discount_amount,
            'actual_amount' => $this->actual_amount,
            'status' => $this->status,
            'remark' => $this->remark,
            'customer' => new CustomerResource($this->whenLoaded('customer')),
            'shipping_address' => new CustomerShippingAddressResource($this->whenLoaded('shippingAddress')),
            'order_items' => OrderItemResource::collection($this->whenLoaded('orderItems')),
            'order_tracking' => OrderTrackingResource::collection($this->whenLoaded('orderTracking')),

        ];
    }
}
