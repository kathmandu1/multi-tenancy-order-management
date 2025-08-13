<?php

namespace Modules\OrderManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\CentralApplication\Traits\ApiResponse;
use Modules\OrderManagement\DataBuilder\OrderTrackingDataBuilder;
use Modules\OrderManagement\Events\OrderCreatedEvent;
use Modules\OrderManagement\Http\Requests\OrderTrackingRequest;
use Modules\OrderManagement\Services\Order\OrderTrackingService;
use Modules\OrderManagement\Transformers\OrderResource;

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
     *         description="Order Tracking Data",
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

    public function index(Request $request, $orderId)
    {
        try {
            $request->merge(['order_id' => $orderId]);
            $eagerLoadWithRelationData = [];
            $data = $this->orderTrackingService->getAll($request, $eagerLoadWithRelationData);
        } catch (Exception $e) {
            // dd($e);
            Log::error($e->getmessage());
            return  $this->errorResponse('Something went wrong', 500);
        }

        return $this->successResponse(OrderResource::collection($data), 'Order tracking information retrieved successfully');
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
     *         description="Order Tracking created successfully"
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

    public function store(OrderTrackingRequest $request, $orderId)
    {
        try {
            $createDataDTO = OrderTrackingDataBuilder::getDtoData($request);
            $order =  $this->orderTrackingService->store($createDataDTO);
            // event(new OrderCreatedEvent($order));
        } catch (Exception $e) {
            dd($e->getMessage());
            Log::error($e->getMessage());
            return  $this->errorResponse('Something went wrong', 500);
        }

        return  $this->successResponse(new OrderResource($order), 'Order tracking information store successfully', 201);
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $data =  $this->orderTrackingService->findById($id);
        return OrderResource::make($data);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(OrderTrackingRequest $request, $orderId)
    {

        try {
            $createDataDTO = OrderTrackingDataBuilder::getDtoData($request);
            $data =  $this->orderTrackingService->update($createDataDTO, $id);
        } catch (Exception $e) {
            Log::error($e->getmessage());
            return  $this->errorResponse('Something went wrong', 500);
        }
        return  $this->successResponse($data, 'Order tracking information update successfully', 201);
    }
}
