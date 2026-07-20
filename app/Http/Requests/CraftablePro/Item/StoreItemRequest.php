<?php

namespace App\Http\Requests\CraftablePro\Item;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.items.create");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'store_id' => ['required', 'integer', 'exists:stores,id'],
            'item_category_id' => ['required', 'integer', 'exists:item_categories,id'],
            'supplier_id' => ['required', 'integer', 'exists:suppliers,id'],
            'measure_unit_id' => ['required', 'integer', 'exists:measure_units,id'],
            'sku_code' => ['nullable', 'string', 'max:25', 'unique:items,sku_code'],
            'name' => ['required', 'string', 'max:50'],
            'image' => ['nullable', 'string', 'max:255'],
            'images' => ['nullable', 'array'],
            'description' => ['nullable', 'string', 'max:255'],
            'is_service' => ['required', 'boolean'],
            'in_stock' => ['required', 'boolean'],
            'using_default_quantity' => ['required', 'boolean'],
            'default_quantity' => ['nullable', 'integer'],
            'current_stock_quantity' => ['required', 'integer'],
            'preferred_stock_quantity' => ['required', 'integer'],
            'min_stock_quantity' => ['required', 'integer'],
            'low_stock_warning' => ['required', 'boolean'],
            'low_stock_quantity' => ['nullable', 'integer'],
            'is_active' => ['required', 'boolean'],
            'comments' => ['nullable', 'string', 'max:1000'],
            
        ];
    }
}
