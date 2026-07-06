<?php

namespace App\Http\Requests\CraftablePro\Price;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdatePriceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.prices.edit");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'item_id' => ['sometimes', 'integer', 'exists:items,id'],
            'store_id' => ['sometimes', 'integer', 'exists:stores,id'],
            'created_by' => ['nullable', 'integer', 'exists:craftable_pro_users,id'],
            'description' => ['nullable', 'string', 'max:255'],
            'current_item_cost' => ['sometimes', 'numeric'],
            'markup_percentage' => ['sometimes', 'integer'],
            'price_before_tax' => ['sometimes', 'numeric'],
            'tax_value' => ['sometimes', 'numeric'],
            'price_after_tax' => ['sometimes', 'numeric'],
            'sale_price' => ['sometimes', 'numeric'],
            'price_change_allowed' => ['sometimes', 'boolean'],
            'start_time' => ['sometimes'],
            'end_time' => ['nullable'],
            'is_active' => ['sometimes', 'boolean'],
            'comments' => ['nullable', 'string', 'max:1000'],
            
        ];
    }
}
