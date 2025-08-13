<?php

namespace Modules\CentralApplication\Transformers;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TenantResource extends JsonResource
{
    /**
     * @OA\Schema(
     * schema="tenantResourceSchema",
     * title="Tenant Resource Schema",
     * description="A schema for a single instance of a tenant resource.",
     * @OA\Property(
     * property="id",
     * type="integer",
     * format="int64",
     * description="The unique identifier for the resource.",
     * example=1
     * ),
     * @OA\Property(
     * property="tenant_name",
     * type="string",
     * description="The name of the tenant.",
     * example="alibaba"
     * ),
     * @OA\Property(
     * property="email",
     * type="string",
     * format="email",
     * description="The email address of the tenant.",
     * example="alibaba@gmail.com"
     * ),
     * @OA\Property(
     * property="phone",
     * type="string",
     * description="The phone number of the tenant.",
     * example="123-456-7890"
     * ),
     * @OA\Property(
     * property="domains",
     * type="array",
     * @OA\Items(type="string"),
     * description="The domains associated with the tenant.",
     * example="alibaba.localhost"
     * )
     * )
     */

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tenant_name' => $this->tenant_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'domains' => $this->domains,
        ];
    }
}
