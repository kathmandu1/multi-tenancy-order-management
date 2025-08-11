<?php

namespace Modules\OrderManagement\DTO;

final class OrderDTO
{
    public function __construct(
        public int $customer_id,
        public string $order_code,
        public float $total_order_amount,
        public ?float $total_discount_amount,
        public float $actual_amount,
        public ?bool $status,
        public ?string $remark,

    ) {}
}
