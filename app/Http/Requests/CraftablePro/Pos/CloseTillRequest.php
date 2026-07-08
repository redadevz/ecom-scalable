<?php

namespace App\Http\Requests\CraftablePro\Pos;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class CloseTillRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('craftable-pro.order-headers.create');
    }

    public function rules(): array
    {
        return [
            'counted_cash' => ['required', 'numeric', 'min:0'],
            'notes'        => ['nullable', 'string', 'max:1000'],
        ];
    }
}
