<?php

namespace Database\Seeders;

use App\Models\DiscountSchedule;
use Illuminate\Database\Seeder;

class DiscountScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DiscountSchedule::factory()->count(5)->create();
    }
}
