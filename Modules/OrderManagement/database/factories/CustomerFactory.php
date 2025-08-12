<?php

namespace Modules\OrderManagement\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\OrderManagement\Enums\PriceTypeEnum;

class CustomerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\OrderManagement\Models\Customer::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'price_type' => $this->faker->randomElement(array_column(PriceTypeEnum::cases(), 'value'))
        ];
    }
}

