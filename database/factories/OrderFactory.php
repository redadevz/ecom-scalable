<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\User;

class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'status' => $this->faker->word(),
            'total' => $this->faker->randomFloat(0, 0, 9999999999.),
        ];
    }
}
