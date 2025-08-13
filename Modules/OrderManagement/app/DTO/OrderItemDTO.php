<?php

namespace Modules\OrderManagement\DTO;

/**
 * @OA\Schema(
 * schema="orderItemSchema",
 * title="Order Item Schema",
 * description="A schema for creating a new order item.",
 * @OA\Property(property="product_id", type="integer", example=1),
 * @OA\Property(property="quantity", type="integer", example=2),
 * )
 */
final class OrderItemDTO
{
    public function __construct(
        public int $product_id,
        public int $quantity,
        // public ?float $price
        public ?int $order_id
    ) {}
}
