<?php

namespace Modules\OrderManagement\Services;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Modules\OrderManagement\Contracts\Customerable;
use Modules\OrderManagement\DTO\CustomerDTO;
use Modules\OrderManagement\Models\Customer;

class CustomerService
{
    public function __construct(

        public  Customerable $customerable
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
            return $this->customerable
                ->getAll($pagination, null, null, $paginationNumber, $eagerLoadWithRelationData);
        } catch (Exception $exception) {
            throw  new Exception($exception);
        }
    }

    public function store(CustomerDTO $customerDTO): Customer
    {
        try {
            DB::beginTransaction();
          $data = [
                'name' => $customerDTO->name,
                'address' => $customerDTO->address,
                'phone' => $customerDTO->phone,
                'email' => $customerDTO->email,
                'price_type' => $customerDTO->price_type,
            ];



            $modelData = $this->customerable->create($data);
        } catch (Exception $exception) {
            dd($exception);
            DB::rollback();
            throw new Exception($exception);
        }
        DB::commit();
        return $modelData;
    }

    public function findById(int $id): Customer
    {
        try {

            $modelData = $this->customerable->getById($id);
        } catch (Exception $exception) {
            throw new Exception($exception);
        }
        return $modelData;
    }

    public function update(CustomerDTO $customerDTO, $id)
    {
        try {
            DB::beginTransaction();
            $data = [
                'name' => $customerDTO->name,
                'address' => $customerDTO->address,
                'phone' => $customerDTO->phone,
                'email' => $customerDTO->email,
                'price_type' => $customerDTO->price_type,
            ];


            $modelData = $this->customerable->update($id, $data);
        } catch (Exception $exception) {
            DB::rollBack();
            throw new Exception($exception);
        }
        DB::commit();
        return $modelData;
    }
}
