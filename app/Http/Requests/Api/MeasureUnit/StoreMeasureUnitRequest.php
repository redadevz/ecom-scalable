<?php

namespace App\Http\Requests\Api\MeasureUnit;

use Illuminate\Foundation\Http\FormRequest;

class StoreMeasureUnitRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can("craftable-pro-api.measure-units.store");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'symbol' => ['required', 'string'],
            'description' => ['nullable'],
            
        ];
    }
}
