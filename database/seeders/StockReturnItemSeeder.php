<?php

namespace Database\Seeders;

use App\Models\StockReturnItem;
use Illuminate\Database\Seeder;

class StockReturnItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StockReturnItem::factory()->count(5)->create();
    }
}
