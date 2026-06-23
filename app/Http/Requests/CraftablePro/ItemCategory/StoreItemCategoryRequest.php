<?php

namespace App\Http\Requests\CraftablePro\ItemCategory;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreItemCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.item-categories.create");
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
