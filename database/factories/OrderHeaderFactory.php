<?php

namespace Database\Factories;

use App\Models\ApprovedBy;
use App\Models\CreatedBy;
use App\Models\Customer;
use App\Models\DeliveryType;
use App\Models\LoyaltyCard;
use App\Models\ManagedBy;
use App\Models\OrderHeader;
use App\Models\PaymentMethod;
use App\Models\PaymentTime;
use App\Models\SaleChannel;
use App\Models\Store;

class OrderHeaderFactory extends Factory
{
    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'store_id' => Store::factory(),
            'sale_channel_id' => SaleChannel::factory(),
            'delivery_type_id' => DeliveryType::factory(),
            'payment_method_id' => PaymentMethod::factory(),
            'payment_time_id' => PaymentTime::factory(),
            'customer_id' => Customer::factory(),
            'loyalty_card_id' => LoyaltyCard::factory(),
            'created_by' => CreatedBy::factory(),
            'approved_by' => ApprovedBy::factory(),
            'managed_by' => ManagedBy::factory(),
            'order_no' => $this->faker->word(),
            'customer_notes' => $this->faker->word(),
            'price_before_tax' => $this->faker->randomFloat(0, 0, 9999999999.),
            'total_tax_value' => $this->faker->randomFloat(0, 0, 9999999999.),
            'price_after_tax' => $this->faker->randomFloat(0, 0, 9999999999.),
            'price_before_discount' => $this->faker->randomFloat(0, 0, 9999999999.),
            'order_items_discount' => $this->faker->randomFloat(0, 0, 9999999999.),
            'order_discount' => $this->faker->randomFloat(0, 0, 9999999999.),
            'total_discount_value' => $this->faker->randomFloat(0, 0, 9999999999.),
            'price_after_discount' => $this->faker->randomFloat(0, 0, 9999999999.),
            'price_adjustment' => $this->faker->randomFloat(0, 0, 9999999999.),
            'price_adjustment_reason' => $this->faker->word(),
            'price' => $this->faker->randomFloat(0, 0, 9999999999.),
            'latest_status' => $this->faker->word(),
            'latest_status_update' => $this->faker->dateTime(),
            'is_submitted' => $this->faker->boolean(),
            'submitted_time' => $this->faker->dateTime(),
            'is_approved' => $this->faker->boolean(),
            'approved_time' => $this->faker->dateTime(),
            'is_canceled' => $this->faker->boolean(),
            'canceled_time' => $this->faker->dateTime(),
            'cancel_reason' => $this->faker->word(),
            'is_scheduled' => $this->faker->boolean(),
            'scheduled_time' => $this->faker->dateTime(),
            'is_ready' => $this->faker->boolean(),
            'ready_time' => $this->faker->dateTime(),
            'is_delivered' => $this->faker->boolean(),
            'delivered_time' => $this->faker->dateTime(),
            'is_paid' => $this->faker->boolean(),
            'payment_time' => $this->faker->dateTime(),
            'is_completed' => $this->faker->boolean(),
            'completed_time' => $this->faker->dateTime(),
            'return_required' => $this->faker->boolean(),
            'return_time' => $this->faker->dateTime(),
            'comments' => $this->faker->word(),
        ];
    }
}
