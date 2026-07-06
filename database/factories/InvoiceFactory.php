<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Invoice;
use App\Models\OrderHeader;

class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'order_id' => OrderHeader::factory(),
            'invoice_no' => $this->faker->word(),
            'is_paid' => $this->faker->boolean(),
            'payment_time' => $this->faker->dateTime(),
        ];
    }
}
