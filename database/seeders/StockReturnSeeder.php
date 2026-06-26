<?php

namespace Database\Seeders;

use App\Models\StockReturn;
use Illuminate\Database\Seeder;

class StockReturnSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StockReturn::factory()->count(5)->create();
    }
}
