<?php

namespace Modules\OrderManagement\Repositories;

use Illuminate\Pipeline\Pipeline;
use Modules\OrderManagement\Contracts\Shippable;
use Modules\OrderManagement\Models\Customer;
use Modules\OrderManagement\Models\CustomerShippingAddress;

class ShippingRepository extends BaseRepository implements Shippable
{
    public function __construct(
        public CustomerShippingAddress $customerShippingAddress
    ) {
        parent::__construct($customerShippingAddress);
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
                // ClientFilter::class,
                // Add more filters here if needed
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
    public function getById(int $id): ?CustomerShippingAddress
    {
        return $this->model->find($id);
    }
}
