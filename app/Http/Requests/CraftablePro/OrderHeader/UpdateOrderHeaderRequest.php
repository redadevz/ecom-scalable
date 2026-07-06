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
            'store_id' => ['sometimes', 'integer', 'exists:stores,id'],
            'sale_channel_id' => ['sometimes', 'integer', 'exists:sale_channels,id'],
            'delivery_type_id' => ['sometimes', 'integer', 'exists:delivery_types,id'],
            'payment_method_id' => ['sometimes', 'integer', 'exists:payment_methods,id'],
            'payment_time_id' => ['sometimes', 'integer', 'exists:payment_times,id'],
            'customer_id' => ['nullable', 'integer', 'exists:customers,id'],
            'loyalty_card_id' => ['nullable', 'integer', 'exists:loyalty_cards,id'],
            'created_by' => ['nullable', 'integer', 'exists:craftable_pro_users,id'],
            'approved_by' => ['nullable', 'integer', 'exists:craftable_pro_users,id'],
            'managed_by' => ['nullable', 'integer', 'exists:craftable_pro_users,id'],
            'order_no' => ['sometimes', 'string', 'max:50'],
            'customer_notes' => ['nullable', 'string', 'max:255'],
            'price_before_tax' => ['sometimes', 'numeric'],
            'total_tax_value' => ['sometimes', 'numeric'],
            'price_after_tax' => ['sometimes', 'numeric'],
            'price_before_discount' => ['sometimes', 'numeric'],
            'order_items_discount' => ['sometimes', 'numeric'],
            'order_discount' => ['sometimes', 'numeric'],
            'total_discount_value' => ['sometimes', 'numeric'],
            'price_after_discount' => ['sometimes', 'numeric'],
            'price_adjustment' => ['nullable', 'numeric'],
            'price_adjustment_reason' => ['nullable', 'string', 'max:255'],
            'price' => ['sometimes', 'numeric'],
            'latest_status' => ['sometimes', 'string', 'max:50'],
            'latest_status_update' => ['sometimes'],
            'is_submitted' => ['sometimes', 'boolean'],
            'submitted_time' => ['nullable'],
            'is_approved' => ['sometimes', 'boolean'],
            'approved_time' => ['nullable'],
            'is_canceled' => ['sometimes', 'boolean'],
            'canceled_time' => ['nullable'],
            'cancel_reason' => ['nullable', 'string', 'max:255'],
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
            'comments' => ['nullable', 'string', 'max:1000'],
            
        ];
    }
}
