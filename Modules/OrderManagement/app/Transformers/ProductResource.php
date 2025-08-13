<?php

namespace Modules\OrderManagement\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
  /**
     * @OA\Schema(
     * schema="ProductResourceSchema",
     * title="Product Resource Schema",
     * description="A schema for a single instance of a product resource.",
     * @OA\Property(
     * property="id",
     * type="integer",
     * format="int64",
     * description="The unique identifier for the resource.",
     * example=1
     * ),
     * @OA\Property(
     * property="product_name",
     * type="string",
     * description="The name of the product.",
     * example="Sample Product"
     * ),
     * @OA\Property(
     * property="meta_title",
     * type="string",
     * description="The meta title of the product.",
     * example="This is a sample product."
     * ),
     * @OA\Property(
     * property="meta_description",
     * type="string",
     * description="The meta description of the product.",
     * example="This is a sample product."
     * ),
     * @OA\Property(
     * property="meta_keywords",
     * type="string",
     * description="The meta keywords of the product.",
     * example="product, stock"
     * ),
     * @OA\Property(
     * property="remarks",
     * type="string",
     * description="Any additional remarks about the product.",
     * example="This is a sample product."
     * ),
     * @OA\Property(
     * property="status",
     * type="string",
     * description="The status of the product.",
     * example="active"
     * ),
     * @OA\Property(
     * property="created_at",
     * type="string",
     * format="date-time",
     * description="The creation date and time of the resource.",
     * example="2023-01-01T00:00:00Z"
     * ),
     * @OA\Property(
     * property="updated_at",
     * type="string",
     * format="date-time",
     * description="The last update date and time of the resource.",
     * example="2023-01-01T00:00:00Z"
     * )
     * )
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'product_name' => $this->product_name,
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'meta_keywords' => $this->meta_keywords,
            'remarks' => $this->remarks,
            'status' => $this->status,
            'productVariant' => new ProductVariantResource($this->whenLoaded('productVariant')),
        ];
    }

}
