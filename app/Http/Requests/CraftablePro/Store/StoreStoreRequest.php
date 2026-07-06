<?php

namespace App\Http\Requests\CraftablePro\Store;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.stores.create");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'city_id' => ['required', 'integer', 'exists:cities,id'],
            'language_id' => ['required', 'integer', 'exists:languages,id'],
            'currency_id' => ['required', 'integer', 'exists:currencies,id'],
            'code' => ['nullable', 'string', 'max:255', 'unique:stores,code'],
            'name' => ['required', 'string', 'max:255', 'unique:stores,name'],
            'is_active' => ['required', 'boolean'],
            'legal_entity_name' => ['required', 'string', 'max:255'],
            'tax_code' => ['required', 'string', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'registration_number' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'fax' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:50'],
            'logo' => ['nullable', 'string', 'max:255'],
            'bank_branch' => ['nullable', 'string', 'max:255'],
            'bank_code' => ['nullable', 'string', 'max:50'],
            'bank_account' => ['nullable', 'string', 'max:50'],
            
        ];
    }
}
