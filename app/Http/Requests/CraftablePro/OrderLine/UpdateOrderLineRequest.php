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
            'store_id' => ['sometimes', 'integer', 'exists:stores,id'],
            'order_id' => ['sometimes', 'integer', 'exists:order_headers,id'],
            'item_id' => ['sometimes', 'integer', 'exists:items,id'],
            'line_no' => ['sometimes', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:255'],
            'customer_notes' => ['nullable', 'string', 'max:255'],
            'quantity' => ['sometimes', 'integer'],
            'current_item_cost' => ['sometimes', 'numeric'],
            'markup_percentage' => ['sometimes', 'integer'],
            'price_before_tax' => ['sometimes', 'numeric'],
            'tax_value' => ['sometimes', 'numeric'],
            'price_after_tax' => ['sometimes', 'numeric'],
            'price_before_discount' => ['sometimes', 'numeric'],
            'discount_value' => ['sometimes', 'numeric'],
            'price_after_discount' => ['sometimes', 'numeric'],
            'price_adjustment' => ['nullable', 'numeric'],
            'price_adjustment_reason' => ['nullable', 'string', 'max:255'],
            'price' => ['sometimes', 'numeric'],
            'is_canceled' => ['sometimes', 'boolean'],
            'canceled_time' => ['nullable'],
            'cancel_reason' => ['nullable', 'string', 'max:255'],
            'return_required' => ['sometimes', 'boolean'],
            'return_quantity' => ['nullable', 'integer'],
            'return_time' => ['nullable'],
            'customer_review' => ['nullable', 'string', 'max:255'],
            'customer_like' => ['nullable', 'boolean'],
            'comments' => ['nullable', 'string', 'max:1000'],
            
        ];
    }
}
