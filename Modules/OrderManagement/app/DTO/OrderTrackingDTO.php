<?php

namespace Modules\OrderManagement\DTO;

class OrderTrackingDTO
{
    public function __construct(
        public ?int $order_id,
        public ?int $order_action_by,
        public string $date,
        public string $order_status,
        public ?string $remarks
    ) {}
}
