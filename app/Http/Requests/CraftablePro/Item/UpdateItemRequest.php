<?php

namespace App\Http\Requests\CraftablePro\Item;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.items.edit");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'store_id' => ['sometimes', 'integer', 'exists:stores,id'],
            'item_category_id' => ['sometimes', 'integer', 'exists:item_categories,id'],
            'supplier_id' => ['sometimes', 'integer', 'exists:suppliers,id'],
            'measure_unit_id' => ['sometimes', 'integer', 'exists:measure_units,id'],
            'sku_code' => ['sometimes', 'string', 'max:25'],
            'name' => ['sometimes', 'string', 'max:50'],
            'image' => ['nullable', 'string', 'max:255'],
            'images' => ['nullable', 'array'],
            'description' => ['nullable', 'string', 'max:255'],
            'is_service' => ['sometimes', 'boolean'],
            'in_stock' => ['sometimes', 'boolean'],
            'using_default_quantity' => ['sometimes', 'boolean'],
            'default_quantity' => ['nullable', 'integer'],
            'current_stock_quantity' => ['sometimes', 'integer'],
            'preferred_stock_quantity' => ['sometimes', 'integer'],
            'min_stock_quantity' => ['sometimes', 'integer'],
            'low_stock_warning' => ['sometimes', 'boolean'],
            'low_stock_quantity' => ['nullable', 'integer'],
            'is_active' => ['sometimes', 'boolean'],
            'comments' => ['nullable', 'string', 'max:1000'],
            
        ];
    }
}
