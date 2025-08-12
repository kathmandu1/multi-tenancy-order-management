<?php

namespace Modules\OrderManagement\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\OrderManagement\Models\Order;
use Modules\OrderManagement\Models\Product;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\OrderManagement\Models\Order::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'order_code' => $this->faker->unique()->word(),
            'delivery_date' => $this->faker->date(),
            'customer_id' => \Modules\OrderManagement\Models\Customer::factory(),
            'shipping_address_id' => \Modules\OrderManagement\Models\CustomerShippingAddress::factory(),
            'total_order_amount' => $this->faker->randomFloat(2, 50, 1000),
            'total_discount_amount' => $this->faker->randomFloat(2, 0, 100),
            'actual_amount' => $this->faker->randomFloat(2, 50, 1000),
            'status' => $this->faker->boolean(),
            'remark' => $this->faker->sentence(),
        ];
    }
    // Attach order item from product in factory
    public function configure(): self
    {
        return $this->afterCreating(function (Order $order) {
            $products = Product::factory()->count(3)->create();

            foreach ($products as $product) {
                $order->orderItems()->create([
                    'product_id' => $product->id,
                    'quantity' => $this->faker->randomDigitNotZero(),
                    'price' => $product->productVariant->b2b_price ?? 0,
                    'subtotal' => $product->productVariant->b2b_price * $this->faker->randomDigitNotZero(),
                    'status' => $this->faker->boolean(),
                ]);
            }
        });
    }
}
