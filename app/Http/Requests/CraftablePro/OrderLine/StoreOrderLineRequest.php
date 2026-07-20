<?php

namespace App\Http\Requests\CraftablePro\OrderLine;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreOrderLineRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.order-lines.create");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'store_id' => ['required', 'integer', 'exists:stores,id'],
            'order_id' => ['required', 'integer', 'exists:order_headers,id'],
            'item_id' => ['required', 'integer', 'exists:items,id'],
            'line_no' => ['nullable', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:255'],
            'customer_notes' => ['nullable', 'string', 'max:255'],
            'quantity' => ['required', 'integer'],
            'current_item_cost' => ['required', 'numeric'],
            'markup_percentage' => ['required', 'integer'],
            'price_before_tax' => ['required', 'numeric'],
            'tax_value' => ['required', 'numeric'],
            'price_after_tax' => ['required', 'numeric'],
            'price_before_discount' => ['required', 'numeric'],
            'discount_value' => ['required', 'numeric'],
            'price_after_discount' => ['required', 'numeric'],
            'price_adjustment' => ['nullable', 'numeric'],
            'price_adjustment_reason' => ['nullable', 'string', 'max:255'],
            'price' => ['required', 'numeric'],
            'is_canceled' => ['required', 'boolean'],
            'canceled_time' => ['nullable'],
            'cancel_reason' => ['nullable', 'string', 'max:255'],
            'return_required' => ['required', 'boolean'],
            'return_quantity' => ['nullable', 'integer'],
            'return_time' => ['nullable'],
            'customer_review' => ['nullable', 'string', 'max:255'],
            'customer_like' => ['nullable', 'boolean'],
            'comments' => ['nullable', 'string', 'max:1000'],
            
        ];
    }
}
