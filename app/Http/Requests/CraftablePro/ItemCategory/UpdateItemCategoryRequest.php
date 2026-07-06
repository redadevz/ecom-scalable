<?php

namespace App\Http\Requests\CraftablePro\ItemCategory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateItemCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.item-categories.edit");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'parent_category_id' => ['nullable', 'integer', 'exists:item_categories,id'],
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable'],
            'is_active' => ['sometimes', 'boolean'],
            
        ];
    }
}
