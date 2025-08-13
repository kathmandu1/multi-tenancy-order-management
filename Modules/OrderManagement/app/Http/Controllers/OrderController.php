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
     *     tags={"Orders"},
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
     *      @OA\Response(
     *         response=200,
     *         description="List of orders",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Order data retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/orderResourceSchema")
     *             ),
     *             @OA\Property(
     *                 property="meta",
     *                 ref="#/components/schemas/PaginationSchema"
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
     *     tags={"Orders"},
     *     summary="Create a new customer order",
     *     description="Creates a new order for a customer inside a tenant.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *          ref="#/components/schemas/customerOrderSchema"
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Order created successfully",
     *        @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Order created successfully"),
     *             @OA\Property(
     *                 property="data",
     *                  type="object",
     *                 ref="#/components/schemas/orderResourceSchema"
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="The name field is required.")
     *         )
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
     * @OA\Get(
     *     path="/api/orders/{id}",
     *     summary="Get order details",
     *     description="Returns the details of a specific order.",
     *     operationId="getOrder",
     *     tags={"Orders"},
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="The unique identifier of the order.",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
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
     *      @OA\Response(
     *         response=200,
     *         description="Details of the order",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Order retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                  type="object",
     *                 ref="#/components/schemas/orderResourceSchema"
     *             ),
     *         )
     *         )
     *     )
     * )
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

}
