<?php

namespace App\Http\Requests\Api\SupplierTaxType;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSupplierTaxTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can("craftable-pro-api.supplier-tax-types.update");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'supplier_id' => ['sometimes'],
            'name' => ['sometimes', 'string'],
            'code' => ['sometimes', 'string'],
            'description' => ['nullable', 'string'],
            'is_percentage' => ['sometimes', 'boolean'],
            'value' => ['sometimes'],
            'start_time' => ['sometimes'],
            'end_time' => ['nullable'],
            'is_active' => ['sometimes', 'boolean'],
            'comments' => ['nullable', 'string'],
            
        ];
    }
}
