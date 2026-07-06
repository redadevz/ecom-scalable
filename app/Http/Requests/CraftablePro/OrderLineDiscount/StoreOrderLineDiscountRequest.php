<?php

namespace App\Http\Requests\CraftablePro\OrderLineDiscount;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreOrderLineDiscountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.order-line-discounts.create");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'discount_id' => ['required', 'integer', 'exists:discounts,id'],
            'order_line_id' => ['required', 'integer', 'exists:order_lines,id'],
            'value' => ['required', 'numeric'],
            'comments' => ['nullable', 'string', 'max:1000'],
            
        ];
    }
}
