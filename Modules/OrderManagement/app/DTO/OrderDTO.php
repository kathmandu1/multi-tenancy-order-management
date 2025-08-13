<?php

namespace Modules\OrderManagement\DTO;

use InvalidArgumentException;

final class OrderDTO
{
    /**
     * @OA\Schema(
     * schema="customerOrderSchema",
     * title="Customer Order Schema",
     * description="A schema for creating a new customer order.",
     * @OA\Property(property="customer_id", type="integer", example=1),
     * @OA\Property(property="order_code", type="string", example="ORD-12345"),
     * @OA\Property(property="order_date", type="string", format="date", example="2023-01-01"),
     * @OA\Property(property="delivery_date", type="string", format="date", example="2023-01-05"),
     * @OA\Property(property="shipping_address_id", type="integer", example=1),
     * @OA\Property(property="total_order_amount", type="number", format="float", example=100.00),
     * @OA\Property(property="total_discount_amount", type="number", format="float", example=10.00),
     * @OA\Property(property="actual_amount", type="number", format="float", example=90.00),
     * @OA\Property(property="status", type="boolean", example=true),
     * @OA\Property(property="remark", type="string", example="Please deliver between 9 AM and 5 PM"),
     * @OA\Property(property="order_items", type="array",
     *     @OA\Items(ref="#/components/schemas/orderItemSchema")
     * )
     * )
     */
    public function __construct(
        public int $customer_id,
        public ?string $order_code,
        public ?string $order_date,
        public string $delivery_date,
        public int $shipping_address_id,
        public ?float $total_order_amount,
        public ?float $total_discount_amount,
        public ?float $actual_amount,
        public ?bool $status,
        public ?string $remark,
        public array $order_items = []

    ) {
        if ($this->customer_id <= 0) {
            throw new InvalidArgumentException("Customer ID must be a positive integer.");
        }

        // foreach ($this->order_items as $item) {
        //     if (!($item instanceof OrderItemDTO)) {
        //         throw new InvalidArgumentException("Each order item must be an instance of OrderItemDTO.");
        //     }
        // }
    }
}
