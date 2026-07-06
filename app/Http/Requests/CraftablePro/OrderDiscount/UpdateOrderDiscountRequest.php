<?php

namespace App\Http\Requests\CraftablePro\OrderDiscount;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateOrderDiscountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.order-discounts.edit");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'discount_id' => ['sometimes', 'integer', 'exists:discounts,id'],
            'order_id' => ['sometimes', 'integer', 'exists:order_headers,id'],
            'value' => ['sometimes', 'numeric'],
            'comments' => ['nullable', 'string', 'max:1000'],
            
        ];
    }
}
