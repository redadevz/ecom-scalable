<?php

namespace Database\Seeders;

use App\Models\SupplierTaxType;
use Illuminate\Database\Seeder;

class SupplierTaxTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SupplierTaxType::factory()->count(5)->create();
    }
}
