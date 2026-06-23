<?php

namespace Database\Factories;

use App\Models\OrderStatus;

class OrderStatusFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'is_default' => $this->faker->boolean(),
        ];
    }
}
