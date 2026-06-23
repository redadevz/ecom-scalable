<?php

namespace Database\Seeders;

use App\Models\OrderHeader;
use Illuminate\Database\Seeder;

class OrderHeaderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OrderHeader::factory()->count(5)->create();
    }
}
