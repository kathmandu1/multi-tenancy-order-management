<?php

namespace Modules\OrderManagement\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\OrderManagement\Models\Customer;

class CustomerShippingAddressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\OrderManagement\Models\CustomerShippingAddress::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'recipient_name' => $this->faker->name(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'city' => $this->faker->city(),
            'state' => $this->faker->state(),
            'postal_code' => $this->faker->postcode(),
            'country' => $this->faker->country(),
            'longitude' => $this->faker->longitude(),
            'latitude' => $this->faker->latitude(),
            'status' => $this->faker->boolean(),
        ];
    }

    /**
     * Indicate that the address is the default one.
     */
    public function default(): self
    {
        return $this->state([
            'status' => true,
        ]);
    }
}
