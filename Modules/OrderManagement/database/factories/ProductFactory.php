<?php

namespace Modules\OrderManagement\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\OrderManagement\Models\ProductVariant;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\OrderManagement\Models\Product::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'product_name' => $this->faker->word(),
            'meta_title' => $this->faker->sentence(),
            'meta_description' => $this->faker->sentence(),
            'meta_keywords' => $this->faker->words(3, true),
            'remarks' => $this->faker->sentence(),
            'status' => $this->faker->boolean(),
        ];
    }

    // // Attach ProductVariantFactory after create
    public function configure(): self
    {
        return $this->afterCreating(function ($product) {
            $product->productVariant()->create(
                ProductVariant::factory()->make()->toArray()
            );
        });
    }
}
