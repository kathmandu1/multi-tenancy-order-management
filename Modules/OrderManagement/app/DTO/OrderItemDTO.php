<?php

namespace Modules\OrderManagement\DTO;

final class OrderItemDTO
{
    public function __construct(
        public int $product_id,
        public int $quantity,
        // public ?float $price
    ) {}
}
