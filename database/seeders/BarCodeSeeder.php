<?php

namespace Database\Seeders;

use App\Models\BarCode;
use Illuminate\Database\Seeder;

class BarCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BarCode::factory()->count(5)->create();
    }
}
