<?php

namespace App\Http\Requests\Api\SupplierItemTaxType;

use Illuminate\Foundation\Http\FormRequest;

class StoreSupplierItemTaxTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can("craftable-pro-api.supplier-item-tax-types.store");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'item_id' => ['required'],
            'supplier_tax_type_id' => ['required'],
            'start_time' => ['required'],
            'end_time' => ['nullable'],
            'description' => ['nullable', 'string'],
            
        ];
    }
}
