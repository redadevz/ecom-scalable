<?php

namespace App\Http\Requests\Api\TaxType;

use Illuminate\Foundation\Http\FormRequest;

class StoreTaxTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can("craftable-pro-api.tax-types.store");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'store_id' => ['required'],
            'name' => ['required', 'string'],
            'code' => ['required', 'string'],
            'description' => ['nullable', 'string'],
            'is_percentage' => ['required', 'boolean'],
            'value' => ['required'],
            'start_time' => ['required'],
            'end_time' => ['nullable'],
            'is_active' => ['required', 'boolean'],
            'comments' => ['nullable', 'string'],
            
        ];
    }
}
