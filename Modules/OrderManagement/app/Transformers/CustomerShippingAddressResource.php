<?php

namespace Modules\OrderManagement\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CustomerShippingAddressResource extends JsonResource
{
    /**
     * @OA\Schema(
     * schema="CustomerShippingAddressResourceSchema",
     * title="Customer Shipping Address Resource Schema",
     * description="A schema for a single instance of a customer shipping address resource.",
     * @OA\Property(
     * property="id",
     * type="integer",
     * format="int64",
     * description="The unique identifier for the resource.",
     * example=1
     * ),
     * @OA\Property(
     * property="customer",
     * ref="#/components/schemas/CustomerResourceSchema"
     * ),
     * @OA\Property(
     * property="recipient_name",
     * type="string",
     * description="The name of the recipient.",
     * example="Jane Doe"
     * ),
     * @OA\Property(
     * property="phone",
     * type="string",
     * description="The phone number of the recipient.",
     * example="123-456-7890"
     * ),
     * @OA\Property(
     * property="address",
     * type="string",
     * description="The address of the recipient.",
     * example="123 Main St"
     * ),
     * @OA\Property(
     * property="city",
     * type="string",
     * description="The city of the recipient.",
     * example="Anytown"
     * ),
     * @OA\Property(
     * property="state",
     * type="string",
     * description="The state of the recipient.",
     * example="CA"
     * ),
     * @OA\Property(
     * property="postal_code",
     * type="string",
     * description="The postal code of the recipient.",
     * example="12345"
     * ),
     * @OA\Property(
     * property="country",
     * type="string",
     * description="The country of the recipient.",
     * example="USA"
     * ),
     * @OA\Property(
     * property="longitude",
     * type="string",
     * description="The longitude coordinate of the delivery location.",
     * example="-123.456"
     * ),
     * @OA\Property(
     * property="latitude",
     * type="string",
     * description="The latitude coordinate of the delivery location.",
     * example="37.7749"
     * ),
     * @OA\Property(
     * property="status",
     * type="boolean",
     * description="The status of the shipping address.",
     * example=true
     * )
     * )
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'customer' => new CustomerResource($this->whenLoaded('customer')),
            'recipient_name' => $this->recipient_name,
            'phone' => $this->phone,
            'address' => $this->address,
            'city' => $this->city,
            'state' => $this->state,
            'postal_code' => $this->postal_code,
            'country' => $this->country,
            'longitude' => $this->longitude,
            'latitude' => $this->latitude,
            'status' => $this->status,
        ];
    }
}
