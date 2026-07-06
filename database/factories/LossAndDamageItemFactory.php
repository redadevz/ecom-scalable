<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Item;
use App\Models\LossAndDamage;
use App\Models\LossAndDamageItem;

class LossAndDamageItemFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'loss_and_damage_id' => LossAndDamage::factory(),
            'item_id' => Item::factory(),
            'quantity' => $this->faker->numberBetween(-10000, 10000),
            'description' => $this->faker->text(),
            'comments' => $this->faker->word(),
        ];
    }
}
