<?php

namespace Modules\OrderManagement\Services\Order;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Modules\OrderManagement\Contracts\Order\Orderable;
use Modules\OrderManagement\Contracts\Order\Productable;
use Modules\OrderManagement\DTO\OrderDTO;
use Modules\OrderManagement\Events\OrderCreatedEvent;
use Modules\OrderManagement\Models\Order;
use Modules\OrderManagement\Models\OrderProduct;

class OrderService
{
    public function __construct(
        public  Orderable $orderable,
        public Productable $productable,
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
                'remark' => $orderDTO->remark,
                'delivery_date' => $orderDTO->delivery_date,
                'shipping_address_id' => $orderDTO->shipping_address_id,
            ];
            $order = $this->orderable->create($data);

            $totalOrderAmount = 0;
            foreach ($orderDTO->order_items as $item) {
                $orderProduct = $order->orderItems()->create($item);
                $totalOrderAmount += ($orderProduct->price * $item['quantity']);
            }

            $actualAmount = $totalOrderAmount - $order->total_discount_amount;
            $order->update([
                'total_order_amount' => $totalOrderAmount,
                'actual_amount' => $actualAmount,
            ]);
        } catch (Exception $exception) {
            DB::rollback();
            throw new Exception($exception);
        }
        DB::commit();
        return $order;
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

    public function updateOrderItems(array $items, int $orderId): Order
    {
        try {
            return DB::transaction(function () use ($orderId, $items) {
                $order = $this->orderable->getById($orderId);
                $currentProductIds = $order->orderItems()->pluck('product_id')->toArray();
                $newProductIds = collect($items)->pluck('product_id')->toArray();

                // Identify and remove items that are no longer in the new orderitems
                $itemsToRemove = array_diff($currentProductIds, $newProductIds);
                if (!empty($itemsToRemove)) {
                    $order->orderItems()->whereIn('product_id', $itemsToRemove)->delete();
                }

                // Add or update items based on the new orderitems
                $totalOrderAmount = 0;
                foreach ($items as $item) {
                    $orderProduct = OrderProduct::updateOrCreate(
                        [
                            'order_id' => $order->id,
                            'product_id' => $item['product_id'],
                        ],
                        [
                            'quantity' => $item['quantity'],
                        ]
                    );
                    $totalOrderAmount += ($orderProduct->price * $item['quantity']);
                }
                $actualAmount = $totalOrderAmount - $order->total_discount_amount;

                $order->update([
                    'total_order_amount' => $totalOrderAmount,
                    'actual_amount' => $actualAmount,
                ]);

                return  $order->fresh();
            });
        } catch (Exception $exception) {
            throw new Exception($exception);
        }
    }
}
