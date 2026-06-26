<?php

namespace Database\Seeders;

use App\Models\InventoryCountItem;
use Illuminate\Database\Seeder;

class InventoryCountItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        InventoryCountItem::factory()->count(5)->create();
    }
}
