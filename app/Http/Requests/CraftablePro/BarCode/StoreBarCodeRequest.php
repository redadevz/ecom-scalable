<?php

namespace App\Http\Requests\CraftablePro\BarCode;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreBarCodeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.bar-codes.create");
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
