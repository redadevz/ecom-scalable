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
            'store_id' => ['sometimes'],
            'item_category_id' => ['sometimes'],
            'supplier_id' => ['sometimes'],
            'measure_unit_id' => ['sometimes'],
            'sku_code' => ['sometimes', 'string'],
            'name' => ['sometimes', 'string'],
            'image' => ['nullable', 'string'],
            'images' => ['nullable', 'array'],
            'description' => ['nullable', 'string'],
            'is_service' => ['sometimes', 'boolean'],
            'in_stock' => ['sometimes', 'boolean'],
            'using_default_quantity' => ['sometimes', 'boolean'],
            'default_quantity' => ['nullable'],
            'current_stock_quantity' => ['sometimes'],
            'preferred_stock_quantity' => ['sometimes'],
            'min_stock_quantity' => ['sometimes'],
            'low_stock_warning' => ['sometimes', 'boolean'],
            'low_stock_quantity' => ['nullable'],
            'is_active' => ['sometimes', 'boolean'],
            'comments' => ['nullable', 'string'],
            
        ];
    }
}
