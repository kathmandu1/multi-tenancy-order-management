<?php

namespace Modules\OrderManagement\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\OrderManagement\Models\CustomerShippingAddress;

class CustomerShippingAddressTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Call CustomerShippingAddressFactory to create shipping addresses
        CustomerShippingAddress::factory()->count(10)->create();
    }
}
