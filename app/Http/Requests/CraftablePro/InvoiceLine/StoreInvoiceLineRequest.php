<?php

namespace App\Http\Requests\CraftablePro\InvoiceLine;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreInvoiceLineRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.invoice-lines.create");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'invoice_id' => ['required'],
            'order_line_id' => ['required'],
            'line_no' => ['required', 'string'],
            'comments' => ['nullable', 'string'],
            
        ];
    }
}
