<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Payment;

class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'provider' => $this->faker->word(),
            'provider_reference' => $this->faker->word(),
            'status' => $this->faker->word(),
            'amount' => $this->faker->randomFloat(0, 0, 9999999999.),
            'currency' => $this->faker->word(),
        ];
    }
}
