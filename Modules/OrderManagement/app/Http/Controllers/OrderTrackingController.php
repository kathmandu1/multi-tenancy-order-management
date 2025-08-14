<?php

namespace Modules\OrderManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\CentralApplication\Traits\ApiResponse;
use Modules\OrderManagement\DataBuilder\OrderTrackingDataBuilder;
use Modules\OrderManagement\Events\OrderCreatedEvent;
use Modules\OrderManagement\Events\OrderTrakingStatusCreate;
use Modules\OrderManagement\Exceptions\InvalidOrderTrackingStatusException;
use Modules\OrderManagement\Http\Requests\OrderTrackingRequest;
use Modules\OrderManagement\Services\Order\OrderTrackingService;
use Modules\OrderManagement\Transformers\OrderResource;
use Modules\OrderManagement\Transformers\OrderTrackingResource;

class OrderTrackingController extends Controller
{
    use ApiResponse;

    public function __construct(
        public OrderTrackingService $orderTrackingService,
    ) {}

    /**
     * @OA\Get(
     *     path="/api/orders/{id}/trackings",
     *     summary="Get order tracking information information regarding when order is processed, when order is delivered",
     *     description="Returns tracking information for a specific order.",
     *     operationId="getOrderTracking",
     *     tags={"Orders"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Order ID",
     *         @OA\Schema(type="integer", example=1)
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
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="tracking information retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/orderTrackingResourceSchema")
     *             ),
     *             @OA\Property(
     *                 property="meta",
     *                 ref="#/components/schemas/PaginationSchema"
     *             )
     *         )
     *     )
     * )
     */

    public function index(Request $request, $orderId)
    {
        try {
            $request->merge(['order_id' => $orderId]);
            $eagerLoadWithRelationData = [];
            $data = $this->orderTrackingService->getAll($request, $eagerLoadWithRelationData);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return  $this->errorResponse('Something went wrong', 500);
        }

        return $this->successResponse(OrderTrackingResource::collection($data), 'Order tracking information retrieved successfully');
    }


    /**
     * @OA\Post(
     *     path="/api/orders/{id}/trackings",
     *     tags={"Tenants", "Orders"},
     *      @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Order ID",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     summary="Create a new tracking entry for an order",
     *     description="Creates a new tracking entry for a specific order .",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="order_action_by",
     *                 type="string",
     *                 example="1"
     *             ),
     *             @OA\Property(
     *                 property="date",
     *                 type="string",
     *                 format="date-time",
     *                 example="2025-08-10 10:00:00"
     *             ),
     *             @OA\Property(
     *                 property="order_status",
     *                 type="string",
     *                 enum={"pending", "processing", "shipped", "delivered", "cancelled"},
     *                 example="shipped"
     *             ),
     *             @OA\Property(
     *                 property="remarks",
     *                 type="string",
     *                 example="Order has been shipped"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Order Taking created successfully",
     *        @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Order Taking created successfully"),
     *             @OA\Property(
     *                 property="data",
     *                  type="object",
     *                 ref="#/components/schemas/orderTrackingResourceSchema"
     *             ),
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

    public function store(OrderTrackingRequest $request, $orderId)
    {
        try {
            $createDataDTO = OrderTrackingDataBuilder::getDtoData($request);
            $orderTracking =  $this->orderTrackingService->store($createDataDTO);
            event(new OrderTrakingStatusCreate($orderTracking));
        } catch (InvalidOrderTrackingStatusException $invalidOrderTrackingStatusException) {
            Log::error($invalidOrderTrackingStatusException->getMessage());
            return $this->errorResponse($invalidOrderTrackingStatusException->getMessage(), 400);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return  $this->errorResponse('Something went wrong', 500);
        }

        return  $this->successResponse(new OrderTrackingResource($orderTracking), 'Order tracking information store successfully', 201);
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        try {
            $data =  $this->orderTrackingService->findById($id);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return  $this->errorResponse('Something went wrong', 500);
        }
        return OrderTrackingResource::make($data);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(OrderTrackingRequest $request, $orderId)
    {

        try {
            $createDataDTO = OrderTrackingDataBuilder::getDtoData($request);
            // $data =  $this->orderTrackingService->update($createDataDTO, $id);
        } catch (Exception $e) {
            Log::error($e->getmessage());
            return  $this->errorResponse('Something went wrong', 500);
        }
        // return  $this->successResponse($data, 'Order tracking information update successfully', 201);
    }
}
