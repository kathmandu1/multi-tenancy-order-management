<?php

namespace Modules\OrderManagement\Services\Order;

use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Modules\OrderManagement\Contracts\Order\Productable;
use Modules\OrderManagement\DTO\ProductDTO;
use Modules\OrderManagement\Models\Product;

class ProductService
{
    public function __construct(

        public  Productable $productable
    ) {}

    public function getAll($request, $eagerLoadWithRelationData = []): Collection|LengthAwarePaginator
    {

        try {
            $pagination = false;
            $paginationNumber = null;
            if ($request->has('pagination') && $request->pagination == true) {
                $pagination = $request->pagination;
                $paginationNumber = 10;
            }
            return $this->productable
                ->getAll($pagination, null, null, $paginationNumber, $eagerLoadWithRelationData);
        } catch (Exception $exception) {
            throw  new Exception($exception);
        }
    }

    public function store(ProductDTO $productDTO): Product
    {
        try {
            DB::beginTransaction();
            $data = [
                'product_name' => $productDTO->product_name,
                'meta_title' => $productDTO->meta_title,
                'meta_description' => $productDTO->meta_description,
                'meta_keywords' => $productDTO->meta_keywords,
                'remarks' => $productDTO->remarks,
                'status' => $productDTO->status,
            ];

            $product = $this->productable->create($data);
            $product->productVariant()->create([
                'base_price' => $productDTO->base_price,
                'b2b_price' => $productDTO->b2b_price,
                'b2c_price' => $productDTO->b2c_price,
                'batch_no' => $productDTO->batch_no,
                'lot_no' => $productDTO->lot_no,
                'available_stock' => $productDTO->available_stock
            ]);
        } catch (Exception $exception) {
            DB::rollback();
            throw new Exception($exception);
        }
        DB::commit();
        return $product;
    }

    public function findById(int $id): Product
    {
        try {

            $modelData = $this->productable->getById($id);
        } catch (Exception $exception) {
            throw new Exception($exception);
        }
        return $modelData;
    }

    public function update(ProductDTO $productDTO, $id)
    {
        try {
            DB::beginTransaction();
            $data = [
                'product_name' => $productDTO->product_name,
                'meta_title' => $productDTO->meta_title,
                'meta_description' => $productDTO->meta_description,
                'meta_keywords' => $productDTO->meta_keywords,
                'remarks' => $productDTO->remarks,
                'status' => $productDTO->status,

            ];


            $modelData = $this->productable->update($id, $data);
        } catch (Exception $exception) {
            DB::rollBack();
            throw new Exception($exception);
        }
        DB::commit();
        return $modelData;
    }

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            $modelData = $this->productable->delete($id);
        } catch (Exception $exception) {
            DB::rollBack();
            throw new Exception($exception);
        }
        DB::commit();
        return $modelData;
    }
}
