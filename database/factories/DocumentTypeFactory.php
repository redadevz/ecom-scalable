<?php

namespace Database\Factories;

use App\Models\DocumentCategory;
use App\Models\DocumentType;

class DocumentTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'document_category_id' => DocumentCategory::factory(),
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'is_active' => $this->faker->boolean(),
            'comments' => $this->faker->word(),
        ];
    }
}
