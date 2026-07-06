<?php

namespace App\Http\Requests\CraftablePro\DiscountSchedule;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateDiscountScheduleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.discount-schedules.edit");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'discount_type_id' => ['sometimes', 'integer', 'exists:discount_types,id'],
            'day_of_week' => ['nullable', 'boolean'],
            'start_time' => ['sometimes'],
            'end_time' => ['sometimes'],
            'comments' => ['nullable', 'string', 'max:1000'],
            
        ];
    }
}
