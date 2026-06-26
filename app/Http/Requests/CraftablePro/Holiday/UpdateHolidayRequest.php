<?php

namespace App\Http\Requests\CraftablePro\Holiday;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateHolidayRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.holidays.edit");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'store_id' => ['sometimes'],
            'name' => ['sometimes', 'string'],
            'reason' => ['nullable', 'string'],
            'starts_at' => ['sometimes'],
            'ends_at' => ['nullable'],
            'comments' => ['nullable', 'string'],
            
        ];
    }
}
