<?php

namespace App\Http\Requests\CraftablePro\Invoice;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows("craftable-pro.invoices.edit");
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'order_id' => ['sometimes'],
            'invoice_no' => ['sometimes', 'string'],
            'is_paid' => ['sometimes', 'boolean'],
            'payment_time' => ['nullable'],
            'comments' => ['nullable', 'string'],
            
        ];
    }
}
