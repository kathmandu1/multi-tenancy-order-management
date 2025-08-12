<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\OrderManagement\Database\Seeders\OrderManagementDatabaseSeeder;
use Modules\OrderManagement\Models\Order;

class DatabaseSeeder extends Seeder
{
      use WithoutModelEvents;
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call([
           OrderManagementDatabaseSeeder::class
        ]);
    }
}
