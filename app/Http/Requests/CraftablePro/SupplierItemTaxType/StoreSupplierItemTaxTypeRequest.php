<?php

namespace App\Http\Requests\CraftablePro\SupplierItemTaxType;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreSupplierItemTaxTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.supplier-item-tax-types.create");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'item_id' => ['required', 'integer', 'exists:items,id'],
            'supplier_tax_type_id' => ['required', 'integer', 'exists:supplier_tax_types,id'],
            'start_time' => ['required'],
            'end_time' => ['nullable'],
            'description' => ['nullable', 'string', 'max:255'],
            
        ];
    }
}
