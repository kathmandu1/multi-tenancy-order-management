<?php

namespace Modules\OrderManagement\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    /**
     * @OA\Schema(
     * schema="orderItemsResourceSchema",
     * title="Order Item Resource Schema",
     * description="A schema for a single instance of an order item resource.",
     * @OA\Property(
     *  property="order_id",
     *  type="integer",
     *  format="int64",
     *  description="The ID of the associated order.",
     *  example=1
     * ),
     * @OA\Property(
     *  property="product_id",
     *  type="integer",
     *  format="int64",
     *  description="The ID of the associated product.",
     *  example=1
     * ),
     * @OA\Property(
     *  property="quantity",
     *  type="integer",
     *  format="int64",
     *  description="The quantity of the product ordered.",
     *  example=2
     * ),
     * @OA\Property(
     *  property="price",
     *  type="integer",
     *  format="int64",
     *  description="The price of the product.",
     *  example=100
     * ),
     * @OA\Property(
     *  property="subtotal",
     *  type="integer",
     *  format="int64",
     *  description="The subtotal for the item (quantity * price).",
     *  example=200
     * ),
     * @OA\Property(
     *  property="status",
     *  type="integer",
     *  format="int64",
     *  description="The status of the order item.",
     *  example=200
     * )
     * )
     */
    public function toArray(Request $request): array
    {
        return [
            'order_id' => $this->order_id,
            'product_id' => $this->product_id,
            'quantity' => $this->quantity,
            'price' => $this->price,
            'subtotal' => $this->subtotal,
            'status' => $this->status,
        ];
    }
}
