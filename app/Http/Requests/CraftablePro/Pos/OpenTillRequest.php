<?php

namespace App\Http\Requests\CraftablePro\Pos;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class OpenTillRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('craftable-pro.order-headers.create');
    }

    public function rules(): array
    {
        return [
            'opening_float' => ['required', 'numeric', 'min:0'],
        ];
    }
}
