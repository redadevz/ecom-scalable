<?php

namespace Database\Seeders;

use App\Models\SaleReturn;
use Illuminate\Database\Seeder;

class SaleReturnSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SaleReturn::factory()->count(5)->create();
    }
}
