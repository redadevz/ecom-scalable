<?php

namespace App\Http\Requests\Api\DiscountType;

use Illuminate\Foundation\Http\FormRequest;

class StoreDiscountTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can("craftable-pro-api.discount-types.store");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'store_id' => ['required'],
            'loyalty_card_type_id' => ['nullable'],
            'name' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'is_percentage' => ['required', 'boolean'],
            'value' => ['required'],
            'coupon_code' => ['nullable', 'string'],
            'min_order_value' => ['required'],
            'min_item_quantity' => ['required'],
            'apply_to_all' => ['required', 'boolean'],
            'apply_to_next' => ['required', 'boolean'],
            'max_discount_value' => ['required'],
            'start_time' => ['required'],
            'end_time' => ['nullable'],
            'is_active' => ['required', 'boolean'],
            'comments' => ['nullable', 'string'],
            
        ];
    }
}
