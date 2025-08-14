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
     *     tags={"Customers"},
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
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Customer retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/CustomerResourceSchema")
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
            $eagerLoadWithRelationData = ['shippingAddresses'];
            $data = $this->customerService->getAll($request, $eagerLoadWithRelationData);
        } catch (Exception $e) {
            Log::error($e->getMessage());
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
     *     tags={"Customers"},
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
     *          ref="#/components/schemas/customerCreateSchema"
     *         )
     *     ),
     *      @OA\Response(
     *         response=201,
     *         description="Customer created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Customer created successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 ref="#/components/schemas/CustomerResourceSchema"
     *             ),
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
     *     @OA\Response(
     *         response=422,
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
            $createDataDTO = CustomerDataBuilder::getDtoData($request);
            $data =  $this->customerService->store($createDataDTO);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return  $this->errorResponse('Something went wrong', 500);
        }

        return  $this->successResponse(new CustomerResource($data), 'Customer Data store successfully', 201);
    }

    /**
     * @OA\Get(
     *     path="/api/customers/{id}",
     *     summary="Get customer by ID",
     *     description="Returns a single customer by their ID",
     *     operationId="getCustomerById",
     *     tags={"Customers"},
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
     *         description="Customer retrieved successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Customer retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/CustomerResourceSchema")
     *             ),
     *         )
     *     )
     * )
     */

    public function show($id)
    {
        try {
            $customer =  $this->customerService->findById($id);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return  $this->errorResponse('Something went wrong', 500);
        }
        return $this->successResponse(new CustomerResource($customer), 'Customer retrieved successfully', 200);
    }


    /**
     * @OA\Patch(
     *     path="/api/customers/{id}",
     *     summary="Update an existing customer",
     *     description="Updates customer information in the system",
     *     operationId="updateCustomer",
     *     tags={ "Customers"},
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
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id of Customer",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             example=1
     *         )
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *          ref="#/components/schemas/customerCreateSchema"
     *         )
     *     ),
     *      @OA\Response(
     *         response=200,
     *         description="List of customers",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Customer update successfully"),
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
    public function update(CustomerRequest $request, $id)
    {

        try {
            $createDataDTO = CustomerDataBuilder::getDtoData($request);
            $data =  $this->customerService->update($createDataDTO, $id);
        } catch (Exception $e) {
            Log::error($e->getmessage());
            return  $this->errorResponse('Something went wrong', 500);
        }
        return  $this->successResponse($data, 'Customer updated successfully', 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/customers/{id}",
     *     summary="Delete customer by ID",
     *     description="Deletes a single customer by their ID",
     *     operationId="deleteCustomerById",
     *     tags={"Customers"},
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
     *         response=204,
     *         description="Customer deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        try {
            $delete =  $this->customerService->delete($id);
        } catch (Exception $e) {
            Log::error($e->getmessage());
            return  $this->errorResponse('Something went wrong', 500);
        }
        return  $this->successResponse(204);
    }
}
