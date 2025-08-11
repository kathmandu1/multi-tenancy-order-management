<?php

namespace Modules\OrderManagement\Services\Order;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Modules\OrderManagement\Contracts\Order\Orderable;
use Modules\OrderManagement\DTO\OrderDTO;
use Modules\OrderManagement\Models\Order;

class OrderService
{
    public function __construct(

        public  Orderable $orderable
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
            return $this->orderable
                ->getAll($pagination, null, null, $paginationNumber, $eagerLoadWithRelationData);
        } catch (Exception $exception) {
            throw  new Exception($exception);
        }
    }

    public function store(OrderDTO $orderDTO): Order
    {
        try {
            DB::beginTransaction();
            $data = [
                'customer_id' => $orderDTO->customer_id,
                'order_code' => $orderDTO->order_code,
                'total_order_amount' => $orderDTO->total_order_amount,
                'total_discount_amount' => $orderDTO->total_discount_amount,
                'actual_amount' => $orderDTO->actual_amount,
                'status' => $orderDTO->status,
                'remark' => $orderDTO->remark,
            ];

            $modelData = $this->orderable->create($data);
        } catch (Exception $exception) {
            dd($exception);
            DB::rollback();
            throw new Exception($exception);
        }
        DB::commit();
        return $modelData;
    }

    public function findById(int $id): Order
    {
        try {

            $modelData = $this->orderable->getById($id);
        } catch (Exception $exception) {
            throw new Exception($exception);
        }
        return $modelData;
    }

    public function update(OrderDTO $orderDTO, $id)
    {
        try {
            DB::beginTransaction();
            $data = [
                'customer_id' => $orderDTO->customer_id,
                'order_code' => $orderDTO->order_code,
                'total_order_amount' => $orderDTO->total_order_amount,
                'total_discount_amount' => $orderDTO->total_discount_amount,
                'actual_amount' => $orderDTO->actual_amount,
                'status' => $orderDTO->status,
                'remark' => $orderDTO->remark,
            ];

            $modelData = $this->orderable->update($id, $data);
        } catch (Exception $exception) {
            DB::rollBack();
            throw new Exception($exception);
        }
        DB::commit();
        return $modelData;
    }
}
