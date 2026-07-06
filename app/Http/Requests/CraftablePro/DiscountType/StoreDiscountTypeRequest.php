<?php

namespace App\Http\Requests\CraftablePro\DiscountType;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreDiscountTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.discount-types.create");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'store_id' => ['required', 'integer', 'exists:stores,id'],
            'loyalty_card_type_id' => ['nullable', 'integer', 'exists:loyalty_card_types,id'],
            'name' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string', 'max:255'],
            'is_percentage' => ['required', 'boolean'],
            'value' => ['required', 'numeric'],
            'coupon_code' => ['nullable', 'string', 'max:50'],
            'min_order_value' => ['required', 'numeric'],
            'min_item_quantity' => ['required', 'integer'],
            'apply_to_all' => ['required', 'boolean'],
            'apply_to_next' => ['required', 'boolean'],
            'max_discount_value' => ['required', 'numeric'],
            'start_time' => ['required'],
            'end_time' => ['nullable'],
            'is_active' => ['required', 'boolean'],
            'comments' => ['nullable', 'string', 'max:1000'],
            
        ];
    }
}
