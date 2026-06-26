<?php

namespace Database\Seeders;

use App\Models\LossAndDamageItem;
use Illuminate\Database\Seeder;

class LossAndDamageItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LossAndDamageItem::factory()->count(5)->create();
    }
}
