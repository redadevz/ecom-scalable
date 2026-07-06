<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\DocumentCategory;

class DocumentCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->name(),
            'description' => $this->faker->text(),
            'is_active' => $this->faker->boolean(),
            'comments' => $this->faker->word(),
        ];
    }
}
