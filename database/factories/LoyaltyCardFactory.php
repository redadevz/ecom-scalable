<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Customer;
use App\Models\LoyaltyCard;
use App\Models\LoyaltyCardType;

class LoyaltyCardFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'loyalty_card_type_id' => LoyaltyCardType::factory(),
            'customer_id' => Customer::factory(),
            'code' => $this->faker->unique()->bothify('C-########'),
            'is_active' => $this->faker->boolean(),
        ];
    }
}
