<?php

namespace Modules\OrderManagement\Services;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Modules\OrderManagement\Contracts\Shippable;
use Modules\OrderManagement\DTO\CustomerDTO;
use Modules\OrderManagement\DTO\CustomerShippingDTO;
use Modules\OrderManagement\Models\CustomerShippingAddress;

class CustomerShippingService
{
    public function __construct(

        public  Shippable $shippable
    ) {}

    public function getAll($request, $eagerLoadWithRelationData = []): Collection|LengthAwarePaginator
    {

        try {
            $pagination = false;
            $paginationNumber = null;
            if ($request->has('pagination') && $request->pagination) {
                $pagination = true;
                $paginationNumber = 10;
            }
            return $this->shippable
                ->getAll($pagination, null, null, $paginationNumber, $eagerLoadWithRelationData);
        } catch (Exception $exception) {
            throw  new Exception($exception);
        }
    }

    public function store(CustomerShippingDTO $customerShippingDTO): CustomerShippingAddress
    {
        try {
            DB::beginTransaction();
            $data = [
                'customer_id' => $customerShippingDTO->customer_id,
                'recipient_name' => $customerShippingDTO->recipient_name,
                'phone' => $customerShippingDTO->phone,
                'address' => $customerShippingDTO->address,
                'city' => $customerShippingDTO->city,
                'state' => $customerShippingDTO->state,
                'postal_code' => $customerShippingDTO->postal_code,
                'country' => $customerShippingDTO->country,
                'longitude' => $customerShippingDTO->longitude,
                'latitude' => $customerShippingDTO->latitude,
                'status' => $customerShippingDTO->status
            ];

            $modelData = $this->shippable->create($data);
        } catch (Exception $exception) {
            DB::rollback();
            throw new Exception($exception);
        }
        DB::commit();
        return $modelData;
    }

    public function findById(int $id): CustomerShippingAddress
    {
        try {

            $modelData = $this->shippable->getById($id);
        } catch (Exception $exception) {
            throw new Exception($exception);
        }
        return $modelData;
    }

    public function update(CustomerShippingDTO $customerShippingDTO, $id)
    {
        try {
            DB::beginTransaction();
            $data = [
                'customer_id' => $customerShippingDTO->customer_id,
                'recipient_name' => $customerShippingDTO->recipient_name,
                'phone' => $customerShippingDTO->phone,
                'address' => $customerShippingDTO->address,
                'city' => $customerShippingDTO->city,
                'state' => $customerShippingDTO->state,
                'postal_code' => $customerShippingDTO->postal_code,
                'country' => $customerShippingDTO->country,
                'longitude' => $customerShippingDTO->longitude,
                'latitude' => $customerShippingDTO->latitude,
                'status' => $customerShippingDTO->status
            ];


            $modelData = $this->shippable->update($id, $data);
        } catch (Exception $exception) {
            DB::rollBack();
            throw new Exception($exception);
        }
        DB::commit();
        return $modelData;
    }
}
