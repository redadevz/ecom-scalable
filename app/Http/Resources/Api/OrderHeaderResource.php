<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderHeaderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'store_id' => $this->store_id,
            'sale_channel_id' => $this->sale_channel_id,
            'delivery_type_id' => $this->delivery_type_id,
            'payment_method_id' => $this->payment_method_id,
            'payment_time_id' => $this->payment_time_id,
            'customer_id' => $this->customer_id,
            'loyalty_card_id' => $this->loyalty_card_id,
            'created_by' => $this->created_by,
            'approved_by' => $this->approved_by,
            'managed_by' => $this->managed_by,
            'order_no' => $this->order_no,
            'customer_notes' => $this->customer_notes,
            'price_before_tax' => $this->price_before_tax,
            'total_tax_value' => $this->total_tax_value,
            'price_after_tax' => $this->price_after_tax,
            'price_before_discount' => $this->price_before_discount,
            'order_items_discount' => $this->order_items_discount,
            'order_discount' => $this->order_discount,
            'total_discount_value' => $this->total_discount_value,
            'price_after_discount' => $this->price_after_discount,
            'price_adjustment' => $this->price_adjustment,
            'price_adjustment_reason' => $this->price_adjustment_reason,
            'price' => $this->price,
            'latest_status' => $this->latest_status,
            'latest_status_update' => $this->latest_status_update,
            'is_submitted' => $this->is_submitted,
            'submitted_time' => $this->submitted_time,
            'is_approved' => $this->is_approved,
            'approved_time' => $this->approved_time,
            'is_canceled' => $this->is_canceled,
            'canceled_time' => $this->canceled_time,
            'cancel_reason' => $this->cancel_reason,
            'is_scheduled' => $this->is_scheduled,
            'scheduled_time' => $this->scheduled_time,
            'is_ready' => $this->is_ready,
            'ready_time' => $this->ready_time,
            'is_delivered' => $this->is_delivered,
            'delivered_time' => $this->delivered_time,
            'is_paid' => $this->is_paid,
            'payment_time' => $this->payment_time,
            'is_completed' => $this->is_completed,
            'completed_time' => $this->completed_time,
            'return_required' => $this->return_required,
            'return_time' => $this->return_time,
            'comments' => $this->comments,
        ];
    }
}
