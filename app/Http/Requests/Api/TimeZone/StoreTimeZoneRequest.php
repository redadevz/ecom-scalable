<?php

namespace App\Http\Requests\Api\TimeZone;

use Illuminate\Foundation\Http\FormRequest;

class StoreTimeZoneRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can("craftable-pro-api.time-zones.store");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'description' => ['nullable'],
            
        ];
    }
}
