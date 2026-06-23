<?php

namespace App\Http\Requests\Api\Customer;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can("craftable-pro-api.customers.store");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'city_id' => ['required'],
            'created_at_store_id' => ['nullable'],
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
            'is_registered_online' => ['required', 'boolean'],
            'email' => ['nullable', 'string'],
            'username' => ['nullable', 'string'],
            'password' => ['nullable', 'string'],
            'credit' => ['nullable'],
            'last_login_time' => ['nullable'],
            'is_active' => ['required', 'boolean'],
            'comments' => ['nullable', 'string'],
            
        ];
    }
}
