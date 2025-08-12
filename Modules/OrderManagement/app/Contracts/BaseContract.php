<?php

namespace Modules\OrderManagement\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface BaseContract
{
    public function getAll(
        bool $pagination = false,
        ?int $limit = null,
        ?string $orderBy = null,
        ?int $paginate = null,
        array $withRelations = [],
    );

    public function getById(int $id): ?Model;

    public function where($conditions);

    public function create(array $data): Model;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;

    public function getWithTrashed(int $id): ?Model;

    public function getAllWithTrashed(): Collection;

    public function restore(int $id): bool;

    public function firstOrCreate(array $searchColumn, array $updateColumn): Model;

    public function updateOrCreate(array $searchColumn, array $updateColumn);

    // public function saveMediaAndFile($model, $file, $path,  $polymorphicRelation = false, $relationName);
}
