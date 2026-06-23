<?php

namespace App\Http\Requests\Api\BarCode;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBarCodeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can("craftable-pro-api.bar-codes.update");
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
