<?php

namespace Modules\OrderManagement\Repositories\Order;

use Illuminate\Pipeline\Pipeline;
use Modules\OrderManagement\Contracts\Order\OrderTrackable;
use Modules\OrderManagement\Models\OrderTracking;
use Modules\OrderManagement\Pipelines\OrderTracking\OrderFilterPipe;
use Modules\OrderManagement\Repositories\BaseRepository;

class OrderTrackingRepository extends BaseRepository   implements OrderTrackable
{
    public function __construct(
        public OrderTracking $orderTracking
    ) {
        parent::__construct($orderTracking);
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
                OrderFilterPipe::class,
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
            ? $query->paginate($paginate ?? 15)
            : $query->get();
    }

    public function getById(int $id): OrderTracking
    {
        return $this->model->find($id);
    }
}
