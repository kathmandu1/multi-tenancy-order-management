<?php

namespace Modules\OrderManagement\Repositories\Order;

use Illuminate\Pipeline\Pipeline;
use Modules\OrderManagement\Contracts\Order\Orderable;
use Modules\OrderManagement\Models\Order;
use Modules\OrderManagement\Pipelines\Order\CustomerFilterPipe;
use Modules\OrderManagement\Pipelines\Order\DeliveryAddressFilterPipe;
use Modules\OrderManagement\Pipelines\Order\DeliveryDateFilterPipe;
use Modules\OrderManagement\Pipelines\Order\StatusFilterPipe;
use Modules\OrderManagement\Pipelines\OrderTracking\OrderStatusFilterPipe;
use Modules\OrderManagement\Repositories\BaseRepository;

class OrderRepository extends BaseRepository implements Orderable
{
    public function __construct(
        public Order $order
    ) {
        parent::__construct($order);
    }

    public function getAll(
        bool $pagination = false,
        ?int $limit = null,
        ?string $orderBy = null,
        ?int $paginate = null,
        array $withRelations = [],
    ) {
        $query = app(Pipeline::class)
            ->send($this->model->newQuery())
            ->through([
                DeliveryDateFilterPipe::class,
                StatusFilterPipe::class,
                CustomerFilterPipe::class,
                DeliveryAddressFilterPipe::class,
            ])
            ->thenReturn();

        if (!empty($withRelations)) {
            $query->with($withRelations);
        }

        if ($orderBy) {
            $query->orderBy($orderBy);
        }

        if (!$pagination && $limit) {
            $query->limit($limit);
        }

        return $pagination
            ? $query->paginate($paginate ?? 05)
            : $query->get();
    }

    public function getById(int $id): ?Order
    {
        return $this->model->with(['customer', 'orderItems', 'orderTracking', 'shippingAddress'])->find($id);
    }
}
