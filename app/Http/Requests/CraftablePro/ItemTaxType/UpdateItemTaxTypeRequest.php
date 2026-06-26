<?php

namespace App\Http\Requests\CraftablePro\ItemTaxType;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateItemTaxTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.item-tax-types.edit");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'item_id' => ['sometimes'],
            'tax_type_id' => ['sometimes'],
            'start_time' => ['nullable'],
            'end_time' => ['nullable'],
            'description' => ['nullable', 'string'],
            
        ];
    }
}
