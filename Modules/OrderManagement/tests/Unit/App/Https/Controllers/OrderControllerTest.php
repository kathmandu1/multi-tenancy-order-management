<?php

namespace Modules\OrderManagement\Tests\Unit\App\Https\Controllers;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Testing\TestResponse;
use Mockery;
use Modules\OrderManagement\DataBuilder\OrderDataBuilder;
use Modules\OrderManagement\Http\Controllers\OrderController;
use Modules\OrderManagement\Http\Requests\OrderRequest;
use Modules\OrderManagement\Models\Order;
use Modules\OrderManagement\Services\Order\OrderService;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{

    use WithFaker;

    protected $product;
    protected $orderServiceMock;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a mock of OrderService
        $this->orderServiceMock = Mockery::mock(OrderService::class);
    }

    public function testIndexMethodReturnSuccessResponse()
    {
        $dummyData = new LengthAwarePaginator(
            items: collect([
                new Order(['id' => 1, 'order_code' => 'order-0001']),
                new Order(['id' => 2, 'order_code' => 'order-0002']),
            ]),
            total: 2,
            perPage: 10,
            currentPage: 1
        );

        $this->orderServiceMock
            ->shouldReceive('getAll')
            ->once()
            ->andReturn($dummyData);

        $controller = new OrderController($this->orderServiceMock);
        $request = Request::create('/orders', 'GET');

        $response = $controller->index($request);
        $testResponse = TestResponse::fromBaseResponse($response);

        $testResponse->assertJson([
            'message' => 'Order data retrieved successfully',

        ]);
    }

    // public function testStoreMethodReturnSuccessResponse()
    // {

    //     // Arrange for the required  data
    //     $dto = new OrderDataBuilder(

    //     );

    //     $expectedOrder = new Order([
    //         'id' => 1,
    //     ]);

    //     // Mock the static data builder method
    //     Mockery::mock('alias:Modules\OrderManagement\DataBuilder\OrderDataBuilder')
    //         ->shouldReceive('getDtoData')
    //         ->once()
    //         ->andReturn($dto);

    //     // Mock the service store method
    //     $this->orderServiceMock
    //         ->shouldReceive('store')
    //         ->once()
    //         ->with($dto)
    //         ->andReturn($expectedOrder);

    //     $controller = new OrderController($this->orderServiceMock);

    //     // Create a OrderRequest with necessary data
    //       $request = OrderRequest::create('/orders', 'POST', [
    //         'customer_id' => 1,
    //         'order_code' => 'order-0001',
    //         'order_date' => '2023-01-01',
    //         'delivery_date' => '2023-01-05',
    //         'shipping_address_id' => 1,
    //         'total_order_amount' => 100.0,
    //         'total_discount_amount' => 10.0,
    //         'actual_amount' => 90.0,
    //         'status' => true,
    //         'remark' => 'Test order',
    //         'order_items' => [
    //             [
    //                 'product_id' => 1,
    //                 'quantity' => 2,
    //                 'price' => 50.0,
    //             ],
    //         ],
    //     ]);

    //     $request->setContainer(app());
    //     $request->setRouteResolver(fn() => app('router')->getRoutes()->match($request));

    //     // Act, this means something trigger to method
    //     $response = $controller->store($request);

    //     // Assert means check test response as per our requirement
    //     TestResponse::fromBaseResponse($response)
    //         ->assertJson([
    //             'message' => 'Order data store successfully',
    //             'data' => [
    //                 'id' => $expectedOrder->id,
    //                 'customer_id' => $expectedOrder->customer_id,
    //                 'order_code' => $expectedOrder->order_code,
    //                 'order_date' => $expectedOrder->order_date,
    //                 'delivery_date' => $expectedOrder->delivery_date,
    //                 'shipping_address_id' => $expectedOrder->shipping_address_id,
    //                 'total_order_amount' => $expectedOrder->total_order_amount,
    //                 'total_discount_amount' => $expectedOrder->total_discount_amount,
    //                 'actual_amount' => $expectedOrder->actual_amount,
    //                 'status' => $expectedOrder->status,
    //                 'remark' => $expectedOrder->remark,
    //                 'order_items' => $expectedOrder->order_items,
    //             ],
    //         ]);
    // }

    // public function testShowReturnsProductResource()
    // {
    //     $customer = Product::factory()->make();

    //     $this->productServiceMock
    //         ->shouldReceive('findById')
    //         ->once()
    //         ->with(1)
    //         ->andReturn($customer);

    //     $controller = new ProductController($this->productServiceMock);
    //     $response = $controller->show(1);

    //     // Assertion to check Check response type
    //     $this->assertInstanceOf(JsonResponse::class, $response);

    //     // Assertion to check HTTP status code
    //     $this->assertEquals(200, $response->getStatusCode());

    //     // Assertion to check response structure
    //     $data = $response->getData(true); // converts JSON to array
    //     $this->assertArrayHasKey('data', $data);
    //     $this->assertArrayHasKey('message', $data);
    //     $this->assertEquals('Product retrieved successfully', $data['message']);
    // }

    // public function testUpdateReturnSuccessResponse()
    // {
    //     $dto = new ProductDTO(
    //         product_name: 'Updated Product Name',
    //         meta_title: 'Updated Meta Title',
    //         meta_description: 'Updated Meta Description',
    //         meta_keywords: 'Updated Meta Keywords',
    //         base_price: 150.0,
    //         b2b_price: 140.0,
    //         b2c_price: 160.0,
    //         batch_no: 'Batch 2',
    //         lot_no: 'Lot 2',
    //         available_stock: 75,
    //         status: true,
    //         remarks: 'Updated Remarks'
    //     );

    //     $updatedMock = (object)['id' => 1, 'name' => 'Updated any name'];

    //     Mockery::mock('alias:Modules\OrderManagement\DataBuilder\ProductDataBuilder')
    //         ->shouldReceive('getDtoData')
    //         ->once()
    //         ->andReturn($dto);

    //     $this->productServiceMock
    //         ->shouldReceive('update')
    //         ->with($dto, 1)
    //         ->once()
    //         ->andReturn($updatedMock);

    //     $controller = new ProductController($this->productServiceMock);

    //     $request = ProductRequest::create('/products/1', 'PATCH', [
    //         'name' => 'Barak Doe',
    //         'email' => 'test@gmail.com',
    //         'phone' => '1234567890',
    //         'address' => '123 Main St',
    //         'price_type' => 'b2b_price',
    //     ]);
    //     $request->setContainer(app());
    //     $request->setRouteResolver(fn() => app('router')->getRoutes()->match($request));

    //     $response = $controller->update($request, 1);

    //     TestResponse::fromBaseResponse($response)->assertJson([
    //         'message' => 'Product update successfully',
    //         'data' => ['id' => 1]
    //     ]);
    // }
}
