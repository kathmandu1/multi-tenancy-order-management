<?php

namespace Modules\OrderManagement\DTO;

final class ProductDTO
{
    /**
     * @OA\Schema(
     * schema="productCreateSchema",
     * title="Product Create Schema",
     * description="A schema for creating a new product.",
     * @OA\Property(property="product_name", type="string", example="i phoneBaba"),
     * @OA\Property(property="meta_title", type="string", nullable=true, example="product"),
     * @OA\Property(property="meta_description", type="string", nullable=true, example="This is a sample product"),
     * @OA\Property(property="meta_keywords", type="string", nullable=true, format="email", example="product, stock"),
     * @OA\Property(property="base_price", type="string", nullable=true, example="1500"),
     * @OA\Property(property="b2b_price", type="string", nullable=true, example="1600"),
     * @OA\Property(property="b2c_price", type="string", nullable=true, example="2000"),
     * @OA\Property(property="batch_no", type="string", nullable=true, example="batch-001"),
     * @OA\Property(property="lot_no", type="string", nullable=true, example="lot-001"),
     * @OA\Property(property="available_stock", type="integer", nullable=true, example=100),
     * )
     */

    public function __construct(
        public string $product_name,
        public ?string $meta_title,
        public ?string $meta_description,
        public ?string $meta_keywords,
        public ?string $remarks,
        public ?bool $status,
        public ?float $base_price,
        public ?float $b2b_price,
        public ?float $b2c_price,
        public ?string $batch_no,
        public ?string $lot_no,
        public ?int $available_stock
    ) {}
}
