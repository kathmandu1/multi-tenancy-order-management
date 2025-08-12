<?php

namespace Modules\OrderManagement\Repositories\Order;

use Illuminate\Pipeline\Pipeline;
use Modules\OrderManagement\Contracts\Order\Productable;
use Modules\OrderManagement\Models\Product;
use Modules\OrderManagement\Repositories\BaseRepository;

class ProductRepository extends BaseRepository implements Productable
{
    public function __construct(
        public Product $product
    ) {
        parent::__construct($product);
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
    public function getById(int $id): ?Product
    {
        return $this->model->with('productVariants')->find($id);
    }
}
