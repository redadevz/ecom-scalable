<?php

namespace App\Http\Requests\CraftablePro\Discount;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateDiscountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.discounts.edit");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'discount_type_id' => ['sometimes', 'integer', 'exists:discount_types,id'],
            'item_category_id' => ['nullable', 'integer', 'exists:item_categories,id'],
            'item_id' => ['nullable', 'integer', 'exists:items,id'],
            'description' => ['nullable', 'string', 'max:255'],
            'comments' => ['nullable', 'string', 'max:1000'],
            
        ];
    }
}
