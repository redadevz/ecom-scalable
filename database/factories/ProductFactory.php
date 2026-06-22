<?php

namespace Database\Factories;

use App\Models\Product;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'slug' => $this->faker->slug(),
            'description' => $this->faker->text(),
            'price' => $this->faker->randomFloat(0, 0, 9999999999.),
            'stock' => $this->faker->numberBetween(-10000, 10000),
            'category' => $this->faker->word(),
            'image_url' => $this->faker->word(),
        ];
    }
}
