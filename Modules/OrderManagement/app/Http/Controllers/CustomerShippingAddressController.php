<?php

namespace Modules\OrderManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\CentralApplication\Traits\ApiResponse;
use Modules\OrderManagement\DataBuilder\CustomerShippingDataBuilder;
use Modules\OrderManagement\Http\Requests\CustomerShippingRequest;
use Modules\OrderManagement\Services\CustomerShippingService;
use Modules\OrderManagement\Transformers\CustomerShippingAddressResource;

class CustomerShippingAddressController extends Controller
{
    use ApiResponse;

    public function __construct(
        public CustomerShippingService $customerShippingService
    ) {}


    /**
     * @OA\Get(
     *     path="/api/customers/{id}/shippingaddresses",
     *     summary="Get customer's shipping addresses",
     *     description="Returns a list of shipping addresses for a specific customer.",
     *     operationId="getCustomerShippingAddresses",
     *     tags={"Tenants", "Customers"},
     *     @OA\Parameter(
     *         name="pagination",
     *         in="query",
     *         description="Enable or disable pagination (true or false)",
     *         required=false,
     *         @OA\Schema(
     *             type="boolean",
     *             example=true
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
     *         name="id",
     *         in="path",
     *         description="Id of Customer",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of customers",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="data", type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="name", type="string", example="John Doe"),
     *                     @OA\Property(property="address", type="string", example="123 Main St"),
     *                     @OA\Property(property="phone", type="string", example="+977-9800000000"),
     *                     @OA\Property(property="email", type="string", example="john@example.com"),
     *                     @OA\Property(property="price_type", type="string", example="retail")
     *                 )
     *             ),
     *             @OA\Property(property="pagination", type="object",
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
            $data = $this->customerShippingService->getAll($request, $eagerLoadWithRelationData);
        } catch (Exception $e) {
            Log::error($e->getmessage());
            return  $this->errorResponse('Something went wrong', 500);
        }

        return $this->successResponse(CustomerShippingAddressResource::collection($data), 'Shipping data retrieved successfully');
    }


    /**
     * @OA\Post(
     *     path="/api/customers/{id}/shippingaddresses",
     *     summary="Create a new customer shipping address",
     *     description="Stores customer shipping address information in the system",
     *     operationId="storeCustomerShippingAddress",
     *     tags={"Tenants", "Customers"},
     *      @OA\Parameter(
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
     *         name="id",
     *         in="path",
     *         description="Id of Customer",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"name"},
     *               @OA\Property(property="recipient_name", type="string", example="John Doe"),
     *                 @OA\Property(property="address", type="string", example="123 Main St, City"),
     *                 @OA\Property(property="phone", type="string", example="+977-9800000000"),
     *                 @OA\Property(property="city", type="string", example="Kathmandu"),
     *                 @OA\Property(property="state", type="string", example="Bagmati"),
     *                 @OA\Property(property="postal_code", type="string", example="44600"),
     *                 @OA\Property(property="country", type="string", example="Nepal"),
     *                 @OA\Property(property="longitude", type="string", example="85.324"),
     *                 @OA\Property(property="latitude", type="string", example="27.7172"),
     *                 @OA\Property(property="status", type="string", example="active")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Customer created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Shipping created successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="recipient_name", type="string", example="John Doe"),
     *                 @OA\Property(property="address", type="string", example="123 Main St, City"),
     *                 @OA\Property(property="phone", type="string", example="+977-9800000000"),
     *                 @OA\Property(property="city", type="string", example="Kathmandu"),
     *                 @OA\Property(property="state", type="string", example="Bagmati"),
     *                 @OA\Property(property="postal_code", type="string", example="44600"),
     *                 @OA\Property(property="country", type="string", example="Nepal"),
     *                 @OA\Property(property="longitude", type="string", example="85.324"),
     *                 @OA\Property(property="latitude", type="string", example="27.7172"),
     *                 @OA\Property(property="status", type="string", example="active")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="The name field is required.")
     *         )
     *     ),
     *     security={{ "bearerAuth": {} }}
     * )
     */
    public function store(CustomerShippingRequest $request)
    {
        $payload = $request->all();
        try {
            // Single object
            $createDataDTO = CustomerShippingDataBuilder::getDtoData($request);
            $data =  $this->customerShippingService->store($createDataDTO);
        } catch (Exception $e) {
            Log::error($e->getmessage());
            return  $this->errorResponse('Something went wrong', 500);
        }

        return  $this->successResponse(new CustomerShippingAddressResource($data), 'Shipping Data store successfully', 201);
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $data =  $this->customerShippingService->findById($id);
        return CustomerShippingAddressResource::make($data);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerShippingRequest $request, $id)
    {
        try {
            $createDataDTO = CustomerShippingDataBuilder::getDtoData($request);
            $data =  $this->customerShippingService->update($createDataDTO, $id);
        } catch (Exception $e) {
            Log::error($e->getmessage());
            return  $this->errorResponse('Something went wrong', 500);
        }
        return  $this->successResponse($data, 'Shipping updated successfully', 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy($id)
    // {
    //     //
    // }
}
