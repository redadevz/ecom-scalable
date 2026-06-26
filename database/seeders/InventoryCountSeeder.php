<?php

namespace Database\Seeders;

use App\Models\InventoryCount;
use Illuminate\Database\Seeder;

class InventoryCountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        InventoryCount::factory()->count(5)->create();
    }
}
