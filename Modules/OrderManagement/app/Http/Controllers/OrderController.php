<?php

namespace Modules\OrderManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\CentralApplication\Traits\ApiResponse;
use Modules\OrderManagement\DataBuilder\OrderDataBuilder;
use Modules\OrderManagement\Http\Requests\OrderRequest;
use Modules\OrderManagement\Services\Order\OrderService;
use Modules\OrderManagement\Transformers\OrderResource;

class OrderController extends Controller
{
    use ApiResponse;

    public function __construct(
        public OrderService $orderService
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $eagerLoadWithRelationData = ['chartItem.chart', 'folder', 'folderItem', 'client', 'createBy', 'verifyBy'];
            $data = $this->orderService->getAll($request, $eagerLoadWithRelationData);
        } catch (Exception $e) {
            Log::error($e->getmessage());
            return  $this->errorResponse('Something went wrong', 500);
        }

        return $this->successResponse(OrderResource::collection($data), 'Chart Record data retrieved successfully');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderRequest $request)
    {
        $payload = $request->all();
        try {

            // Single object
            $createDataDTO = OrderDataBuilder::getDtoData($request);
            $data =  $this->orderService->store($createDataDTO);
        } catch (Exception $e) {
            dd($e);
            Log::error($e->getmessage());
            return  $this->errorResponse('Something went wrong', 500);
        }

        return  $this->successResponse(new OrderResource($data), 'Chart data store successfully', 201);
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $data =  $this->orderService->findById($id);
        return OrderResource::make($data);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(OrderRequest $request, $id)
    {

        try {
            $createDataDTO = OrderDataBuilder::getDtoData($request);
            $data =  $this->orderService->update($createDataDTO, $id);
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
