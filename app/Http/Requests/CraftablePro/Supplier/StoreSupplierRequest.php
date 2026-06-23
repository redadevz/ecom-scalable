<?php

namespace App\Http\Requests\CraftablePro\Supplier;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreSupplierRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.suppliers.create");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'store_id' => ['required'],
            'city_id' => ['required'],
            'created_by' => ['nullable'],
            'code' => ['required', 'string'],
            'phone' => ['required', 'string'],
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'is_company' => ['required', 'boolean'],
            'company_name' => ['nullable', 'string'],
            'tax_number' => ['nullable', 'string'],
            'is_tax_exempted' => ['required', 'boolean'],
            'billing_address' => ['required', 'string'],
            'postal_code' => ['nullable', 'string'],
            'email' => ['required', 'string'],
            'is_active' => ['required', 'boolean'],
            'comments' => ['nullable', 'string'],
            
        ];
    }
}
