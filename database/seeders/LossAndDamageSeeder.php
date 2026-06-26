<?php

namespace Database\Seeders;

use App\Models\LossAndDamage;
use Illuminate\Database\Seeder;

class LossAndDamageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LossAndDamage::factory()->count(5)->create();
    }
}
