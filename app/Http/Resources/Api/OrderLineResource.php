<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderLineResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'store_id' => $this->store_id,
            'order_id' => $this->order_id,
            'item_id' => $this->item_id,
            'line_no' => $this->line_no,
            'description' => $this->description,
            'customer_notes' => $this->customer_notes,
            'quantity' => $this->quantity,
            'current_item_cost' => $this->current_item_cost,
            'markup_percentage' => $this->markup_percentage,
            'price_before_tax' => $this->price_before_tax,
            'tax_value' => $this->tax_value,
            'price_after_tax' => $this->price_after_tax,
            'price_before_discount' => $this->price_before_discount,
            'discount_value' => $this->discount_value,
            'price_after_discount' => $this->price_after_discount,
            'price_adjustment' => $this->price_adjustment,
            'price_adjustment_reason' => $this->price_adjustment_reason,
            'price' => $this->price,
            'is_canceled' => $this->is_canceled,
            'canceled_time' => $this->canceled_time,
            'cancel_reason' => $this->cancel_reason,
            'return_required' => $this->return_required,
            'return_quantity' => $this->return_quantity,
            'return_time' => $this->return_time,
            'customer_review' => $this->customer_review,
            'customer_like' => $this->customer_like,
            'comments' => $this->comments,
        ];
    }
}
