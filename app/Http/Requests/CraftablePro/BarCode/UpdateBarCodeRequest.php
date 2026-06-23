<?php

namespace App\Http\Requests\CraftablePro\BarCode;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateBarCodeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.bar-codes.edit");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'item_id' => ['sometimes'],
            'bar_code' => ['sometimes', 'string'],
            'is_active' => ['sometimes', 'boolean'],
            'description' => ['nullable', 'string'],
            
        ];
    }
}
