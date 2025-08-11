<?php

namespace Modules\OrderManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\CentralApplication\Traits\ApiResponse;
use Modules\OrderManagement\DataBuilder\ProductDataBuilder;
use Modules\OrderManagement\Http\Requests\ProductRequest;
use Modules\OrderManagement\Services\Order\ProductService;
use Modules\OrderManagement\Transformers\ProductResource;

class ProductController extends Controller
{
    use ApiResponse;

    public function __construct(
        public ProductService $productService
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $eagerLoadWithRelationData = ['chartItem.chart', 'folder', 'folderItem', 'client', 'createBy', 'verifyBy'];
            $data = $this->productService->getAll($request, $eagerLoadWithRelationData);
        } catch (Exception $e) {
            // dd($e);
            Log::error($e->getmessage());
            return  $this->errorResponse('Something went wrong', 500);
        }

        return $this->successResponse(ProductResource::collection($data), 'Chart Record data retrieved successfully');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        $payload = $request->all();
        try {

            // Single object
            $createDataDTO = ProductDataBuilder::getDtoData($request);
            $data =  $this->productService->store($createDataDTO);
        } catch (Exception $e) {
            dd($e);
            Log::error($e->getmessage());
            return  $this->errorResponse('Something went wrong', 500);
        }

        return  $this->successResponse(new ProductResource($data), 'Chart data store successfully', 201);
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $data =  $this->productService->findById($id);
        return ProductResource::make($data);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, $id)
    {

        try {
            $createDataDTO = ProductDataBuilder::getDtoData($request);
            $data =  $this->productService->update($createDataDTO, $id);
        } catch (Exception $e) {
            Log::error($e->getmessage());
            return  $this->errorResponse('Something went wrong', 500);
        }
        return  $this->successResponse($data, 'Chart update successfully', 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
