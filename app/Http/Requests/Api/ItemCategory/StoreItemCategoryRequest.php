<?php

namespace App\Http\Requests\Api\ItemCategory;

use Illuminate\Foundation\Http\FormRequest;

class StoreItemCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can("craftable-pro-api.item-categories.store");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'parent_category_id' => ['nullable'],
            'name' => ['required', 'string'],
            'description' => ['nullable'],
            'is_active' => ['required', 'boolean'],
            
        ];
    }
}
