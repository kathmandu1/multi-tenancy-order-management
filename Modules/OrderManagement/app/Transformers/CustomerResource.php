<?php

namespace Modules\OrderManagement\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
    /**
     * @OA\Schema(
     * schema="CustomerResourceSchema",
     * title="Customer Resource Schema",
     * description="A schema for a single instance of a customer resource.",
     * @OA\Property(
     * property="id",
     * type="integer",
     * format="int64",
     * description="The unique identifier for the resource.",
     * example=1
     * ),
     * @OA\Property(
     * property="name",
     * type="string",
     * description="The name of the customer.",
     * example="John Doe"
     * ),
     * @OA\Property(
     * property="email",
     * type="string",
     * format="email",
     * description="The email address of the customer.",
     * example="john.doe@example.com"
     * ),
     * @OA\Property(
     * property="phone",
     * type="string",
     * description="The phone number of the customer.",
     * example="123-456-7890"
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
     * ),
     * @OA\Property(
     * property="price_type",
     * type="string",
     * description="The pricing type of the customer.",
     * example="b2b price"
     * ),
     * @OA\Property(
     * property="shippings",
     * type="array",
     * @OA\Items(ref="#/components/schemas/CustomerShippingAddressResourceSchema")
     * )
     * )
     */

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'price_type' => $this->price_type,
            'shippings' => CustomerShippingAddressResource::collection($this->whenLoaded('shippingAddresses')),
        ];
    }
}
