<?php

namespace App\Http\Requests\CraftablePro\Supplier;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateSupplierRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.suppliers.edit");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'store_id' => ['sometimes'],
            'city_id' => ['sometimes'],
            'created_by' => ['nullable'],
            'code' => ['sometimes', 'string'],
            'phone' => ['sometimes', 'string'],
            'first_name' => ['sometimes', 'string'],
            'last_name' => ['sometimes', 'string'],
            'is_company' => ['sometimes', 'boolean'],
            'company_name' => ['nullable', 'string'],
            'tax_number' => ['nullable', 'string'],
            'is_tax_exempted' => ['sometimes', 'boolean'],
            'billing_address' => ['sometimes', 'string'],
            'postal_code' => ['nullable', 'string'],
            'email' => ['sometimes', 'string'],
            'is_active' => ['sometimes', 'boolean'],
            'comments' => ['nullable', 'string'],
            
        ];
    }
}
