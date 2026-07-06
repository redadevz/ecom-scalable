<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\PaymentMethod;

class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'invoice_id' => Invoice::factory(),
            'payment_method_id' => PaymentMethod::factory(),
            'payment_no' => $this->faker->word(),
            'amount' => $this->faker->randomFloat(0, 0, 9999999999.),
            'cash_paid' => $this->faker->randomFloat(0, 0, 9999999999.),
            'cash_change' => $this->faker->randomFloat(0, 0, 9999999999.),
            'payment_time' => $this->faker->dateTime(),
            'comments' => $this->faker->word(),
        ];
    }
}
