<?php

namespace Modules\OrderManagement\DTO;

/**
 * @OA\Schema(
 * schema="customerCreateSchema",
 * title="Customer Create Schema",
 * description="A schema for creating a new customer.",
 * @OA\Property(property="name", type="string", example="Ali Baba"),
 * @OA\Property(property="address", type="string", nullable=true, example="123 Main St, City"),
 * @OA\Property(property="phone", type="string", nullable=true, example="+977-9800000000"),
 * @OA\Property(property="email", type="string", nullable=true, format="email", example="alibaba@example.com"),
 * @OA\Property(property="price_type", type="string", nullable=true, example="b2b price")
 * )
 */
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
