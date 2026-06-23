<?php

namespace App\Http\Requests\Api\TaxType;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaxTypeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can("craftable-pro-api.tax-types.update");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'store_id' => ['sometimes'],
            'name' => ['sometimes', 'string'],
            'code' => ['sometimes', 'string'],
            'description' => ['nullable', 'string'],
            'is_percentage' => ['sometimes', 'boolean'],
            'value' => ['sometimes'],
            'start_time' => ['sometimes'],
            'end_time' => ['nullable'],
            'is_active' => ['sometimes', 'boolean'],
            'comments' => ['nullable', 'string'],
            
        ];
    }
}
