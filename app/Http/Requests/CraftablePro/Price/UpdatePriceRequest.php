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
            'item_id' => ['sometimes'],
            'store_id' => ['sometimes'],
            'created_by' => ['nullable'],
            'description' => ['nullable', 'string'],
            'current_item_cost' => ['sometimes'],
            'markup_percentage' => ['sometimes'],
            'price_before_tax' => ['sometimes'],
            'tax_value' => ['sometimes'],
            'price_after_tax' => ['sometimes'],
            'sale_price' => ['sometimes'],
            'price_change_allowed' => ['sometimes', 'boolean'],
            'start_time' => ['sometimes'],
            'end_time' => ['nullable'],
            'is_active' => ['sometimes', 'boolean'],
            'comments' => ['nullable', 'string'],
            
        ];
    }
}
