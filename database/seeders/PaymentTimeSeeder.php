<?php

namespace Database\Seeders;

use App\Models\PaymentTime;
use Illuminate\Database\Seeder;

class PaymentTimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentTime::factory()->count(5)->create();
    }
}
