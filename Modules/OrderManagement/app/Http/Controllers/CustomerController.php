<?php

namespace Modules\OrderManagement\Http\Controllers;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\CentralApplication\Traits\ApiResponse;
use Modules\OrderManagement\DataBuilder\CustomerDataBuilder;
use Modules\OrderManagement\Http\Requests\CustomerRequest;
use Modules\OrderManagement\Services\CustomerService;
use Modules\OrderManagement\Transformers\CustomerResource;

class CustomerController extends Controller
{
    use ApiResponse;

    public function __construct(
        public CustomerService $customerService
    ) {}


    /**
     * @OA\Get(
     *     path="/api/customers",
     *     summary="Get customers list",
     *     description="Returns a list of customers. Supports enabling or disabling pagination.",
     *     operationId="getCustomers",
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
            $data = $this->customerService->getAll($request, $eagerLoadWithRelationData);
        } catch (Exception $e) {
            Log::error($e->getmessage());
            return  $this->errorResponse('Something went wrong', 500);
        }

        return $this->successResponse(CustomerResource::collection($data), 'Customer data retrieved successfully');
    }


    /**
     * @OA\Post(
     *     path="/api/customers",
     *     summary="Create a new customer",
     *     description="Stores customer information in the system",
     *     operationId="storeCustomer",
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
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Ali Baba"),
     *             @OA\Property(property="address", type="string", nullable=true, example="123 Main St, City"),
     *             @OA\Property(property="phone", type="string", nullable=true, example="+977-9800000000"),
     *             @OA\Property(property="email", type="string", nullable=true, format="email", example="alibaba@example.com"),
     *             @OA\Property(property="price_type", type="string", nullable=true, example="b2b price")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Customer created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Customer created successfully"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="address", type="string", example="123 Main St, City"),
     *                 @OA\Property(property="phone", type="string", example="+977-9800000000"),
     *                 @OA\Property(property="email", type="string", example="john@example.com"),
     *                 @OA\Property(property="price_type", type="string", example="b2b price")
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
    public function store(CustomerRequest $request)
    {
        $payload = $request->all();
        try {

            // Single object
            $createDataDTO = CustomerDataBuilder::getDtoData($request);
            $data =  $this->customerService->store($createDataDTO);
        } catch (Exception $e) {
            dd($e);
            Log::error($e->getmessage());
            return  $this->errorResponse('Something went wrong', 500);
        }

        return  $this->successResponse(new CustomerResource($data), 'Customer Data store successfully', 201);
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        $data =  $this->customerService->findById($id);
        return CustomerResource::make($data);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(CustomerRequest $request, $id)
    {

        try {
            $createDataDTO = CustomerDataBuilder::getDtoData($request);
            $data =  $this->customerService->update($createDataDTO, $id);
        } catch (Exception $e) {
            Log::error($e->getmessage());
            return  $this->errorResponse('Something went wrong', 500);
        }
        return  $this->successResponse($data, 'Customer updated successfully', 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
