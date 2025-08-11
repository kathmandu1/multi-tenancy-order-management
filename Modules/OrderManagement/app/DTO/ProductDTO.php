<?php

namespace Modules\OrderManagement\DTO;

final class ProductDTO
{
    public function __construct(
        public string $product_name,
        public ?string $meta_title,
        public ?string $meta_description,
        public ?string $meta_keywords,
        public ?string $remarks,
        public ?bool $status,

    ) {}
}
