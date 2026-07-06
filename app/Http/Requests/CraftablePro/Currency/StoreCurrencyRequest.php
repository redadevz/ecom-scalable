<?php

namespace App\Http\Requests\CraftablePro\Currency;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreCurrencyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.currencies.create");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:currencies,name'],
            'short_name' => ['required', 'string', 'max:255', 'unique:currencies,short_name'],
            'symbol' => ['required', 'string', 'max:255', 'unique:currencies,symbol'],
            'description' => ['nullable'],
            
        ];
    }
}
