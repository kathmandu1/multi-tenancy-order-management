<?php

namespace Modules\OrderManagement\DTO;


final class CustomerShippingDTO
{
    public function __construct(
        public int $customer_id,
        public string $recipient_name,
        public string $phone,
        public string $address,
        public string $city,
        public ?string $state,
        public ?string $postal_code,
        public ?string $country,
        public ?string $longitude,
        public ?string $latitude,
        public bool $status
    ) {}
}
