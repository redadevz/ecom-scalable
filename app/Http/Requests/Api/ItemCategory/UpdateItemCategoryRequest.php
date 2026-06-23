<?php

namespace App\Http\Requests\Api\ItemCategory;

use Illuminate\Foundation\Http\FormRequest;

class UpdateItemCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can("craftable-pro-api.item-categories.update");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'parent_category_id' => ['nullable'],
            'name' => ['sometimes', 'string'],
            'description' => ['nullable'],
            'is_active' => ['sometimes', 'boolean'],
            
        ];
    }
}
