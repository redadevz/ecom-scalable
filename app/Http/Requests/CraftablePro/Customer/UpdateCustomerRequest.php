<?php

namespace App\Http\Requests\CraftablePro\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.customers.edit");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'city_id' => ['sometimes'],
            'created_at_store_id' => ['nullable'],
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
            'is_registered_online' => ['sometimes', 'boolean'],
            'email' => ['nullable', 'string'],
            'username' => ['nullable', 'string'],
            'password' => ['nullable', 'string'],
            'credit' => ['nullable'],
            'last_login_time' => ['nullable'],
            'is_active' => ['sometimes', 'boolean'],
            'comments' => ['nullable', 'string'],
            
        ];
    }
}
