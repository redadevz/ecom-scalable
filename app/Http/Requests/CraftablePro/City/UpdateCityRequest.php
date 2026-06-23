<?php

namespace App\Http\Requests\CraftablePro\City;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateCityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.cities.edit");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['sometimes', 'string'],
            'region_id' => ['sometimes'],
            'timezone_id' => ['sometimes'],
            'zipcode' => ['nullable'],
            
        ];
    }
}
