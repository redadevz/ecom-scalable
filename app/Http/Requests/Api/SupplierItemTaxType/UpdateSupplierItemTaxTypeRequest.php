<?php

namespace App\Http\Requests\Api\SupplierItemTaxType;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSupplierItemTaxTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can("craftable-pro-api.supplier-item-tax-types.update");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'item_id' => ['sometimes'],
            'supplier_tax_type_id' => ['sometimes'],
            'start_time' => ['sometimes'],
            'end_time' => ['nullable'],
            'description' => ['nullable', 'string'],
            
        ];
    }
}
