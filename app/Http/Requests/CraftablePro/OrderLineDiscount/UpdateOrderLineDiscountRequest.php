<?php

namespace App\Http\Requests\CraftablePro\OrderLineDiscount;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateOrderLineDiscountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.order-line-discounts.edit");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'discount_id' => ['sometimes'],
            'order_line_id' => ['sometimes'],
            'value' => ['sometimes'],
            'comments' => ['nullable', 'string'],
            
        ];
    }
}
