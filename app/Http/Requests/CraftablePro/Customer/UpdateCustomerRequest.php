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
            'city_id' => ['sometimes', 'integer', 'exists:cities,id'],
            'created_at_store_id' => ['nullable', 'integer', 'exists:stores,id'],
            'created_by' => ['nullable', 'integer', 'exists:craftable_pro_users,id'],
            'code' => ['sometimes', 'string', 'max:25'],
            'phone' => ['sometimes', 'string', 'max:50'],
            'first_name' => ['sometimes', 'string', 'max:50'],
            'last_name' => ['sometimes', 'string', 'max:50'],
            'is_company' => ['sometimes', 'boolean'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'tax_number' => ['nullable', 'string', 'max:50'],
            'is_tax_exempted' => ['sometimes', 'boolean'],
            'billing_address' => ['sometimes', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:50'],
            'is_registered_online' => ['sometimes', 'boolean'],
            'email' => ['nullable', 'string', 'email', 'max:50'],
            'username' => ['nullable', 'string', 'max:50'],
            'password' => ['nullable', 'string', 'max:255'],
            'credit' => ['nullable', 'numeric'],
            'last_login_time' => ['nullable'],
            'is_active' => ['sometimes', 'boolean'],
            'comments' => ['nullable', 'string', 'max:1000'],
            
        ];
    }
}
