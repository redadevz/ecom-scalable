<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\ItemCategory;

class ItemCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'parent_category_id' => null,
            'name' => $this->faker->unique()->name(),
            'description' => $this->faker->text(),
            'is_active' => $this->faker->boolean(),
        ];
    }
}
