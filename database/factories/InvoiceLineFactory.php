<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\InvoiceLine;
use App\Models\OrderLine;

class InvoiceLineFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'invoice_id' => Invoice::factory(),
            'order_line_id' => OrderLine::factory(),
            'line_no' => $this->faker->word(),
            'comments' => $this->faker->word(),
        ];
    }
}
