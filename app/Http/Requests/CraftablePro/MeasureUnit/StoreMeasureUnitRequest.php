<?php

namespace App\Http\Requests\CraftablePro\MeasureUnit;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreMeasureUnitRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.measure-units.create");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:measure_units,name'],
            'symbol' => ['required', 'string', 'max:255', 'unique:measure_units,symbol'],
            'description' => ['nullable'],
            
        ];
    }
}
