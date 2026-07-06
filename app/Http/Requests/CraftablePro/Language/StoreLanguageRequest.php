<?php

namespace App\Http\Requests\CraftablePro\Language;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreLanguageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.languages.create");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:languages,name'],
            'short_name' => ['required', 'string', 'max:255', 'unique:languages,short_name'],
            'description' => ['nullable'],
            
        ];
    }
}
