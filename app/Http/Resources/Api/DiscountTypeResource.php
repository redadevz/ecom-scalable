<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DiscountTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'store_id' => $this->store_id,
            'loyalty_card_type_id' => $this->loyalty_card_type_id,
            'name' => $this->name,
            'description' => $this->description,
            'is_percentage' => $this->is_percentage,
            'value' => $this->value,
            'coupon_code' => $this->coupon_code,
            'min_order_value' => $this->min_order_value,
            'min_item_quantity' => $this->min_item_quantity,
            'apply_to_all' => $this->apply_to_all,
            'apply_to_next' => $this->apply_to_next,
            'max_discount_value' => $this->max_discount_value,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'is_active' => $this->is_active,
            'comments' => $this->comments,
        ];
    }
}
