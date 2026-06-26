<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Order;

class InvoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'invoice_no' => $this->faker->word(),
            'is_paid' => $this->faker->boolean(),
            'payment_time' => $this->faker->dateTime(),
            'comments' => $this->faker->word(),
        ];
    }
}
