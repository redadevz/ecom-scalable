<?php

namespace App\Http\Requests\Api\BarCode;

use Illuminate\Foundation\Http\FormRequest;

class StoreBarCodeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can("craftable-pro-api.bar-codes.store");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'item_id' => ['required'],
            'bar_code' => ['required', 'string'],
            'is_active' => ['required', 'boolean'],
            'description' => ['nullable', 'string'],
            
        ];
    }
}
