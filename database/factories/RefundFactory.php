<?php

namespace Database\Factories;

use App\Models\PaymentMethod;
use App\Models\Refund;
use App\Models\SaleReturn;

class RefundFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'sale_return_id' => SaleReturn::factory(),
            'payment_method_id' => PaymentMethod::factory(),
            'refund_no' => $this->faker->word(),
            'amount' => $this->faker->randomFloat(0, 0, 9999999999.),
            'cash_paid' => $this->faker->randomFloat(0, 0, 9999999999.),
            'cash_change' => $this->faker->randomFloat(0, 0, 9999999999.),
            'refund_time' => $this->faker->dateTime(),
            'comments' => $this->faker->word(),
        ];
    }
}
