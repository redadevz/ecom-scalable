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
            'city_id' => ['required'],
            'language_id' => ['required'],
            'currency_id' => ['required'],
            'code' => ['nullable', 'string'],
            'name' => ['required', 'string'],
            'is_active' => ['required', 'boolean'],
            'legal_entity_name' => ['required', 'string'],
            'tax_code' => ['required', 'string'],
            'address' => ['required', 'string'],
            'registration_number' => ['required', 'string'],
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
