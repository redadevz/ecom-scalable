<?php

namespace Database\Seeders;

use App\Models\LoyaltyCard;
use Illuminate\Database\Seeder;

class LoyaltyCardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LoyaltyCard::factory()->count(5)->create();
    }
}
