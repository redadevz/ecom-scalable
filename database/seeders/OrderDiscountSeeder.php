<?php

namespace Database\Seeders;

use App\Models\OrderDiscount;
use Illuminate\Database\Seeder;

class OrderDiscountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OrderDiscount::factory()->count(5)->create();
    }
}
