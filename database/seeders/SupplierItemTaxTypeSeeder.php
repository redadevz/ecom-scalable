<?php

namespace Database\Seeders;

use App\Models\SupplierItemTaxType;
use Illuminate\Database\Seeder;

class SupplierItemTaxTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SupplierItemTaxType::factory()->count(5)->create();
    }
}
