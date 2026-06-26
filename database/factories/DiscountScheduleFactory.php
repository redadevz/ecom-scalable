<?php

namespace Database\Factories;

use App\Models\DiscountSchedule;
use App\Models\DiscountType;

class DiscountScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'discount_type_id' => DiscountType::factory(),
            'day_of_week' => $this->faker->boolean(),
            'start_time' => $this->faker->time(),
            'end_time' => $this->faker->time(),
            'comments' => $this->faker->word(),
        ];
    }
}
