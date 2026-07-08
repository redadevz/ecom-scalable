<?php

namespace App\Http\Requests\CraftablePro\Pos;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class CheckoutPosRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * The checkout drives the full engine, so it needs every gate the flow
     * touches. The payment permission is only required when actually charging.
     */
    public function authorize(): bool
    {
        $charging = $this->boolean('pay_now') || ! empty($this->input('payments'));

        return Gate::allows('craftable-pro.order-headers.create')
            && Gate::allows('craftable-pro.order-headers.confirm')
            && Gate::allows('craftable-pro.order-headers.invoice')
            && (! $charging || Gate::allows('craftable-pro.invoices.pay'));
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'customer_id'       => ['nullable', 'integer', 'exists:customers,id'],
            'payment_method_id' => ['nullable', 'integer', 'exists:payment_methods,id'],
            'pay_now'           => ['boolean'],
            'lines'             => ['required', 'array', 'min:1'],
            'lines.*.item_id'   => ['required', 'integer', 'exists:items,id'],
            'lines.*.quantity'  => ['required', 'integer', 'min:1'],
            'discount'          => ['nullable', 'array'],
            'discount.type'     => ['required_with:discount', 'in:amount,percent'],
            'discount.value'    => ['required_with:discount', 'numeric', 'min:0'],
            'payments'                     => ['nullable', 'array'],
            'payments.*.payment_method_id' => ['required_with:payments', 'integer', 'exists:payment_methods,id'],
            'payments.*.amount'            => ['required_with:payments', 'numeric', 'min:0.01'],
        ];
    }
}
