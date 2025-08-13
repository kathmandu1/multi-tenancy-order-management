<?php

namespace Modules\OrderManagement\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantResource extends JsonResource
{
    /**
     * @OA\Schema(
     * schema="ProductVariantResourceSchema",
     * title="Product Variant Resource Schema",
     * description="A schema for a single instance of a product variant resource.",
     * @OA\Property(
     * property="id",
     * type="integer",
     * format="int64",
     * description="The unique identifier for the resource.",
     * example=1
     * ),
     * @OA\Property(
     * property="product_id",
     * type="integer",
     * format="int64",
     * description="The ID of the associated product.",
     * example=1
     * ),
     * @OA\Property(
     * property="base_price",
     * type="number",
     * format="float",
     * description="The base price of the product variant.",
     * example=99.99
     * ),
     * @OA\Property(
     * property="b2b_price",
     * type="number",
     * format="float",
     * description="The B2B price of the product variant.",
     * example=89.99
     * ),
     * @OA\Property(
     * property="b2c_price",
     * type="number",
     * format="float",
     * description="The B2C price of the product variant.",
     * example=109.99
     * ),
     * @OA\Property(
     * property="available_stock",
     * type="integer",
     * format="int32",
     * description="The available stock quantity of the product variant.",
     * example=100
     * ),
     * @OA\Property(
     * property="batch_no",
     * type="string",
     * description="The batch number of the product variant.",
     * example="BATCH123"
     * ),
     * @OA\Property(
     * property="lot_no",
     * type="string",
     * description="The lot number of the product variant.",
     * example="LOT456"
     * ),
     * @OA\Property(
     * property="keyword",
     * type="string",
     * description="A keyword associated with the product variant.",
     * example="example-keyword"
     * )
     * )
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'base_price' => $this->base_price,
            'b2b_price' => $this->b2b_price,
            'b2c_price' => $this->b2c_price,
            'available_stock' => $this->available_stock,
            'batch_no' => $this->batch_no,
            'lot_no' => $this->lot_no,
            'keyword' => $this->keyword,
        ];
    }
}
