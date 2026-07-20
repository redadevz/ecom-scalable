<?php

namespace App\Http\Requests\CraftablePro\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.customers.create");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'city_id' => ['required', 'integer', 'exists:cities,id'],
            'created_at_store_id' => ['nullable', 'integer', 'exists:stores,id'],
            'created_by' => ['nullable', 'integer', 'exists:craftable_pro_users,id'],
            'code' => ['nullable', 'string', 'max:25'],
            'phone' => ['required', 'string', 'max:50', 'unique:customers,phone'],
            'first_name' => ['required', 'string', 'max:50'],
            'last_name' => ['required', 'string', 'max:50'],
            'is_company' => ['required', 'boolean'],
            'company_name' => ['nullable', 'string', 'max:255'],
            'tax_number' => ['nullable', 'string', 'max:50'],
            'is_tax_exempted' => ['required', 'boolean'],
            'billing_address' => ['required', 'string', 'max:255'],
            'postal_code' => ['nullable', 'string', 'max:50'],
            'is_registered_online' => ['required', 'boolean'],
            'email' => ['nullable', 'string', 'email', 'max:50', 'unique:customers,email'],
            'username' => ['nullable', 'string', 'max:50', 'unique:customers,username'],
            'password' => ['nullable', 'string', 'max:255'],
            'credit' => ['nullable', 'numeric'],
            'last_login_time' => ['nullable'],
            'is_active' => ['required', 'boolean'],
            'comments' => ['nullable', 'string', 'max:1000'],
            
        ];
    }
}
