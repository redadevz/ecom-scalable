<?php

namespace App\Http\Requests\Api\SupplierTaxType;

use Illuminate\Foundation\Http\FormRequest;

class StoreSupplierTaxTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can("craftable-pro-api.supplier-tax-types.store");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'supplier_id' => ['required'],
            'name' => ['required', 'string'],
            'code' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'is_percentage' => ['required', 'boolean'],
            'value' => ['required'],
            'start_time' => ['required'],
            'end_time' => ['nullable'],
            'is_active' => ['required', 'boolean'],
            'comments' => ['nullable', 'string'],
            
        ];
    }
}
