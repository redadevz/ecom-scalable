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
            'store_id' => ['sometimes', 'integer', 'exists:stores,id'],
            'city_id' => ['sometimes', 'integer', 'exists:cities,id'],
            'created_by' => ['nullable', 'integer', 'exists:craftable_pro_users,id'],
            'code' => ['sometimes', 'string', 'max:10'],
            'phone' => ['sometimes', 'string', 'max:50'],
            'first_name' => ['sometimes', 'string', 'max:50'],
            'last_name' => ['sometimes', 'string', 'max:50'],
            'is_company' => ['sometimes', 'boolean'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'tax_number' => ['nullable', 'string', 'max:50'],
            'is_tax_exempted' => ['sometimes', 'boolean'],
            'billing_address' => ['sometimes', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:50'],
            'email' => ['sometimes', 'string', 'email', 'max:50'],
            'is_active' => ['sometimes', 'boolean'],
            'comments' => ['nullable', 'string', 'max:1000'],
            
        ];
    }
}
