<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Models\User;

class CartFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
        ];
    }
}
