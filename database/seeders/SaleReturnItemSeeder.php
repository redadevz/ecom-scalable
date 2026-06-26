<?php

namespace Database\Seeders;

use App\Models\SaleReturnItem;
use Illuminate\Database\Seeder;

class SaleReturnItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SaleReturnItem::factory()->count(5)->create();
    }
}
