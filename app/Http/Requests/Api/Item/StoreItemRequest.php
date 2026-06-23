<?php

namespace App\Http\Requests\Api\Item;

use Illuminate\Foundation\Http\FormRequest;

class StoreItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can("craftable-pro-api.items.store");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'store_id' => ['required'],
            'item_category_id' => ['required'],
            'supplier_id' => ['required'],
            'measure_unit_id' => ['required'],
            'sku_code' => ['required', 'string'],
            'name' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'is_service' => ['required', 'boolean'],
            'in_stock' => ['required', 'boolean'],
            'using_default_quantity' => ['required', 'boolean'],
            'default_quantity' => ['nullable'],
            'current_stock_quantity' => ['required'],
            'preferred_stock_quantity' => ['required'],
            'min_stock_quantity' => ['required'],
            'low_stock_warning' => ['required', 'boolean'],
            'low_stock_quantity' => ['nullable'],
            'is_active' => ['required', 'boolean'],
            'comments' => ['nullable', 'string'],
            
        ];
    }
}
