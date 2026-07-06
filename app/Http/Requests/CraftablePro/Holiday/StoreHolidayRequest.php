<?php

namespace App\Http\Requests\CraftablePro\Holiday;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreHolidayRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.holidays.create");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'store_id' => ['required', 'integer', 'exists:stores,id'],
            'name' => ['required', 'string', 'max:255'],
            'reason' => ['nullable', 'string', 'max:255'],
            'starts_at' => ['required'],
            'ends_at' => ['nullable'],
            'comments' => ['nullable', 'string', 'max:1000'],
            
        ];
    }
}
