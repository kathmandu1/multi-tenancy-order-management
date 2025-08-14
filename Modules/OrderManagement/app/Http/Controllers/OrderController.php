<?php

namespace Modules\OrderManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\CentralApplication\Traits\ApiResponse;
use Modules\OrderManagement\DataBuilder\OrderDataBuilder;
use Modules\OrderManagement\DataBuilder\OrderItemDataBuilder;
use Modules\OrderManagement\Events\OrderCreatedEvent;
use Modules\OrderManagement\Http\Requests\OrderRequest;
use Modules\OrderManagement\Http\Requests\UpdateOrderItemsRequest;
use Modules\OrderManagement\Models\Order;
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
     *     summary="Get orders list, use parameter for filtering such as filter by customer, delivery date and deliver and pending orders",
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
     *      @OA\Parameter(
     *         name="delivery_date_from",
     *         in="query",
     *         description="Filter orders by delivery date from (YYYY-MM-DD)",
     *         required=false,
     *         @OA\Schema(
     *             type="date",
     *             example="2023-01-01"
     *         )
     *     ),
     *      @OA\Parameter(
     *         name="delivery_date_to",
     *         in="query",
     *         description="Filter orders by delivery date to (YYYY-MM-DD)",
     *         required=false,
     *         @OA\Schema(
     *             type="date",
     *             example="2023-01-01"
     *         )
     *     ),
     *      @OA\Parameter(
     *         name="customer_id",
     *         in="query",
     *         description="Filter orders by customer ID",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         )
     *     ),
     *      @OA\Parameter(
     *         name="status",
     *         in="query",
     *         description="Filter orders by status, 0 for pending, 1 for completed",
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
     *      @OA\Parameter(
     *         name="shipping_address",
     *         in="query",
     *         description="Shipping address for the order, it will return all order matching to shipping address",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             example="kathmandu"
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
            $eagerLoadWithRelationData = ['customer', 'orderItems', 'orderTracking', 'shippingAddress'];
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
     *     description="Create a new order for a customer inside a tenant.",
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
        try {
            $data =  $this->orderService->findById($id);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return  $this->errorResponse('Something went wrong', 500);
        }
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
     * @OA\Patch(
     * path="/api/orders/{order}/orderitems",
     * tags={"Orders"},
     * summary="Update items of an existing order, remove and add item from order until order is confirm",
     * description="Add, remove, or update quantities of items in an order.",
     * @OA\Parameter(
     * name="order",
     * in="path",
     * required=true,
     * @OA\Schema(type="integer"),
     * description="Order ID"
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * @OA\Property(
     * property="order_items",
     * type="array",
     * @OA\Items(
     * @OA\Property(property="product_id", type="integer", example=1),
     * @OA\Property(property="quantity", type="integer", example=3)
     * )
     * )
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Order items updated successfully",
     * @OA\JsonContent(
     * @OA\Property(property="status", type="string", example="success"),
     * @OA\Property(property="message", type="string", example="Order items updated successfully"),
     * @OA\Property(
     * property="data",
     * type="object",
     * ref="#/components/schemas/orderResourceSchema"
     * ),
     * )
     * ),
     * @OA\Response(
     * response=404,
     * description="Order not found"
     * ),
     * @OA\Parameter(
     * name="X-Tenant",
     * in="header",
     * required=true,
     * @OA\Schema(type="string"),
     * description="Tenant identifier",
     * example="71662abc-5751-4bcc-a61f-24a4ec7ef698"
     * )
     * )
     */
    public function updateOrderItems(UpdateOrderItemsRequest $request,  $orderID)
    {
        try {
            $updatedOrder = $this->orderService->updateOrderItems($request->order_items, $orderID);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return $this->errorResponse('Something went wrong', 500);
        }
        return $this->successResponse(new OrderResource($updatedOrder), 'Order items updated successfully');
    }
}
