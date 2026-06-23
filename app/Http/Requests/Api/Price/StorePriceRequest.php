<?php

namespace App\Http\Requests\Api\Price;

use Illuminate\Foundation\Http\FormRequest;

class StorePriceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can("craftable-pro-api.prices.store");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'item_id' => ['required'],
            'store_id' => ['required'],
            'created_by' => ['nullable'],
            'description' => ['nullable', 'string'],
            'current_item_cost' => ['required'],
            'markup_percentage' => ['required'],
            'price_before_tax' => ['required'],
            'tax_value' => ['required'],
            'price_after_tax' => ['required'],
            'sale_price' => ['required'],
            'price_change_allowed' => ['required', 'boolean'],
            'start_time' => ['required'],
            'end_time' => ['nullable'],
            'is_active' => ['required', 'boolean'],
            'comments' => ['nullable', 'string'],
            
        ];
    }
}
