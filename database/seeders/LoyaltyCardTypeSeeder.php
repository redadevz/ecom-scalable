<?php

namespace Database\Seeders;

use App\Models\LoyaltyCardType;
use Illuminate\Database\Seeder;

class LoyaltyCardTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LoyaltyCardType::factory()->count(5)->create();
    }
}
