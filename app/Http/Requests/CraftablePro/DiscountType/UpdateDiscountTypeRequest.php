<?php

namespace App\Http\Requests\CraftablePro\DiscountType;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateDiscountTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.discount-types.edit");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'store_id' => ['sometimes', 'integer', 'exists:stores,id'],
            'loyalty_card_type_id' => ['nullable', 'integer', 'exists:loyalty_card_types,id'],
            'name' => ['sometimes', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:255'],
            'is_percentage' => ['sometimes', 'boolean'],
            'value' => ['sometimes', 'numeric'],
            'coupon_code' => ['nullable', 'string', 'max:50'],
            'min_order_value' => ['sometimes', 'numeric'],
            'min_item_quantity' => ['sometimes', 'integer'],
            'apply_to_all' => ['sometimes', 'boolean'],
            'apply_to_next' => ['sometimes', 'boolean'],
            'max_discount_value' => ['sometimes', 'numeric'],
            'start_time' => ['sometimes'],
            'end_time' => ['nullable'],
            'is_active' => ['sometimes', 'boolean'],
            'comments' => ['nullable', 'string', 'max:1000'],
            
        ];
    }
}
