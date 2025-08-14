<?php

namespace Modules\OrderManagement\Tests\Unit\App\Https\Controllers;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Testing\TestResponse;
use Mockery;
use Modules\OrderManagement\DTO\ProductDTO;
use Modules\OrderManagement\Http\Controllers\ProductController;
use Modules\OrderManagement\Http\Requests\ProductRequest;
use Modules\OrderManagement\Models\Product;
use Modules\OrderManagement\Services\Order\ProductService;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{

    use WithFaker;

    protected $product;
    protected $productServiceMock;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a mock of ProductService
        $this->productServiceMock = Mockery::mock(ProductService::class);
    }

    public function testIndexMethodReturnSuccessResponse()
    {
        $dummyData = new LengthAwarePaginator(
            items: collect([
                new Product(['id' => 1, 'name' => 'Product 1']),
                new Product(['id' => 2, 'name' => 'Product 2']),
            ]),
            total: 2,
            perPage: 10,
            currentPage: 1
        );

        $this->productServiceMock
            ->shouldReceive('getAll')
            ->once()
            ->andReturn($dummyData);

        $controller = new ProductController($this->productServiceMock);
        $request = Request::create('/products', 'GET');

        $response = $controller->index($request);
        $testResponse = TestResponse::fromBaseResponse($response);

        $testResponse->assertJson([
            'message' => 'Product data retrieved successfully',
        ]);
    }

    public function testStoreMethodReturnSuccessResponse()
    {

        // Arrange for the required  data
        $dto = new ProductDTO(
            product_name: 'Product 1',
            meta_title: 'Meta Title 1',
            meta_description: 'Meta Description 1',
            meta_keywords: 'Meta Keywords 1',
            remarks: 'Remarks 1',
            status: true,
            base_price: 100.0,
            b2b_price: 90.0,
            b2c_price: 110.0,
            batch_no: 'Batch 1',
            lot_no: 'Lot 1',
            available_stock: 50
        );

        $expectedProduct = new Product([
            'id' => 1,
            'product_name' => 'Product 1',
            'meta_title' => 'Meta Title 1',
            'meta_description' => 'Meta Description 1',
            'meta_keywords' => 'Meta Keywords 1',
        ]);

        // Mock the static data builder method
        Mockery::mock('alias:Modules\OrderManagement\DataBuilder\ProductDataBuilder')
            ->shouldReceive('getDtoData')
            ->once()
            ->andReturn($dto);

        // Mock the service store method
        $this->productServiceMock
            ->shouldReceive('store')
            ->once()
            ->with($dto)
            ->andReturn($expectedProduct);

        $controller = new ProductController($this->productServiceMock);

        // Create a ProductRequest with necessary data
        $request = ProductRequest::create('/products', 'POST', [
            'product_name' => 'Product 1',
            'meta_title' => 'Meta Title 1',
            'meta_description' => 'Meta Description 1',
            'meta_keywords' => 'Meta Keywords 1',
            'remarks' => 'Remarks 1',
            'status' => true,
            'base_price' => 100.0,
            'b2b_price' => 90.0,
            'b2c_price' => 110.0,
            'batch_no' => 'Batch 1',
            'lot_no' => 'Lot 1',
            'available_stock' => 50
        ]);
        $request->setContainer(app());
        $request->setRouteResolver(fn() => app('router')->getRoutes()->match($request));

        // Act, this means something trigger to method
        $response = $controller->store($request);

        // Assert means check test response as per our requirement
        TestResponse::fromBaseResponse($response)
            ->assertJson([
                'message' => 'Product data store successfully',
                'data' => [
                    'id' => $expectedProduct->id,
                    'product_name' => $expectedProduct->product_name,
                    'meta_title' => $expectedProduct->meta_title,
                    'meta_description' => $expectedProduct->meta_description,
                    'meta_keywords' => $expectedProduct->meta_keywords,
                ],
            ]);
    }

    public function testShowReturnsProductResource()
    {
        $customer = Product::factory()->make();

        $this->productServiceMock
            ->shouldReceive('findById')
            ->once()
            ->with(1)
            ->andReturn($customer);

        $controller = new ProductController($this->productServiceMock);
        $response = $controller->show(1);

        // Assertion to check Check response type
        $this->assertInstanceOf(JsonResponse::class, $response);

        // Assertion to check HTTP status code
        $this->assertEquals(200, $response->getStatusCode());

        // Assertion to check response structure
        $data = $response->getData(true); // converts JSON to array
        $this->assertArrayHasKey('data', $data);
        $this->assertArrayHasKey('message', $data);
        $this->assertEquals('Product retrieved successfully', $data['message']);
    }

    public function testUpdateReturnSuccessResponse()
    {
        $dto = new ProductDTO(
            product_name: 'Updated Product Name',
            meta_title: 'Updated Meta Title',
            meta_description: 'Updated Meta Description',
            meta_keywords: 'Updated Meta Keywords',
            base_price: 150.0,
            b2b_price: 140.0,
            b2c_price: 160.0,
            batch_no: 'Batch 2',
            lot_no: 'Lot 2',
            available_stock: 75,
            status: true,
            remarks: 'Updated Remarks'
        );

        $updatedMock = (object)['id' => 1, 'name' => 'Updated any name'];

        Mockery::mock('alias:Modules\OrderManagement\DataBuilder\ProductDataBuilder')
            ->shouldReceive('getDtoData')
            ->once()
            ->andReturn($dto);

        $this->productServiceMock
            ->shouldReceive('update')
            ->with($dto, 1)
            ->once()
            ->andReturn($updatedMock);

        $controller = new ProductController($this->productServiceMock);

        $request = ProductRequest::create('/products/1', 'PATCH', [
            'name' => 'Barak Doe',
            'email' => 'test@gmail.com',
            'phone' => '1234567890',
            'address' => '123 Main St',
            'price_type' => 'b2b_price',
        ]);
        $request->setContainer(app());
        $request->setRouteResolver(fn() => app('router')->getRoutes()->match($request));

        $response = $controller->update($request, 1);

        TestResponse::fromBaseResponse($response)->assertJson([
            'message' => 'Product update successfully',
            'data' => ['id' => 1]
        ]);
    }
}
