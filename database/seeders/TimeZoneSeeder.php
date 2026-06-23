<?php

namespace Database\Seeders;

use App\Models\TimeZone;
use Illuminate\Database\Seeder;

class TimeZoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TimeZone::factory()->count(5)->create();
    }
}
