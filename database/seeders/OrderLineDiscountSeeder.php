<?php

namespace Database\Seeders;

use App\Models\OrderLineDiscount;
use Illuminate\Database\Seeder;

class OrderLineDiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OrderLineDiscount::factory()->count(5)->create();
    }
}
