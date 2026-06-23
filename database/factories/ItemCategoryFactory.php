<?php

namespace Database\Factories;

use App\Models\ItemCategory;
use App\Models\ParentCategory;

class ItemCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'parent_category_id' => ParentCategory::factory(),
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'is_active' => $this->faker->boolean(),
        ];
    }
}
