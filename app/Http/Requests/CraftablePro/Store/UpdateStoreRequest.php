<?php

namespace App\Http\Requests\CraftablePro\Store;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.stores.edit");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'city_id' => ['sometimes'],
            'language_id' => ['sometimes'],
            'currency_id' => ['sometimes'],
            'code' => ['nullable', 'string'],
            'name' => ['sometimes', 'string'],
            'is_active' => ['sometimes', 'boolean'],
            'legal_entity_name' => ['sometimes', 'string'],
            'tax_code' => ['sometimes', 'string'],
            'address' => ['sometimes', 'string'],
            'registration_number' => ['sometimes', 'string'],
            'phone' => ['nullable', 'string'],
            'fax' => ['nullable', 'string'],
            'email' => ['nullable', 'string'],
            'logo' => ['nullable', 'string'],
            'bank_branch' => ['nullable', 'string'],
            'bank_code' => ['nullable', 'string'],
            'bank_account' => ['nullable', 'string'],
            
        ];
    }
}
