<?php

namespace Database\Seeders;

use App\Models\ItemTaxType;
use Illuminate\Database\Seeder;

class ItemTaxTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ItemTaxType::factory()->count(5)->create();
    }
}
