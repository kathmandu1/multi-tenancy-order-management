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
     * @OA\Get(
     *     path="/api/products",
     *     summary="Get products list",
     *     description="Returns a list of products. Supports enabling or disabling pagination.",
     *     operationId="getProducts",
     *     tags={"Tenants", "Products"},
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
     *         description="List of products",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Products retrieved successfully"),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/ProductResourceSchema")
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
            $eagerLoadWithRelationData = ['productVariant'];
            $data = $this->productService->getAll($request, $eagerLoadWithRelationData);
        } catch (Exception $e) {
            // dd($e);
            Log::error($e->getmessage());
            return  $this->errorResponse('Something went wrong', 500);
        }

        return $this->successResponse(ProductResource::collection($data), 'Product data retrieved successfully');
    }

    /**
     * @OA\Post(
     *     path="/api/products",
     *     summary="Create a new product",
     *     description="Stores product information in the system",
     *     operationId="storeProduct",
     *     tags={"Products"},
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
     *          ref="#/components/schemas/productCreateSchema"
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Product created successfully",
     *        @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Product created successfully"),
     *             @OA\Property(
     *                 property="data",
     *                  type="object",
     *                 ref="#/components/schemas/ProductResourceSchema"
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
     *     security={{ "bearerAuth": {} }}
     * )
     */

    public function store(ProductRequest $request)
    {
        try {
            $createDataDTO = ProductDataBuilder::getDtoData($request);
            $data =  $this->productService->store($createDataDTO);
        } catch (Exception $e) {
            dd($e);
            Log::error($e->getmessage());
            return  $this->errorResponse('Something went wrong', 500);
        }

        return  $this->successResponse(new ProductResource($data), 'Product data store successfully', 201);
    }


    public function show($id)
    {
        $data =  $this->productService->findById($id);
        return ProductResource::make($data);
    }

    /**
     * @OA\Patch(
     *     path="/api/products/{id}",
     *     summary="Update an existing product",
     *     description="Updates product information in the system",
     *     operationId="updateProduct",
     *     tags={"Products"},
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
     *          ref="#/components/schemas/productCreateSchema"
     *         )
     *     ),
     *      @OA\Response(
     *         response=200,
     *         description="List of products",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Product update successfully"),
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

    public function update(ProductRequest $request, $id)
    {

        try {
            $createDataDTO = ProductDataBuilder::getDtoData($request);
            $data =  $this->productService->update($createDataDTO, $id);
        } catch (Exception $e) {
            dd($e);
            Log::error($e->getmessage());
            return  $this->errorResponse('Something went wrong', 500);
        }
        return  $this->successResponse($data, 'Product update successfully', 201);
    }


    /**
     * @OA\Delete(
     *     path="/api/products/{id}",
     *     summary="Delete product by ID",
     *     description="Deletes a single product by their ID",
     *     operationId="deleteProductById",
     *     tags={"Products"},
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
     *         description="Product deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        try {
            $delete =  $this->productService->delete($id);
        } catch (Exception $e) {
            Log::error($e->getmessage());
            return  $this->errorResponse('Something went wrong', 500);
        }
        return  $this->successResponse(204);
    }
}
