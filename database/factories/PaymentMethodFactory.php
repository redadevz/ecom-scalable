<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\PaymentMethod;

class PaymentMethodFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->name(),
            'code' => $this->faker->unique()->bothify('C-########'),
            'sequence_no' => $this->faker->numberBetween(0, 1000),
            'is_active' => $this->faker->boolean(),
            'is_customer_required' => $this->faker->boolean(),
            'description' => $this->faker->text(),
        ];
    }
}
