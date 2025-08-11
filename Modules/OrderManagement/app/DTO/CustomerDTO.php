<?php

namespace Modules\OrderManagement\DTO;

final class CustomerDTO
{
    public function __construct(
        public string $name,
        public ?string $address,
        public ?string $phone,
        public ?string $email,
        public ?string $price_type,

    ) {}
}
