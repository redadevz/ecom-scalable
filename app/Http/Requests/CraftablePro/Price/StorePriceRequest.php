<?php

namespace App\Http\Requests\CraftablePro\Price;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StorePriceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.prices.create");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'item_id' => ['required', 'integer', 'exists:items,id'],
            'store_id' => ['required', 'integer', 'exists:stores,id'],
            'created_by' => ['nullable', 'integer', 'exists:craftable_pro_users,id'],
            'description' => ['nullable', 'string', 'max:255'],
            'current_item_cost' => ['required', 'numeric'],
            'markup_percentage' => ['required', 'integer'],
            'price_before_tax' => ['required', 'numeric'],
            'tax_value' => ['required', 'numeric'],
            'price_after_tax' => ['required', 'numeric'],
            'sale_price' => ['required', 'numeric'],
            'price_change_allowed' => ['required', 'boolean'],
            'start_time' => ['required'],
            'end_time' => ['nullable'],
            'is_active' => ['required', 'boolean'],
            'comments' => ['nullable', 'string', 'max:1000'],
            
        ];
    }
}
