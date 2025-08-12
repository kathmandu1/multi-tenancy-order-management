<?php

namespace Modules\OrderManagement\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class BaseRepository
{
    public function __construct(
        public Model $model
    ) {}


    public function getAll(
        bool $pagination = false,
        ?int $limit = null,
        ?string $orderBy = null,
        ?int $paginate = null,
        array $withRelations = [],
    ) {
        $query = $this->model->query();
        // Eager load relationships
        if (!empty($withRelations)) {
            $query->with($withRelations);
        }


        // Optional ordering
        if ($orderBy) {
            $query->orderBy($orderBy);
        }

        // Optional limit if not paginating
        if (!$pagination && $limit) {
            $query->limit($limit);
        }

        // Return paginated or full result
        if ($pagination) {
            return $query->paginate($paginate ?? 15); // default to 15 if null
        }

        return $query->get();
    }

    public function getById(int $id): ?Model
    {
        return $this->model->find($id);
    }

    public function create(array $data): Model
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): bool
    {
        $model = $this->getById($id);

        return $model ? $model->update($data) : false;
    }

    public function delete(int $id): bool
    {
        $model = $this->getById($id);

        return $model ? $model->delete() : false;
    }

    public function getWithTrashed(int $id): ?Model
    {
        return $this->model->withTrashed()->find($id);
    }

    public function getAllWithTrashed(): Collection
    {
        return $this->model->withTrashed()->get();
    }

    public function restore(int $id): bool
    {
        $model = $this->getWithTrashed($id);

        return $model ? $model->restore() : false;
    }

    public function firstOrCreate(array $searchColumn, array $updateColumn): Model
    {
        return $this->model->firstOrCreate($searchColumn, $updateColumn);
    }

    public function updateOrCreate(array $searchColumn, array $updateColumn)
    {
        return $this->model->updateOrCreate($searchColumn, $updateColumn);
    }

    public function where($conditions)
    {
        $query = $this->model;

        if (is_callable($conditions)) {
            // If a closure is passed
            $query = $query->where($conditions);
        } elseif (is_array($conditions)) {

            // If an array of conditions is passed
            foreach ($conditions as $field => $value) {
                $query = $query->where($field, $value);
            }
        }
        return $query;
    }
}
