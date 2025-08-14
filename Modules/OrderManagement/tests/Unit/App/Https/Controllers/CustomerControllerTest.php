<?php

namespace Modules\OrderManagement\Tests\Unit\App\Https\Controllers;

use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Testing\TestResponse;
use Mockery;
use Modules\OrderManagement\DTO\CustomerDTO;
use Modules\OrderManagement\Http\Controllers\CustomerController;
use Modules\OrderManagement\Http\Requests\CustomerRequest;
use Modules\OrderManagement\Models\Customer;
use Modules\OrderManagement\Services\CustomerService;
use Modules\OrderManagement\Transformers\CustomerResource;
use Tests\TestCase;

class CustomerControllerTest extends TestCase
{

    use WithFaker;

    protected $customerServiceMock;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a mock of CustomerService
        $this->customerServiceMock = Mockery::mock(CustomerService::class);
    }

    public function testIndexMethodReturnSuccessResponse()
    {
        $dummyData = new LengthAwarePaginator(
            items: collect([
                new Customer(['id' => 1, 'name' => 'codewings.com']),
                new Customer(['id' => 2, 'email' => 'raratheme.com']),
            ]),
            total: 2,
            perPage: 10,
            currentPage: 1
        );

        $this->customerServiceMock
            ->shouldReceive('getAll')
            ->once()
            ->andReturn($dummyData);

        $controller = new CustomerController($this->customerServiceMock);
        $request = Request::create('/customers', 'GET');

        $response = $controller->index($request);
        $testResponse = TestResponse::fromBaseResponse($response);

        $testResponse->assertJson([
            'message' => 'Customer data retrieved successfully',
        ]);
    }

    public function testStoreMethodReturnSuccessResponse()
    {

        // Arrange for the required  data
        $dto = new CustomerDTO(
            name: 'Barak Doe',
            email: 'test@gmail.com',
            phone: '1234567890',
            address: '123 Main St',
            price_type: 'b2b_price',
        );

        $expectedCustomer = new Customer([
            'id' => 1,
            'name' => 'Barak Doe',
            'email' => 'test@gmail.com',
            'phone' => '1234567890',
            'address' => '123 Main St',
            // 'price_type' => 'b2b_price',
        ]);

        // Mock the static data builder method
        Mockery::mock('alias:Modules\OrderManagement\DataBuilder\CustomerDataBuilder')
            ->shouldReceive('getDtoData')
            ->once()
            ->andReturn($dto);

        // Mock the service store method
        $this->customerServiceMock
            ->shouldReceive('store')
            ->once()
            ->with($dto)
            ->andReturn($expectedCustomer);

        $controller = new CustomerController($this->customerServiceMock);

        // Create a CustomerRequest with necessary data
        $request = CustomerRequest::create('/customers', 'POST', [
            'name' => 'Barak Doe',
            'email' => 'test@gmail.com',
            'phone' => '1234567890',
            'address' => '123 Main St',
            'price_type' => 'b2b_price',
        ]);
        $request->setContainer(app());
        $request->setRouteResolver(fn() => app('router')->getRoutes()->match($request));

        // Act, this means something trigger to method
        $response = $controller->store($request);

        // Assert means check test response as per our requirement
        TestResponse::fromBaseResponse($response)
            ->assertJson([
                'message' => 'Customer data store successfully',
                'data' => [
                    'id' => $expectedCustomer->id,
                    'name' => $expectedCustomer->name,
                    'email' => $expectedCustomer->email,
                    'phone' => $expectedCustomer->phone,
                ],
            ]);
    }

    public function testShowReturnsCustomerResource()
    {
        $customer = Customer::factory()->make();

        $this->customerServiceMock
            ->shouldReceive('findById')
            ->once()
            ->with(1)
            ->andReturn($customer);

        $controller = new CustomerController($this->customerServiceMock);
        $response = $controller->show(1);

        // Assertion to check Check response type
        $this->assertInstanceOf(JsonResponse::class, $response);

        // Assertion to check HTTP status code
        $this->assertEquals(200, $response->getStatusCode());

        // Assertion to check response structure
        $data = $response->getData(true); // converts JSON to array
        $this->assertArrayHasKey('data', $data);
        $this->assertArrayHasKey('message', $data);
        $this->assertEquals('Customer retrieved successfully', $data['message']);
    }

    public function testUpdateReturnSuccessResponse()
    {
        $dto = new CustomerDTO(
            name: 'Barak Doe',
            email: 'test@gmail.com',
            phone: '1234567890',
            address: '123 Main St',
            price_type: 'b2b_price',
        );

        $updatedMock = (object)['id' => 1, 'name' => 'Updated any name'];

        Mockery::mock('alias:Modules\OrderManagement\DataBuilder\CustomerDataBuilder')
            ->shouldReceive('getDtoData')
            ->once()
            ->andReturn($dto);

        $this->customerServiceMock
            ->shouldReceive('update')
            ->with($dto, 1)
            ->once()
            ->andReturn($updatedMock);

        $controller = new CustomerController($this->customerServiceMock);

        $request = CustomerRequest::create('/customers/1', 'PATCH', [
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
            'message' => 'Customer updated successfully',
            'data' => ['id' => 1]
        ]);
    }
}
