<?php

namespace Modules\OrderManagement\Repositories;

use Illuminate\Pipeline\Pipeline;
use Modules\OrderManagement\Contracts\Customerable;
use Modules\OrderManagement\Models\Customer;

class CustomerRepository extends BaseRepository implements Customerable
{
    public function __construct(
        public Customer $customer
    ) {
        parent::__construct($customer);
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
    public function getById(int $id): ?Customer
    {
        return $this->model->find($id);
    }
}
