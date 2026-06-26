<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderReview;
use App\Models\RepliedBy;

class OrderReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'customer_id' => Customer::factory(),
            'replied_by' => RepliedBy::factory(),
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
