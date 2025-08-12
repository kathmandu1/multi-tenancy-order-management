<?php

namespace Modules\OrderManagement\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductVariantFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\OrderManagement\Models\ProductVariant::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'product_id' => $this->faker->randomNumber(),
            'base_price' => $this->faker->randomFloat(2, 1, 100),
            'b2b_price' => $this->faker->randomFloat(2, 1, 100),
            'b2c_price' => $this->faker->randomFloat(2, 1, 100),
            'available_stock' => $this->faker->randomNumber(),
            'batch_no' => $this->faker->word(),
            'lot_no' => $this->faker->word(),
            'keyword' => $this->faker->word(),
        ];
    }

}



