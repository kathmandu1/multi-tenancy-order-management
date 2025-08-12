<?php

namespace Modules\OrderManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\CentralApplication\Traits\ApiResponse;
use Modules\OrderManagement\DataBuilder\OrderDataBuilder;
use Modules\OrderManagement\Events\OrderCreatedEvent;
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
     * @OA\Get(
     *     path="/api/orders",
     *     summary="Get orders list",
     *     description="Returns a list of orders. Supports enabling or disabling pagination.",
     *     operationId="getOrders",
     *     tags={"Tenants", "Orders"},
     *     @OA\Parameter(
     *         name="pagination",
     *         in="query",
     *         description="Enable or disable pagination (true or false)",
     *         required=false,
     *         @OA\Schema(
     *             type="boolean",
     *             example=1
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="X-Tenant",
     *         in="header",
     *         description="Id of Tenant",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             example="71662abc-5751-4bcc-a61f-24a4ec7ef698"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of orders",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="product_name", type="string", example="Apple pro max"),
     *                     @OA\Property(property="meta_title", type="string", example="Ios"),
     *                     @OA\Property(property="meta_description", type="string", example="smartphone, cellphone"),
     *                     @OA\Property(property="status", type="string", example="true"),
     *                     @OA\Property(property="remarks", type="string", example="product sample")
     *                 )
     *             ),
     *             @OA\Property(property="meta", type="object",
     *                 @OA\Property(property="total", type="integer", example=100),
     *                 @OA\Property(property="per_page", type="integer", example=10),
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="last_page", type="integer", example=10)
     *             )
     *         )
     *     )
     * )
     */

    public function index(Request $request)
    {
        try {
            $eagerLoadWithRelationData = [];
            $data = $this->orderService->getAll($request, $eagerLoadWithRelationData);
        } catch (Exception $e) {
            Log::error($e->getmessage());
            return  $this->errorResponse('Something went wrong', 500);
        }

        return $this->successResponse(OrderResource::collection($data), 'Order data retrieved successfully');
    }


    /**
     * @OA\Post(
     *     path="/api/orders",
     *     tags={"Tenants", "Orders"},
     *     summary="Create a new customer order",
     *     description="Creates a new order for a customer inside a tenant.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="customer_id",
     *                 type="integer",
     *                 example=1
     *             ),
     *             @OA\Property(
     *                 property="remark",
     *                 type="string",
     *                 example="Sample remark"
     *             ),
     *             @OA\Property(
     *                 property="order_items",
     *                 type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(
     *                         property="product_id",
     *                         type="integer",
     *                         example=1
     *                     ),
     *                     @OA\Property(
     *                         property="quantity",
     *                         type="integer",
     *                         example=4
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Order created successfully"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid input"
     *     ),
     *     @OA\Parameter(
     *         name="X-Tenant",
     *         in="header",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         description="Tenant identifier",
     *         example="71662abc-5751-4bcc-a61f-24a4ec7ef698"
     *     )
     * )
     */

    public function store(OrderRequest $request)
    {
        try {
            $createDataDTO = OrderDataBuilder::getDtoData($request);
            $order =  $this->orderService->store($createDataDTO);
            event(new OrderCreatedEvent($order));
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return  $this->errorResponse('Something went wrong', 500);
        }

        return  $this->successResponse(new OrderResource($order), 'Order data store successfully', 201);
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
        return  $this->successResponse($data, 'Order update successfully', 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
