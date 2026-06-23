<?php

namespace App\Http\Requests\Api\DiscountType;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDiscountTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can("craftable-pro-api.discount-types.update");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'store_id' => ['sometimes'],
            'loyalty_card_type_id' => ['nullable'],
            'name' => ['sometimes', 'string'],
            'description' => ['nullable', 'string'],
            'is_percentage' => ['sometimes', 'boolean'],
            'value' => ['sometimes'],
            'coupon_code' => ['nullable', 'string'],
            'min_order_value' => ['sometimes'],
            'min_item_quantity' => ['sometimes'],
            'apply_to_all' => ['sometimes', 'boolean'],
            'apply_to_next' => ['sometimes', 'boolean'],
            'max_discount_value' => ['sometimes'],
            'start_time' => ['sometimes'],
            'end_time' => ['nullable'],
            'is_active' => ['sometimes', 'boolean'],
            'comments' => ['nullable', 'string'],
            
        ];
    }
}
