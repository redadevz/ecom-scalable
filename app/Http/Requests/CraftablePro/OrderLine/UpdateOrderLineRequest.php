<?php

namespace App\Http\Requests\CraftablePro\OrderLine;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateOrderLineRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.order-lines.edit");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'store_id' => ['sometimes'],
            'order_id' => ['sometimes'],
            'item_id' => ['sometimes'],
            'line_no' => ['sometimes', 'string'],
            'description' => ['nullable', 'string'],
            'customer_notes' => ['nullable', 'string'],
            'quantity' => ['sometimes'],
            'current_item_cost' => ['sometimes'],
            'markup_percentage' => ['sometimes'],
            'price_before_tax' => ['sometimes'],
            'tax_value' => ['sometimes'],
            'price_after_tax' => ['sometimes'],
            'price_before_discount' => ['sometimes'],
            'discount_value' => ['sometimes'],
            'price_after_discount' => ['sometimes'],
            'price_adjustment' => ['nullable'],
            'price_adjustment_reason' => ['nullable', 'string'],
            'price' => ['sometimes'],
            'is_canceled' => ['sometimes', 'boolean'],
            'canceled_time' => ['nullable'],
            'cancel_reason' => ['nullable', 'string'],
            'return_required' => ['sometimes', 'boolean'],
            'return_quantity' => ['nullable'],
            'return_time' => ['nullable'],
            'customer_review' => ['nullable', 'string'],
            'customer_like' => ['nullable', 'boolean'],
            'comments' => ['nullable', 'string'],
            
        ];
    }
}
