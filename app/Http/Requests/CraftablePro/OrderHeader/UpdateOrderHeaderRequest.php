<?php

namespace App\Http\Requests\CraftablePro\OrderHeader;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateOrderHeaderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.order-headers.edit");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'store_id' => ['sometimes'],
            'sale_channel_id' => ['sometimes'],
            'delivery_type_id' => ['sometimes'],
            'payment_method_id' => ['sometimes'],
            'payment_time_id' => ['sometimes'],
            'customer_id' => ['nullable'],
            'loyalty_card_id' => ['nullable'],
            'created_by' => ['nullable'],
            'approved_by' => ['nullable'],
            'managed_by' => ['nullable'],
            'order_no' => ['sometimes', 'string'],
            'customer_notes' => ['nullable', 'string'],
            'price_before_tax' => ['sometimes'],
            'total_tax_value' => ['sometimes'],
            'price_after_tax' => ['sometimes'],
            'price_before_discount' => ['sometimes'],
            'order_items_discount' => ['sometimes'],
            'order_discount' => ['sometimes'],
            'total_discount_value' => ['sometimes'],
            'price_after_discount' => ['sometimes'],
            'price_adjustment' => ['nullable'],
            'price_adjustment_reason' => ['nullable', 'string'],
            'price' => ['sometimes'],
            'latest_status' => ['sometimes', 'string'],
            'latest_status_update' => ['sometimes'],
            'is_submitted' => ['sometimes', 'boolean'],
            'submitted_time' => ['nullable'],
            'is_approved' => ['sometimes', 'boolean'],
            'approved_time' => ['nullable'],
            'is_canceled' => ['sometimes', 'boolean'],
            'canceled_time' => ['nullable'],
            'cancel_reason' => ['nullable', 'string'],
            'is_scheduled' => ['sometimes', 'boolean'],
            'scheduled_time' => ['nullable'],
            'is_ready' => ['sometimes', 'boolean'],
            'ready_time' => ['nullable'],
            'is_delivered' => ['sometimes', 'boolean'],
            'delivered_time' => ['nullable'],
            'is_paid' => ['sometimes', 'boolean'],
            'payment_time' => ['nullable'],
            'is_completed' => ['sometimes', 'boolean'],
            'completed_time' => ['nullable'],
            'return_required' => ['sometimes', 'boolean'],
            'return_time' => ['nullable'],
            'comments' => ['nullable', 'string'],
            
        ];
    }
}
