<?php

namespace Database\Factories;

use App\Models\PaymentMethod;

class PaymentMethodFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'code' => $this->faker->word(),
            'sequence_no' => $this->faker->numberBetween(-10000, 10000),
            'is_active' => $this->faker->boolean(),
            'is_customer_required' => $this->faker->boolean(),
            'description' => $this->faker->text(),
        ];
    }
}
