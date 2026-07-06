<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\Customer;
use App\Models\OrderHeader;
use App\Models\OrderReview;

class OrderReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'order_id' => OrderHeader::factory(),
            'customer_id' => Customer::factory(),
            'replied_by' => null,
            'rating' => $this->faker->boolean(),
            'review' => $this->faker->word(),
            'review_time' => $this->faker->dateTime(),
            'reply' => $this->faker->word(),
            'reply_time' => $this->faker->dateTime(),
            'is_compensated' => $this->faker->boolean(),
            'compensation_value' => $this->faker->word(),
            'comments' => $this->faker->word(),
        ];
    }
}
