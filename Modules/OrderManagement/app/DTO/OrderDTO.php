<?php

namespace Modules\OrderManagement\DTO;

use InvalidArgumentException;

final class OrderDTO
{
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
