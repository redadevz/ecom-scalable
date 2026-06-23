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
            'store_id' => ['required'],
            'sale_channel_id' => ['required'],
            'delivery_type_id' => ['required'],
            'payment_method_id' => ['required'],
            'payment_time_id' => ['required'],
            'customer_id' => ['nullable'],
            'loyalty_card_id' => ['nullable'],
            'created_by' => ['nullable'],
            'approved_by' => ['nullable'],
            'managed_by' => ['nullable'],
            'order_no' => ['required', 'string'],
            'customer_notes' => ['nullable', 'string'],
            'price_before_tax' => ['required'],
            'total_tax_value' => ['required'],
            'price_after_tax' => ['required'],
            'price_before_discount' => ['required'],
            'order_items_discount' => ['required'],
            'order_discount' => ['required'],
            'total_discount_value' => ['required'],
            'price_after_discount' => ['required'],
            'price_adjustment' => ['nullable'],
            'price_adjustment_reason' => ['nullable', 'string'],
            'price' => ['required'],
            'latest_status' => ['required', 'string'],
            'latest_status_update' => ['required'],
            'is_submitted' => ['required', 'boolean'],
            'submitted_time' => ['nullable'],
            'is_approved' => ['required', 'boolean'],
            'approved_time' => ['nullable'],
            'is_canceled' => ['required', 'boolean'],
            'canceled_time' => ['nullable'],
            'cancel_reason' => ['nullable', 'string'],
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
            'comments' => ['nullable', 'string'],
            
        ];
    }
}
