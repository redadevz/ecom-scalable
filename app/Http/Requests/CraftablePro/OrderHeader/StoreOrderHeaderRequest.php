<?php

namespace App\Http\Requests\CraftablePro\OrderHeader;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreOrderHeaderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.order-headers.create");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'store_id' => ['required', 'integer', 'exists:stores,id'],
            'sale_channel_id' => ['required', 'integer', 'exists:sale_channels,id'],
            'delivery_type_id' => ['required', 'integer', 'exists:delivery_types,id'],
            'payment_method_id' => ['required', 'integer', 'exists:payment_methods,id'],
            'payment_time_id' => ['required', 'integer', 'exists:payment_times,id'],
            'customer_id' => ['nullable', 'integer', 'exists:customers,id'],
            'loyalty_card_id' => ['nullable', 'integer', 'exists:loyalty_cards,id'],
            'created_by' => ['nullable', 'integer', 'exists:craftable_pro_users,id'],
            'approved_by' => ['nullable', 'integer', 'exists:craftable_pro_users,id'],
            'managed_by' => ['nullable', 'integer', 'exists:craftable_pro_users,id'],
            'order_no' => ['required', 'string', 'max:50', 'unique:order_headers,order_no'],
            'customer_notes' => ['nullable', 'string', 'max:255'],
            'price_before_tax' => ['required', 'numeric'],
            'total_tax_value' => ['required', 'numeric'],
            'price_after_tax' => ['required', 'numeric'],
            'price_before_discount' => ['required', 'numeric'],
            'order_items_discount' => ['required', 'numeric'],
            'order_discount' => ['required', 'numeric'],
            'total_discount_value' => ['required', 'numeric'],
            'price_after_discount' => ['required', 'numeric'],
            'price_adjustment' => ['nullable', 'numeric'],
            'price_adjustment_reason' => ['nullable', 'string', 'max:255'],
            'price' => ['required', 'numeric'],
            'latest_status' => ['required', 'string', 'max:50'],
            'latest_status_update' => ['required'],
            'is_submitted' => ['required', 'boolean'],
            'submitted_time' => ['nullable'],
            'is_approved' => ['required', 'boolean'],
            'approved_time' => ['nullable'],
            'is_canceled' => ['required', 'boolean'],
            'canceled_time' => ['nullable'],
            'cancel_reason' => ['nullable', 'string', 'max:255'],
            'is_scheduled' => ['required', 'boolean'],
            'scheduled_time' => ['nullable'],
            'is_ready' => ['required', 'boolean'],
            'ready_time' => ['nullable'],
            'is_delivered' => ['required', 'boolean'],
            'delivered_time' => ['nullable'],
            'is_paid' => ['required', 'boolean'],
            'payment_time' => ['nullable'],
            'is_completed' => ['required', 'boolean'],
            'completed_time' => ['nullable'],
            'return_required' => ['required', 'boolean'],
            'return_time' => ['nullable'],
            'comments' => ['nullable', 'string', 'max:1000'],
            
        ];
    }
}
