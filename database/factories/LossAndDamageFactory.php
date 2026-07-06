<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\LossAndDamage;
use App\Models\Store;

class LossAndDamageFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'store_id' => Store::factory(),
            'exit_stock_time' => $this->faker->dateTime(),
            'description' => $this->faker->text(),
            'comments' => $this->faker->word(),
        ];
    }
}
