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
     *     @OA\Response(
     *         response=200,
     *         description="List of customers",
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
     *     tags={"Tenants", "Products"},
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
     *             required={"product_name"},
     *             @OA\Property(property="product_name", type="string", example="i phoneBaba"),
     *             @OA\Property(property="meta_title", type="string", nullable=true, example="product"),
     *             @OA\Property(property="meta_description", type="string", nullable=true, example="This is a sample product"),
     *             @OA\Property(property="meta_keywords", type="string", nullable=true, format="email", example="product, stock"),
     *             @OA\Property(property="base_price", type="string", nullable=true, example="1500"),
     *             @OA\Property(property="b2b_price", type="string", nullable=true, example="1600"),
     *             @OA\Property(property="b2c_price", type="string", nullable=true, example="2000"),
     *             @OA\Property(property="batch_no", type="string", nullable=true, example="batch-001"),
     *             @OA\Property(property="lot_no", type="string", nullable=true, example="lot-001"),
     *             @OA\Property(property="available_stock", type="string", nullable=true, example="100"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Product created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="message", type="string", example="Product created successfully"),
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



    public function update(ProductRequest $request, $id)
    {

        try {
            $createDataDTO = ProductDataBuilder::getDtoData($request);
            $data =  $this->productService->update($createDataDTO, $id);
        } catch (Exception $e) {
            Log::error($e->getmessage());
            return  $this->errorResponse('Something went wrong', 500);
        }
        return  $this->successResponse($data, 'Product update successfully', 201);
    }


    public function destroy($id)
    {
        //
    }
}
