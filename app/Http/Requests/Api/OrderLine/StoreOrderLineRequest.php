<?php

namespace App\Http\Requests\Api\OrderLine;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderLineRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can("craftable-pro-api.order-lines.store");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'store_id' => ['required'],
            'order_id' => ['required'],
            'item_id' => ['required'],
            'line_no' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'customer_notes' => ['nullable', 'string'],
            'quantity' => ['required'],
            'current_item_cost' => ['required'],
            'markup_percentage' => ['required'],
            'price_before_tax' => ['required'],
            'tax_value' => ['required'],
            'price_after_tax' => ['required'],
            'price_before_discount' => ['required'],
            'discount_value' => ['required'],
            'price_after_discount' => ['required'],
            'price_adjustment' => ['nullable'],
            'price_adjustment_reason' => ['nullable', 'string'],
            'price' => ['required'],
            'is_canceled' => ['required', 'boolean'],
            'canceled_time' => ['nullable'],
            'cancel_reason' => ['nullable', 'string'],
            'return_required' => ['required', 'boolean'],
            'return_quantity' => ['nullable'],
            'return_time' => ['nullable'],
            'customer_review' => ['nullable', 'string'],
            'customer_like' => ['nullable', 'boolean'],
            'comments' => ['nullable', 'string'],
            
        ];
    }
}
