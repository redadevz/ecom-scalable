<?php

namespace App\Http\Requests\Api\SupplierItemTaxType;

use Illuminate\Foundation\Http\FormRequest;

class DestroySupplierItemTaxTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can("craftable-pro-api.supplier-item-tax-types.destroy");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            
        ];
    }
}
